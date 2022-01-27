<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
            //  Output demo content to screen
			if( ! $data = $this->getIdentifierData() ){ return false; }
		
            $url = $_REQUEST['url'];
            
            if( ! in_array( $url, self::getPages( $data['layout_name'], 'list' ) ) )
            {
                $this->setViewContent( self::__( '<p class="badnews">Page not found in theme.</p>' ) ); 
                return false;   
            }
            $pageThemeFileUrl = $url;
            if( $pageThemeFileUrl == '/' )
            {
                $pageThemeFileUrl = '/index';
            }
            $fPaths = array();
            $themeName = strtolower( $data['layout_name'] );
            
            $fPaths = static::getPagePaths( $themeName, $pageThemeFileUrl );
            $from = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $fPaths['include'];

            if( ! is_file( $from ) && $pageThemeFileUrl != '/template' )
            {
                //  don't create this page unless it's saved
                $this->setViewContent( self::__( '<p class="badnews">Theme page has no saved content</p>' ) ); 
                return false;
            }

            $message = 'Delete contents of  "' . $url . '" in "' . $data['layout_label'] . '".';

            if( $pageThemeFileUrl == '/template' )
            {
                $message .= ' WARNING!! Because you are deleting /template, This will also WIPE all saved data of this theme, not just the theme page.';
            }
			$this->createConfirmationForm( 'Clear', $message );

            $this->setViewContent( $this->getForm()->view(), true);

			if( ! $values = $this->getForm()->getValues() ){ return false; }

            foreach( $fPaths as $key => $each )
            {
                $from = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $fPaths[$key];
                Ayoola_File::trash( $from );
            }
            if( $pageThemeFileUrl == '/template' )
            {
                $all = 'documents/layout/' . $themeName . '/theme';
                $all = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $all;
                $files = Ayoola_Doc::getFilesRecursive( $all );
                foreach( $files as $each )
                {
                    Ayoola_File::trash( $each );
                }
            }


            $this->setViewContent(  '' . self::__( '<p class="goodnews">"' . $url . '" page cleared successfully.</p>' ) . '', true  );
            // - end of widget process
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( 'Theres an error in the code' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
