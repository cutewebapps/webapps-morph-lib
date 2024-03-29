<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Class to represent an integer property
 *
 * @package Morph
 * @subpackage Property
 */
class Morph_Property_Regex extends Morph_Property_Generic
{
	/**
	 * @param string $name
     *
     * @return void
	 */
	public function __construct($name)
	{
		parent::__construct($name, null);
	}

	/**
	 * Sets the value of this attribute
	 *
	 * @param string $value
	 */
	public function setValue($value)
	{
        parent::setValue(new MongoRegex((string)$value));
	}

	/**
	 * Returns this attributes value
	 *
	 * @return string
	 */
	public function getValue()
	{
		return '/' . (string)parent::getValue()->regex . '/';
	}
}
