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
		array( 'Application_Settings_Editor' => array( 'legend' => '', 'parameters' => array( 'settingsname_name' => 'SiteInfo' ) ) ),
		array( 'Application_User_AdminCreator' => array( 'legend' => 'Create the first Admin Account', 'parameters' => null ) ),
	);
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {		
		$startTime = microtime( true );
		//	Reset domain
		  
		//	Clear cache
		if( ( ! empty( $_REQUEST['rebuild_widget'] ) || ! $this->getParameter( 'rebuild_widget' ) ) )
		{
			//Application_Cache_Clear::viewInLine();
			Ayoola_Application::setDomainSettings( true );
		}
		
		//	We have to go about and do a separate authentication for this module
		//	If this is not a new install, we must be admin  

		//	set table to private so when parent have admin, we dont allow new admin on child
		//	if not like this, it becomes a security breach on .com
		$response = Application_User_Abstract::getUsers( array( 'access_level' => 99 ) ); 
		if( is_array( @$response ) ) 
		{
			$prefix = Ayoola_Application::getUrlPrefix();
			switch( count( $response ) )
			{
				case 0:
					//	New install
					//	Using this to ensure that a user that just signed in before he trys to personalize is not locked out
					//	The Sign In module also "signs" automatically
					//	That "One" user must not be an admin
					$oneUser = array_pop( $response );
					if( intval( $oneUser['access_level'] ) === 1 ){ break; }  
					$userTable = new PageCarton_MultiSite_Table();
					if( $response = $userTable->selectOne( null, array( 'directory' => Ayoola_Application::getPathPrefix() ) ) )
					{
						$prefix = $response['parent_dir'] . Ayoola_Application::getUrlPrefixController();
					}
					else
					{
						break;
					}
				default:
					if( $this->getParameter( 'only_show_when_no_admin' ) )
					{
						return false; 
					}
				
					if( ! self::hasPriviledge( array( 99, 98 ) ) )
					{
						//	IF WE ARE HERE, WE ARE NOT AUTHORIZED     
						$urlToGo = '' . $prefix . '/accounts/signin/';
						$urlToGo = Ayoola_Page::setPreviousUrl( $urlToGo ); 
						{ 
							
							//	must log out first to avoid redirct at the login page.
							
							$encodeLoginMessage = new Ayoola_Access_Login();
							$encodeLoginMessage->getObjectStorage( 'pc_coded_login_message' )->store( 'Please login to continue personalization' );
							
							$jsCode = 'ayoola ? ( ayoola.div.getParent( window, 5 ).location = "' . $urlToGo . '?pc_coded_login_message=1&previous_url=" + encodeURIComponent( ayoola.div.getParent( window, 5 ).location ) ) : ( window.location = "' . $urlToGo . '?pc_coded_login_message=1&previous_url=" + encodeURIComponent( window.location ) );';
							Application_Javascript::addCode( $jsCode );

							if( ! $objectPlay )
							{
								header( 'Location: ' . $urlToGo );	
								exit();
							}
						}
						return false;
					}
				break;
					
			}
			
		}
		
		try
		{ 
			//	Always Log out to allow login again
			require_once 'Ayoola/Access.php'; 
			// Avoid personalization for localhost
			
            $this->createForm();
            $this->setViewContent( $this->getForm()->view() ); 
            $values = $this->getForm()->getValues();
            if( ! $values )
            { 
                return false; 
            }

            //	Reset domain
            Ayoola_Application::setDomainSettings( true );

            //	Go through the process again to set the info for the personalized app dir
            //	similate install to allow Ayoola_Access_UpgradeSelf
            //	Always Log out to allow login again
            require_once 'Ayoola/Access.php'; 
            $auth = new Ayoola_Access();

            foreach( self::$_stages as $class )
            {
                foreach( $class as $each => $parameters )
                {
                    $each = new $each( $parameters['parameters'] );
                    $each->fakeValues = $values;
                    $each->init();
                }
            }
			
			//	Clear cache
			if( is_dir( CACHE_DIR ) )
			{
                //Application_Cache_Clear::do();
			}
			$this->setViewContent(  '' . self::__( '<h2 class="">Basic Settings Saved</h2>' ) . '', true  );   
			$this->setViewContent( self::__( '<p>Welcome to endless possibilities! PageCarton helps to publish great content to the web fast, easy using award-winning secure methods. You can make stunning websites easily and apps with PageCarton.</p>' ) );

			$this->setViewContent( self::__( '<h4 class="xpc-notify-info">What to do next?</h4>' ) ); 
			$this->setViewContent
			( 
				'<ul>
					<li>Try <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/PageCarton_NewSiteWizard/">Simple Web Builder - an Easy way to Build a Website</a>  (Recommended)</li>
					<li>Go to <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/">Home Page</a> - <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/">' . Ayoola_Page::getCanonicalUrl( '/' ) .  '</a></li>
					<li>Go to <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/pc-admin">PageCarton Admin  Panel</a> - <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/pc-admin">' . Ayoola_Page::getCanonicalUrl( '/pc-admin' ) .  '</a></li>
					<li>Study <a target="_blank" href="http://docs.pagecarton.org">PageCarton Documentation</a> - <a target="_blank" href="http://docs.pagecarton.org">http://docs.pagecarton.org</a></li>
				</ul>' 
			); 

			if( is_file( Ayoola_Application::$installer ) )
			{ 
				//	SELF DESTRUCT THE INSTALLER
				if( ! unlink( Ayoola_Application::$installer ) )
				{
					$this->setViewContent( self::__( '<p class="badnews">ERROR: Please re-install or manually remove the installer.</p>' ) ); 
				}
			}
			
		}
		catch( Ayoola_Exception $e )
		{ 
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
				foreach( $fieldsets as $fieldset )
				{
					$parameters['legend'] ? 
					$fieldset->addLegend( $parameters['legend'] ) : 
					$fieldset->addLegend( $fieldset->getLegend() );
					$form->addFieldset( $fieldset );
				}
			}
		}
		$this->setForm( $form );
   }
	// END OF CLASS
}
