<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_Email_Abstract
 */
 
require_once 'Application/User/Email/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Email_Editor extends Application_User_Email_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	var_export( __LINE__ );
		try
		{
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Edit', 'Edit ' . $data['email'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( $values['password'] != $values['password2'] )
			{ 
				$this->getForm()->setBadnews( 'Password does not match' );
				$this->setViewContent( $this->getForm()->view(), true );
				return false;
			}
			elseif( $values['password'] )
			{
				//	password update not needed
				$values['password'] = '{SHA}' . sha1( $values['password'] );
			}
			if( ! $provider = Application_Settings_Abstract::getSettings( 'E-mail', 'provider' ) )
			{
				$provider = 'ayoola';
			}
		//	var_export( $values );
			
		//	remove this as it messes up the db update process
			unset( $values['editing_options'] );
			switch( $provider )
			{
				case 'ayoola':
					$values['old_user_id'] = $this->getIdentifierUserId();
					$values = array_merge( $data, $values );
					$response = Application_User_Email_Api_Editor::send( $values );

					//	var_export( $response );
					if( ! empty( $response['data'] ) )
					{
						$this->setViewContent(  '' . self::__( 'Email Account Edited Successfully' ) . '', true  ); 
					}
				break;
				case 'self':
					if( $this->updateDb( $values ) ){ $this->setViewContent(  '' . self::__( 'Email Account Edited Successfully' ) . '', true  ); }
				break;
			
			}
		}
		catch( Exception $e ){ return false; }
    } 
	// END OF CLASS
}
