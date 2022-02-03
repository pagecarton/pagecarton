<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Object_Play
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Play.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see Ayoola_Object_Abstract
 */
 
require_once 'Ayoola/Object/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Object_Play
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Object_Play extends Ayoola_Object_Abstract
{
	
    /**
     * Does the class process
     *
     * @param void
     */
    public function init()
    {
		//	Make the application know we are using class player
        $_SERVER['HTTP_APPLICATION_MODE'] = $this->getObjectName();


        //var_export( $this->getParameter() );
		try
		{
			{
			//	ALLOW THE USE OF CLASS_NAME
				$identifier = null;

				if( @$_REQUEST['object_name'] )
				{ 
					$identifier = array( 'class_name' => $_REQUEST['object_name'] );
                    $this->_objectData['identifier'] = $identifier;
				}
				elseif( ! empty( $_REQUEST['pc_module_url_values'][0] ) && $_REQUEST['pc_module_url_values'][0] !== 'name' && $_REQUEST['pc_module_url_values'][0] !== 'object_name' )
				{
					$identifier = array( 'class_name' => $_REQUEST['pc_module_url_values'][0] );
                    $this->_objectData['identifier'] = $identifier;
				}
				elseif( @$_REQUEST['name'] )
				{
					$identifier = array( 'class_name' => $_REQUEST['name'] );
                    $this->_objectData['identifier'] = $identifier;
				}
				elseif( ($this->getParameter( 'list_all_widgets' ) || @$_REQUEST['list_all_widgets']) && self::hasPriviledge() )
				{
                    $this->setViewContent( '<br><h2>PageCarton Widgets</h2><br>' );
                    $this->_objectData['widgets'] = Ayoola_Object_Embed::getWidgets( false );

                    foreach( Ayoola_Object_Embed::getWidgets( false ) as $class ) 
                    {
                        $this->setViewContent( '<li>' . $class . ' - <a href="?object_name=' . $class . '">content</a>  - <a href="?widget_code=' . $class . '">code</a> </li>' );
                    }
                    return false;
				}
				elseif( (@$_REQUEST['widget_code']) && self::hasPriviledge() )
				{
                    if( Ayoola_Loader::loadClass( $_REQUEST['widget_code'] ) )
                    {
                        $filter   = new Ayoola_Filter_ClassToFilename();
                        $filename = $filter->filter( $_REQUEST['widget_code'] );
                        $this->setViewContent( '<br><h2>PageCarton Widget Class Preview </h2><br>' );
                        $this->setViewContent( highlight_file( $filename, true ) );
                        $this->_objectData['highlight_file'] = highlight_file( $filename, true );
                    }
                    return true;
				}
				elseif( $widgetId = $this->getParameter( 'widget_id' ) ? : @$_REQUEST['widget_id'] )
				{
                    $this->_objectData['widget_id'] = $widgetId;
                    if( $widget = Ayoola_Object_PageWidget::getInstance()->selectOne( null,  array( 'pagewidget_id' => $widgetId ) ) )
                    {
                        $class = $widget['class_name'];
                        $class = new $class( array( 'pagewidget_id' => $widgetId ) + $widget['parameters'] );
                       $this->setViewContent( '' . $class->view() . '' );
                        return true;
                    }
                    elseif( $widget = Ayoola_Object_SavedWidget::getInstance()->selectOne( null,  array( 'savedwidget_id' => $widgetId ) ) )
                    {
                        $class = $widget['class_name'];
                        $class = new $class( array( 'pagewidget_id' => $widgetId ) + $widget['parameters'] );
                        $this->setViewContent( '' . $class->view() . '' );
                        return true;
                    }
				}
				$this->setIdentifierData( $identifier );
			}
			//	I want to allow a convenient way of playing class
			if( $identifier['class_name'] === __CLASS__ )
			{
				exit( $identifier['class_name'] );
			}
			if( Ayoola_Loader::loadClass( @$identifier['class_name'] ) )
			{ 

				if( $identifier['class_name']::isPlayable() )
				{
					$identifier['object_name'] = $identifier['class_name'];
                    $identifier['auth_level'] = $identifier['class_name']::getAccessLevel();

					if( self::checkObject( $identifier ) )
					{
						$_SERVER['HTTP_AYOOLA_PLAY_CLASS'] = $identifier['class_name'];
						if( isset( $_SERVER['HTTP_AYOOLA_PLAY_MODE'] ) )
						{
							$playMode = $_SERVER['HTTP_AYOOLA_PLAY_MODE'] ? : $this->_playMode;
                        }
						if( isset( $_SERVER['HTTP_PC_WIDGET_OUTPUT_METHOD'] ) )
						{
							$playMode = $_SERVER['HTTP_PC_WIDGET_OUTPUT_METHOD'] ? : $this->_playMode;
                        }
						if( isset( $_REQUEST['pc_widget_output_method'] ) )
						{
							$playMode = $_REQUEST['pc_widget_output_method'];
                        }
						$classToPlay = new $identifier['class_name'];
						$classToPlay->setParameter( array( 'play_mode' => @$playMode ) );
						$classToPlay->initOnce();
						$this->setViewContent( $classToPlay->view(), true );
						if( @$_REQUEST['close_on_success'] )
						{ 
						//	if( Ayoola_Loader::loadClass( $classToPlay ) )
							{
								if( method_exists( $classToPlay, 'getPercentageCompleted' ) )
								{
									$percentage = $classToPlay::getPercentageCompleted();
								}
							}			
							if( $classToPlay->getForm()->getValues() )
							{
								Application_Javascript::addCode(
									'
										ayoola.events.add
										(
											window, "load", function(){ 
												ayoola.spotLight.close();
											//	parent.ayoola.spotLight.close();
											} 
										);
									'
								);
							}  
							elseif( $percentage == 100 )
							{
								Application_Javascript::addCode(
									'
										ayoola.events.add
										(
											window, "load", function(){
											//	alert( document.getElementsByClassName( "goodnews" ) );
											//	alert( document.getElementsByClassName( "goodnews" ).length );
												if( document.getElementsByClassName( "goodnews" ).length == 1 )
												{
													ayoola.spotLight.close();
												//	parent.ayoola.spotLight.close();
												}

											} 
										);
									'
								);
							}
						}
						if( ! $title = $identifier['class_name']::getObjectTitle() )
						{
							$title = $identifier['class_name'];
							$title = str_ireplace( array( 'Ayoola_', 'Application_', 'Article_', 'Object_', 'Classplayer_', ), '', $title );  
							$title = ucwords( implode( ' ', explode( '_', $title ) ) );
							$title = ucwords( implode( ' ', explode( '-', $title ) ) );
						}
						if( strpos( Ayoola_Page::getCurrentPageInfo( 'title' ), $title ) === false )
						{
							$pageInfo = array(
								'title' => trim( $title . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
							);
							Ayoola_Page::setCurrentPageInfo( $pageInfo );
						}
					}
					return true;
				}
			}
		}
		catch( Ayoola_Exception $e )
		{
            if( $this->getParameter( 'play_mode' ) )
            {
                $this->_playMode = $this->getParameter( 'play_mode' );
            }
            elseif( isset( $_SERVER['HTTP_AYOOLA_PLAY_MODE'] ) )
            {
                $this->_playMode = $_SERVER['HTTP_AYOOLA_PLAY_MODE'];
            }
            elseif( isset( $_SERVER['HTTP_PC_WIDGET_OUTPUT_METHOD'] ) )
            {
                $this->_playMode = $_SERVER['HTTP_PC_WIDGET_OUTPUT_METHOD'];
            }
    
			$this->setViewContent( self::__( '<h2 class="badnews">WIDGET ERROR</h2>' ) );
			$this->setViewContent( self::__( '<p class="pc-notify">' . $e->getMessage() . '</p>' ) );
            $this->_objectData['badnews'] = $e->getMessage();
            $this->_objectData['http_code'] = 500;
			return false;
		}
		if( ! $this->getParameter( 'silent_when_object_not_found' ) )
		{
            if( $this->getParameter( 'play_mode' ) )
            {
                $this->_playMode = $this->getParameter( 'play_mode' );
            }
            elseif( isset( $_SERVER['HTTP_AYOOLA_PLAY_MODE'] ) )
            {
                $this->_playMode = $_SERVER['HTTP_AYOOLA_PLAY_MODE'];
            }
            elseif( isset( $_SERVER['HTTP_PC_WIDGET_OUTPUT_METHOD'] ) )
            {
                $this->_playMode = $_SERVER['HTTP_PC_WIDGET_OUTPUT_METHOD'];
            }
    
            $this->_objectData['badnews'] = "No valid widget embedded";
            $this->_objectData['http_code'] = 404;
			$this->setViewContent( self::__( '<p class="badnews">No valid widget embedded.</p>' ) );
		}
		
    }
	// END OF CLASS
}
