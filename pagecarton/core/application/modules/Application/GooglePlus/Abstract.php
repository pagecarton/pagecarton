<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_GooglePlus_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_GooglePlus_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/GooglePlus/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_GooglePlus_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_GooglePlus_Abstract extends Application_SocialMedia_Abstract
{
	
    /**
     * SocialMedia Settings
     * 
     * @var array
     */
	protected static $_settings;
	
    /**
     * loads the GooglePlus sdk
     * 
     */
	public static function load()
    {
	//	if( self::$_loaded ){ return; }
		$code = "(function(){ var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true; po.src = 'https://apis.google.com/js/plusone.js'; var s = document.getElementsByTagName('script')[0];  s.parentNode.insertBefore(po, s);  })();";
		Application_Javascript::addCode( $code );
		self::$_loaded = true;
    } 
	// END OF CLASS
}
