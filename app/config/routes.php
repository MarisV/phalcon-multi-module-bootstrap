<?php

use Phalcon\Mvc\Router;

// Create the router
$router = new Router();

$router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);

$router->setDefaultModule('frontend');



//
// +++++ NOT FOUND +++++
//
$router->notFound(
    array(
        "controller" => "error",
        "action"     => "error404"
    )
);

//
// +++++ DEFAULT +++++
//

$router->add('/', array(
    'module'      =>  'frontend',
    'controller'  => 'index',
    'action'      => 'index'
));

$router->add('/admin', array(
    'module'      => 'backend',
    'controller'  => 'index',
    'action'      => 'index'
));


return $router;