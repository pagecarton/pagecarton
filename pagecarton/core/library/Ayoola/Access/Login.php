<?php


/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_Login
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Login.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Abstract
 */

require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_Login
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Login extends Ayoola_Access_Abstract
{

    /**
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;

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
     * Url to return to after a successful login
     *
     * @var string
     */
	public static $returnUrl;

    /**
     * Whether to login after authentication
     *
     * @var boolean
     */
	public static $loginOnAuthentication = true;

    /**
     * Whether to show remember me option
     *
     * @var string
     */
	public static $showRememberMe = true;

    /**
     * This method performs the class' essense.
     *
     * @param void
     * @return boolean
     */
    public function init()
    {
	//	var_export( __LINE__ );
		require_once 'Ayoola/Access.php';
		require_once 'Ayoola/Page.php';
		$accountPage = '/account';
		if( $defaultAccountPage = Application_Settings_Abstract::getSettings( 'UserAccount', 'default_account_page' ) )
		{
			if( Ayoola_Page::getInfo( $defaultAccountPage ) )
			{
				$accountPage = $defaultAccountPage;
			}

		}
	//	var_export( $accountPage );
		if( ! $this->getParameter( 'no_redirect' )  )
		{
			$urlToGo = '/account';
			$urlToGo = Application_User_Settings::retrieve( 'default_account_page' ) ? : $urlToGo;
			$urlToGo = self::$returnUrl ? : $urlToGo;
			$urlToGo = $this->getParameter( 'return_url' ) ? : $urlToGo;
			$urlToGo = Ayoola_Application::getUrlPrefix() . $urlToGo;
			$urlToGo = Ayoola_Page::getPreviousUrl( $urlToGo );
		//	var_export( $urlToGo );
			Application_Javascript::header( $urlToGo );
		}

//		var_export( $urlToGo );
		$logResult = array( 'medium' => 'cookie', 'result' => 'fail', 'message' => null );

		//	Check if there is a logged in user
		$auth = new Ayoola_Access();
		if( $auth->isLoggedIn() )
		{
			if( Ayoola_Page::getPreviousUrl() )
			{
				header( 'Location: ' . Ayoola_Page::getPreviousUrl() );
				exit();
			}
		//	$this->setViewContent( Ayoola_Access_Bar::viewInLine(), true );
		//	return;
		}
		if( Ayoola_Page::getPreviousUrl() && ! empty( $_REQUEST['pc_coded_login_message'] ) )
		{
			$this->setViewContent( self::__( '<p class="badnews boxednews">' . $this->getObjectStorage( 'pc_coded_login_message' )->retrieve() . '</p>' ) );
		}
		$this->setViewContent( $this->getForm()->view() );

		//	Try to login with the form
		if( ! $values = $this->getForm()->getValues() )
		{
			return false;
		}
	//	var_export( $values );
	//	exit();

		//	Check if user sent a username or an email
		$validator = new Ayoola_Validator_EmailAddress();
		$validUserInfo = array();
		$values['username'] = trim( strtolower( $values['username'] ) );
		if( $validator->validate( $values['username'] ) )
		{
			$values['email'] = $values['username'];
			$validUserInfo['email'] = $values['email'];
			$validUserInfo['password'] = $values['password'];
			$validUserInfo['auth_mechanism'] = 'EmailPassword';
			unset( $values['username'] );
		}
		else
		{
			$validator = new Ayoola_Validator_Username();
			if( ! $validator->validate( $values['username'] ) )
			{
				return false;
			}
			$validUserInfo['username'] = $values['username'];
			$validUserInfo['password'] = $values['password'];
		}
		$userInfo = array();
		do
		{

			// First local Login
			if( self::localLogin( $validUserInfo ) )
			{
			//	var_export( false );
				break;
			}

			// then Cloud Login
		//		var_export( true );

			if( self::apiLogin( $validUserInfo ) )
			{
	//			var_export( $validUserInfo );
				break;
			}



			//	Use the DbTable
			//	$auth->authenticate( $values );
		//	if( $userInfo = $auth->getUserInfo() )
			{
		//		break;
			}
		}
		while( false );
	//	var_export( $settings );
		if( $userInfo = $auth->getStorage()->retrieve() )
		{
			//	Log success
			$logResult['result'] = 'success';
			$logResult['medium'] = 'form';
//			$values['password'] = sha1( $cookie . '0-1-2-3' );
			$values['user_id'] = $userInfo['user_id'] || $_SERVER['USERNAME'];
			$this->log( array_merge( $values, $logResult ) );

			//	var_export( $cookie );
			//	var_export( $values );

			if( @$values['remember'] )
			{
				// Make Session last for two weeks
				//	Use hashed password to set cookie
				$hashedCredentials = $auth->hashCredentials( $values );
			//	var_export( $hashedCredentials );
				$userInfo['password'] = $hashedCredentials['password'];
			//	var_export();

				$cookie = self::getPersistentCookieValue( $userInfo['email'], $userInfo['password'] );
				$expire = time() + 1728000; // Expire in 20 days
				@setcookie( $this->getObjectName(), $cookie, $expire, '/', null, false, true );
			}

			if( ! Ayoola_Application::isXmlHttpRequest() && ! $this->getParameter( 'no_redirect' ) )
			{
				@header( 'Location: ' . $urlToGo );
			//	var_export( __LINE__ );
				exit();
			}
			// Do ajax
			if( $urlToGo )
			{
				$this->setViewContent(  '' . self::__( 'Login Successful. You are being redirected to the previous page... <a href="' . $urlToGo . '">Click here if you are not redirected to the page in 5 seconds.</a>' ) . '', true  );
				Application_Javascript::addCode( 'document.location = "' . $urlToGo . '";' );
			}
			return true;
		}

		//	LOG FAILURE
		$logResult['medium'] = 'form';
	//	var_export( $values );
		$values['user_id'] = ! empty( $values['username'] ) ? $values['username'] : $values['email'];
//		var_export( $values['user_id'] );
		$this->log( array_merge( $values, $logResult ) );

		$this->getForm()->setBadnews( 'Invalid Login Information' );
		$this->setViewContent( $this->getForm()->view(), true );
		return false;
    }

    /**
     * Log the process
     *
     */
    public static function log( $info )
    {
		$result = Application_Log_View_SignIn::log( $info );
    }

    /**
     * Store userInfo into storage
     *
     */
    public static function login( array $userInfo )
    {
		if( $settings = Application_Settings_Abstract::getSettings( 'UserAccount', 'signin-requirement' ) )
		{
			foreach( $settings as $each )
			{
	//	var_export( $userInfo[$each] );
				if( empty( $userInfo[$each] ) && ! in_array( $userInfo['access_level'], array( 99, 98 ) )  )
				{
					return false;
				}
			}
		}
		$userInfo['access_level'] = $userInfo['access_level'] ? : 1;
	//	var_export( $userInfo );
	//	self::v( $userInfo );
	//	exit();

		//	Add access info
		$userInfo += self::getAccessInformation( $userInfo['username'], array( 'skip_user_check' => true ) ) ? : array();
	//	var_export( $userInfo );
		if( self::$loginOnAuthentication )
		{
			$auth = new Ayoola_Access();
			$auth->getStorage()->store( $userInfo );
		}
		return $userInfo;
	}

    /**
     * Login to the local db
     *
     */
    public static function localLogin( array & $values )
    {
		// Find user in the LocalUser table
		$table = Ayoola_Access_LocalUser::getInstance();

		//	Retrieve the password hash
		$access = new Ayoola_Access();
		$hashedCredentials = $access->hashCredentials( $values );
	//	$table->drop();
	//	self::v( $table->select() );
	//	var_export( $hashedCredentials );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
		if( $info = $table->selectOne( null, array_map( 'strtolower', $hashedCredentials ) ) )
		{
		//	var_export( $info );
		//	return false;
			if( $info['user_information'] )
			{
			//	var_export( $info );
				return self::login( $info['user_information'] );
			}
		}
	//	var_export( $hashedCredentials );
		return false;
	}

    /**
     * Login to the ayoola cloud api
     *
     */
    public static function apiLogin( array & $values )
    {
		//	first try cloud sign in
	//	$data['options'] = array( 'request_type' => 'SignIn', 'domain_name' => Ayoola_Page::getDefaultDomain() );
		$data['data'] = $values;
		$userInfo = array();
		$data = Ayoola_Api_SignIn::send( $data );
//		var_export( $data );
		if( is_array( $data['data'] ) )
		{
	//	var_export( $data );
			$data = $data['data'];

			if( isset( $data['username'] ) )
			{
		//	var_export( $data );
				if( empty( $data['applicationusersettings_id'] ) )
				{
					unset( $data['enabled'], $data['verified'], $data['approved'] );

					if( $data = Ayoola_Api_UserEditor::send( $data ) )
					{
						if( isset( $data['data'] ) )
						{
							// Look for the user information once again
							$data = Ayoola_Api_SignIn::send( $values );
					//		var_export( $data );
							$data = $data['data'];

							//	Register the user in the storage.\
							$userInfo = $data;
						}
					}
				}
				else
				{
					//	Register the user in the storage.\
					$userInfo = $data;
				}
			}
		}
		if( $userInfo )
		{
			//	Localize information
			try
			{
/* 				$table = new Ayoola_Access_LocalUser();
				$table->delete( array( 'username' => $userInfo['username'] ) );
 */
				//	Retrieve the password hash
				$access = new Ayoola_Access();
				$hashedCredentials = $access->hashCredentials( $values );
				$newInfo = $userInfo;
				$newInfo['password'] = $hashedCredentials['password'];
			//	var_export( $newInfo );
				Ayoola_Access_Localize::info( $newInfo );

			}
			catch( Exception $e ){ null; }


			return self::login( $userInfo );
		}
		return false;
    }

    /**
     * Creates the form
     *
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		require_once 'Ayoola/Form.php';
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'class' => 'smallFormElements', 'data-not-playable' => 'true' ) );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'class' => '' ) );
		$form->submitValue = 'Login' ;
		$fieldset = new Ayoola_Form_Element();
		$fieldset->id = __CLASS__;
		$fieldset->placeholderInPlaceOfLabel = true;
//		$fieldset->hashElementName = false;
		$fieldset->useDivTagForElement = false;
		$fieldset->addElement( array( 'name' => 'username', 'placeholder' => 'E-mail Address', 'type' => 'InputText', 'style' => '' ) );
		$fieldset->addElement( array( 'name' => 'password', 'placeholder' => 'Password', 'type' => 'InputPassword', 'style' => '' ) );
	//	$fieldset->addElement( 'name=>username::placeholder =>E-mail or Username:: type=>InputText' );
	//	$fieldset->addElement( 'name=>password::placeholder =>Password::type=>InputPassword' );
		$fieldset->addRequirements( 'NotEmpty' );
		if( static::$showRememberMe )
		{
			$rememberMe = array( 1 => 'Remember me' );
			$fieldset->addElement( array( 'name' => 'remember', 'label' => '', 'type' => 'Checkbox', 'value' => @$values['remember'] ? : array( 1 ) ), $rememberMe );
		}
		//	also allow fake values
		if( $this->getGlobalValue( 'username' ) && ! $this->getParameter( 'ignore_user_check' ) )
		{
			$fieldset->addRequirement( 'username', array( 'AccountAccessLevel' => array( 'username' => $this->getGlobalValue( 'username' ), 'password' => $this->getGlobalValue( 'password' ) ) ) );
		}
	//	$fieldset->addElement( array( 'name' => 'Login Now', 'value' => 'Login', 'type' => 'Submit' ) );

		//$fieldset->addRequirement( 'password', 'WordCount=>8;;16' );
		$fieldset->addFilters( 'Trim' );
//		$fieldset->addFilters( 'Trim::Escape' );
	//	$fieldset->addFilter( 'username','Username' );
	//	$fieldset->addLegend( '' );
		$form->addFieldset( $fieldset );

		$this->setForm( $form );
    }
	// END OF CLASS
}
