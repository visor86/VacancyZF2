<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $_objectManager;
    
    public function indexAction()
    {
        $objectManager = $this->getObjectManager();
        
        $cache = new \Doctrine\Common\Cache\ArrayCache();
        
        $vacancy = new \Application\Entity\Vacancies();
        
        $request = $this->getRequest();
        
        $filter['lang'] = (int) $request->getQuery('lang', 1);
        foreach($request->getQuery('dep', array()) as $v) {
            $filter['dep'][] = (int) $v;
        }
        
        $vacancies = $vacancy->getVacanciesAll($objectManager, $filter);
        
        $cacheId = 'languagesForVacancies';
        if ($cache->contains($cacheId)) {
            $languages = $cache->fetch($cacheId);
        } else {
            $languages = $vacancy->getLanguagesForVacancies($objectManager);
            $cache->save($cacheId, $languages);
        }
        
        $cacheId = 'departmentsForVacancies';
        if ($cache->contains($cacheId)) {
            $departments = $cache->fetch($cacheId);
        } else {
            $departments = $vacancy->getDepartnetsForVacancies($objectManager);
            $cache->save($cacheId, $departments);
        }
        
        return  new ViewModel(array(
            'languages' => $languages,
            'departments' => $departments,
            'vacancies' => $vacancies,
            'filter' => $filter
        ));
    }
    
    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_objectManager;
    }
}
