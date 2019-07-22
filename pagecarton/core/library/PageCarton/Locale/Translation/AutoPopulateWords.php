<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Locale_Translation_AutoPopulateWords
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: AutoPopulateWords.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Locale_Translation_AutoPopulateWords extends PageCarton_Locale_Translation_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
    protected static $_objectTitle = 'Auto-populate translation words';   

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			$this->createConfirmationForm( 'Load', 'Try to view all the pages and widgets in the background so as to populate new words for translation' );
			$this->setViewContent( $this->getForm()->view(), true );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
            
            $pages = new Ayoola_Page_Page();
            $pages->getDatabase()->getAdapter()->setAccessibility( $pages::SCOPE_PROTECTED );
            $pages->getDatabase()->getAdapter()->setRelationship( $pages::SCOPE_PROTECTED );
            $pages = $pages->select();
			$this->setViewContent(  '' . self::__( '<div class="goodnews">Words populated successfully</div>' ) . '', true  );
            $currentUrl = Ayoola_Application::getRuntimeSettings( 'real_url' );
            set_time_limit( 0 );
            foreach( $pages as $page )
            {
                if( $page['url'] === '/accounts/signout' )
                {
                    continue;
                }
                $link  = Ayoola_Page::getHomePageUrl() . $page['url'];
//                self::v( $link );
//                continue;
                if( ! $pagePaths = Ayoola_Application::getViewFiles( $page['url'] ) )
                {
                //    self::v( $link );
                    continue;
                }
                Ayoola_Application::setRuntimeSettings( 'real_url', $page['url'] );
                include_once( $pagePaths['include'] );
                $this->setViewContent(  '<li class=""><a href="' . $link . '">' . $link . '</a>  viewed successfully</li>' );
                //                self::v( $link );
            //    self::fetchLink( $link );
            }
            Ayoola_Application::setRuntimeSettings( 'real_url', $currentUrl );

            foreach( Ayoola_Object_Embed::getWidgets() as $class )
            {
                
                $filter = new Ayoola_Filter_ClassToFilename();
				$classFile = $filter->filter( $class );
				$classFile = Ayoola_Loader::getFullPath( $classFile );
			//	var_export( $classFile );
				$fileContent = file_get_contents( $classFile );
				$content .= $fileContent;
                preg_match_all( "|setViewContent\( ?([^']*)'([^$()_]*)'([^']*) ?\)|", $fileContent, $output );
                $link = '/widgets/' . $class;
                Ayoola_Application::setRuntimeSettings( 'real_url', $link );
            //    var_export( $output[2] );

                foreach( $output[2] as $phrase )
                {
                    $phrase = trim( $phrase );
                    if( false === strpos( $phrase, ' ' ) )
                    {
                        continue;
                    }
                   self::__( $phrase );
                //    $this->setViewContent( $phrase );
                }
                $class::viewInLine();
                $this->setViewContent( '<li class=""><a href="' . Ayoola_Page::getHomePageUrl() . $link . '">' . $class . '</a>  widget viewed successfully</li>' );
            }
            Ayoola_Application::setRuntimeSettings( 'real_url', $currentUrl );

        //    var_export( $pages );
            

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
