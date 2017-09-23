<?php

class Ayoola_Filter_StripTags implements Ayoola_Filter_Interface
{
		
    /**
     * Loop filter if value is array
     *
     * @var boolean
     * @access public
     */
	public $loopFilter = true;

	protected $_allowedTags = array();
	
    public function filter( $value )
	{
		$value = strip_tags( $value, implode( '', $this->_allowedTags )  );
		return $value;
	}
	
    public function setAllowedTag( $value )
	{
		if( strpos( $value, '<' ) !== 0 || strpos( $value, '>' ) < 2)
		return false;
		return $this->_allowedTags[] = (string) $value;
	}
 
    public function setAllowedTags(array $values )
	{
		foreach( $values as $value )
			$this->setAllowedTag( $value );
		return $this;
	}
	
    public function getAllowedTags( )
	{
		return $this->_allowedTags;
	}
 
}
