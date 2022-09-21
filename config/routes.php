<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {

    $routes->setRouteClass(DashedRoute::class);


    $routes->prefix('Admin', function (RouteBuilder $route) {
        $route->connect('/manual/*', ['controller' => 'Pages', 'action' => 'manual', 'prefix' => null]);
        $route->connect('/', ['controller' => 'users', 'action' => 'index']);
        $route->connect('/usuarios', ['controller' => 'users', 'action' => 'index']);
        $route->connect('/usuarios/agregar', ['controller' => 'users', 'action' => 'add']);
        $route->fallbacks();
    });

    $routes->prefix('Auditor', function (RouteBuilder $route) {
        $route->connect('/manual/*', ['controller' => 'Pages', 'action' => 'manual', 'prefix' => null]);
        $route->connect('/listado', ['controller' => 'reports', 'action' => 'index']);
        $route->connect('/', ['controller' => 'reports', 'action' => 'withOutDiagnostic']);
        $route->connect('/listado-sin-diagnostico', ['controller' => 'reports', 'action' => 'withOutDiagnostic']);
        $route->connect('/licencias/diagnosticar/{id}', ['controller' => 'reports', 'action' => 'edit'])
            ->setPass(['id'])
            ->setPatterns([
                'id' => '[0-9]+',
            ]);
        $route->connect('/licencias/ver/{id}', ['controller' => 'reports', 'action' => 'view'])
            ->setPass(['id'])
            ->setPatterns([
                'id' => '[0-9]+',
            ]);
        $route->fallbacks();
    });

    $routes->prefix('redPrestacional', function (RouteBuilder $route) {
        $route->connect('/manual/*', ['controller' => 'Pages', 'action' => 'manual', 'prefix' => null]);
        $route->connect('/', ['controller' => 'Patients', 'action' => 'index']);
        $route->connect('/listado', ['controller' => 'Patients', 'action' => 'index']);
        $route->connect('/nuevo-ausente', ['controller' => 'Patients', 'action' => 'addWithReport']);
        $route->connect('/nuevo-paciente', ['controller' => 'Patients', 'action' => 'add']);
        $route->connect('/paciente/ver/{id}', ['controller' => 'Patients', 'action' => 'view'])
            ->setPass(['id'])
            ->setPatterns([
                'id' => '[0-9]+',
            ]);
        $route->connect('/paciente/editar/{id}', ['controller' => 'Patients', 'action' => 'edit'])
            ->setPass(['id'])
            ->setPatterns([
                'id' => '[0-9]+',
            ]);
        $route->connect('/paciente/resultado/{id}/{paciente}', ['controller' => 'Patients', 'action' => 'result'])
            ->setPass(['id'])
            ->setPatterns([
                'id' => '[0-9]+',
            ]);
        $route->connect('/empresas', ['controller' => 'Companies', 'action' => 'index']);
        $route->connect('/empresas/crear', ['controller' => 'Companies', 'action' => 'add']);
        $route->fallbacks();
    });

    $routes->scope('/', function (RouteBuilder $builder) {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        $builder->connect('/', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/salir', ['controller' => 'Users', 'action' => 'logout']);
        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */
        $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder) {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
