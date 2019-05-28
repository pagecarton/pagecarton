<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
		//	Reset domain
		  
		//	Clear cache
		if( ( ! empty( $_REQUEST['rebuild_widget'] ) || ! $this->getParameter( 'rebuild_widget' ) ) )
		{
			Application_Cache_Clear::viewInLine();
			Ayoola_Application::setDomainSettings( true );
		}
		
		//	We have to go about and do a separate authentication for this module
		//	If this is not a new install, we must be admin  
//		$response = Ayoola_Api_UserList::send( array() );

		//	set table to private so when parent have admin, we dont allow new admin on child
		//	if not like this, it becomes a security breach on .com
		$response = Application_User_Abstract::getUsers( array( 'access_level' => 99 ) ); 
/* 		$userTable = $userTable::getInstance( $userTable::SCOPE_PROTECTED );
		$userTable->getDatabase()->getAdapter()->setAccessibility( $userTable::SCOPE_PROTECTED );
		$userTable->getDatabase()->getAdapter()->setRelationship( $userTable::SCOPE_PROTECTED );
		$response = $userTable->select( null, array( 'access_level' => 99 ), array( 'disable_cache' => true ) );
	//	$response = Ayoola_Api_UserList::send( array( 'access_level' => 99 ) );
	//	var_export( $response );
 */		if( is_array( @$response ) ) 
		{
			$prefix = Ayoola_Application::getUrlPrefix();
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
					$userTable = new PageCarton_MultiSite_Table();
					if( $response = $userTable->selectOne( null, array( 'directory' => Ayoola_Application::getPathPrefix() ) ) )
					{
			//			var_export( $response );
			//			var_export( Ayoola_Application::getUrlPrefixController() );
						$prefix = $response['parent_dir'] . Ayoola_Application::getUrlPrefixController();
					}
					else
					{
						break;
					}
		//		break;
				default:
					if( $this->getParameter( 'only_show_when_no_admin' ) )
					{
						return false; 
					}
				
				//	var_export( self::hasPriviledge() );
					if( ! self::hasPriviledge( array( 99, 98 ) ) )
					{
						//	IF WE ARE HERE, WE ARE NOT AUTHORIZED     
						$urlToGo = '' . $prefix . '/accounts/signin/';
					//	var_export( $urlToGo );
					//	exit;
						$urlToGo = Ayoola_Page::setPreviousUrl( $urlToGo ); 
					//	var_export( $urlToGo );
					//	exit;
			//			$access = self::getInstance();
				//     var_export( $pageAccessLevel );
				//    var_export( Ayoola_Application::isClassPlayer()  );
					//    exit();
				//		var_export( $objectPlay );
					//	exit();
				//		if( ! $access->isLoggedIn() )   
						{ 
							//	$access->logout();
							
							//	must log out first to avoid redirct at the login page.
							
							$encodeLoginMessage = new Ayoola_Access_Login();
							$encodeLoginMessage->getObjectStorage( 'pc_coded_login_message' )->store( 'Please login to continue personalization' );
							
				//            var_export( $urlToGo );
				//            exit();

							$jsCode = 'ayoola ? ( ayoola.div.getParent( window, 5 ).location = "' . $urlToGo . '?pc_coded_login_message=1&previous_url=" + encodeURIComponent( ayoola.div.getParent( window, 5 ).location ) ) : ( window.location = "' . $urlToGo . '?pc_coded_login_message=1&previous_url=" + encodeURIComponent( window.location ) );';
							Application_Javascript::addCode( $jsCode );

					//		if(  ! Ayoola_Application::isClassPlayer() )
							if( ! $objectPlay )
							{
								header( 'Location: ' . $urlToGo );	
								exit();
							}
						}
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
	//		$domainDir = Application_Domain_Abstract::getSubDomainDirectory( Ayoola_Page::getDefaultDomain() );
	//		if( @$values['create_personal_path'] )
	//		var_export( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) );
//			var_export( $domainDir );
	//		if( ! is_dir( $domainDir ) && Ayoola_Application::getDomainSettings( APPLICATION_PATH ) == APPLICATION_PATH ) 
			{
								
	//			@Ayoola_Doc::createDirectory( $domainDir );

				//	Reset domain to reflect created dir
	//			Ayoola_Application::setDomainSettings( true );
								
				
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
			$this->setViewContent( '<h2 class="">Basic Settings Saved</h2>', true );   
			$this->setViewContent( '<p>Welcome to endless possibilities! PageCarton helps to publish great content to the web fast, easy using award-winning secure methods. You can make stunning websites easily and apps with PageCarton.</p>' );

			$this->setViewContent( '<h4 class="xpc-notify-info">What to do next?</h4>' ); 
			$this->setViewContent
			( 
				'<ul>
					<li>Try <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/PageCarton_NewSiteWizard/">Simple Web Builder</a> (Recommended)</li>
					<li>Go to <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/">Home Page</a> - ' . Ayoola_Page::getCanonicalUrl( '/' ) .  '</li>
					<li>Go to <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/pc-admin">PageCarton Admin  Panel</a> - ' . Ayoola_Page::getCanonicalUrl( '/pc-admin' ) .  '</li>
					<li>Study <a target="_blank" href="http://docs.pagecarton.org">PageCarton Documentation</a> - http://docs.pagecarton.org</li>
				</ul>' 
			); 

			if( is_file( Ayoola_Application::$installer ) )
			{ 
				//	SELF DESTRUCT THE INSTALLER
				if( ! unlink( Ayoola_Application::$installer ) )
				{
					$this->setViewContent( '<p class="badnews">ERROR: Please re-install or manually remove the installer.</p>' ); 
				//	return false; 
				}
			}
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
     * 
     * 
     */
	public static function getPercentageCompleted()
    {
		$percentage = 0;
		if( Application_Settings_CompanyInfo::getSettings( 'SiteInfo', 'site_headline' ) )
		{
			$percentage += 50;
		}
		if( Application_Settings_CompanyInfo::getSettings( 'SiteInfo', 'site_description' ) )
		{
			$percentage += 50;
		}
	//	var_export( $percentage );
		return $percentage;
	}
		
    /**
     * Creates the form to select which Personalization to view
     * 
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = 'Continue Personalization';
		$form->oneFieldSetAtATimeJs = true;
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
