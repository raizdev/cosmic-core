<?php

namespace Ares\Core\Command\Config;

class Minifier
{
    //--------------------------------------------------------------------
    // Enable minify class
    //--------------------------------------------------------------------
    // Use this variable to turn on and off minification of the assets.
    // This can be useful during app development - for easy debugging.

    public $minify = true;

    //--------------------------------------------------------------------
    // Base URL for assets
    //--------------------------------------------------------------------
    // Use this variable when you want to set absolute path to the asset
    // files. If no other URLs are set, like $baseJsUrl or $baseCssUrl
    // then values set to $dirJS and $dirCss will be added to the final URL.
    //
    // Example values:
    //      https://mydomain.com
    //      https://static.mydomain.com

    public $baseUrl = '';

    //--------------------------------------------------------------------
    // Base JS URL for assets
    //--------------------------------------------------------------------
    // Use this variable when your JS assets are served from subdomain.
    // Bear in mind that in this case variable $dirJs won't be added
    // to the URL.
    //
    // Example value:
    //      https://js.mydomain.com

    public $baseJsUrl = '/assets/js/';

    //--------------------------------------------------------------------
    // Base CSS URL for assets
    //--------------------------------------------------------------------
    // Use this variable when your CSS assets are served from subdomain.
    // Bear in mind that in this case variable $dirCSS won't be added
    // to the URL.
    //
    // Example value:
    //      https://css.mydomain.com

    public $baseCssUrl = '/assets/css/';

    //--------------------------------------------------------------------
    // JS adapter
    //--------------------------------------------------------------------

    public $adapterJs =  \Ares\Core\Command\Adapters\Js\MinifyAdapter::class;

    //--------------------------------------------------------------------
    // CSS adapter
    //--------------------------------------------------------------------

    public $adapterCss =  \Ares\Core\Command\Adapters\Css\MinifyAdapter::class;

    //--------------------------------------------------------------------
    // JS assets directory
    //--------------------------------------------------------------------

    public $dirJs = '/public/assets/js';

    //--------------------------------------------------------------------
    // CSS assets directory
    //--------------------------------------------------------------------

    public $dirCss = '/public/assets/css';

    //--------------------------------------------------------------------
    // JS minified assets directory
    //--------------------------------------------------------------------

    public $dirMinJs = null;

    //--------------------------------------------------------------------
    // CSS minified assets directory
    //--------------------------------------------------------------------

    public $dirMinCss = null;

    //--------------------------------------------------------------------
    // Version assets directory
    //--------------------------------------------------------------------

    public $dirVersion = '/public/assets';

    //--------------------------------------------------------------------
    // JS tag
    //--------------------------------------------------------------------

    public $tagJs = '<script type="text/javascript" src="%s"></script>';

    //--------------------------------------------------------------------
    // CSS tag
    //--------------------------------------------------------------------

    public $tagCss = '<link rel="stylesheet" href="%s">';

    //--------------------------------------------------------------------
    // Return type
    //--------------------------------------------------------------------
    // Determines how the files will be returned. The dafault value is
    // 'html' and it uses the $tagJs and $tagCss variables. Using 'array'
    // will return the php array and 'json' type will return a json string.
    //
    // Available types:
    //      'html', 'array', 'json'

    public $returnType = 'html';

    //--------------------------------------------------------------------
    // Enable auto deploy on change
    //--------------------------------------------------------------------
    // Use this variable to automatically deploy when there are any
    // changes in assets files.

    public $autoDeployOnChange = false;

    //--------------------------------------------------------------------
    // JS files config
    //--------------------------------------------------------------------
    // This array defines files to minify.
    //
    // Example array:
    //      'all.min.js' => [
    //          'jquery-3.2.1.min.js', 'bootstrap-3.3.7.min.js', 'main.js',
    //      ],

    public $js = [
        'all.min.js' => [
            'jquery-3.2.1.min.js', 'jquery.ui.touch-punch.min.js', 'js.cookie.js', 'jquery.fullscreen.min.js', 'jquery.history.js', 'jquery.magnific-popup.js', 'loading.js'
        ]
    ];

    //--------------------------------------------------------------------
    // CSS files config
    //--------------------------------------------------------------------
    // This array defines files to minify.
    //
    // Example array:
    //      'all.min.css' => [
    //          'bootstrap-3.3.7.min.css', 'font-awesome-4.7.0.min.css', 'main.css',
    //      ],

    public $css = [
        'all.min.css' => [
            'fonts.css', 'magnific-popup.css', 'custom.css', 'circle.css', 'flaticon.css', 'style.css', 'responsive.css'
        ]
    ];
}
