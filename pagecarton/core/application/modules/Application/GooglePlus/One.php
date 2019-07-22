<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_GooglePlus_One
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: One.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_GooglePlus_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/GooglePlus/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_GooglePlus_One
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_GooglePlus_One extends Application_GooglePlus_Abstract
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
	//	var_export( $appId );
		if( empty( $username['googleplus_id'] ) ){ return; }
		$username = $username['googleplus_id'];
		$this->setViewContent(  '' . self::__( '<div class="g-plusone" data-size="medium" data-href="' . $this->getUrl() . '" ></div>' ) . '', true  );
    } 
	// END OF CLASS
}
