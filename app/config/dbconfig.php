<?php

return array(
    'host'     => 'localhost',
    'username' => getenv('APP_DATABASE_USER'),
    'password' => getenv('APP_DATABASE_PASS'),
    'dbname' => getenv('APP_DATABASE_NAME'),
    'charset' => 'utf8'
);