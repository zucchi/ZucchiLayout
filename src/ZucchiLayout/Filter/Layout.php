<?php
namespace ZucchiLayout\Filter;

use ZucchiArchive\AdapterAwareTrait;
use ZucchiArchive\Adapter;
use Zend\Filter\AbstractFilter;
use Zend\Json\Json;

class Layout extends AbstractFilter
{
    use AdapterAwareTrait;
    
    protected $destination;
    
    public function __construct($options)
    {
        $this->setOptions($options);
    }
    
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }
    
    public function getDestination()
    {
        return $this->destination;
    }
    
    /**
     * Defined by Zend_Filter_Filter
     *
     * Decompresses the content $value with the defined settings
     *
     * @param  string $value Content to decompress
     * @return string The decompressed content
     */
    public function filter($value)
    {
        $adapter = $this->getAdapter($value);
        $adapter->setArchive($value);
        
        $jsonString = $adapter->getFileContents('layout.json');
        $layoutData = Json::decode($jsonString);
        
        $key = $layoutData->vendor . '-' . $layoutData->name;
        
        $destination  = $this->getDestination();

        if (!is_dir($destination)) {
            $destination = dirname($destination) . DIRECTORY_SEPARATOR;
        }
        
        $destination .= $key;
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        $adapter->setTarget($destination);
        
        $result = $adapter->decompress();
        
        return $result;
    }
}