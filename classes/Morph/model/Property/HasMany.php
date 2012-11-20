<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * This property type represents a collection of many Morph_Objects.
 *
 * Each object contained within this collection is stored separately as it's own object,
 * independent of the parent object
 *
 * @package Morph
 * @subpackage Property
 */
class  Morph_Property_HasMany extends Morph_Property_Complex
{

    /**
     * @var boolean
     */
    protected $loaded = false;

    /**
     * @var String
     */
    protected $type;

    /**
     * @var array
     */
    protected $references;


    /**
     *
     * @param String $name
     * @param String $type
     * @return Morph_Property_HasMany
     */
    public function __construct($name, $type)
    {
        $this->type = $type;
        $default = new Morph_Property_StatefulCollection($this);
        $default->setPermissableType($type);
        parent::__construct($name, $default);
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value, $state = null)
    {
        $this->references = $value;
        $this->loaded = false;
        if (null != $state) {
            $this->state = $state;
        }
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__getRawValue()
     */
    public function __getRawValue()
    {
        if(count($this->value) > 0){
            $refs = array();
            foreach ($this->value as $object){
                if($object->state() != Morph_Enum::STATE_CLEAN){
                    Morph_Storage::instance()->save($object);
                }
                $refs[] = Morph_Utils::objectReference($object);
            }
            $this->references = $refs;
        }
        return $this->references;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#getValue()
     */
    public function getValue()
    {
        if($this->loaded === false){
            $this->loadFromReferences();
        }
        return $this->value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#setValue()
     */
    public function setValue($value)
    {
        if($value instanceof Morph_Property_StatefulCollection) {
            $value->setOwner($this);
            $this->value = $value;
            $this->references = array();
        }
    }


    /**
     * Loads the stored references
     * @return void
     */
    private function loadFromReferences()
    {
        $ids = array();
        $collection = null;
        if (count($this->references) > 0) {
            foreach ($this->references as $reference){
                $ids[] = $reference['$id'];
            }

            $query = new Morph_Query();
            $query->property('_id')->in($ids);

            $object = new $this->type;

            $collection = Morph_Storage::instance()
                    ->findByQuery($object, $query)
                    ->toCollection(); //@todo this could get nasty with large collections!
            
        }
        
        $this->value =  new Morph_Property_StatefulCollection($this, $collection);
        $this->loaded = true;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        $segments = array();
        foreach ($this->getValue() as $object) {
            $segments[] = $object->__toString();
        }
        return implode("\n", $segments);
    }
}
