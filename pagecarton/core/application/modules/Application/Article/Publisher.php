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
            $dir = DOCUMENTS_DIR . DS . 'layout' . DS . $defaultLayout . DS . 'template';
            $path = Ayoola_Loader::checkFile( $dir );
            $dir = dirname( $path );
            $basename = array( 'data_json_content', 'content.json' );
            $files = array_unique( Ayoola_Doc::getFilesRecursive( $dir, array( 'whitelist_basename' => $basename ) ) );
            $postTypes = array();

            if( time() - filemtime( $path ) < 9000 && $defaultLayout && ! $files )
            {
                //  compatibiity
                $sanitize = new Ayoola_Page_Editor_Sanitize();
            //    var_export( $defaultLayout );
                
                $sanitize->sanitize( $defaultLayout );
            //    exit();
            }
        //    self::v( $files );
            foreach( $files as $each )
            {
            //    $extension = array_pop( explode( "/", strtolower( $each ) ) );
                $extension = explode( "/", strtolower( $each ) );
                $extension = array_pop( $extension );
                if( ! in_array( $extension, $basename ) )
                {
                    continue;
                }
       //         self::v( $each );
                $content = json_decode( file_get_contents( $each ), true ) ? : array();
          //      self::v( $content );
                foreach( $content as $section )
                {
                    foreach( $section as $widget )
                    {
                        if( Ayoola_Loader::loadClass( $widget['class'] ) )
                        {
           //     self::v( $widget['parameters'] );
                            $class = $widget['class'];
                            switch( $widget['class'] )
                            {
                                case 'Application_Article_ShowAll':        
                                case 'Application_Category_ShowAll':                                            
                                case 'Application_Profile_ShowAll':

                                break;
                                case 'Ayoola_Page_Editor_Text':
                                    if( !  @array_intersect( $widget['parameters']['markup_template_object_name'], array( 'Application_Article_ShowAll', 'Application_Category_ShowAll', 'Application_Profile_ShowAll' ) ) )
                                    {
                                        continue 2;
                                    }
                
                                break;
                                default:
                                    continue 2;
                                break;
                            }
                        //    self::sanitizeParameters( $widget['parameters'] );
                            $widget['parameters'] =  ( $widget['parameters'] ? : array() ) + array( 'add_a_new_post_classplayer' => '/tools/classplayer/get/name' );
             //   self::v( $widget['parameters'] );
                            $class = new $class( $widget['parameters'] );
                            $widgets = array();
                            if( method_exists( $class, 'getMarkupTemplateObjects' ) )
                            {
                                $widgets = $class->getMarkupTemplateObjects();
                            }
                            else
                            {
                                $widgets[] = $class;
                            }
                            foreach( $widgets as $eachWidget ) 
                            {
            //    self::v( $eachWidget->getParameter() );
                            //    self::v( $eachWidget );
                                $values = $eachWidget->getObjectTemplateValues();
                                $noRequired = ( $eachWidget->getParameter( 'no_of_post_to_show' ) ? : 1 );
                                $postType = ( $eachWidget->getParameter( 'article_types' ) ? : $eachWidget->getParameter( 'true_post_type' ) ) ? : ( method_exists( $eachWidget, 'getItemName' ) && $eachWidget::getItemName() ? $eachWidget::getItemName() : 'Post' );
                                $category = $eachWidget->getParameter( 'category_name' ) ? : null;
                                $kind = $category . $postType;
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
                            //    var_export( get_class( $eachWidget ) );
                                $link = '' . Ayoola_Application::getUrlPrefix() . '' . $eachWidget->getParameter( 'add_a_new_post_full_url' ) . '&close_on_success=1';
                                $html .= '<a style="text-align:center;" class="pc-btn ' .  $cssClass  . '" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $link . '\', \'' . $this->getObjectName() . '\' );" href="javascript:" > 
                                ' . $postType . ' ' . ( $category ? ' [' . $category . '] ' : $category ) . '
                                
                                <br><br>
                                ' . $values['total_no_of_posts'] . ( $values['total_no_of_posts'] > $noRequired ? null : ( '/' .  $noRequired )  ) . '
                               
                                <br>
                               <i  style="margin:10px;" class="fa fa-external-link"></i>  Add a new item  </a>';
                            //    self::v( $eachWidget->getParameter( 'add_a_new_post_full_url' ) );
                            }

                       //     $this->setViewContent( $class->view() ); 
                        }
                    }
    
                }
            }
            if( ! $html )
            {
                $this->setViewContent( Application_Article_Creator::viewInLine() ); 
                $done = Application_Article_Table::getInstance()->select();
            }
            $this->setViewContent( '<div style="text-align:center;">' . $html . '</div>' ); 
            $this->setViewContent( '<div style="text-align:center;"><br><br><a style="text-align:center;" class="" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_Article_List\', \'page_refresh\' );" href="javascript:;" ><i  style="margin:10px;" class="fa fa-external-link"></i>  Manage all Posts  </a><br><br></div>' ); 
            return $done;
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
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
//$self::init();
		if( $self->init()  )
		{
			$percentage += 100;
		}
	//	var_export( $percentage );
 //   var_export( self::getUpdates() );
//    var_export( $themeInfoAll['dummy_search'] );
		return $percentage;
	}
	// END OF CLASS
}
