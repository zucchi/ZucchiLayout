<?php
/**
 * ZucchiLayout (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/ZucchiLayout for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiLayout\Entity;

use ZucchiDoctrine\Entity\AbstractEntity;
use ZucchiDoctrine\Entity\ChangeTrackingTrait;
use ZucchiDoctrine\Behavior\Timestampable\TimestampableTrait;

use Zucchi\Debug\Debug;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Zend\Form\Annotation AS Form;

/**
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiLayout
 * @subpackage Entity

 * @ORM\Entity
 * @ORM\Table(name="zucchi_layout_keywords")
 * @ORM\HasLifecycleCallbacks
 * @Form\Name("layout_keywords")
 * @Form\Hydrator("zucchidoctrine.entityhydrator")
 */
class Keyword extends AbstractEntity
{
    /**
     * 
     * @var string
     * @ORM\Column(type="string")
     */
    protected $value;
    
    /**
     * 
     * @var array
     * @ORM\ManyToOne(targetEntity="ZucchiLayout\Entity\Layout", inversedBy="Keywords")
     * @ORM\JoinColumn(name="Layout_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $Layout;
}