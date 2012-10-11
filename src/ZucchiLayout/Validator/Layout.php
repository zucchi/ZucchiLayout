<?php
namespace ZucchiLayout\Validator;

use Zucchi\Archive\AdapterAwareTrait;
use Zend\Validator\AbstractValidator;
use Zend\Json\Json;

class Layout extends AbstractValidator
{
    use AdapterAwareTrait;
    
    
    const MISSING_FILE    = 'missingFile';
    const INVALID_JSON    = 'invalidJson';
    const MISSING_VALUE    = 'missingValue';
    
    protected $messageTemplates = array(
        self::MISSING_FILE         => "No '%value%' file found in the root of the archive",
        self::INVALID_JSON         => "There was a problem with the 'layout.json' file : %value%",
        self::MISSING_VALUE        => "The 'layout.json' file is missing a value for '%value%'",
    );
    
    protected $files = array();
    
    
    public function isValid($value)
    {
        
        $adapter = $this->getAdapter($value);
        $adapter->setArchive($value);
        $contents = $adapter->listContent();
        
        $hasJson = false;
        $hasPhtml = false;
        $layout = false;
        foreach ($contents as $c) {
            if ($c['filename'] == 'layout.json') {
                $hasJson = true;
            }
            if ($c['filename'] == 'layout.phtml') {
                $hasPhtml = true;
            }
            if ($c['filename'] == 'layout.png') {
                $hasPhtml = true;
            }
        }
        
        if (!$hasPhtml) {
            $this->error(self::MISSING_FILE, 'layout.phtml');
            return false;
        }
        
        if (!$hasPhtml) {
            $this->error(self::MISSING_FILE, 'layout.png');
            return false;
        }
        
        if ($hasJson) {
            $jsonString = $adapter->getFileContents('layout.json');
            try {
                $layoutData = Json::decode($jsonString);
            } catch (Exception $e) {
                $this->error(self::INVALID_JSON, $e->getMessage());
                return false;
            }
            
            if (!isset($layoutData->vendor) || strlen($layoutData->vendor) == 0) {
                $this->error(self::MISSING_VALUE, 'vendor');
                return false;
            }
            if (!isset($layoutData->name) || strlen($layoutData->name) == 0) {
                $this->error(self::MISSING_VALUE, 'name');
                return false;
            }
            
        } else {
            $this->error(self::MISSING_FILE, 'layout.json');
            return false;
        }
        
        return true;
    }
}
