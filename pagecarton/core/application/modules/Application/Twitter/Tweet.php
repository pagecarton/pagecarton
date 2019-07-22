<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Twitter_Tweet
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Tweet.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Twitter_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Twitter/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Twitter_Tweet
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Twitter_Tweet extends Application_Twitter_Abstract
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
	//	if( empty( $usernames['twitter_username'] ) ){ return; }
		$usernames = @$usernames['twitter_username'];
		$username = array_shift( explode( ',', $usernames ) );
		$title = $this->getParameter( 'title' );
		$title = $title ? $title . ' - Click to read more on:' : null;
		$text = $title ? 'data-text="' . $title . '"' : null;
	//	var_export( $text );
	//	var_export( $this->getUrl() );
		$this->setViewContent(  '' . self::__( '<a style="display:inline;" href="https://twitter.com/share" class="twitter-share-button" ' . $text . ' data-url="' . $this->getUrl() . '" data-via="' . $username . '" data-related="' . $usernames . '">Tweet</a>' ) . '', true  );
//		var_export( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) );
    } 
	// END OF CLASS
}
