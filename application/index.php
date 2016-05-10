<?php
use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Sqlite as Database;
use Phalcon\Mvc\Micro\Collection;

//
// Autoloading
//
$loader = new Loader();
$loader->registerNamespaces(array(
	'Api\Models' => __DIR__ . '/model/',
	'Api\Controllers' => __DIR__ . '/controller/',
))->register();

//
// Dependency injection bindings
// 
$di = new FactoryDefault();
$di->set('db', function () {
    return new Database(
        array(
        	"dbname" => __DIR__ . "/database/db.sqlite"
        )
    );
});

$app = new Micro($di);
$app->getRouter()->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);

// 
// Router
//
$users = new Collection();
$users->setHandler(new Api\Controllers\UserController());
$users->setPrefix('/api/users');
$users->get('/', 'index');
$users->get('/{id}', 'read');
$users->get('/search/{name}', 'search');
$users->post('/', 'create');
$users->put('/{id}', 'update');
$users->delete('/{id}', 'delete');
$app->mount($users);

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo json_encode([
    	'error' => "Path {$app->request->getURI()} not found in routes",
    	'result' => []
    ]);
});

$app->handle();