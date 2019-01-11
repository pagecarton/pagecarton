<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Domain_UserDomain_Creator
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php Wednesday 20th of December 2017 03:23PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_UserDomain_Creator extends Application_Domain_UserDomain_Abstract
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1 );  
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Add domain'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
		//	self::v( CACHE_DIR );  
			if( ! $this->requireRegisteredAccount() )
			{
				return false;
			}
			if( ! $this->requireProfile() )
			{
				return false;
			}
            //  Code that runs the widget goes here...
			$this->createForm( 'Submit...', 'Link a domain name to your account' );
			$this->setViewContent( $this->getForm()->view() );

		//	self::v( $_POST );
			if( ! $values = $this->getForm()->getValues() ){ return false; }

			//	the domain name must point to this ip address
			$values['domain_name'] = strtolower( str_ireplace( 'www', '', array_pop( explode( '//', $values['domain_name'] ) ) ) );
			$values['username'] = strtolower( Ayoola_Application::getUserInfo( 'username' ) );
			$values['user_id'] = strtolower( Ayoola_Application::getUserInfo( 'user_id' ) );
			
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = __CLASS__;
			$mailInfo['body'] = 'Form submitted on your PageCarton Installation with the following information: "' . htmlspecialchars_decode( var_export( $values, true ) ) . '". 
			
			';
			try
			{
		//		var_export( $mailInfo );
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
		//	if( ! $this->insertDb() ){ return false; }
			
			//	clear domain cache
			Ayoola_File_Storage::purgeDomain( $values['domain_name'] );

			if( $this->insertDb( $values ) )
			{ 
				$userIp = gethostbyname( $values['domain_name'] );
				$serverIp = gethostbyname( $_SERVER['SERVER_NAME'] );
				$this->setViewContent( '<div class="goodnews">Added successfully. </div>', true ); 
				if( $userIp != $serverIp )
				{
					$this->setViewContent( '<div class="badnews">Add a DNS "A" record that is pointing to the ip address "' .$serverIp .  '". It appears to be pointing to "' .$userIp .  '". </div>' ); 				
				//	$this->setViewContent( $this->getForm()->view() );
				//	return false;
				}
				return true;
			}
		//	$this->setViewContent( $this->getForm()->view() );
            


            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
