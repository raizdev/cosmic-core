<?php

namespace Cosmic\Core\Command\Minifier;

use Ares\Config\Commands\Minifier as Config;
use Cosmic\Core\Command\Minifier\Exceptions\MinifierException;
use Exception;

class Minifier
{
    /**
     * Config object.
     *
     * @var Config
     */
    protected $config;

    /**
     * Error string.
     *
     * @var string
     */
    protected $error = '';

    /**
     * Prepare config to use
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        if (! isset($this->config->baseJsUrl))
        {
            $this->config->baseJsUrl = null;
        }

        if (! isset($this->config->baseCssUrl))
        {
            $this->config->baseCssUrl = null;
        }

        if (! isset($this->config->returnType))
        {
            $this->config->returnType = 'html';
        }
    }

    /**
     * Load minified file
     *
     * @param string $filename File name
     *
     * @return string|array
     */
    public function load(string $filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (! in_array($ext, ['js', 'css']))
        {
            throw MinifierException::forWrongFileExtension($ext);
        }

        if (! in_array($this->config->returnType, ['html', 'array', 'json']))
        {
            throw MinifierException::forWrongReturnType($this->config->returnType);
        }

        if ($this->config->autoDeployOnChange)
        {
            $this->autoDeployCheckFile($ext, $filename);
        }

        $versions = $this->getVersion($this->config->dirVersion);

        $filenames = [];

        if ($this->config->minify)
        {
            if (isset($versions[$ext][$filename]))
            {
                $filenames[] = $filename . '?v=' . $versions[$ext][$filename];
            }
        }
        else
        {
            // load all files from config array for this filename
            $type      = $this->config->$ext;
            $filenames = $type[$filename];
        }

        $tag = ($ext === 'js') ?  $this->config->tagJs : $this->config->tagCss;

        $dir = $this->determineUrl($ext);

        return $this->prepareOutput($filenames, $dir, $tag);
    }

    /**
     * Deploy
     *
     * @param string $mode Deploy mode
     *
     * @return bool
     */
    public function deploy(string $mode = 'all'): bool
    {
        if ( ! in_array($mode, ['all', 'js', 'css']))
        {
            throw MinifierException::forIncorrectDeploymentMode($mode);
        }

        $files = [];

        try
        {
            switch ($mode)
            {
                case 'js':
                    $files = $this->deployFiles('js', $this->config->js, $this->config->dirJs, $this->config->dirMinJs);
                    break;
                case 'css':
                    $files = $this->deployFiles('css', $this->config->css, $this->config->dirCss, $this->config->dirMinCss);
                    break;
                default:
                    $files['js']  = $this->deployFiles('js', $this->config->js, $this->config->dirJs, $this->config->dirMinJs);
                    $files['css'] = $this->deployFiles('css', $this->config->css, $this->config->dirCss, $this->config->dirMinCss);
            }
            $this->setVersion($mode, $files, $this->config->dirVersion);

            return true;
        }
        catch (Exception $e)
        {
            $this->error = $e->getMessage();
            return false;
        }


    }

    /**
     * Return error
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Auto deploy check
     *
     * @param string $filename Filename
     * @param string $ext      File extension
     *
     * @return void
     */
    protected function autoDeployCheck(string $filename, string $ext): void
    {
        $this->autoDeployCheckFile($ext, $filename);
    }

    /**
     *
     * @param string $filename Filename
     *
     * @deprecated deprecated since version 1.4.0 - use autoDeployCheckFile() instead
     *
     * @return bool
     */
    protected function autoDeployCheckJs(string $filename): bool
    {
        return $this->autoDeployCheckFile('js', $filename);
    }

    /**
     *
     * @param string $filename Filename
     *
     * @deprecated deprecated since version 1.4.0 - use autoDeployCheckFile() instead
     *
     * @return bool
     */
    protected function autoDeployCheckCss(string $filename): bool
    {
        return $this->autoDeployCheckFile('css', $filename);
    }

