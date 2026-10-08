<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;
use Dotenv\Dotenv;

return function (ContainerBuilder $containerBuilder) {

    // Charger les variables du fichier .env
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {

            return new Settings([
                'displayErrorDetails' => true,
                'logError'            => false,
                'logErrorDetails'     => false,

                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker'])
                        ? 'php://stdout'
                        : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],

                // Configuration de la base de données
                'db' => [
                    'host' => $_ENV['DB_HOST'],
                    'port' => (int) $_ENV['DB_PORT'],
                    'database' => $_ENV['DB_DATABASE'],
                    'username' => $_ENV['DB_USERNAME'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'charset' => $_ENV['DB_CHARSET'],

                    'flags' => [
                        \PDO::ATTR_PERSISTENT => false,
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_EMULATE_PREPARES => true,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    ],
                ],
            ]);
        }
    ]);
};