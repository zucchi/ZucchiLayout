<?php
/**
 * ZucchiLayout (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/ZucchiLayout for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited. (http://zucchi.co.uk)
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
use ZucchiDoctrine\Form\Annotation as DoctrineForm;

/**
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiLayout
 * @subpackage Entity
 * 
 * @ORM\Entity
 * @ORM\Table(name="zucchi_layout")
 * @ORM\HasLifecycleCallbacks
 * @Form\Name("layout")
 * @Form\Hydrator("zucchidoctrine.entityhydrator")
 */
class Layout extends AbstractEntity
{
    use ChangeTrackingTrait;
    use TimestampableTrait;

    /**
     * 
     * @var string
     * @ORM\Column(type="string")
     * @Form\Required(false)
     * @Form\Type("\Zend\Form\Element\Text")
     * @Form\Attributes({"type":"text", "disabled" : true})
     * @Form\Options({
     *     "label":"Name",
     *     "bootstrap": {
     *         "help": {
     *             "style": "inline",
     *             "content": "The Name of the layout"
     *         }
     *     }
     * })
     */
    protected $name;
    
    /**
     * 
     * @var string
     * @ORM\Column(type="string")
     * @Form\Required(false)
     * @Form\Type("\Zend\Form\Element\Text")
     * @Form\Attributes({"type":"text", "disabled" : true})
     * @Form\Options({
     *     "label":"Vendor",
     *     "bootstrap": {
     *         "help": {
     *             "style": "inline",
     *             "content": "The Name of the Vendor"
     *         }
     *     }
     * })
     */
    protected $vendor;
    
    /**
     * 
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"vendor","name"}, separator="-", unique=false, updatable=false)
     * @Form\Required(false)
     * @Form\Type("\Zend\Form\Element\Text")
     * @Form\Attributes({"type":"text", "disabled" : true})
     * @Form\Options({
     *     "label":"Key",
     *     "bootstrap": {
     *         "help": {
     *             "style": "inline",
     *             "content": "The Key for the layout"
     *         }
     *     }
     * })
     */
    protected $folder;
    
    /**
     * 
     * @var string
     * @ORM\Column(type="string")
     * @Form\Required(false)
     * @Form\Type("\Zend\Form\Element\Text")
     * @Form\Attributes({"type":"text", "disabled" : true})
     * @Form\Options({
     *     "label":"Description",
     *     "bootstrap": {
     *         "help": {
     *             "style": "inline",
     *             "content": "A description of the layout"
     *         }
     *     }
     * })
     */
    protected $description;
    
    /**
     * 
     * @var unknown_type
     * @ORM\Column(type="string")
     * @Form\Required(false)
     * @Form\Type("\Zend\Form\Element\Text")
     * @Form\Attributes({"type":"text", "disabled" : true})
     * @Form\Options({
     *     "label":"Homepage",
     *     "bootstrap": {
     *         "help": {
     *             "style": "inline",
     *             "content": "The homepage for the layout"
     *         }
     *     }
     * })
     */
    protected $homepage;
    
    /**
     * 
     * @var string
     * @ORM\Column(type="string")
     * @Form\Required(false)
     * @Form\Type("\Zend\Form\Element\Text")
     * @Form\Attributes({"type":"text", "disabled" : true})
     * @Form\Options({
     *     "label":"License",
     *     "bootstrap": {
     *         "help": {
     *             "style": "inline",
     *             "content": "The license the layout has been released under"
     *         }
     *     }
     * })
     */
    protected $license;

    /**
     *
     * @var string
     * @ORM\Column(type="boolean")
     * @Form\Required(false)
     * @Form\Type("\Zend\Form\Element\Checkbox")
     * @Form\Required(false)
     * @Form\Attributes({"type":"checkbox"})
     * @Form\Options({
     *     "label":"Active",
     *     "bootstrap": {
     *         "help": {
     *             "style": "inline",
     *             "content": "Is the Layout active and available for display"
     *         }
     *     }
     * })
     */
    protected $active;
    
    /**
     * 
     * @var array
     * @ORM\OneToMany(targetEntity="ZucchiLayout\Entity\Keyword", mappedBy="Layout")
     * @Form\Exclude
     */
    protected $Keywords;
    
    /**
     * 
     * @var array
     * @ORM\OneToMany(targetEntity="ZucchiLayout\Entity\Author", mappedBy="Layout")
     * @Form\Exclude
     */
    protected $Authors;

    /**
     * @var PersistantCollection
     * @ORM\OneToMany(
     *      targetEntity="ZucchiLayout\Entity\Schedule",
     *      mappedBy="Layout",
     *      cascade={"persist"},
     *      orphanRemoval=true
     * )
     * @ORM\OrderBy({"displayFrom" = "ASC"})
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Collection")
     * @Form\Attributes({"class":"table table-hover table-condensed", "style": "background-color:#FFF"})
     * @Form\Options({
     *     "label" : "Schedule",
     *     "bootstrap": {
     *         "displayAs":"table",
     *     },
     *     "count" : 0,
     *     "should_create_template" : true,
     *     "allow_add" : true,
     *     "allow_remove" : true,
     *     "target_element" : {
     *          "composedObject" : "ZucchiLayout\Entity\Schedule"
     *      }
     * })
     */
    protected $Schedule;
}