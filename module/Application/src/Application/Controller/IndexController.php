<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Entity;

class IndexController extends BaseController
{
    
    public function indexAction()
    {
        $objectManager = $this->getObjectManager();
        
        $cache = new \Doctrine\Common\Cache\ArrayCache();
        
        $vacancy = new \Application\Entity\Vacancies();
        
        $request = $this->getRequest();
        
        $filter['lang'] = (int) $request->getQuery('lang', 1);
        $filter['dep'] = array();
        foreach($request->getQuery('dep', array()) as $v) {
            $filter['dep'][] = (int) $v;
        }
        
        $vacancies = $vacancy->getVacanciesAll($objectManager, $filter);
        
        arsort($vacancies);
        
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
}
