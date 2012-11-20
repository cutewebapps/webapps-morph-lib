<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Class to represent a property within a table
 *
 * See Morph_Property_* for more specific implementations
 *
 * If you wish to create a more specialised property type you can extend
 * this class. Overloading getValue() and setValue() allow you to
 * provide automatic marshalling of data.
 *
 * @package Morph
 * @subpackage Property
 */
class Morph_Property_Complex extends Morph_Property_Generic
{
    /**
     * Called when the property changes
     * 
     * @return void
     */
    public function _onChange()
    {
        if ($this->state == Morph_Enum::STATE_CLEAN) {
            $this->state = Morph_Enum::STATE_DIRTY;
        }
    }
}
