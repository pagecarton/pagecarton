<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
	public static function canCopy( $url, $themeName )
    {     
        //  create page if they don't exist'
        $class = new Ayoola_Page_Editor_Sanitize(  array( 'no_init' => true, 'auto_create_page' => true )  );
  //      var_export( $pageInfo );


    //    var_export( $tPaths );
        $pageThemeFileUrl = $url;
        if( $pageThemeFileUrl == '/' )
        {
            $pageThemeFileUrl = '/index';
        }
        $fPaths = static::getPagePaths( $themeName, $pageThemeFileUrl );
        $pageFile = 'documents/layout/' . $themeName . '' . $pageThemeFileUrl . '.html';
        $pageFile = Ayoola_Loader::getFullPath( $pageFile, array( 'prioritize_my_copy' => true ) );
        if( ! is_file( $pageFile ) )
        {
            return false;
        }
//    var_export();

        if( ! Ayoola_Loader::getFullPath( $fPaths['include'], array( 'prioritize_my_copy' => true ) ) )
        {
            //  don't create this page unless it's saved
            return false;
        }

        //  when other files like template and data wasn't checked, theme pages are being sanitized
        #   when the default theme was sanitized

        if( ! Ayoola_Loader::getFullPath( $fPaths['template'], array( 'prioritize_my_copy' => true ) ) )
        {
            //  don't create this page unless it's saved
            return false;
        }

        if( ! Ayoola_Loader::getFullPath( $fPaths['data_json'], array( 'prioritize_my_copy' => true ) ) )
        {
            //  don't create this page unless it's saved
            return false;
        }

        //  page does not need to exit to be able to copy
        //  to allow Ayoola_Page::getInfo to work for theme pages
    //    if( ! $pageInfo = $class->sourcePage( $url ) )
        {
    //         return false;
        }
        if( ! empty( $pageInfo['layout_name'] ) && $pageInfo['layout_name'] != $themeName )
        {
             return false;
        }
    //    foreach( $fPaths as $key => $each )
        {
     //       if( $from = Ayoola_Loader::getFullPath( $each, array( 'prioritize_my_copy' => true ) ) )
            {
            //    var_export( $key . '<br>');
            //    var_export( $from . '<br>');
            //    copy( $from, $to );
            }
        }
        return true;
    }

    /**
     * Performs the whole widget running process
     * 
     */
	public static function this( $url, $themeName )
    {     
        if( ! static::canCopy( $url, $themeName ) )
        {
            return false;
        }
        $fPaths = $tPaths = Ayoola_Page::getPagePaths( $url ); 
        $pageThemeFileUrl = $url;
        if( $pageThemeFileUrl == '/' )
        {
            $pageThemeFileUrl = '/index';
        }
        $fPaths = static::getPagePaths( $themeName, $pageThemeFileUrl );
    //    var_export(  $fPaths );

        foreach( $fPaths as $key => $each )
        {
            if( $from = Ayoola_Loader::getFullPath( $each, array( 'prioritize_my_copy' => true ) ) )
            {
                $to = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $tPaths[$key];
                Ayoola_Doc::createDirectory( dirname( $to ) );
                copy( $from, $to );
            }
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
                $this->setViewContent( self::__( '<p class="badnews">Page not found in theme.</p>' ) ); 
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


            //  create here for compatibility. Pages copied before will keep asking for password
            $class = new Ayoola_Page_Editor_Sanitize(  array( 'no_init' => true, 'auto_create_page' => true )  );
            if( ! $pageInfo = $class->sourcePage( $url ) )
            {
                return false;  
            }


            if( self::this( $url, $data['layout_name'] ) )
            {
                $this->setViewContent( '<p class="goodnews">"' . $url . '" page copied successfully.</p>', true ); 
            }
            else
            {
                $this->setViewContent( self::__( '<p class="badnews">Page could not be copied.</p>' ) ); 
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
