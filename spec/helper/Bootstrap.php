<?php

$di = new \Phalcon\DI\FactoryDefault();

$di->set('db', function () {
    return new Phalcon\Db\Adapter\Pdo\Sqlite(array(
      "dbname" => __DIR__ . "/../database/test.sqlite"
    ));
});

\Phalcon\DI::setDefault($di);
