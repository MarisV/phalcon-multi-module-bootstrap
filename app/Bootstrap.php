<?php

namespace app;

use Phalcon\Loader;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;


/**
 *  Init and register common services for modules.
 *
 * Class Bootstrap
 * @package app
 */
class Bootstrap
{
    /**
     * Di container
     *
     * @var \Phalcon\DI\FactoryDefault
     */
    private $_di;

    /**
     * Constructor
     *
     * @param \Phalcon\DI\FactoryDefault $di
     */
    public function __construct($di)
    {
        $this->_di = $di;
    }

    public function bootstrap()
    {
        $this->initConfig();
        $this->initLoader();
        $this->initDb();
        $this->initUrlProvider();
        $this->initSession();
        $this->initRouter();

        return $this->_di;
    }

    /**
     * Register dirs -
     * init loader service
     */
    private function initLoader()
    {
        $loader = new Loader();

        $loader->registerDirs(array(
            './app/frontend/controllers/',
            './app/frontend/library/',
            './app/frontend/model/',
            './app/frontend/views/',
            './app/backend/controllers/',
            './app/backend/library/',
            './app/backend/model/',
            './app/backend/views/',
        ))->register();

        $this->_di->setShared('loader', $loader);
    }

    /**
     * Init database service
     */
    private function initDb()
    {
        // Setup the database service
        $access = include __DIR__.'/config/dbconfig.php';

        $db = new DbAdapter($access);

        $db->getInternalHandler()
            ->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $this->_di->setShared('db', $db);
    }

    /**
     * Init url provider service
     */
    private function initUrlProvider()
    {
        $di = $this->_di;

        $this->_di->set('url', function () use ($di) {
            $url = new UrlProvider();
            $url->setBaseUri('/' . $di->getLocale()->languageCode . '/');
            return $url;
        });
    }

    /**
     *  Init session service
     */
    private function initSession()
    {
        $di =$this->_di;
        $this->_di->setShared('session', function() use ($di) {
            $session = new SessionAdapter(array(
                'db' => $di->get('db')
            ));

            $session->setName('cpsid');
            $session->start();

            return $session;
        });
    }

    /**
     * Init router service
     */
    private function initRouter()
    {
        $di = $this->_di;

        // Create the router
        $router = null;
        require __DIR__ . '/config/routes.php';
        $this->_di->setShared('router', $router);
    }
}