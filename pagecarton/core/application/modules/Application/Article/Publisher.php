<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Publisher
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Publisher.php Tuesday 25th of December 2018 04:27AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Article_Publisher extends Application_Article_Creator
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Post New Content'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			if( ! $this->isAuthorized() )
			{
				return false;
			}
            $html = null;
            $done = true;

            //  Output demo content to screen
            $defaultLayout = Application_Settings_CompanyInfo::getSettings( 'Page', 'default_layout' );
            $dir = DOCUMENTS_DIR . DS . 'layout' . DS . $defaultLayout . DS . 'theme/template';
            if( ! $path = Ayoola_Loader::checkFile( $dir ) )
            {
                $dir = DOCUMENTS_DIR . DS . 'layout' . DS . $defaultLayout . DS . 'theme/variant/auto/template';
                if( ! $path = Ayoola_Loader::checkFile( $dir ) )
                {
                    //  give up
                }    
            }
            $dir = dirname( $path );
            $basename = array( 'data_json_content', 'content.json' );
            $files = array_unique( Ayoola_Doc::getFilesRecursive( $dir, array( 'whitelist_basename' => $basename ) ) );

            if( ! self::getObjectStorage( 'sanitized' )->retrieve() && time() - filemtime( $path ) < 9000 && $defaultLayout && ! $files )
            {
                self::getObjectStorage( 'sanitized' )->store( true );

                //  compatibiity
                $sanitize = new Ayoola_Page_Editor_Sanitize();
                $sanitize->sanitize( $defaultLayout );
                $files = array_unique( Ayoola_Doc::getFilesRecursive( $dir, array( 'whitelist_basename' => $basename ) ) );
            }

            $postTypes = array();

            foreach( $files as $each )
            {
                $extension = explode( "/", strtolower( $each ) );
                $extension = array_pop( $extension );
                if( ! in_array( $extension, $basename ) )
                {
                    continue;
                }

                $content = json_decode( file_get_contents( $each ), true ) ? : array();
                foreach( $content as $section )
                {
                    foreach( $section as $widget )
                    {
                        //    var_export( $widget['class'] );

                        $widgets = array();
                        if( Ayoola_Loader::loadClass( $widget['class'] ) )
                        {
                            $class = $widget['class'];
                            switch( $widget['class'] )
                            {
                                case 'Application_Article_ShowAll':        
                                case 'Application_Category_ShowAll':                                            
                                case 'Application_Profile_ShowAll':
                                case 'Application_Profile_All':

                                break;
                                case 'Ayoola_Page_Editor_Text':
                                    //    var_export( $widget['parameters']['content'] );
                                    if( !  @array_intersect( $widget['parameters']['markup_template_object_name'], array( 'Application_Article_ShowAll', 'Application_Category_ShowAll', 'Application_Profile_ShowAll' ) ) )
                                    {
                                        
                                        $widgets = array();
                                        $content = $widget['parameters']['content'];
                                        $includes = Ayoola_Page_Editor_Text::getContentIncludes( $content );
                                        $content = Ayoola_Page_Editor_Text::setContentIncludes( $content, $includes );
                                        Ayoola_Page_Editor_Text::embedWidget( $content, array(), $widgets );
                                        if( empty( $widgets ) )
                                        {
                                            //var_export( $widget['parameters'] );
                                            continue 2;
                                        }
    
                                    }
                                    
                
                                break;
                                default:
                                    continue 2;
                                break;
                            }
                            $widget['parameters'] =  ( $widget['parameters'] ? : array() ) + array( 'add_a_new_post_classplayer' => '/tools/classplayer/get/name' );
                            $class = new $class( $widget['parameters'] );
                            if( method_exists( $class, 'getMarkupTemplateObjects' ) )
                            {
                                $widgets = $widgets + $class->getMarkupTemplateObjects();
                            }
                            else
                            {
                                $widgets[] = $class;
                            }
                            foreach( $widgets as $eachWidget ) 
                            {
                                $values = $eachWidget->getObjectTemplateValues();
                                $noRequired = ( $eachWidget->getParameter( 'add_a_new_post' ) ? : 1 );
                                $category = $eachWidget->getParameter( 'category_name' ) ? : $eachWidget->getParameter( 'category' );
                            //    var_export( $category );
                                switch( get_class( $eachWidget ) )
                                {
                                    case 'Application_Article_ShowAll':
                                        $postType = ( $eachWidget->getParameter( 'article_types' ) ? : $eachWidget->getParameter( 'true_post_type' ) ) ? : ( method_exists( $eachWidget, 'getItemName' ) && $eachWidget::getItemName() ? $eachWidget::getItemName() : 'Post' );
                                        $kind = $category . $postType;
                                    break;
                                    case 'Application_Category_ShowAll':
                                        $postType = 'Category';
                                        $kind = get_class( $eachWidget );

                                    default:
                                    $kind = get_class( $eachWidget );
                                    break;
                                }
                                $kind = $kind . $noRequired;
                                if( ( $kind && @$postTypes[$kind] ) || ! $eachWidget->getParameter( 'add_a_new_post_full_url' ) || @$postTypes[$eachWidget->getParameter( 'add_a_new_post_full_url' )] )
                                {
                                    continue;
                                }
                                $postTypes[$kind] = $kind;
                                $postTypes[$eachWidget->getParameter( 'add_a_new_post_full_url' )] = $eachWidget->getParameter( 'add_a_new_post_full_url' );
                                $cssClass = 'goodnews';
                                if( $values['total_no_of_posts'] < $noRequired )
                                {
                                     $done = false;
                                     $cssClass = '';
                                }
                                $link = '' . Ayoola_Application::getUrlPrefix() . '' . $eachWidget->getParameter( 'add_a_new_post_full_url' ) . '&close_on_success=1';
                                $html .= '<a style="text-align:center;" class="pc-btn ' .  $cssClass  . '" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $link . '\', \'' . $this->getObjectName() . '\' );" href="javascript:" > 
                                <br><br>
                                ' . ucfirst( $postType ) . ' ' . ( $category ? ' [' . $category . '] ' : $category ) . '
                                
                                <br><br>
                                ' . $values['total_no_of_posts'] . ( $values['total_no_of_posts'] > $noRequired ? null : ( '/' .  $noRequired )  ) . '
                               
                                <br><br>
                               <i  style="margin:10px;" class="fa fa-plus"></i>  Add a new ' . ucfirst( $postType ) . ' <i  style="margin:10px;" class="fa"></i>
                               <br><br>
                               </a>';
                            }

                        }
                    }
    
                }
            }
            if( ! $html )
            {
                $this->setViewContent( Application_Article_Creator::viewInLine() ); 
                $done = Application_Article_Table::getInstance()->select();
            }
            $this->setViewContent( self::__( '<div style="text-align:center; display:flex;flex-wrap:wrap">' . $html . '</div>' ) ); 
            $this->setViewContent( self::__( '<div style="text-align:center;"><br><br><a style="text-align:center;" class="" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_Article_List\', \'page_refresh\' );" href="javascript:;" ><i  style="margin:10px;" class="fa fa-external-link"></i>  Manage all posts  </a><br><br></div>' ) ); 
            return $done;
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
		
    /**
     * 
     * 
     */
	public static function getPercentageCompleted()
    {
        $percentage = 0;
        $self = new static;
		if( $self->init()  )
		{
			$percentage += 100;
		}
		return $percentage;
	}
	// END OF CLASS
}
