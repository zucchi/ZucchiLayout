<?php
namespace ZucchiLayout\Service;

use ZucchiLayout\Options\LayoutOptions;
use Zend\Config\Config;
use Zend\Config\Reader\Json;

class LayoutService
{
    protected $options;
    
    public function setOptions(LayoutOptions $options)
    {
        $this->options = $options;
        return $this;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function getInstalledLayoutData()
    {
        $path = $this->getOptions()->getPath();
        $reader = new Json();
        $data = new Config(array(), true);
        $iterator = new \DirectoryIterator(getcwd() . $path);
        foreach ($iterator as $dir) {
            $key = $dir->getFilename();
            $jsonFile = $dir->getPath() . '/' . $key . '/layout.json'; 
            if (file_exists($jsonFile)) {
                $data->{$key} = $reader->fromFile($jsonFile); 
            }
        }
        
        return $data;
    }
}