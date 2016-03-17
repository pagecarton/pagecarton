<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_MaxChar
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: MaxChar.php 5.23.2012 7.54am ayoola $
 */

/**
 * @see Ayoola_
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_MaxChar
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Filter_MaxChar implements Ayoola_Filter_Interface
{	
	
    /**
     * The maximum charater to return as a filter
     *
     * @var string
     */
    protected $_maxCharacters = 100;
	
    /**
     * The allowed mode of truncation
     *
     * @var string
     */
    protected $_truncationMode = 'end';
	
    /**
     *
     * @param string
     * @return 
     */
    public function __construct()
    {

    } 

    /**
     * This method does the main filtering biz.
     *
     * @param 
     * @return 
     */
    public function filter( $value )
    {
	//	var_export( $value );
	
		switch( $this->_truncationMode )
		{
			case 'start':
				$this->_maxCharacters = 0 - intval( $this->_maxCharacters );
				$value = substr( $value, $this->_maxCharacters );
			break;
			
			default:
				$value = substr( $value, 0, $this->_maxCharacters );
			break;
		}
	//	var_export( $this->_maxCharacters );
	//	var_export( $value );
        return $value;
    } 	
	
    /**
     * This method
     *
     * @param array
     * @return 
     */
    public function autofill( $parameter )
    {
	//	var_export( $parameter );
		if( ! empty( $parameter[0] ) ){ $this->_maxCharacters = $parameter[0]; }
		if( ! empty( $parameter[1] ) ){ $this->_truncationMode = $parameter[1]; }
    } 
	
	
	// END OF CLASS
}
