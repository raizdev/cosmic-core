<?php

namespace Cosmic\Core\Command\Minifier\Exceptions;

class MinifierException extends \RuntimeException implements ExceptionInterface
{
    public static function forWrongFileExtension(string $ext)
    {
        return __('Minifier.wrongFileExtension', [$ext]);
    }

    public static function forNoVersioningFile()
    {
        return __('Minifier.noVersioningFile');
    }

    public static function forIncorrectDeploymentMode(string $mode)
    {
        return __('Minifier.incorrectDeploymentMode', [$mode]);
    }

    public static function forWrongReturnType(string $type)
    {
        return __('Minifier.wrongReturnType', [$type]);
    }

    public static function forFileCopyError(string $file1, string $file2)
    {
        return __('Minifier.fileCopyError'. [$file1, $file2]);
    }
}
