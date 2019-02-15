<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Help_ChangePassword
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ForgotUsernameOrPassword.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Help_Abstract
 */
 
require_once 'Application/User/Help/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Help_ChangePassword
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Help_ChangePassword extends Application_User_Help_Abstract
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
	protected static $_accessLevel = array( 1, 98 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->createForm( 'Change Password' );
			$this->setViewContent( '<h4>Change Password</h4>' );   
		//	$this->setViewContent( '' );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			$identifier = array( 'email' => Ayoola_Application::getUserInfo( 'email' ) );
			
			
			//	First seek in the local flatfile
			$table = Ayoola_Access_LocalUser::getInstance();
			if( $wholeInfo = $table->selectOne( null, $identifier ) )
			{
				if( $wholeInfo['user_information'] )  
				{
					$info = $wholeInfo['user_information'];
				}
			}
			require_once 'Ayoola/Filter/Hash.php';
			$filter = new Ayoola_Filter_Hash( 'sha512' );
			$values['old_password'] = $filter->filter( $values['old_password'] );
			if( strtolower( $values['old_password'] ) !== strtolower( $wholeInfo['password'] ) )
			{ 
		//		$this->getForm()->setBadnews( 'Invalid Information - ' . $each . ' - ' . $info[$each] . ' - ' . $values[$each] );
				$this->getForm()->setBadnews( 'Current Password you entered is Invalid' );
				$this->setViewContent( $this->getForm()->view(), true );
				return false;
			}
			if( self::changePassword( $identifier, $info, $values['password'] ) )
			{
				$this->setViewContent( 'New password saved successfully.', true );
				return true;
			}
			$this->getForm()->setBadnews( 'Invalid Information. Please try again.' );
			$this->setViewContent( $this->getForm()->view(), true );   
			
			//	Log Failure
	//		$values['result'] = 'failed';
	//		Application_Log_View_ForgetUsernameOrPassword::log( $values );
		}
		catch( Application_User_Help_Exception $e ){ return false; }
    }   
	
    /**
     * Changes password for a user
     * 
     * param array identifier for the user
     */
	public static function changePassword( array $identifier, array $info = null, $newPassword = null )
    {
		//	First seek in the local flatfile
		if( ! $info )
		{
			$table = Ayoola_Access_LocalUser::getInstance();
			if( $wholeInfo = $table->selectOne( null, $identifier ) )
			{
				if( $wholeInfo['user_information'] )  
				{
					$info = $wholeInfo['user_information'];
				}
			}
		}
		$auto = false;
		if( ! $newPassword )
		{
			//	generate new password
			#	http://stackoverflow.com/questions/6101956/generating-a-random-password-in-php
			$newPassword = substr(str_shuffle('abdeghijkmnpqrstuvwxyzABDEFGHJKMNPQRSTUVWXYZ123456789@:^$#+') , 0 , 10 );
			$auto = true;
			$informationToSend = array( 'password' =>  $newPassword );
		}
		else
		{
			$informationToSend = array();
		}
		$informationToUpdate = array( 'password' =>  $newPassword );;
		$filter = new Ayoola_Filter_Hash( 'sha512' );
		$informationToUpdate['password'] = $filter->filter( $informationToUpdate['password'] );
		
		do
		{
			//	Change in the flat-file
			try
			{
				if( $info )
				{
					$mailInfo = array( 'to' => $info['email'] );
					$mailInfo['body'] = 'PASSWORD CHANGED SUCCESSFULLY.' . "\r\n" . "\r\n";
					$mailInfo['subject'] = 'Account Information Update';
					
					if( $informationToSend )
					{
						foreach( $informationToSend as $key => $value )
						{
							$mailInfo['body'] .= ucfirst( $key ) . ': ' . $value . "\r\n";
						}
						$mailInfo['body'] .= 'Since your password was reset by the system, please change your password immediately to something memorable and secure.';
					}
					

					self::sendMail( $mailInfo );
					$info = array_merge( $info, $informationToUpdate );
				//		var_export( $info );  
					if( Ayoola_Access_Localize::info( $info ) )
					{
					//	$this->setViewContent( 'New password saved successfully.', true );
					//	$this->setViewContent( '<div class="boxednews goodnews">User account edited successfully</div>', true );
					}
				}
			}
			catch( Exception $e )
			{
			//	var_export( $e->getMessage() );
			//	var_export( $e->getTraceAsString() );
			}
			
			return true;
		}
		while( false );
	}
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		require_once 'Ayoola/Form.php';
//		$form = new Ayoola_Form( 'name=>' . $this->getObjectName() );
	//	if( @$_REQUEST['previous_url'] )
		{
			$form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => $this->getObjectName() ) );
		}
		
		$form->submitValue = 'Save...' ;
	//	$form->oneFieldSetAtATime = true;
		$form->formNamespace = get_class( $this ) . $values['user_id'];
		$account = new Ayoola_Form_Element;
		$account->id = __CLASS__ . 'account';
	//	$account->placeholderInPlaceOfLabel = true;
		$account->addElement( array( 'name' => 'old_password', 'label' => 'Enter Current Password', 'placeholder' => 'Current Password', 'type' => 'InputPassword' ) );
		$account->addElement( array( 'name' => 'password', 'label' => 'Choose New Password', 'placeholder' => 'Choose New password', 'type' => 'InputPassword' ) );
		$account->addElement( array( 'name' => 'password2', 'label' => 'Confirm New Password', 'placeholder' => 'Confirm New password', 'type' => 'InputPassword' ) );
		$account->addRequirement( 'old_password','WordCount=>6;;18' ); 
		$account->addRequirement( 'password','WordCount=>6;;18' ); 
		$account->addRequirement( 'password2', array( 'DefiniteValueSilent' => $this->getGlobalValue( 'password' ) ) ); 
		
		$form->addFieldset( $account );

		return $this->setForm( $form );
    } 
	// END OF CLASS
}