    /**
     *
     * @param string $fileType File type [css, js]
     * @param string $filename Filename
     *
     * @return bool
     */
    protected function autoDeployCheckFile(string $fileType, string $filename): bool
    {
        $dir    = 'dir' . ucfirst(strtolower($fileType));
        $dirMin = 'dirMin' . ucfirst(strtolower($fileType));

        if ($this->config->$dirMin === null) {
            $dirMin = $dir;
        }
        
        $assets   = [$filename => $this->config->$fileType[$filename]];
        $filePath = $this->config->$dirMin . '/' . $filename;

        if (! file_exists($filePath))
        {
            $this->deployFiles($fileType, $assets, $this->config->$dir, $this->config->$dirMin);
            return true;
        }

        $lastDeployTime = filemtime($filePath);

        foreach ($assets[$filename] as $file)
        {
            $currentFileTime = filemtime($this->config->$dir . '/' . $file);
            if ($currentFileTime > $lastDeployTime)
            {
                $this->deployFiles($fileType, $assets, $this->config->$dir, $this->config->$dirMin);
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param string $ext Extension type
     *
     * @return string
     */
    protected function determineUrl(string $ext): string
    {
        if ($ext === 'js' && $this->config->baseJsUrl !== null)
        {
            return rtrim($this->config->baseJsUrl, '/');
        }

        if ($ext === 'css' && $this->config->baseCssUrl !== null)
        {
            return rtrim($this->config->baseCssUrl, '/');
        }

        $dir = ($ext === 'js') ? $this->config->dirMinJs : $this->config->dirMinCss;
        $dir = ltrim(trim($dir, '/'), './');

        if ($this->config->baseUrl !== null)
        {
            $dir = rtrim($this->config->baseUrl, '/') . '/' . $dir;
        }

        return $dir;
    }

    /**
     *
     * @param array  $filenames Filenames to return
     * @param string $dir       Directory
     * @param string $tag       HTML tag
     *
     * @return string|array
     */
    protected function prepareOutput(array $filenames, string $dir, string $tag)
    {
        $output = '';

        foreach ($filenames as &$file)
        {
            if ($this->config->returnType === 'html')
            {
                $output .= sprintf($tag, $dir . '/' . $file) . PHP_EOL;
            }
            else
            {
                $file = $dir . '/' . $file;
            }
        }

        if ($this->config->returnType === 'html')
        {
            return $output;
        }

        if ($this->config->returnType === 'json')
        {
            return json_encode($filenames);
        }

        return $filenames;
    }

    /**
     *
     * @param string $dir Directory
     *
     * @return array
     */
    protected function getVersion(string $dir): array
    {
        static $versions = null;

        if ($versions === null)
        {
            $dir = rtrim($dir, '/');

            if (! file_exists(base_dir() . $dir . '/versions.json'))
            {
                throw MinifierException::forNoVersioningFile();
            }

            $versions = json_decode(file_get_contents(base_dir() . $dir . '/versions.json'), true);
        }

        return $versions;
    }

    /**
     *
     * @param string $mode  Mode
     * @param array  $files Files
     * @param string $dir   Directory
     *
     * @return void
     */
    protected function setVersion(string $mode, array $files, string $dir): void
    {
        $dir = rtrim($dir, '/');

        if (file_exists(base_dir() . $dir . '/versions.json'))
        {
            $versions = json_decode(file_get_contents(base_dir() .$dir . '/versions.json'), true);
        }

        if ($mode === 'all')
        {
            $versions = $files;
        }
        else
        {
            $versions[$mode] = $files;
        }

        file_put_contents(base_dir() . $dir . '/versions.json', json_encode($versions));
    }

    /**
     *
     * @param array       $assets JS assets
     * @param string      $dir    Directory
     * @param string|null $minDir Minified directory
     *
     * @return array
     */
    protected function deployJs(array $assets, string $dir, string $minDir = null): array
    {
        return $this->deployFiles('js', $assets, $dir, $minDir);
    }

    /**
     *
     * @param array       $assets CSS assets
     * @param string      $dir    Directory
     * @param string|null $minDir Minified directory
     *
     * @deprecated deprecated since version 1.4.0 - use deployFiles() instead
     *
     * @return array
     */
    protected function deployCss(array $assets, string $dir, string $minDir = null): array
    {
        return $this->deployFiles('css', $assets, $dir, $minDir);
    }

    /**
     *
     * @param string      $fileType File type [css, js]
     * @param array       $assets   CSS assets
     * @param string      $dir      Directory
     * @param string|null $minDir   Minified directory
     *
     * @return array
     */
    protected function deployFiles(string $fileType, array $assets, string $dir, string $minDir = null): array
    {
        $adapterType = 'adapter' . ucfirst(strtolower($fileType));

        $dir = rtrim($dir, '/');

        if ($minDir === null)
        {
            $minDir = $dir;
        }

        $class = $this->config->$adapterType;
        $results = [];

        foreach ($assets as $asset => $files)
        {
            $miniCss = new $class();
            foreach ($files as $file)
            {
                if ($this->config->minify)
                {
                    $miniCss->add(base_dir() . $dir . DIRECTORY_SEPARATOR . $file);
                }
                elseif ($dir !== $minDir)
                {
                    $this->copyFile($dir . DIRECTORY_SEPARATOR . $file, $minDir . DIRECTORY_SEPARATOR . $file);
                    $results[$file] = md5_file($minDir . DIRECTORY_SEPARATOR . $file);
                }
            }

            if ($this->config->minify)
            {
                $miniCss->minify(base_dir() . $minDir . DIRECTORY_SEPARATOR . $asset);
                $results[$asset] = md5_file(base_dir() . $minDir . DIRECTORY_SEPARATOR . $asset);
            }
        }

        return $results;
    }

    /**
     *
     * @param string $dir    Directory
     * @param string $minDir Minified directory
     *
     * @return void
     */
    protected function copyFile(string $dir, string $minDir): void
    {
        $path = pathinfo($minDir);

        if (! file_exists($path['dirname']))
        {
            mkdir($path['dirname'], 0755, true);
        }

        if (! copy($dir, $minDir))
        {
            throw MinifierException::forFileCopyError($dir, $minDir);
        }
    }

    /**
     *
     * @param string $dir Directory
     *
     * @deprecated deprecated since version 1.4.0
     *
     * @return void
     */
    protected function emptyFolder(string $dir): void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileInfo) {
            $todo = ($fileInfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileInfo->getRealPath());
        }
    }
}
