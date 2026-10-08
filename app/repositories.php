<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Application\Repositories\ArtistRepository;
use App\Application\Repositories\AlbumRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([

        // Repository des utilisateurs
        UserRepository::class => \DI\autowire(
            InMemoryUserRepository::class
        ),

        // Repository des artistes
        ArtistRepository::class => \DI\autowire(
            ArtistRepository::class
        ),

        // Repository des albums
        AlbumRepository::class => \DI\autowire(
            AlbumRepository::class
        ),
    ]);
};