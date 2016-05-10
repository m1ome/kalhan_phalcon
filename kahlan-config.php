<?php
use Kahlan\Filter\Filter;
use Kahlan\Jit\Interceptor;
use Kahlan\Jit\Patcher\Layer;

$args = $this->args();
$args->argument('ff', 'default', 1);
$args->argument('coverage', 'default', null);
$args->argument('reporter', 'default', 'dot');
$args->set('include', [
  'Api',
]);

Filter::register('api.namespaces', function($chain) {

  $this->autoloader()->addPsr4('Api\\Models\\', __DIR__ . '/application/model/');
  $this->autoloader()->addPsr4('Api\\Controllers\\', __DIR__ . '/application/controller/');
  $this->autoloader()->addPsr4('Spec\\Helper\\', __DIR__ . '/spec/helper/');

  return $chain->next();

});

Filter::register('api.patchers', function($chain) {
    if (!$interceptor = Interceptor::instance()) {
        return;
    }
    $patchers = $interceptor->patchers();
    $patchers->add('layer', new Layer([
        'override' => [
            'Phalcon\Mvc\Model' // this will dynamically apply a layer on top of the `Phalcon\Mvc\Model` to make it stubbable.
        ]
    ]));

    return $chain->next();
});

Filter::register('api.fixtures', function($chain) {
	$di = \Phalcon\DI::getDefault();

	$db = $di->get('db');
	$db->query("DELETE FROM users");

    require_once __DIR__ . '/spec/fixtures/user.php';

    return $chain->next();
});

Filter::apply($this, 'namespaces', 'api.namespaces');
Filter::apply($this, 'patchers', 'api.patchers');
Filter::apply($this, 'run', 'api.fixtures');

require_once __DIR__ . '/spec/helper/Bootstrap.php';
