<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_MultiSite_Creator
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php Wednesday 20th of December 2017 03:23PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_MultiSite_Creator extends PageCarton_MultiSite_Abstract
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'New PageCarton Site'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			$this->createForm( 'Continue...', 'Create a new site' );
			$this->setViewContent( $this->getForm()->view() );

		//	self::v( $_POST );
			if( ! $values = $this->getForm()->getValues() ){ return false; }

		//	var_export( $values );

            $values['directory'] = Ayoola_Application::getRealPathPrefix() . '/' . trim( $values['directory'], '/\\' );
            $values['parent_dir'] = Ayoola_Application::getRealPathPrefix();
		//	var_export( $values );
           
            if( $response = $this->getDbTable()->selectOne( null, array( 'directory' => Ayoola_Application::getRealPathPrefix() ) ) )
            {
                //	Don't run this if we are a product of multi-site
                $values['parent_dir'] = $response['parent_dir'];
            }
			if( $this->getDbTable()->selectOne( null, array( 'directory' => $values['directory'] ) ) )
			{
				$this->getForm()->setBadnews( 'Enter a different directory for this site. There is a site with the same directory: ' . $values['directory'] );
				$this->setViewContent( '<p class="badnews">Enter a different directory for this site. There is a site with the same directory: ' . $values['directory'] . '</p>', true );		
				$this->setViewContent( $this->getForm()->view() );		
				return false; 
			}
            $values['creation_time'] = time();

            if( ! self::copyFiles( $values['directory'] ) )
            {
                $this->getForm()->setBadnews( 'Enter a different directory for this site. Specified directory is in use: ' . $values['directory'] );
				$this->setViewContent( '<p class="badnews">Enter a different directory for this site. Specified directory is in use: ' . $values['directory'] . '</p>', true );		
				$this->setViewContent( $this->getForm()->view() );		
                return false;
            }     
			
			//	Notify Admin
			$link = '' . Ayoola_Page::getRootUrl() . '' . $values['directory'];
			$newSiteLink = '' . Ayoola_Page::getRootUrl() . '' . $values['directory'] . '/new-site-wizard';
			$mailInfo = array();
			$mailInfo['subject'] = 'A new site created';
			$mailInfo['body'] = 'A new site has been created on your PageCarton Installation with the following information: "' . self::arrayToString( $values ) . '". 
			
			Preview the site on: ' . $link . '
			';
			try
			{
		//		var_export( $mailInfo );
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
		//	if( ! $this->insertDb() ){ return false; }
			if( $this->insertDb( $values ) )
			{ 
				$this->setViewContent( '<div class="goodnews">Site created successfully. <a class="" href="' . $link . '"> Preview</a> or <a class="" href="' . $newSiteLink . '">Create a site!</a></div>', true );  
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
