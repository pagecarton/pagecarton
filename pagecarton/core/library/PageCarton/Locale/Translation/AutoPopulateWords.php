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


            $options = PageCarton_Locale_Settings::retrieve( 'locale_options' );
            if( is_array( $options ) && in_array( 'autosave_new_words', $options ) )
            {
                
            }
            else
            {
                $each = new Application_Settings_Editor( array( 'settingsname_name' => 'Locale' ) );
                $settings = Ayoola_Page_Settings::retrieve();
                $settings['locale_options'] = is_array( $settings['locale_options'] ) ? $settings['locale_options'] : array();
                $settings['locale_options'][] = 'autosave_new_words';
                $each->fakeValues = $settings;
                if( ! $each->init() )
                {
                    $this->setViewContent( self::__( '<p class="badnews">We could not set the system to save new words automatically.</p>' ) ); 
                    return false;
                }
    
            }

            
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
                if( ! $pagePaths = Ayoola_Application::getViewFiles( $page['url'] ) )
                {
                    continue;
                }
                Ayoola_Application::setRuntimeSettings( 'real_url', $page['url'] );
                include_once( $pagePaths['include'] );
                $this->setViewContent(  '<li class=""><a  target="_blank" href="' . $link . '">' . $link . '</a>  viewed successfully</li>' );
            }
            Ayoola_Application::setRuntimeSettings( 'real_url', $currentUrl );

            foreach( Ayoola_Object_Embed::getWidgets( false ) as $class )
            {
                $filter = new Ayoola_Filter_ClassToFilename();
				$classFile = $filter->filter( $class );
				$classFile = Ayoola_Loader::getFullPath( $classFile );
				$fileContent = file_get_contents( $classFile );
                $link = '/widgets/' . $class;
                Ayoola_Application::setRuntimeSettings( 'real_url', $link );

                //  set words set right
                preg_match_all( "|self::__\( ?'([^:>']*)' ?\)|", $fileContent, $output );
                foreach( $output[1] as $phrase )
                {

                    $phrase = trim( $phrase );
                    if( false === strpos( trim( $phrase ), ' ' ) )
                    {
                        continue;
                    }
                    self::__( $phrase );
                }

                //  other words
                preg_match_all( "|setViewContent\( ?([^']*)'([^$()_]*)'([^']*) ?\)|", $fileContent, $output );
                foreach( $output[2] as $phrase )
                {
                    $phrase = trim( $phrase );
                    if( false === strpos( trim( $phrase ), ' ' ) )
                    {
                        continue;
                    }
                   self::__( $phrase );
                }

                if( false === stripos( $fileContent, 'exit(' ) 
                
                && false === stripos( $fileContent, 'die(' ) 
                
                && false === stripos( $fileContent, 'header(' ) 
                
                && false === stripos( $fileContent, 'echo' )

                && false === stripos( $fileContent, '_logout' ) 
                
                )
                {
                    if( ! empty( $_SESSION[$classFile] ) )
                    {
                        continue;
                    }
                    try
                    {
                        try
                        {
                            $class::viewInLine( array( 'play_mode' => static::PLAY_MODE_HTML ) );
                            $_SESSION[$classFile] = true;    
                        }
                        catch( Exception $e )
                        { 
                            //  Alert! Clear the all other content and display whats below.
                            
                            $this->setViewContent( self::__( '<p class="badnews">' . $classFile . ' - '.$e->getMessage() . $e->getTraceAsString() . '</p>' ) );
                            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
                            //return false; 
                        }
                    }
                    catch( Error $e )
                    { 
                        //  Alert! Clear the all other content and display whats below.
                        
                        $this->setViewContent( self::__( '<p class="badnews">' . $classFile . ' - '.$e->getMessage() . $e->getTraceAsString() . '</p>' ) );
                        $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
                        //return false; 
                    }
            
                }
                $this->setViewContent( '<li class=""><a target="_blank" href="' . Ayoola_Page::getHomePageUrl() . $link . '">' . $class . '</a>  widget viewed successfully</li>' );
            }
            Ayoola_Application::setRuntimeSettings( 'real_url', $currentUrl );

            

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( ( '<p class="badnews">' . $e->getMessage() . $e->getTraceAsString() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
