<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_ContactUs_ShowMap
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowMap.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_ContactUs_Abstract
 */
 
require_once 'Application/ContactUs/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_ContactUs_ShowMap
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_ContactUs_ShowMap extends Application_ContactUs_Abstract
{
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Whether to wrap _viewContent in a tag
     *
     * @var boolean
     */
	public $wrapViewContent = false;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$googleMapLink = Application_Settings_CompanyInfo::getSettings( 'SocialMedia', 'google_map_link' ) ? : "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12084.143170610365!2d-73.96770330299584!3d40.783227259584116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2589a018531e3%3A0xb9df1f7387a94119!2sCentral+Park!5e0!3m2!1sen!2s!4v1391601567888";
			
		//	$this->setViewContent( '<iframe src="' . $googleMapLink . '"></iframe>', true );
	//		$this->setViewContent( '' . $googleMapLink . '', true );
			
		}
		catch( Application_ContactUs_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
