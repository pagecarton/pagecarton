<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Twitter_Follow
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Follow.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Twitter_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Twitter/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Twitter_Follow
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Twitter_Follow extends Application_Twitter_Abstract
{
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
		self::load();
		$usernames = self::getSettings();
	//	var_export( $appId );
		if( empty( $usernames['twitter_username'] ) ){ return; }
		$usernames = $usernames['twitter_username'];
		$username = array_shift( explode( ',', $usernames ) );
		$this->setViewContent(  '' . self::__( '<a href="https://twitter.com/' . $username . '" class="twitter-follow-button" data-show-count="true" data-lang="en">Follow @' . $username . '</a>' ) . '', true  );
    } 
	// END OF CLASS
}
