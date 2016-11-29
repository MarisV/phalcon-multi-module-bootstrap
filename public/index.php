<?php

use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL | E_STRICT); // Must be disabled on production!
ini_set('display_errors', 'on'); // Must be disabled on production!

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    require __DIR__ . '/../app/Bootstrap.php';

    $di = new FactoryDefault();

    // get application env from server variables
    // if not available set default to production
    if (isset($_SERVER['APPLICATION_ENV'])) {
        switch($_SERVER['APPLICATION_ENV']) {
            case 'production':
                define('APPLICATION_ENV', 'production');
                break;
            case 'development':
                define('APPLICATION_ENV', 'development');
                break;

            case 'local':
                define('APPLICATION_ENV', 'local');
                break;
            default:
                define('APPLICATION_ENV', 'production');
                break;
        }
    }

    $bootstrap = new \app\Bootstrap($di);

    $application = new \Phalcon\Mvc\Application($bootstrap->bootstrap());

    $application->registerModules([
        "frontend" => [
            "className" => "app\\frontend\\Module",
            "path"      => "../app/frontend/Module.php",
        ],
        "backend"  => [
            "className" => "app\\backend\\Module",
            "path"      => "../app/backend/Module.php",
        ]
    ]);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
