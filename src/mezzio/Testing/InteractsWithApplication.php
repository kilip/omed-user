<?php

/*
 * This file is part of the Omed User project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Omed\User\Mezzio\Testing;

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Mezzio\Application;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Omed\User\Mezzio\ConfigProvider;
use Psr\Container\ContainerInterface;

trait InteractsWithApplication
{
    protected static ?ContainerInterface $container = null;

    protected static ?Application $app = null;

    protected static array $configProviders = [
        \Mezzio\Session\ConfigProvider::class,
        \Mezzio\Authentication\ConfigProvider::class,
        \DoctrineModule\ConfigProvider::class,
        \Laminas\Cache\ConfigProvider::class,
        \Laminas\Form\ConfigProvider::class,
        \Laminas\InputFilter\ConfigProvider::class,
        \Laminas\Filter\ConfigProvider::class,
        \Laminas\Paginator\ConfigProvider::class,
        \Laminas\Validator\ConfigProvider::class,
        \Laminas\Router\ConfigProvider::class,
        \Laminas\Hydrator\ConfigProvider::class,
        \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
        \Laminas\HttpHandlerRunner\ConfigProvider::class,

        \Mezzio\Helper\ConfigProvider::class,
        \Mezzio\ConfigProvider::class,
        \Mezzio\Router\ConfigProvider::class,
        \Laminas\Diactoros\ConfigProvider::class,

        ConfigProvider::class,
    ];

    /**
     * @param string|ArrayProvider|PhpFileProvider $provider
     */
    public static function addProvider($provider): void
    {
        if ( ! \in_array($provider, static::$configProviders, true)) {
            static::$configProviders[] = $provider;
        }
    }

    protected static function configure(): void
    {
    }

    protected static function initialize(): void
    {
        static::configure();
        static::initContainer();
    }

    protected static function initContainer(): void
    {
        $aggregator                 = new ConfigAggregator(static::$configProviders);
        $config                     = $aggregator->getMergedConfig();
        $deps                       = $config['dependencies'];
        $deps['services']['config'] = $config;

        $container         = new ServiceManager($deps);
        $app               = $container->get(Application::class);
        $middlewareFactory = $container->get(MiddlewareFactory::class);

        static::configurePipeline($app, $middlewareFactory, $container);

        static::$container = $container;
    }

    protected static function configurePipeline(
        Application $app,
        MiddlewareFactory $factory,
        ContainerInterface $container
    ): void {
        $app->pipe(ErrorHandler::class);
        $app->pipe(ServerUrlMiddleware::class);
        $app->pipe(RouteMiddleware::class);
        $app->pipe(ImplicitHeadMiddleware::class);
        $app->pipe(ImplicitOptionsMiddleware::class);
        $app->pipe(MethodNotAllowedMiddleware::class);
        $app->pipe(UrlHelperMiddleware::class);
        $app->pipe(DispatchMiddleware::class);
        $app->pipe(NotFoundHandler::class);
    }

    protected static function configureRoutes(
        Application $app,
        MiddlewareFactory $factory,
        ContainerInterface $container
    ) {
    }

    protected function getApplication(): Application
    {
        return $this->getService(Application::class);
    }

    protected function getService(string $id): object
    {
        if (null === static::$container) {
            static::initialize();
        }

        return static::$container->get($id);
    }
}
