<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Filter_Boolean
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Boolean.php 10.13.2011 1:55PM ayoola $
 */

/**
 * @see Ayoola_
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Filter_Boolean
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Filter_Boolean implements Ayoola_Filter_Interface
{
	
    /**
     * Prefix to use for the filtered value
     *
     * @var array
     */
	public $prefix = array( 0 => 'No', 1 => 'Yes' );
	
	
    /**
     *
     * @param string
     * @return 
     */
    public function __construct( $timeSettings = null )
    {
		if( isset( $timeSettings[0], $timeSettings[1] ) )
		{
			return $this->prefix = $timeSettings;
		}
    } 

    /**
     * This method does the main filtering biz.
     *
     * @param int UNIX time()
     * @return string 
     */
    public function filter( $value )
    {
	//	var_export( $value );
	//	var_export( $this->prefix );
		return $this->prefix[intval($value)];
    } 
	
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public function autofil( $parameter )
    {
		if( isset( $parameter[0], $parameter[1] ) )
		{
			return $this->prefix = $timeSettings;
		}
    } 
	
	
	// END OF CLASS
}
