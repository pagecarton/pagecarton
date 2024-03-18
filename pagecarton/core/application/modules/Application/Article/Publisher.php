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
            $sections = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . dirname( $dir ) . DS . 'pagewidgetsettings';

            $summit = json_decode( file_get_contents( $sections ), true ) ? : array();

            if( isset( $_REQUEST['ajax_post'] ) )
            {
            
                foreach( $_POST as $key => $value )
                {
                    list( $page, $widgetId, $parameter ) = explode( '--', $key );
                    $summit[$page][$widgetId][$parameter] = $value;
                }
                Ayoola_Doc::createDirectory( dirname( $sections ) );
                Ayoola_File::putContents( $sections, json_encode( $summit ) );  
                //var_export( $sections );
                exit;
            }

            if( ! $path = Ayoola_Loader::checkFile( $dir ) )
            {
                $dir = DOCUMENTS_DIR . DS . 'layout' . DS . $defaultLayout . DS . 'theme/variant/auto/template';

                //var_export( Ayoola_Loader::checkFile( $dir ) ); 

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
            $count = 0;
            foreach( $files as $each )
            {
                //var_export($dir);
                $pageUrl = explode( $dir, $each );
                $pageUrl = $pageUrl[1];

                $extension = explode( "/", strtolower( $each ) );
                $extension = array_pop( $extension );
                if( ! in_array( $extension, $basename ) )
                {
                    continue;
                }

                $textContent = file_get_contents( $each );
                
                if( dirname( $pageUrl ) == '/')
                {
                    //$pageUrl = '/index/' . basename( $each );
                    $realIndexPage = $dir . DS . 'index' . DS . basename( $each );
                    $textContent = file_get_contents( $realIndexPage );
                    unset( $files[$realIndexPage] );
                }
                elseif( dirname( $pageUrl ) == '/index' )
                {
                    continue;
                }
                if( stripos( $textContent, '<widget' ) === false && stripos( $textContent, '<include' ) === false )
                {
                    continue;
                }


                $html .= '<div style=" margin:1em; font-size:small;" >';
                $html .= '<h2 style="float:left;">Page ' . ++$count . ' </h2>';
                $html .= '<p style="float:right;"> 
                                > <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '' . ( dirname( $pageUrl ) ) . '">' . Ayoola_Page::getHomePageUrl() . '' . ( dirname( $pageUrl ) ) . '</a> 
                                <br>
                                > <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Page_Editor?url=' . ( dirname( $pageUrl ) ) . '">Page Settings</a>
                            </p>
                            <div style="clear:both;"></div>

                            '
                            ;
                            
                //$html .= '<p>' . ( $realIndexPage ) . '</p>';
                $html .= '</div>

                ';
                            
                $html .= '<div style="text-align:center; display:flex;flex-wrap:wrap">';
                $content = json_decode( $textContent, true ) ? : array();


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
                                    if( ! is_array( $widget['parameters']['markup_template_object_name'] ) )
                                    {
                                        $widget['parameters']['markup_template_object_name'] = array();
                                    }
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
                                if( is_array( $widgets ) && is_array( $class->getMarkupTemplateObjects() ) )
                                {
                                    $widgets = $widgets + $class->getMarkupTemplateObjects();
                                }
                            }
                            else
                            {
                                $widgets[] = $class;
                            }
                            $kindWithNoReq = array();
                            $content = array();
                            foreach( $widgets as $eachWidget ) 
                            {
                                $values = $eachWidget->getObjectTemplateValues();
                                $noRequired = ( $eachWidget->getParameter( 'add_a_new_post' ) ? : 1 );
                                
                                $category = $eachWidget->getParameter( 'category_name' ) ? : $eachWidget->getParameter( 'category' );
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
                                if( isset( $kindWithNoReq[$kind] ) && intval( $kindWithNoReq[$kind] ) >  $noRequired )
                                {
                                    //continue;
                                }

                                $kindWithNoReq[$kind] = $noRequired;

                                //$kind = $kind . $noRequired;
                                if( ( $kind && @$postTypes[$kind] ) || ! $eachWidget->getParameter( 'add_a_new_post_full_url' ) || @$postTypes[$eachWidget->getParameter( 'add_a_new_post_full_url' )] )
                                {
                                    //continue;
                                }
                                $postTypes[$kind] = $kind;
                                $postTypes[$eachWidget->getParameter( 'add_a_new_post_full_url' )] = $eachWidget->getParameter( 'add_a_new_post_full_url' );
                                $cssClass = 'goodnews';
                                $style = 'color: #4F8A10;
                                background-color: #DFF2BF;';
                                if( $values['total_no_of_posts'] < $noRequired )
                                {
                                     $done = false;
                                     $cssClass = 'badnews';
                                     $style = 'color: #D8000C; 
                                     background-color: #FFBABA45;
                                     border: 1px solid #FFBABAA3;';
                                }
                                $link = '' . Ayoola_Application::getUrlPrefix() . '' . $eachWidget->getParameter( 'add_a_new_post_full_url' ) . '&close_on_success=1';

                                $widgetId = $eachWidget->getParameter( 'widget_id' );

                                // if( $postType == 'event')
                                // {
                                //     var_export( $widgetId );
                                //     var_export( $postType );
                                //     var_export( $eachWidget->getParameter() );

                                // }
                                //var_export( $summit[dirname( $pageUrl )][$widgetId] );
                                if( isset($summit[dirname( $pageUrl )][$widgetId]['add_a_new_post']) )
                                {
                                    $noRequired = $summit[dirname( $pageUrl )][$widgetId]['add_a_new_post'];
                                }
                                $options = '';
                                foreach( range( 1, 50 ) as $each )
                                {
                                    $selected = null;
                                    if( $each == $noRequired )
                                    {
                                        $selected = 'selected = "selected"';
                                    }
                                    $options .= '<option value="' . $each . '" ' . $selected . ' >' . $each . ' needed</option>';
                                }
                                $content[$kind] = 
                                '<div style="text-align:center; margin:1em; border: 2px solid #ccc;" > 
                                <br><br>
                                ' . ucfirst( $postType ) . ' ' . ( $category ? ' [' . $category . '] ' : $category ) . '
                                <br>
                                <br>
                                <select class="sample-xyu" style="padding:0.5em;" id="' . dirname( $pageUrl ) . '--' . $widgetId . '--add_a_new_post">
                                <option value="0">Disabled</option>
                                ' . $options . '
                                </select>
                                <br><br>
                                <span style="padding:1em;' .  $style  . '">
                                ' . intval( $values['total_no_of_posts'] ) . ' added
                                </span>
                                <br>

                                <br><br>
                                <a class="pc-btn" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $link . '\', \'' . $this->getObjectName() . '\' );" href="javascript:"> 
                                    <i  style="margin:10px;" class="fa fa-plus"></i>  Add a new ' . ucfirst( $postType ) . ' <i  style="margin:10px;" class="fa"></i>
                                </a>

                               </div>';
                            }
                            if( $content )
                            {

                                $html .= implode( "\r\n", $content );
                            }
                        }
                    }
    
                }
                $html .= '</div>';
            }
            if( ! $html )
            {
                $this->setViewContent( Application_Article_Creator::viewInLine() ); 
                $done = Application_Article_Table::getInstance()->select();
            }
            Application_Javascript::addCode(
                '
                ayoola.events.add
                (
                    window, "load", function(){

                        $("select.sample-xyu").on("change", function(event) { 

                            let eg = {}
                            eg[this.id] = this.value;
                            $.post( 
                                "' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/' . $this->getObjectName() . '/?ajax_post=1", 
                                eg
                                ,
                                function( data, status ){
                                    alert( "Settings Saved Successfully." );
                                }
                            );
                       } );
                 
                    }
                );
                '
            );
            $this->setViewContent( self::__( '<div>' . $html . '</div>' ) ); 
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
