<?php
    
	
class Ayoola_Filter_Trim implements Ayoola_Filter_Interface
{
		
    /**
     * Loop filter if value is array
     *
     * @var boolean
     * @access public
     */
	public $loopFilter = true;

    public function filter( $value )
	{
		$value = trim( $value );
		return $value;
	}
 
}
