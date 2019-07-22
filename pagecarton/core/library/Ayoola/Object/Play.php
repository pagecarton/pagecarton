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
	//	Ayoola_Application::$mode = 'module';
	//	unset( $parameters['markup_template_no_data'] );  
		//	self::v( __LINE__ );
		//	exit();     
		try
		{
	//		try
			{
		//		if( ! $object = $this->getIdentifierData() )
				{ 
		//			throw new Ayoola_Object_Exception( self::ERROR_MESSAGE_OBJECT_NOT_FOUND ); 
				}
			}
	//		catch( Ayoola_Exception $e )
			{
			//	ALLOW THE USE OF CLASS_NAME
		//			var_export( $object );
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
				else
				{
					return false;
				}
			//	var_export( $_REQUEST['pc_module_url_values'][0] );
			//	$identifier = array( 'class_name' => $identifierKey );
				$this->setIdentifierData( $identifier );
	///			if( ! $object = $this->getIdentifierData() ){ throw new Ayoola_Object_Exception( self::ERROR_MESSAGE_OBJECT_NOT_FOUND ); }
			}
			//	I want to allow a convenient way of playing class
			if( $identifier['class_name'] === __CLASS__ )
			{
				exit( $identifier['class_name'] );
			//	return false;
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
