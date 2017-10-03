<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Personalization
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Personalization.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Personalization
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Personalization extends Ayoola_Abstract_Table
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
     * Personalization process stages
     *
     * @var array
     */
	protected static $_stages = array
	(
	//	array( 'Ayoola_Access_Logout' => null ),
	//	array( 'Ayoola_Access_AccountRequired' => array( 'legend' => 'Sign in or sign up', 'parameters' => array( 'ignore_user_check' => true  ) ) ),
	//	array( 'Ayoola_Access_UpgradeSelf' => null ),
		array( 'Application_Settings_Editor' => array( 'legend' => '', 'parameters' => array( 'settingsname_name' => 'SiteInfo' ) ) ),
		array( 'Application_User_AdminCreator' => array( 'legend' => 'Create the first Admin Account', 'parameters' => null ) ),
	//	array( 'Application_Settings_Editor' => array( 'legend' => '', 'parameters' => array( 'settingsname_name' => 'Page' ) ) ),
	//	array( 'Application_Settings_Editor' => array( 'legend' => 'Your website contact information', 'parameters' => array( 'settingsname_name' => 'CompanyInformation' ) ) ),
//		array( 'Application_Settings_Editor' => array( 'legend' => 'Your website contact information', 'parameters' => array( 'settingsname_name' => 'SocialMedia' ) ) ),
	//	array( 'Ayoola_Page_Editor_Sanitize' => array( 'legend' => 'Build your pages with the selected layout template.', 'parameters' => array( 'workaround' ) ) ),
	);
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {		
		set_time_limit( 0 );
		ignore_user_abort( true );
		$startTime = microtime( true );
		  
		//	Clear cache
/* 		if( is_dir( CACHE_DIR ) ) 
		{
			//	This is important or the application_dir won't change causing files to save in global dir
			Ayoola_Doc::deleteDirectoryPlusContent( CACHE_DIR ); 
		}
 */		//	refresh domain information
//		Ayoola_Application::setDomainSettings( true );
		
/*		if( is_file( Ayoola_Application::$installer ) &&  is_writable( Ayoola_Application::$installer ) )
		{ 
			//	For new install, clear the following databases
			$tables = array
			(
				'Application_Domain',   
		//		'Application_Backup',
		//		'Ayoola_Access_LocalUser', 
			//	'Application_Settings',
				'Ayoola_Api_Api',
			);
			foreach( $tables as $each )
			{
				try
				{
						$class = new $each();
						$method = 'drop';
						if( method_exists( $class, $method ) )
						{
							$class->$method();
						}
				}
				catch( Exception $e )
				{
				//	$this->setViewContent( '<div class="boxednews badnews"> ERROR DELETING ' . $each . ' - ' . $e->getMessage() . '</div>' ); 
				}
			}
		
		}*/
		//	Reset domain
		Ayoola_Application::setDomainSettings( true );
		
		//	We have to go about and do a separate authentication for this module
		//	If this is not a new install, we must be admin  
//		$response = Ayoola_Api_UserList::send( array() );
		$userTable = new Ayoola_Access_LocalUser();
		$response = $userTable->select( null, array( 'access_level' => 99 ) );
	//	$response = Ayoola_Api_UserList::send( array( 'access_level' => 99 ) );
	//	var_export( $response );
		if( is_array( @$response ) ) 
		{
		
			switch( count( $response ) )
			{
				case 0:
					//	New install
	//			break;
	//			case 1:
					//	Using this to ensure that a user that just signed in before he trys to personalize is not locked out
					//	The Sign In module also "signs" automatically
					//	That "One" user must not be an admin
					$oneUser = array_pop( $response );
					if( intval( $oneUser['access_level'] ) === 1 ){ break; }  
				break;
				default:
				//	var_export( self::hasPriviledge() );
					if( ! self::hasPriviledge( array( 99, 98 ) ) )
					{
					//	var_export( self::hasPriviledge() );
			//			exit();
				//		header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/404/' );
						
						//	Secure installer
				//		@unlink( Ayoola_Application::$installer );
						return false;
					}
				break;
					
			}
			
		}
		else
		{
		}
		
		try
		{ 
			//	Always Log out to allow login again
			require_once 'Ayoola/Access.php'; 
			$domainDir = Application_Domain_Abstract::getSubDomainDirectory( Ayoola_Page::getDefaultDomain() );
	//		if( @$values['create_personal_path'] )
			if( ! is_dir( $domainDir ) && Ayoola_Application::getDomainSettings( APPLICATION_PATH ) == APPLICATION_PATH ) 
			{
								
				@Ayoola_Doc::createDirectory( $domainDir );

				//	Reset domain to reflect created dir
				Ayoola_Application::setDomainSettings( true );
								
				
			}
			// Avoid personalization for localhost
		//	if( $_SERVER['REMOTE_ADDR' ] !== '127.0.0.1' )
			{
				$this->createForm();
				$this->setViewContent( $this->getForm()->view() ); 
				$values = $this->getForm()->getValues();
				if( ! $values )
				{ 
					return false; 
				}
				
				//	Clear cache
				if( is_dir( CACHE_DIR ) )
				{
					@Ayoola_Doc::deleteDirectoryPlusContent( CACHE_DIR );
				}

				//	Reset domain
				Ayoola_Application::setDomainSettings( true );
				//	Go through the process again to set the info for the personalized app dir
				//	similate install to allow Ayoola_Access_UpgradeSelf
				//		file_put_contents( Ayoola_Application::$installer, __CLASS__ );
				//	Always Log out to allow login again
				require_once 'Ayoola/Access.php'; 
				$auth = new Ayoola_Access();
			//	$auth->logout();

				foreach( self::$_stages as $class )
				{
					foreach( $class as $each => $parameters )
					{
						$each = new $each( $parameters['parameters'] );
						$each->fakeValues = $values;
						$each->init();
					}
				}
			}
			//	Clear cache
			if( is_dir( CACHE_DIR ) )
			{
				@Ayoola_Doc::deleteDirectoryPlusContent( CACHE_DIR );
			}

			if( is_file( Ayoola_Application::$installer ) )
			{ 
				//	SELF DESTRUCT THE INSTALLER
				if( ! unlink( Ayoola_Application::$installer ) )
				{
					$this->setViewContent( '<h1 class="badnews">ERROR: Please re-install or manually remove the installer.</h1>', true ); 
					return false; 
				}
			}
			$this->setViewContent( '<p><strong>PageCarton Personalization Completed</strong></p>', true );   
			$this->setViewContent( '<p>Welcome to endless possibilities! You have successfully completed the installation and personalization sequence of PageCarton. </p>' ); 
			$this->setViewContent( '<p>Next? Go to:</p>' ); 
			$this->setViewContent
			( 
				'<ul>
					<li><a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/">Home Page</a></li>
					<li><a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/pc-admin/">Control Panel</a></li>
					<li><a target="_blank" href="http://pagecarton.org/docs">Help! How to use PageCarton?</a></li>
				</ul>' 
			); 
	//	exit();
			
		}
		catch( Ayoola_Exception $e )
		{ 
		//	var_export( self::hasPriviledge() );
		//	var_export( $e->getMessage() );
			return false;  
		}
	}
		
    /**
     * Creates the form to select which Personalization to view
     * 
     */
	public function createForm()
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = 'Continue Personalization';
		$form->oneFieldSetAtATime = true;
	//	$form->oneFieldSetAtATime = false;
		foreach( self::$_stages as $class )
		{
			foreach( $class as $each => $parameters )
			{
				if( ! Ayoola_Loader::loadClass( $each ) )  
				{
					throw new Ayoola_Object_Exception( 'INVALID CLASS: ' . $each );
				}
				$each = new $each( $parameters['parameters'] );
				if( ! method_exists( $each, 'createForm' ) ){ continue; }
				$fieldsets = $each->getForm()->getFieldsets();
			//	var_export( count( $fieldsets ) );
			//	var_export( $fieldsets );
				foreach( $fieldsets as $fieldset )
				{
					$parameters['legend'] ? 
					$fieldset->addLegend( $parameters['legend'] ) : 
					$fieldset->addLegend( $fieldset->getLegend() );
					$form->addFieldset( $fieldset );
				}
			}
		}
/* 		
		//	Create personalized APPLICATION_PATH
		$domainDir = Application_Domain_Abstract::getSubDomainDirectory( Ayoola_Page::getDefaultDomain() );
		//	Sub domains are not allowed
		if( ! is_dir( $domainDir ) && Ayoola_Application::getDomainSettings( APPLICATION_PATH ) == APPLICATION_PATH )
		{
			//	Other personalization settings
			$fieldset = new Ayoola_Form_Element;

			$option = array( 0 => 'Experimental', 1 => 'Development/Production' );
			$fieldset->addElement( array( 'name' => 'create_personal_path', 'label' => 'Please describe your installation...', 'type' => 'Radio', 'value' => @$values['create_personal_path'] ), $option );
			$fieldset->addRequirement( 'create_personal_path', array( 'InArray' => array_keys( $option ) ) );
			$form->addFieldset( $fieldset );
		}
 */		$this->setForm( $form );
   }
	// END OF CLASS
}
