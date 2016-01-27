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
    public function __construct()
    {
		//	Make the application know we are using class player
		$_SERVER['HTTP_APPLICATION_MODE'] = $this->getObjectName();
		try
		{
			try
			{
				if( ! $object = $this->getIdentifierData() ){ throw new Ayoola_Object_Exception( self::ERROR_MESSAGE_OBJECT_NOT_FOUND ); }
			}
			catch( Ayoola_Exception $e )
			{
			//	ALLOW THE USE OF CLASS_NAME
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
				$this->setIdentifierData( $identifier );
				if( ! $object = $this->getIdentifierData() ){ throw new Ayoola_Object_Exception( self::ERROR_MESSAGE_OBJECT_NOT_FOUND ); }
			}
			self::checkObject( $object );
		//	self::v( $object );
			if( ! isset( $object['view_parameters'] ) ){ $object['view_parameters'] = null; }
			if( ! isset( $object['view_option'] ) ){ $object['view_option'] = null; }
			$this->setViewContent( $object['class_name']::viewInLine( $object['view_parameters'], $object['view_option'] ), true );
		}
		catch( Ayoola_Exception $e )
		{
			//	I want to allow a convenient way of playing class
			if( Ayoola_Loader::loadClass( @$identifier['class_name'] ) )
			{ 
				if( $identifier['class_name']::isPlayable() && is_int( $identifier['class_name']::getAccessLevel() ) )
				{
					$identifier['object_name'] = $identifier['class_name'];
					$identifier['auth_level'] = $identifier['class_name']::getAccessLevel();
					self::checkObject( $identifier );
					$this->setViewContent( $identifier['class_name']::viewInLine(), true );
				//	var_export( $identifier['class_name']::viewInLine() );
				}
				return true;
			}
	//		var_export( $identifier );
	//		var_export( $e->getMessage() );
			throw new Ayoola_Exception( 'OBJECT TO BE PLAYED NOT FOUND' );
 			$this->setViewContent( '<h4>ERROR:</h4>', true );
			$this->setViewContent( '<p>There is an error on the page. An administrator has been notified about this error.</p>' );
 	//		header( 'Location: /404/' ); 
	//		exit();
		}
		
    }
	// END OF CLASS
}
