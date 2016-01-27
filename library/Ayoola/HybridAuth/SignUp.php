<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_HybridAuth_SignUp
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SignUp.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_HybridAuth_SignUp
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_HybridAuth_SignUp extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;

    /**
     * "Plays" the class
     *
     * @param 
     * 
     */
    public function init()
    {
		try
		{
			$hybridAuth = new Ayoola_HybridAuth();
/* 			var_export( $hybridAuth );
			var_export( $_GET );
			var_export( $_SERVER );
				exit( 'done' );
 */			@$provider = $_GET['provider'] ? $_GET['provider'] : $_GET['hauth.done'];
/*  			$provider = strip_tags( $provider );
			
			var_export( $provider );
				exit( 'done' );
			if( ! empty( $provider ) )
			{
				if( ! empty( $provider ) && $hybridAuth->isConnectedWith( $provider ) )
				{
					$adapter = $hybridAuth->getAdapter( $provider );
					$userData = $adapter->getUserProfile();
					
					//	Register a new social media
					$table = new Application_SocialMedia();
					$values = array();
					$values['socialmedia_id'] = self::getPrimaryId( $table, array( 'socialmedia_name' => $adapter->providerId ) );
					$values['socialmediauser_info'] = $adapter->getUserProfile();
					$values['socialmediauser_foreign_id'] = $adapter->identifier;
					
					//	Cloud Signin with
					$values['email'] = $userData->email;
					$values['socialmediauser_foreign_id'] = $values['socialmediauser_foreign_id'];
					$values['auth_mechanism'] = 'EmailSocialMediaUserForeignId';
					
					if( self::apiLogin( $values ) )
					{
						//	we are logged in
						return true;
					}
 */	/* 				
					//	See if user has been previously registered.
					$table = new Application_User();
					if( $socialmediauserInfo = $table->selectOne( null, 'socialmediauser, ', array( 'socialmedia_id' => $values['socialmedia_id'], 'socialmediauser_foreign_id' => $values['socialmediauser_foreign_id'] ) ) )
					{
						
					}
	 *//* 				else
					{

						//	Signup the user into the application
						$class = new Application_User_Creator();
						$captcha = new Ayoola_Captcha();
						$fakeValues = array( 'email' => $userData->email, 'password' => $captcha->getCode(), 'firstname' => $userData->firstName, 'lastname' => $userData->lastName, 'sex' => $userData->gender, 'birth_date' => $userData->birthYear . '-' . $userData->birthMonth . '-' . $userData->birthDay, );
						$class->fakeValues = $fakeValues;
						$class->init();
					//	$class->view();
					//	var_export( $class->view() );
						$badnews = array();
						if( ! $class->getForm()->getValues() || $class->getForm()->getBadnews() )
						{
							$badnews = $class->getForm()->getBadnews();
						}
						$values['user_id'] = $class->insertId;
						
						//	Record the socialmedia information.
						$table = new Application_SocialMedia_User();
						$table->insert( $values );
						
						//	Import UserLocation and Phonenumber
						//	Country
						$table = new Application_Country();
						$values['country_id'] = self::getPrimaryId( $table, array( 'country' => $adapter->country ) );
						
						//	Phone Number
						$table = new Application_PhoneNumber();
						$values['phonenumber_id'] = self::getPrimaryId( $table, array( 'phonenumber' => $adapter->phone, 'country_id' => $values['country_id'] ) );
						$table = new Application_User_UserPhoneNumber();
						$values['phonenumber_id'] = self::getPrimaryId( $table, array( 'phonenumber_id' => $values['phonenumber_id'], 'user_id' => $values['user_id'] ), array( 'phonenumber_id' => $values['phonenumber_id'] ) );
						
						//	Address
						$table = new Application_Province();
						$values['province_id'] = self::getPrimaryId( $table, array( 'province' => $adapter->region ) );
						$table = new Application_City();
						$values['city_id'] = self::getPrimaryId( $table, array( 'city' => $adapter->city, 'province_id' => $values['province_id'] ) );
						$table = new Application_UserLocation();
						$values =  $values + array( 'street_address' => $userData->address, 'zip' => $userData->zip );
						$table->insert( $values );
						
					}
					
				}
						
			}
		}
		catch( Exception $e )
		{
			$this->setViewContent( '<p>We encountered an error.</p>', true );
		}
    */ }
	
	
	// END OF CLASS
}
