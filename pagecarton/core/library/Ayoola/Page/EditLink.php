<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_EditLink
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: EditLink.php Thursday 2nd of November 2017 10:42PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_EditLink extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 98, 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Show Link to Edit Current Page'; 

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
		    $currentUrl = rtrim( Ayoola_Application::getPresentUri(), '/' ) ? : '/';
			switch( $currentUrl )
			{
				case '/tools/classplayer':
				case '/object':
				case '/pc-admin':
				case '/widget':
		//		case true:
					//	Do nothing.
					//	 had to go through this route to process for 0.00
			//		var_export( __LINE__ );
				break;
				default:
                    $html = '<div style="text-align:center;">';
                    $html .= '<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=' . $currentUrl . '\' );" title="Edit this page">Edit this page "' . $currentUrl . '"</a>';
                    $html .= '</div>';
                    $this->setViewContent( $html );   
				break;
			}
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
