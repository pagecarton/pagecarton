<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_MakeDefault
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: MakeDefault.php Saturday 16th of September 2017 05:40PM  $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_Layout_MakeDefault extends Ayoola_Page_Layout_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Set as site theme'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            
            //  Code that runs the widget goes here...
			if( ! $data = $this->getIdentifierData() ){ return false; }
                        
			$this->createConfirmationForm( 'Confirm', 'Set  "' . $data['layout_label'] . '" as the main site theme' );
			$this->setViewContent( $this->getForm()->view(), true);
			if( ! $values = $this->getForm()->getValues() ){ return false; }

            $each = new Application_Settings_Editor( array( 'settingsname_name' => 'Page' ) );
            $each->fakeValues = array( 'default_layout' => $data['layout_name'] );
            if( ! $each->init() )
            {
                $this->setViewContent( '<p class="badnews">An error was encountered while changing the theme.</p>' ); 
                return false;
            }
            $this->setViewContent( '<p class="goodnews">Theme successfully set as main site theme. <a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/PageCarton_NewSiteWizard">New Website Wizard</a></p>', true );   

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
