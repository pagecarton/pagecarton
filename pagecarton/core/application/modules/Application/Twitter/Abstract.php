<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Twitter_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Twitter_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Twitter/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Twitter_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Twitter_Abstract extends Application_SocialMedia_Abstract
{
	
    /**
     * loads the Twitter sdk
     * 
     */
	public static function load()
    {
	//	if( self::$_loaded ){ return; }
		$code = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';
		Application_Javascript::addCode( $code );
		self::$_loaded = true;
    } 
	// END OF CLASS
}
