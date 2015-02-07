<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class BaseController extends AbstractActionController
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_objectManager;
 
    /**
     * for managing entities via Doctrine
     * @return Doctrine\ORM\EntityManager
     */
    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_objectManager;
    }
}