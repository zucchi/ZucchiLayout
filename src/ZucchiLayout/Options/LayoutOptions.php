<?php
/**
 * ZucchiLayout (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiLayout for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiLayout\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiLayout 
 * @subpackage Options 
 */
 class LayoutOptions extends AbstractOptions 
{
    protected $path = '/data/zucchi/layout/';
    
    protected $layout = 'zucchi-simple';
	/**
     * @return the $path
     */
    public function getPath ()
    {
        return $this->path;
    }

	/**
     * @param string $path
     */
    public function setPath ($path)
    {
        $path = trim($path,' /');
        $this->path = '/' . $path . '/';
    }

	/**
     * @return the $layout
     */
    public function getLayout ()
    {
        return $this->layout;
    }

	/**
     * @param string $layout
     */
    public function setLayout ($layout)
    {
        $this->layout = $layout;
    }

}