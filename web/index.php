<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application(require __DIR__ . '/../app/config.php');

/**
 * register service provider
 */
$app->register(new \Silex\Provider\SessionServiceProvider());
$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new \Silex\Provider\HttpFragmentServiceProvider());
$app->register(new \Silex\Provider\ServiceControllerServiceProvider());
$app->register(new \Silex\Provider\MonologServiceProvider(), $app['monolog_config']);
$app->register(new \Silex\Provider\TwigServiceProvider(), $app['twig_config']);
$app->register(new \Silex\Provider\DoctrineServiceProvider(), $app['db_config']);

if ($app['debug']) {
    Symfony\Component\Debug\Debug::enable(E_ALL, true);
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), [
        'profiler.cache_dir' => __DIR__ . '/../app/cache/profiler'
    ]);
}

/**
 * register internal object in container
 */

$app['twitter.client'] = function ($app) {
    return new TwitterAPIExchange($app['twitter_auth']);
};

$app['twitter.service.factory'] = function ($app) {
    return new \Jowy\RabbitInternet\Factory\TwitterServiceFactory($app['twitter.client']);
};

$app['twitter.service'] = function ($app) {
    return $app['twitter.service.factory']->create();
};

$app['google.geocode.factory'] = function ($app) {
    return new \Jowy\RabbitInternet\Factory\GoogleGeocodeServiceFactory();
};

$app['geocode.service'] = function ($app) {
    return $app['google.geocode.factory']->create($app['google_auth']);
};

$app['sqlite.storage.factory'] = function ($app) {
    return new \Jowy\RabbitInternet\Factory\SqliteStorageFactory($app);
};

$app['sqlite.storage'] = function ($app) {
    return $app['sqlite.storage.factory']->create();
};

$app['search.service'] = function ($app) {
    return new \Jowy\RabbitInternet\Services\Search(
        $app['sqlite.storage'],
        $app['twitter.service'],
        $app['settings']['cache_ttl']
    );
};

/**
 * mount controller
 */
$app->mount('/', new \Jowy\RabbitInternet\Controllers\MainController());

/**
 * setting middleware to set and retrieve cookie
 */
$app->after(function (
    \Symfony\Component\HttpFoundation\Request $request,
    \Symfony\Component\HttpFoundation\Response $response
) {
    $cookie = $request->cookies;

    if (! $cookie->has('Jowy-UserId')) {
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie(
            'Jowy-UserId',
            uniqid('Jowy', false)
        ));
    }

    return $response;
});

$app->run();
