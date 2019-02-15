<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_GooglePlus_Badge
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Badge.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_GooglePlus_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/GooglePlus/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_GooglePlus_Badge
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_GooglePlus_Badge extends Application_GooglePlus_Abstract
{
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
		self::load();
		$username = self::getSettings();
	//	if( empty( $usernames['googleplus_id'] ) ){ return; }
		$username = @$username['googleplus_id'];
	//	$this->setViewContent( '<div class="g-plus" data-width="195" data-height="69" data-href="' . Ayoola_Application::getUrlPrefix() . '//plus.google.com/' . $username . '" data-rel="publisher"></div>', true );
		$this->setViewContent( '<div class="g-plusone" data-href="' . $this->getUrl() . '" ></div>', true );
    } 
	// END OF CLASS
}
