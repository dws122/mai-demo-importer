<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1d019251c6efb26bc9276d307299a951
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'ProteusThemes\\WPContentImporter2\\' => 33,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'ProteusThemes\\WPContentImporter2\\' => 
        array (
            0 => __DIR__ . '/..' . '/proteusthemes/wp-content-importer-v2/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1d019251c6efb26bc9276d307299a951::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1d019251c6efb26bc9276d307299a951::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}