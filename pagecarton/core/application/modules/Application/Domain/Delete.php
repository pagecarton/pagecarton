<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Domain_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Abstract
 */
 
require_once 'Application/Domain/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Delete extends Application_Domain_Abstract
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
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete ' . $data['domain_name'],  'Delete Domain' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $data );
				
				//	clear domain cache
			Ayoola_File_Storage::purgeDomain( $data['domain_name'] );  
			
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = 'Domain Information Deleted';
			$mailInfo['body'] = 'The domain table have been altered: Here is the domain information: "' . htmlspecialchars_decode( var_export( $data, true ) ) . '". 
			
			Domain options are available on: http://' . Ayoola_Page::getDefaultDomain() . '/ayoola/domain/.
			';
			try
			{
			//	var_export( $newCart );
				Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
			if( $data['sub_domain'] )
			{
				if( Ayoola_Doc::removeDirectory( self::getSubDomainDirectory( $data['domain_name'] ), true ) )
				{
					if( $this->deleteDb( false ) ){ $this->setViewContent( 'Domain deleted successfully', true ); }
				}
			}
			else
			{
				if( $this->deleteDb( false ) ){ $this->setViewContent( 'Domain deleted successfully', true ); }
			}
			
			//	reset domain
			Ayoola_Application::setDomainSettings( true );
		}
		catch( Application_Domain_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
