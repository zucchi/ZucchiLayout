<?php
/**
 * ZucchiLayout (http://framework.zend.com/)
 *
 * @link      http://github.com/zucchi/ZucchiLayout for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZucchiLayout\Entity;

/**
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiLayout
 * @subpackage Entity
 * @category Layout
 */
class Layout
{
    /**
     * id
     * @var integer
     */
    public $id;
    
    /**
     * unique identifying string format: publisher/design i.e. zucchi/garish 
     * @var string
     */
    public $key;
    
}