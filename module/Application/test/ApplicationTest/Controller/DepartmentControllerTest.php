<?php

namespace ApplicationTest\Controller;

use ApplicationTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\DepartmentController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class DepartmentControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new DepartmentController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'department'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    
    public function testIndexActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'add');
        
        $this->request->setMethod('post');
        $this->request->getPost()->set('title', 'Department test');
        $this->request->getPost()->set('submit', 'Add');        
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEditActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', '1');
        
        $this->request->setMethod('post');
        $this->request->getPost()->set('title', 'Department test');
        $this->request->getPost()->set('submit', 'Add');        

        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        fwrite(STDERR, print_r($this->routeMatch, TRUE));
        
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testDeleteActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', '2');
        
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        
        $this->assertEquals(302, $response->getStatusCode());
    }

    
}

