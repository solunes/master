<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit871dcb5e1723e9a64ecdbe36c3686d1b
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Solunes\\Master\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Solunes\\Master\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'DemoTest' => __DIR__ . '/../..' . '/tests/GeneralTest.php',
        'MasterDatabase' => __DIR__ . '/../..' . '/src/database/migrations/0000_00_00_000003_master_database.php',
        'NodesDatabase' => __DIR__ . '/../..' . '/src/database/migrations/0000_00_00_000005_nodes_database.php',
        'PasswordResetsTable' => __DIR__ . '/../..' . '/src/database/migrations/0000_00_00_000002_password_resets_table.php',
        'SessionTable' => __DIR__ . '/../..' . '/src/database/migrations/0000_00_00_000001_session_table.php',
        'UsersDatabase' => __DIR__ . '/../..' . '/src/database/migrations/0000_00_00_000004_users_database.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit871dcb5e1723e9a64ecdbe36c3686d1b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit871dcb5e1723e9a64ecdbe36c3686d1b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit871dcb5e1723e9a64ecdbe36c3686d1b::$classMap;

        }, null, ClassLoader::class);
    }
}
