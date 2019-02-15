<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Help_ResetPassword
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ResetPassword.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Help_Abstract
 */
 
require_once 'Application/User/Help/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Help_ResetPassword
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Help_ResetPassword extends Application_User_Help_ChangePassword
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! self::hasPriviledge( array( 99, 98 ) ) )
			{ 
				return false;
			}
			$this->createForm( 'Reset Password' );
			$this->setViewContent( '<h4>Reset Password</h4>' );   
			$this->setViewContent( $this->getForm()->view() );
			
			
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			$validator = new Ayoola_Validator_EmailAddress();
			
			if( $validator->validate( $values['email'] ) )
			{
				$identifier = array( 'email' => $values['email'] );
			}
			else
			{
				$identifier = array( 'username' => $values['email'] );
			}
			
			//	First seek in the local flatfile
			$table = Ayoola_Access_LocalUser::getInstance();
			if( $wholeInfo = $table->selectOne( null, $identifier ) )
			{
				if( $wholeInfo['user_information'] )  
				{
					$info = $wholeInfo['user_information'];
				}
			}
			else
			{
				$this->getForm()->setBadnews( 'Invalid Email Address or Username' );
				$this->setViewContent( $this->getForm()->view(), true );
				return false;
			}
			
			
			if( self::changePassword( $identifier, $info, $values['password'] ) )
			{
				$this->setViewContent( '<p class="goodnews boxednews">New password saved successfully.</p>', true );
				$this->setViewContent( $this->getForm()->view() );
				return true;
			}
			$this->getForm()->setBadnews( 'Invalid Information. Please try again.' );
			$this->setViewContent( $this->getForm()->view(), true );   
		}
		catch( Application_User_Help_Exception $e ){ return false; }
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
		if( ! empty( $_REQUEST['username'] ) )
		{
			$account->addElement( array( 'name' => 'email', 'type' => 'InputText', 'value' => strtolower( $_REQUEST['username'] ) ) );	
		}
		else
		{
			$account->addElement( array( 'name' => 'email', 'label' => 'E-mail Address or Username', 'type' => 'InputText' ) );		
		}
		$account->addElement( array( 'name' => 'password', 'label' => 'Enter New Password', 'placeholder' => 'Choose New password', 'type' => 'InputPassword' ) );
		$account->addElement( array( 'name' => 'password2', 'label' => 'Confirm New Password', 'placeholder' => 'Confirm New password', 'type' => 'InputPassword' ) );
		$account->addRequirement( 'email','WordCount=>6;;50' ); 
//		$account->addRequirement( 'password','WordCount=>6;;18' ); 
		$account->addRequirement( 'password2', array( 'DefiniteValueSilent' => $this->getGlobalValue( 'password' ) ) ); 
		
		$form->addFieldset( $account );

		return $this->setForm( $form );
    } 
	// END OF CLASS
}
