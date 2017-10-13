<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
			try
			{
				if( ! $object = $this->getIdentifierData() ){ throw new Ayoola_Object_Exception( self::ERROR_MESSAGE_OBJECT_NOT_FOUND ); }
			}
			catch( Ayoola_Exception $e )
			{
			//	ALLOW THE USE OF CLASS_NAME
				$identifier = null;
				if( @$_REQUEST['object_name'] )
				{ 
					$identifier = $this->getIdentifier();
					$identifierKey = $identifier[$this->_identifierKeys[0]];
				//	var_export( $identifierKey );
					$identifier = array( 'class_name' => $identifierKey );
				}
				elseif( @$_REQUEST['name'] )
				{
				//	var_export( $_REQUEST['name'] );
					$identifier = array( 'class_name' => $_REQUEST['name'] );
					$this->setIdentifier( $identifier );
				}
		//			var_export(  $object );
				$this->setIdentifierData( $identifier );
				if( ! $object = $this->getIdentifierData() ){ throw new Ayoola_Object_Exception( self::ERROR_MESSAGE_OBJECT_NOT_FOUND ); }
			}
			if( ! isset( $object['view_parameters'] ) ){ $object['view_parameters'] = null; }
			if( ! isset( $object['view_option'] ) ){ $object['view_option'] = null; }
			if( ! self::checkObject( $object ) )
			{
				$this->setViewContent( '<p class="badnews">You currently do not have access to this content.</p>', true );
			}
			if( ! $title = $object['class_name']::getObjectTitle() )
			{
				$title = $object['class_name'];
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
			$this->setViewContent( $object['class_name']::viewInLine( $object['view_parameters'], $object['view_option'] ), true );
		}
		catch( Ayoola_Exception $e )
		{
		//	var_export( $identifier );
			//	I want to allow a convenient way of playing class
			if( Ayoola_Loader::loadClass( @$identifier['class_name'] ) )
			{ 
				if( $identifier['class_name']::isPlayable() )
				{
					$identifier['object_name'] = $identifier['class_name'];
					$identifier['auth_level'] = $identifier['class_name']::getAccessLevel();
					if( self::checkObject( $identifier ) )
					{
				//	var_export( $identifier );
						$this->setViewContent( $identifier['class_name']::viewInLine(), true );
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
						$this->setViewContent( '<p class="badnews">You currently do not have access to this content.</p>', true );
					}
				//	var_export( self::checkObject( $identifier ) );
				//	var_export( $identifier['class_name']::viewInLine() );
				}
				return true;
			}
	//		var_export( $identifier );
	//		var_export( $this->getParameter() );
	//		var_export( $e->getMessage() );
	//		throw new Ayoola_Exception( 'OBJECT TO BE PLAYED NOT FOUND' );
 	//		$this->setViewContent( '<h4>ERROR:</h4>', true );
	 		if( ! $this->getParameter( 'silent_when_object_not_found' ) )
			{
				$this->setViewContent( '<p class="badnews">INVALID WIDGET MODULE EMBEDDED</p>' );
			}
			
 	//		header( 'Location: /404/' ); 
	//		exit();
		}
		
    }
	// END OF CLASS
}
