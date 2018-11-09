<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Pages_ClearContent
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ClearContent.php Friday 15th of September 2017 03:02PM  $
 */

/**
 * @see Ayoola_Page_Layout_Pages
 */

class Ayoola_Page_Layout_Pages_ClearContent extends Ayoola_Page_Layout_Pages
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Clear content from Theme Page'; 

    /**
     * 
     * 
     * @var Ayoola_Page_Page 
     */
	protected static $_table; 

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
			if( ! $data = $this->getIdentifierData() ){ return false; }
		
		//	var_export( $this->getFilename() );
            $url = $_REQUEST['url'];
            
            if( ! in_array( $url, self::getPages( $data['layout_name'], 'list' ) ) )
            {
                $this->setViewContent( '<p class="badnews">Page not found in theme.</p>' ); 
                return false;   
            }
            $pageThemeFileUrl = $url;
        //    $pageThemeFileUrl = $data['layout_name'];
            if( $pageThemeFileUrl == '/' )
            {
                $pageThemeFileUrl = '/index';
            }
            $fPaths = array();
            $themeName = strtolower( $data['layout_name'] );
/*             $fPaths['include'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/include';
            $fPaths['template'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/template';
            $fPaths['data_json'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_json';
 */            
            $fPaths = static::getPagePaths( $themeName, $pageThemeFileUrl );
            $from = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $fPaths['include'];
      //      var_export( $from );
            if( ! is_file( $from ) )
            {
                //  don't create this page unless it's saved
                $this->setViewContent( '<p class="badnews">Theme page has no saved content</p>' ); 
                return false;
            }
            
			$this->createConfirmationForm( 'Clear', 'Delete contents of  "' . $url . '" in "' . $data['layout_label'] . '"' );
			$this->setViewContent( $this->getForm()->view(), true);
			if( ! $values = $this->getForm()->getValues() ){ return false; }

    //     return false;
            foreach( $fPaths as $key => $each )
            {
                $from = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $fPaths[$key];
            //  var_export( $from );
                unlink( $from );
            }

       //     if( self::this( $url, $data['layout_name'] ) )
            {
                $this->setViewContent( '<p class="goodnews">"' . $url . '" page cleared successfully.</p>', true ); 
            }
         //   else
            {
          //      $this->setViewContent( '<p class="badnews">Page could not be copied.</p>' ); 
            }

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
