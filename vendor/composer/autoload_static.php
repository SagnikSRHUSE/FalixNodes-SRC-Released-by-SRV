<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba12a318b1731d921cfde2be3b2c7e67
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PleskX\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PleskX\\' => 
        array (
            0 => __DIR__ . '/..' . '/plesk/api-php-lib/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitba12a318b1731d921cfde2be3b2c7e67::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitba12a318b1731d921cfde2be3b2c7e67::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
