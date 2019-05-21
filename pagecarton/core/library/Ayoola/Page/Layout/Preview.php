<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Preview
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Preview.php Saturday 16th of September 2017 10:23PM  $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_Layout_Preview extends Ayoola_Page_Layout_Abstract
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
	protected static $_objectTitle = 'Theme Preview'; 

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
            
            header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/?pc_page_layout_name=' . $data['layout_name'] . '' );
            exit();

			$themeName = $data['layout_name'];
		//	$themeName = $themeName ? : Ayoola_Page_Editor_Layout::getDefaultLayout();
			$pagePaths['include'] = 'documents/layout/' . $themeName . '/theme' . '/include';
			$pagePaths['template'] = 'documents/layout/' . $themeName . '/theme' . '/template';
			
			//	theme copy
			$PAGE_INCLUDE_FILE = Ayoola_Loader::getFullPath( $pagePaths['include'], array( 'prioritize_my_copy' => true ) );
			$PAGE_TEMPLATE_FILE = Ayoola_Loader::getFullPath( $pagePaths['template'], array( 'prioritize_my_copy' => true ) );
			if( ! $PAGE_INCLUDE_FILE AND ! $PAGE_TEMPLATE_FILE )
			{
                $this->setViewContent( '<p class="badnews">Theme files not found.</p>' ); 
				//	not found
				return false;
			}

            //  Output demo content to screen
            include $PAGE_INCLUDE_FILE;

			$temIncludeFile = tempnam( CACHE_DIR, __CLASS__ );           
			$content = file_get_contents( $PAGE_TEMPLATE_FILE );
    //        var_export( $content );
			$content = preg_replace( '/@@@([a-zA-Z_\-0-9]*)@@@/', '', $content );
            file_put_contents( $temIncludeFile, $content );
   //         var_export( $content );
            include $temIncludeFile;
            unlink( $temIncludeFile );
            exit();
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
