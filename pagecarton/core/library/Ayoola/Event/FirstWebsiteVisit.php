<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Event_FirstWebsiteVisit
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: FirstWebsiteVisit.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Event_FirstWebsiteVisit
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Event_FirstWebsiteVisit extends Ayoola_Event
{
	
    /**
     * Plays the class
     * 
     */
	public function init()
    {
		//	Have we been here before?
		if( ! empty( $_COOKIE['pc_user_instance_id'] ) )
		{ 
			return false; 
		}
		
		//	record new visit
		setcookie( 'pc_user_instance_id',  'PC_' . uniqid(), time() + ( 10 * 365 * 24 * 60 * 60 ), '/', null, false, true );
		do
		{
			//	Let's do referrer ids
			if( empty( $_GET['referrer_id'] )  )
			{
				break;
			}
			setcookie( 'referrer_id', $_GET['referrer_id'], time() + ( 10 * 365 * 24 * 60 * 60 ), '/', null, false, true );
		}
		while( false );
 		return true;
   } 
	// END OF CLASS
}
