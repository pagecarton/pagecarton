<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Pages_Copy
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Copy.php Friday 15th of September 2017 03:02PM  $
 */

/**
 * @see Ayoola_Page_Layout_Pages
 */

class Ayoola_Page_Layout_Pages_Copy extends Ayoola_Page_Layout_Pages
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
	protected static $_objectTitle = 'Copy page from theme'; 

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
	public static function this( $url, $themeName )
    {     
        //  create page if they don't exist'
        $class = new Ayoola_Page_Editor_Sanitize(  array( 'no_init' => true, 'auto_create_page' => true )  );
  //      var_export( $pageInfo );

        $fPaths = $tPaths = Ayoola_Page::getPagePaths( $url );
    //    var_export( $tPaths );
        $pageThemeFileUrl = $url;
        if( $pageThemeFileUrl == '/' )
        {
            $pageThemeFileUrl = '/index';
        }
    //    $themeName = strtolower( $data['layout_name'] );
        $fPaths['include'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/include';
        $fPaths['template'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/template';
        $fPaths['data_json'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_json';

        if( ! Ayoola_Loader::getFullPath( $fPaths['include'], array( 'prioritize_my_copy' => true ) ) )
        {
            //  don't create this page unless it's saved
            return false;
        }

        if( ! $pageInfo = $class->sourcePage( $url ) )
        {
             return false;
        }

        foreach( $fPaths as $key => $each )
        {
            if( $from = Ayoola_Loader::getFullPath( $each, array( 'prioritize_my_copy' => true ) ) )
            {
                $to = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $tPaths[$key];
                Ayoola_Doc::createDirectory( dirname( $to ) );
                copy( $from, $to );
            }
    //          var_export( $from );
    //         var_export( $to );
    //        var_export( $tPaths[$each] );
        }
        return true;
    }

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
            
			$this->createConfirmationForm( 'Copy', 'Copy contents of  "' . $url . '" in "' . $data['layout_label'] . '" to main page' );
			$this->setViewContent( $this->getForm()->view(), true);
			if( ! $values = $this->getForm()->getValues() ){ return false; }
/*
            
            //	update theme files
            $class = new Ayoola_Page_Editor_Layout( array( 'no_init' => true ) );
            $class->setPageInfo( array( 'url' => $url ) );
            $class->updateLayoutOnEveryLoad = true;
            $class->setPagePaths();
            $class->setValues();
            $class->init(); // invoke the template update for this page.
*/        //    $paths = $this->getPageFilesPaths( $values['origin'] );

            if( self::this( $url, $data['layout_name'] ) )
            {
                $this->setViewContent( '<p class="goodnews">"' . $url . '" page copied successfully.</p>', true ); 
            }
            else
            {
                $this->setViewContent( '<p class="badnews">Page could not be copied.</p>' ); 
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
