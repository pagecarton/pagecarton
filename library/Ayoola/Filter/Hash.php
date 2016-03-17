<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_Hash
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Hash.php 10.13.2011 1:55PM ayoola $
 */

/**
 * @see Ayoola_
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_Hash
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Filter_Hash implements Ayoola_Filter_Interface
{
    /**
     * The algorithm used in hashing
     *
     * @var string
     */
	protected $_algo = 'MD5';
	
	
    /**
     * Constructor allows me to accept algorithm as a parameter when instantiating class .
     *
     * @param string
     * @return 
     */
    public function __construct( $algo = 'MD5' )
    {
        return $this->setAlgo( $algo );
    } 

    /**
     * This method does the main filtering biz.
     *
     * @param 
     * @return 
     */
    public function filter( $value )
    {
        return hash( $this->getAlgo(), $value );
		
    } 

    /**
     * Sets the Algorithm to a value
     *
     * @param string
     * @return mixed
     */
    public function setAlgo( $hash = null )
    {
        $algos = hash_algos();
		
		if( in_array( $hash, $algos ) )
			return $this->_algo = $hash;
    } 
	
	
	
    /**
     * Retrieves the algorithm
     *
     * @param void
     * @return string
     */
    public function getAlgo( )
    {
        return $this->_algo;
		
    } 
	
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public function autofil( $parameter )
    {
		if( ! empty( $parameter[0] ) )
        return setAlgo( $parameter[0] );
		
    } 
	
	
	// END OF CLASS
}
