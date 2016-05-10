<?php
namespace Spec\Helper;

use Kahlan\Plugin\Stub;

class Call 
{
  private $di;
  private $controller;
  private $instance;

  public function __construct($controller) 
  {
    $this->di = $this->getDI();
    $this->setContoller($controller);
  }

  public function setRequest($request) 
  {
  	$object = json_decode(json_encode($request));
  	$request = Stub::create(['extends' => 'Phalcon\Http\Request', 'layer' => true]);
    Stub::on($request)->method('getJsonRawBody')->andReturn($object);

    $this->di->set('request', $request);
  }

  public function setContoller($name) 
  {
    $this->controller = $name;
  }

  public function initialize() 
  {
    $controller = '\Api\\Controllers\\' . ucfirst($this->controller) . 'Controller';
    $this->instance = new $controller;
    $this->instance->setDI($this->di);

    return $this->instance;
  }

  public function getDI() 
  {
    return \Phalcon\DI::getDefault();
  }

  public function call($action, $args = []) 
  {
  	ob_start();
	call_user_func_array([$this->instance, $action], $args);
	$message = ob_get_contents();
	ob_end_clean();

	return json_decode($message, true);
  }

  public function run($action='index', $args = []) 
  {
    $this->initialize();
    return $this->call($action, $args);
  }
}
