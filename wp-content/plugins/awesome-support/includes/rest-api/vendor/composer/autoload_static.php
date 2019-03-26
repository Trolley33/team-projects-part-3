<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit885b18122da05366f734ab988138ea61
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPAS_API\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPAS_API\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit885b18122da05366f734ab988138ea61::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit885b18122da05366f734ab988138ea61::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
