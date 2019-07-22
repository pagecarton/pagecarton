<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_UserSiteManager_Creator
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php Monday 14th of January 2019 04:16PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_UserSiteManager_Creator extends Application_Profile_Creator
{
	
    /**
     * 
     *
     * @var boolean
     */
	protected static $_subdomain = true;
	
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
	protected static $_objectTitle = 'Create a new site'; 
	
    /**
     * 
     * @var string 
     */
	protected static $_submitButton = 'Create site'; 
	
    /**
     * 
     * @var string 
     */
	protected static $_urlName = 'Site Name'; 


	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function setConfirmationPage( $values )
    {
		
		$fullUrl = 'http://' . $values['profile_url'] . '.' . Ayoola_Application::getDomainName() . '';
		
        $this->setViewContent(  self::__( '
        <div class="goodnews">
            New site created successfully. 
            <a href="' . $fullUrl . '/new-site-wizard" target="_blank">New Website Wizard</a>
        </div>' ), true );
//		$this->setViewContent( self::__( '<div class="" title="Share this new profile page with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' ) );  
		if( @$_GET['previous_url'] )
		{
			$this->setViewContent( self::__( '<div class="pc-info-notify"><a href="' . $_GET['previous_url'] . '"><img style="margin-right:0.5em;" alt="Edit" src="' . Ayoola_Application::getUrlPrefix() . '/open-iconic/png/arrow-circle-left-2x.png">Go Back</a></div>' ) );
		}
		$this->_objectData['profile_url'] = $values['profile_url']; 
	//	$this->setViewContent(  );

		
		//	Notify Admin
		$mailInfo['subject'] = 'New Site Created';
		$mailInfo['body'] = 'A new site name has been created. You can view the new profile by clicking this link: ' . $fullUrl . '
		';
		Application_Log_View_General::log( array( 'type' => 'New Site', 'info' => array( $mailInfo ) ) );
		try
		{
			@Ayoola_Application_Notification::mail( $mailInfo );
		}
		catch( Ayoola_Exception $e ){ null; }

		$mailInfo['to'] = Ayoola_Application::getUserInfo( 'email' );
		$mailInfo['subject'] = 'Your new site';
		$mailInfo['body'] = 'You have successfully created your site. Next is to add content and build it.
		
		Site Homepage Link: ' . $fullUrl . '
		Start building the site here: ' . $fullUrl . '/new-site-wizard
		Manage your sites: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '/account';
		self::sendMail( $mailInfo );

	}
	// END OF CLASS
}
