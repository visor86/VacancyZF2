<?php

namespace ApplicationTest\Controller;

use ApplicationTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\VacancyController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class VacancyControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        
        $this->controller = new VacancyController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'vacancy'));
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
        
        $postData = array(
            'departmentsDepartment' => array(1),
            'vacancyId' => '',
            'enabled' => 1,
            "title_{$this->getConfig()->language_default}" => 'Vacancy test title for language EN',
            "text_{$this->getConfig()->language_default}" => 'Vacancy test text for language EN',
            'submit' => 'Add'
        );
        
        $this->request->setMethod('post');
        foreach($postData as $key => $value) {
            $this->request->getPost()->set($key, $value);
        }
        
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEditActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', '1');
        
        $postData = array(
            'departmentsDepartment' => array(1),
            'vacancyId' => 1,
            'enabled' => 1,
            "title_{$this->getConfig()->language_default}" => 'Vacancy test title for language EN',
            "text_{$this->getConfig()->language_default}" => 'Vacancy test text for language EN',
            'id' => array($this->getConfig()->language_default => 1),
            'submit' => 'Save'
            
        );
        
        $this->request->setMethod('post');
        foreach($postData as $key => $value) {
            $this->request->getPost()->set($key, $value);
        }
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testDeleteActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', '1');
        
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        
        $this->assertEquals(302, $response->getStatusCode());
        // Delete test department
        $department = new DepartmentControllerTest();
        $department->deleteActionCanBeAccessed();
        // Delete test language
        $language = new LanguageControllerTest();
        $language->deleteActionCanBeAccessed();
    }
    
    public function getConfig() {
        return new \Zend\Config\Config(include __DIR__ . '/../../../config/config.php');
    }

    
}

