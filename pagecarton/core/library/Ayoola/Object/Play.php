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
		try
		{
			{
			//	ALLOW THE USE OF CLASS_NAME
				$identifier = null;

				if( @$_REQUEST['object_name'] )
				{ 
					$identifier = array( 'class_name' => $_REQUEST['object_name'] );
				}
				elseif( @$_REQUEST['name'] )
				{
				//	var_export( $_REQUEST['name'] );
					$identifier = array( 'class_name' => $_REQUEST['name'] );
				}
				elseif( ! empty( $_REQUEST['pc_module_url_values'][0] ) )
				{
					$identifier = array( 'class_name' => $_REQUEST['pc_module_url_values'][0] );
				}
				elseif( $this->getParameter( 'list_all_widgets' ) || @$_REQUEST['list_all_widgets'] )
				{
                    foreach( Ayoola_Object_Embed::getWidgets( false ) as $class ) 
                    {
                        $this->setViewContent( '<div>' . $class . ' - <a href="?object_name=' . $class . '">content</a>  - <a href="?object_name=' . $class . '">code</a> </div>' );
                    }
                    return false;
				}
				elseif( $widgetId = $this->getParameter( 'widget_id' ) ? : @$_REQUEST['widget_id'] )
				{
                //    var_export( Ayoola_Object_PageWidget::getInstance()->select() );
                //    var_export( $widgetId );
                    if( $widget = Ayoola_Object_PageWidget::getInstance()->selectOne( null,  array( 'pagewidget_id' => $widgetId ) ) )
                    {
                    //    var_export( $widget );
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
				//	var_export( $identifier );
						$_SERVER['HTTP_AYOOLA_PLAY_CLASS'] = $identifier['class_name'];
						if( isset( $_SERVER['HTTP_AYOOLA_PLAY_MODE'] ) )
						{
							$playMode = $_SERVER['HTTP_AYOOLA_PLAY_MODE'] ? : $this->_playMode;
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
					//	$classToPlay	$this->setViewContent( $identifier['class_name']::viewInLine(  ), true );
						if( ! $title = $identifier['class_name']::getObjectTitle() )
						{
							$title = $identifier['class_name'];
							$title = str_ireplace( array( 'Ayoola_', 'Application_', 'Article_', 'Object_', 'Classplayer_', ), '', $title );  
							$title = ucwords( implode( ' ', explode( '_', $title ) ) );
							$title = ucwords( implode( ' ', explode( '-', $title ) ) );
							
							//	Delete generic names
						//	$title = ucwords( implode( ' ', explode( 'Ayoola ', $title ) ) );
						//	$title = ucwords( implode( ' ', explode( 'Application ', $title ) ) );
						}
						if( strpos( Ayoola_Page::getCurrentPageInfo( 'title' ), $title ) === false )
						{
							$pageInfo = array(
								'title' => trim( $title . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
							);
							Ayoola_Page::setCurrentPageInfo( $pageInfo );
						}
					}
					else
					{
					}
				//	var_export( self::checkObject( $identifier ) );
				//	var_export( $identifier['class_name']::viewInLine() );
					return true;
				}
			}
		}
		catch( Ayoola_Exception $e )
		{
		//	var_export( $identifier );
	//		var_export( $identifier );
	//		var_export( $this->getParameter() );
	//		var_export( $e->getMessage() );
			$this->setViewContent( self::__( '<h2 class="badnews">WIDGET ERROR</h2>' ) );
			$this->setViewContent( self::__( '<p class="pc-notify">' . $e->getMessage() . '</p>' ) );
	//		throw new Ayoola_Exception( 'OBJECT TO BE PLAYED NOT FOUND' );
 	//		$this->setViewContent(  '' . self::__( '<h4>ERROR:</h4>' ) . '', true  );
			
 	//		header( 'Location: /404/' ); 
	//		exit();
			return false;
		}
		if( ! $this->getParameter( 'silent_when_object_not_found' ) )
		{
			$this->setViewContent( self::__( '<p class="badnews">No valid widget embedded.</p>' ) );
		}
		
    }
	// END OF CLASS
}
