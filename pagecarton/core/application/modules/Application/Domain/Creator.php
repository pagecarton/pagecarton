<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Domain_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Domain_Abstract
 */
 
require_once 'Application/Domain/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Creator extends Application_Domain_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			$this->createForm( 'Create', 'Add a new Domain' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 
		//	var_export( $values ); 
			
		//	var_export( $values ); 
			$this->resetDefaultDomain();
			$this->createSubDomain();
			if( ! $this->insertDb() ){ return $this->setViewContent( $this->getForm()->view(), true ); }
	//		var_export( $values ); 
			
			//	clear domain cache
			Ayoola_File_Storage::purgeDomain( $values['domain_name'] );

			$this->setViewContent( '<p>Domain name added successfully.</p>', true );
			
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = 'Domain name added';
			$mailInfo['body'] = 'A new domain have been added to your application. Here is the domain information: "' . var_export( $values, true ) . '". 
			
			Domain options are available on: http://' . Ayoola_Page::getDefaultDomain() . '/ayoola/domain/.
			';
			//	var_export( $newCart );
			Ayoola_Application_Notification::mail( $mailInfo );
			
			//	reset domain
			Ayoola_Application::setDomainSettings( true );
		}
		catch( Ayoola_Exception $e ){ null; }
   } 
	
    /**
     * Create Subdomain
     * 
     */
	protected function createSubDomain()
    {
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( ! @$values['sub_domain'] || @$values['domain_type'] == 'sub_domain' ){ return false; }
		
		//	Create Folder
		//$path = Ayoola_Loader::getPaths( $data['domain_settings'][APPLICATION_DIR] . DS . 'sub-domain' );
		$path = self::getSubDomainDirectory( $values['domain_name'] );
		Ayoola_Doc::createDirectory( $path );
		 
		//	Save the config to allow user upgrade later
/* 		$config = $path . DS . 'config';
		$a = array( __CLASS__ => Ayoola_Application::getUserInfo( 'username' ) );
		file_put_contents( $config, 'return ' . var_export( $a, true ) . ';' ); 
 */   } 
	// END OF CLASS
}
