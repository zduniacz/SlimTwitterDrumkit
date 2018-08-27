<?php
/*  This is an initial file of the application
    It launches the app in Slim framework with all the settings. 
*/
require '../vendor/autoload.php';

// load phpdotenv package to get easy access to sensitive variables in .env file with superglobal $_ENV('NAME')
$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
$dotenv->load();

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

// load the container to inject our dependencies
$container = $app->getContainer();

// add Twig engine to the app container
$container['view'] = function ($container) {
    $view = new Slim\Views\Twig(__DIR__ . '\views');
    // Instatiate Twig and add Slim extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};

// add chosen Twitter integration service to the app container
$container['twitter'] = function () {
    return new App\Services\Twitter\TwitterCodebird;
};

require 'routes.php';
