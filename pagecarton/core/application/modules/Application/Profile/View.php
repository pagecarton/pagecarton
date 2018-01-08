<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Profile_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Profile_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_View extends Application_Profile_Abstract
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
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'View User Profile'; 
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = $this->getIdentifierData() )
			{
		//		if( )
			}
			$this->_objectTemplateValues = array_merge( $data ? : array(), $this->_objectTemplateValues ? : array() );
		//	var_export( $data );
		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
		}
	//	var_export( $this->_xml );
    } 
	// END OF CLASS
}
