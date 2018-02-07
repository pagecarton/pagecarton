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
            if( ! self::hasPriviledge( array( 98, 99 ) ) )  
            {
                return false;
            } 
            //  Output demo content to screen
		    $currentUrl = rtrim( Ayoola_Application::getRuntimeSettings( 'real_url' ), '/' ) ? : '/';
    //        var_export( $currentUrl );
            $editorMode = false;
			switch( $currentUrl )
			{
				case '/tools/classplayer':
				case '/object':
				case '/pc-admin':
				case '/widgets':
				case '/widget':
		//		case true:
					//	Do nothing.
					//	 had to go through this route to process for 0.00
			//		var_export( __LINE__ );
					if( @$_REQUEST['url'] && @$_REQUEST['name'] || ( @$_REQUEST['rebuild_widget'] ) )
                    {
                        $currentUrl = $_REQUEST['url'];
                        $editorMode = true;
                        break;
                    }
                    return false;
				break;
				default:
      //      var_export( $currentUrl );
				break;
			}
            if( ! $editorMode )
            {
               $editorLink = 'href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=' . $currentUrl . '\', \'page_refresh\' );"';
            }
            else
            {
               $editorLink = 'href="javascript:" onClick="alert( \'Link will not work in Page Editor!\' );"';
            }
            $html = '<div style="text-align:center;">';
            $html .= '<a ' . $editorLink . ' title="Edit this page">Edit this Page ' . ( $currentUrl ? '<span style="font-size:x-small;">[' . $currentUrl . ']</span>' : '' ) . '</a>';
            $html .= '</div>';
            $this->setViewContent( $html );   
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
