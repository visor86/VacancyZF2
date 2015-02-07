<?php

namespace ApplicationTest\Model;

use Application\Entity\Languages as Languages;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class LanguagesTest extends PHPUnit_Framework_TestCase 
{
    public function testLanguageInitialState()
    {
        $model = new Languages();

        $this->assertNull(
            $model->getTitle(),
            '"title" should initially be null'
        );
        $this->assertNull(
            $model->languageId,
            '"languageId" should initially be null'
        );
    }
    
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $model = new Languages();
        $data  = array(
            'languageId'     => 123,
            'title'  => 'Language'
        );

        $model->exchangeArray($data);

        $this->assertSame(
            $data['languageId'],
            $model->languageId,
            '"languageId" was not set correctly'
        );
        $this->assertSame(
            $data['title'],
            $model->title,
            '"title" was not set correctly'
        );
    }
}
