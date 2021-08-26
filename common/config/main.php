<?php

use yii\db\Connection;

return [
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=localhost:3306;dbname=',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];
