<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_HybridAuth_EndPoint
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: EndPoint.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_HybridAuth_EndPoint
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_HybridAuth_EndPoint extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;

    /**
     * "Plays" the class
     *
     * @param 
     * 
     */
    public function init()
    {
		try
		{
			//	Register new users or just log in existing users
			Ayoola_HybridAuth_SignUp::viewInLine();
			
			//	The real endpoint process
			$this->setViewContent( Hybrid_Endpoint::process() );
		}
		catch( Exception $e )
		{
			$this->setViewContent( '<p>We encountered an error.</p>', true );
		}
		//	var_export( $_REQUEST );
    }
	
	
	// END OF CLASS
}
