<?php
/**
 * @author Jonathan Moss
 * @copyright 2009 Jonathan Moss <xirisr@gmail.com>
 * @package Morph
 */

/**
 * Morph collection object
 *
 * This is designed to work with finder methods within Morph_Object
 *
 * This object behaves like an array (e.g. it is Iterable, Countable and
 * provides ArrayAccess).
 *
 * This collection class also provides a bunch of additional functionality
 * such as sorting, filtering, reducing etc
 *
 * @package Morph
 */
class Morph_Property_StatefulCollection extends Morph_Collection
{
    
    protected $owner; 
    
    public function __construct(Morph_Property_Complex $owner, Morph_Collection $collection = null)
    {
        if (null !== $collection)
        {
            foreach ($collection as $object) {
                $this->append($object);
            }
        }
        $this->setOwner($owner);
    }
    
    public function setOwner(Morph_Property_Complex $owner)
    {
        $this->owner = $owner;
    }
    
    public function append($object)
    {
        $this->onChange();
        parent::append($object);
    }
    
    public function offsetSet($index, $newval)
    {
        $this->onChange();
        parent::offsetSet($index, $newval);
    }
    
    private function onChange()
    {
        if (null !== $this->owner) {
            $this->owner->_onChange();
        }
    }
}