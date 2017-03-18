<?php


use App\Controllers\IndexController;
use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

$container = $app->getContainer();

$container['logger'] = function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Logger($settings['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

$container['view'] = function (ContainerInterface $c) {

    $settings = $c->get('settings')['view'];
    $view = new Twig(
        $settings['templates'], [
            'cache' => $settings['cache']
        ]
    );

    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($c['router'], $basePath));

    return $view;
};

$container['twitterAPI'] = function (ContainerInterface $c) {

    $settings = $c->get('settings')['twitter'];
    $twitter = App\DgTwitter::create(
        $settings['consumer']['key'],
        $settings['consumer']['secret'],
        $settings['access']['token'],
        $settings['access']['secret'],
        $settings['cache']
    );

    return $twitter;
};

$container['textAPI'] = function (ContainerInterface $c) {

    $settings = $c->get('settings')['aylien'];
    $textapi = new AYLIEN\TextAPI(
        $settings['app_id'],
        $settings['key']
    );

    return $textapi;
};

$container[IndexController::class] = function(ContainerInterface $c) {
    $view = $c->get("view"); // retrieve the 'view' from the container
    $twitterAPI = $c->get('twitterAPI');
    $textAPI = $c->get('textAPI');
    return new IndexController($view, $twitterAPI, $textAPI);
};



