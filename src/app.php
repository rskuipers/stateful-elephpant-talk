<?php

namespace Application;

use Silex\Application;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

$app = new Application();

$app['debug'] = true;

$app->register(new UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SessionServiceProvider());

$app['twig.path'] = [__DIR__.'/../templates'];
$app['twig.options'] = ['cache' => __DIR__.'/../var/cache/twig'];

require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/checkout.php';

return $app;
