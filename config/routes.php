<?php


use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    
    $routes->setRouteClass(DashedRoute::class);

    $routes->prefix('/dienst',function(RouteBuilder $route){
        $route->connect('/',['controller'=> 'Clients','action'=>'index']);
        $route->connect('/auditoria',['controller'=>'Auditor','action'=>'view']);
        $route->connect('/empresas',['controller'=>'Company','action'=>'view']);
        $route->connect('/auditores',['controller'=>'Auditor','action'=>'view']);

        $route->fallbacks();
    });
    $routes->prefix('Admin',function(RouteBuilder $route){
        $route->connect('/',['controller'=>'User','action'=>'index']);
        $route->fallbacks();
    });
    $routes->scope('/', function (RouteBuilder $builder) {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        $builder->connect('/', ['controller' => 'Users', 'action' => 'login']);

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
