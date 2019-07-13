<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Share_Website
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Website.php Tuesday 25th of December 2018 07:22AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Share_Website extends PageCarton_Widget
{

    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );

    /**
     *
     *
     * @var string
     */
	protected static $_objectTitle = 'Share Website';

    /**
     * Performs the whole widget running process
     *
     */
	public function init()
    {
		try
		{
            //  Code that runs the widget goes here...

            //  Output demo content to screen
             $this->setViewContent( self::__( '<h3>How to share ' . Ayoola_Page::getHomePageUrl() . '</h3>' ) ); 
             $this->setViewContent( self::__( '<p>Copy and share content below to any social wall or click the share link below:</p>' ) );
             $text =  strtoupper( Application_Settings_CompanyInfo::getSettings( 'SiteInfo', 'site_headline' ) ) . ": \r\n \r\n" . Application_Settings_CompanyInfo::getSettings( 'SiteInfo', 'site_description' ) . ". \r\n \r\nVisit: " . Ayoola_Page::getHomePageUrl() . "   and please help share!";
             $this->setViewContent( self::__( '<textarea style="width:100%;margin: 10px 0; padding: 10px;">' . $text . '</textarea>' ) );  
             $this->setViewContent( self::__( '<a target="_blank" class="pc-btn" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_Share/?url=/&title=' . htmlentities( $text ) . '" href="javascript:;"><i  style="margin:5px;" class="fa fa-external-link"></i> Share...</a>' ) );  

             // end of widget process

		}
		catch( Exception $e )
        {
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
