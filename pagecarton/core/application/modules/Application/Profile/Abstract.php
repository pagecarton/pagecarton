<?php
/**
 * PageCarton 
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Profile_Exception 
 */
 
require_once 'Application/Profile/Exception.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Profile_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * 
     *
     * @var string
     */
	protected static $_profileTable;
	
    /**
     * 
     *
     * @var boolean
     */
	protected static $_subdomain;
	
    /**
     * 
     * @var string 
     */
	protected static $_submitButton = 'Continue'; 
	
    /**
     * 
     * @var string 
     */
	protected static $_urlName = 'Handle'; 
	
    /**
     * 
     *
     * @var string
     */
	protected $_idColumn = 'profile_url';
	
    /**
     * 
     *
     * @var string
     */
	protected $_tableClass = 'Application_Profile_Table';
	
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
	public static $_myProfiles;
	
    /**
     * returns the profile folder
     * 
     */
	public static function getFolder()
    {
		return Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES .  DS . 'profiles';
	}
	
    /**
     * 
     * 
     */
	public static function getProfileDir( $url )
    {
		return self::getFolder() . DS . strtolower( implode( DS, str_split( $url, 2 ) ) );
	}
	
    /**
     * 
     * 
     */
	public static function getProfileFilesDir( $url )
    {
		return PC_BASE . DS . 'sites' . DS . 'default' . DS . AYOOLA_MODULE_FILES .  DS . 'profiles' . DS . strtolower( implode( DS, str_split( $url, 2 ) ) );
	}
	
    /**
     * 
     * 
     */
	public static function getProfilePath( $url )
    {
		return self::getProfileDir( $url ) . '.profile';  
	}
		
    /**
     * The method does the whole Class Process
     * 
     */
	public static function getMyProfiles()
    {   
		if( ! is_null( self::$_myProfiles ) )
		{
			return self::$_myProfiles;
		}
		$access = new Ayoola_Access();
		$userInfo = $access->getUserInfo();
	//	var_export( $userInfo );
	//	@$userInfo['profiles'] = is_array( $userInfo['profiles'] ) ? $userInfo['profiles'] : array();
	//	var_export( $userInfo );
//		if( ! $userInfo['profiles'] )
		{
			$table = Application_Profile_Table::getInstance();
			$profiles = $table->select( null, array( 'username' => $userInfo['username'] ) );
	//		self::v( $table->select() );
			foreach( $profiles as $profileInfo )
			{
				self::$_myProfiles[] = $profileInfo['profile_url'];
			}
		}
/*		else
		{
			foreach( $userInfo['profiles'] as $url )
			{
				$values = self::getProfileInfo( $url );
	//		var_export( $url );
	//		var_export( $values );
				if( ! $values )
				{
					continue;
				}
				self::$_myProfiles[] = $url;
			} 
		}
*/
		
	//	var_export( $profiles );

		return self::$_myProfiles;
	}
	
    /**
     * 
     * 
     */
	public static function getMyDefaultProfile()
    {
		$profile = Ayoola_Application::getUserInfo( 'profile_url' );
	//	var_export( $profile );
	//	var_export( Ayoola_Application::getUserInfo() );
		if( ! $profileInfo = self::getProfileInfo( $profile, true ) )
		{
		 	if( $others = self::getMyProfiles() )
			{
				while( $profile = array_pop( $others ) )
				{
					if( $profileInfo = self::getProfileInfo( $profile, true ) )
					{
						break;
					}
				}
			}
		}
		return $profileInfo;  
	}
	
    /**
     * Save the profile
     * 
     */
	public static function saveProfile( $values )
    {
	//	var_export( $values );
		if( empty( $values['profile_url'] ) ) 
		{
			return false;
		}
		if( empty( $values['modified_time'] ) )
		{
			$values['modified_time'] = array();
			$values['modified_ip'] = array();  
		}
		if( empty( $values['access_level'] ) )
		{
			$values['access_level'] = 1;
		}
		$values['modified_time'][] = time();
		$values['modified_ip'][] = $_SERVER['REMOTE_ADDR'];
	//	var_export( self::getProfileInfo( $values['profile_url'] ) );
		$values['profile_data'] = $values;
		$values['profile_url'] = strtolower( $values['profile_url'] );
		if( self::getProfileInfo( $values['profile_url'] ) )
		{
			return Application_Profile_Table::getInstance()->update( $values, array( 'profile_url' => strtolower( $values['profile_url'] ) ) );
		}
		else
		{
			return Application_Profile_Table::getInstance()->insert( $values );
		}
	}

    /**
     * 
     * 
     */
	public static function getProfileTable()
    {
		if( ! self::$_profileTable )
		{
			self::$_profileTable = Application_Profile_Table::getInstance();
		}
		return self::$_profileTable;
	}

    /**
     * 
     * 
     */
	public static function isSubDomain()
    {
		return @$_GET['subdomain'] || static::$_subdomain;
	}

    /**
     * 
     * 
     */
	public static function getProfileInfo( $profileUrL, $private = false )
    {
		$profileUrL = strtolower( $profileUrL );
		$table = "Application_Profile_Table";
		if( $private )
		{
			$table = $table::getInstance( $table::SCOPE_PRIVATE );
			$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
			$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
		}
		else
		{
			$table = $table::getInstance();
		}
		if( $profileData = $table->selectOne( null, array( 'profile_url' => $profileUrL ), array( 'x' => 'work-around-to-avoid-stupid-cache' ) ) )
		{
			//	main table data should be there because 
			//	values like display_name is absent in inner data
			$profileData = $profileData['profile_data'] + $profileData;
		}
//			var_export( Application_Profile_Table::getInstance()->selectOne() );
	//	self::v( $profileData );
		$table = Ayoola_Access_AuthLevel::getInstance();
		if( @$profileData['access_level'] != 1 && @$profileData['access_level'] != 0 )
		{
			$authInfo = $table->selectOne( null, array( 'auth_level' => $profileData['access_level'] ) );
	//		var_export( $authInfo );
	//		var_export( $profileData );
			$profileData +=  is_array( $authInfo ) ? $authInfo : array();
		}
		switch( @$profileData['access_level'] )
		{
			case 1:
	//			$profileData['auth_name'] = 'Standard';
			break;
		}
		//	var_export( array( 'profile_url' => $profileUrL ) );
		//	var_export( Application_Profile_Table::getInstance()->select() );
		
		//	getting from php file makes a request call an abitrary php file
	//	self::v( $_SERVER );
/*		
		if( ! $profileData )
		{
			$filename = self::getProfilePath( $url );
			$profileData = @include $profileUrL;
		}
*/		
		if( @$profileData['profile_url'] )
		{
			if( ! @$profileData['display_picture'] )
			{
			//	$profileData['display_picture'] = '/img/placeholder-image.jpg';
			}
			if( ! @$profileData['display_name'] )
			{
			//	$profileData['display_name'] = $profileData['profile_url'];
			}
			if( ! @$profileData['profile_description'] )
			{
			//	$profileData['profile_description'] = $profileData['profile_url'];
			}
		}
		return $profileData;
	}

    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData( $identifier = NULL )
    {
		// Comes from a file
		if( ! $data = $this->getParameter( 'data' ) )
		{
			try
			{
				$profileUrl = $this->getIdentifier();
			}
			catch( Exception $e )
			{

			}
		//	var_export( $profileUrl );
			$url = $profileUrl[$this->getIdColumn()] ? : ( @$_GET['profile_url'] ? : ( Ayoola_Application::$GLOBAL['profile']['profile_url'] ? : Ayoola_Application::$GLOBAL['post']['profile_url'] ) );  
		//	var_export( $url );
			$data = self::getProfileInfo( $url );
		//	var_export( $data );
		}
		//	var_export( $filename );
		if( ! $data  
			|| ( ! @$data['publish'] && ! self::isOwner( @$data['user_id'] ) )   
			|| ! self::hasPriviledge( $data['auth_level'] )
		)
		{
	//		return array();
		}
		$this->_identifierData = $data;
    } 
	  
	//	This is to implement the abstract method of the parent class. Not all inheriting classes needs a form
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'id' => $this->getObjectName() . @$_REQUEST['access_level'] . @$values['profile_url'], 'data-not-playable' => true ) );      
      //  $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = false;
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;
		if( ! static::isSubDomain() )
		{
		//	$fieldset->addRequirement( 'display_picture', array( 'NotEmpty' => array( 'badnews' => 'Please select a valid file to upload...', ) ) );
			
			//	Profile type
			$authLevel = new Ayoola_Access_AuthLevel;
			$authLevel = $authLevel->select();
			$options = array();
			foreach( $authLevel as $each )
			{
				if( is_array( $each['auth_options'] ) && in_array( 'allow_signup', $each['auth_options'] ) )
				{
					$options[$each['auth_level']] =  "{$each['auth_name']}";
				}
			}
			
			if( ! @$_REQUEST['access_level'] && $options )
			{
				$account = new Ayoola_Form_Element;
			//	$account->id = __CLASS__ . 'level';
				$account->addElement( array( 'name' => 'access_level', 'label' => '', 'onchange' => 'location.search+=\'&access_level=\'+ this.value;', 'type' => 'Select', 'required' => 'required', 'value' => ( @$values['access_level'] ? : $this->getParameter( 'access_level' ) ) ), array( 'Select Profile Type' ) + $options );  
				$account->addRequirement( 'access_level', array( 'InArray' => array_keys( $options )  ) );
				$account->addLegend( $legend );
				unset( $authLevel );
				$form->addFieldset( $account ); 
			}
			elseif( @$_REQUEST['access_level'] && $options )
			{
				$fieldset->addElement( array( 'name' => 'access_level', 'type' => 'Hidden', 'value' => $_REQUEST['access_level'] ) );  
				$fieldset->addRequirement( 'access_level', array( 'InArray' => array_keys( $options )  ) );
			}
			$accessLevel = ( @$_REQUEST['access_level'] ? : $this->getGlobalValue( 'access_level' ) ) ? : $values['access_level'];
	//		var_export( $accessLevel );
	//		var_export( $values['access_level'] );
			$customForm = false;
			if( $accessLevel )
			{
				$authLevel = new Ayoola_Access_AuthLevel;
				$authLevel = $authLevel->selectOne( null, array( 'auth_level' => $accessLevel ) );
		//		var_export( $authLevel );
				if( ! empty( $authLevel['additional_forms'] ) && is_array( $authLevel['auth_options'] ) && in_array( 'attach_forms', $authLevel['auth_options'] ) ) 
				{

					foreach( $authLevel['additional_forms'] as $formName )
					{
						//	 We could use this values later in the Form Viewer
						is_array( $values ) ? Ayoola_Form::setDefaultValues( $values ) : null;
						$class = new Ayoola_Form_View( array( 'form_name' => $formName, 'default_values' => $values ) );
						if( ! $class->getIdentifierData() )
						{  
						//	var_export( $formName );  
							continue;
						}
						$customForms = $class->getForm()->getFieldsets();
						if( $customForms )
						{
							$customForm = true;
						}
					}
				}
				
			}
		//	var_export( $customForm );
			if( @$_REQUEST['form_name'] )     
			{
				is_array( $values ) ? Ayoola_Form::setDefaultValues( $values ) : null;
				$class = new Ayoola_Form_View( array( 'form_name' => $_REQUEST['form_name'], 'default_values' => $values ) );
				if( ! $class->getIdentifierData() )
				{  
				//	var_export( $formName );  
				//	continue;
				}
				$customForms = $class->getForm()->getFieldsets();   
			//	$form->addFieldset( $fieldset ); 
				foreach( $customForms as $each ) 
				{
					$each->addLegend( $legend );
					$form->addFieldset( $each );
				}
			}
			elseif( ! @$customForms )
			{
				$access = new Ayoola_Access();
				$userInfo = $access->getUserInfo();
				$fieldset->addElement( array( 'name' => 'display_name', 'label' => 'Display Name', 'onkeyup' => '', 'placeholder' => 'Display Name e.g. John Smith', 'type' => 'InputText', 'value' => @$values['display_name'] ? : trim( $userInfo['firstname'] . ' ' . $userInfo['lastname'] ) ) );
				
				$fieldset->addRequirement( 'display_name', array( 'NotEmpty' => array( 'badnews' => 'Please choose a display name', ), 'WordCount' => array( 2, 50 ) ) );

				$fieldset->addElement( array( 'name' => 'profile_description', 'label' => 'Profile Description', 'placeholder' => 'Enter your profile description here...', 'type' => 'TextArea', 'value' => @$values['profile_description'] ) );

				//	Profile picture
				$fieldset->addElement( array( 'name' => 'display_picture', 'data-document_type' => 'image', 'label' => 'Display Picture', 'type' => 'Document', 'value' => @$values['display_picture'], ) ); 

				$fieldset->addLegend( $legend );
		//		$form->addFieldset( $fieldset ); 
			}
			else
			{
				foreach( $customForms as $each ) 
				{
					$each->addLegend( $legend );
					$form->addFieldset( $each );
				}
			}
		}
		else
		{
		//	$form->addFieldset( $fieldset ); 
		}
		Application_Javascript::addCode
		(
			'
				ayoola.events.add
				(
					window, "load", function()
					{ 
						var a = document.getElementsByName( "display_name" );
						if( ! a.length )
						{
							var a = document.getElementsByName( "' . Ayoola_Form::hashElementName( 'display_name' ) . '" );
						}
						ayoola.events.add( a[0], "keyup", function()
						{
							var a = document.getElementById( \'profile_url_field\' );
							a.value = this.value; 
							ayoola.addShowProfileUrl( a );
						});
					} 
				);
			'
		);
		if( is_null( $values ) )
		{
			
			if( ! static::isSubDomain() )
			{
				Application_Javascript::addCode
				(
					'
						ayoola.addShowProfileUrl = function( target )
						{
							var element = document.getElementById( "element_to_show_profile_url" );
							element = element ? element : document.createElement( "div" );
							element.id = "element_to_show_profile_url";
							var a = false;
							var xx = target.value;
					//		alert( xx.replace(/[^a-zA-Z0-9_]*/g, "" ) );
							target.value = xx.replace(/[^a-zA-Z0-9_]*/g, "" );
							if( target.value )
							{
								a = true;
							}
							if( a )
							{
								element.innerHTML = "<span class=\'\' style=\'font-size:x-small\'>The profile URL will be: <a target=\'_blank\' href=\'http://' . Ayoola_Page::getDefaultDomain() . Ayoola_Application::getUrlPrefix() . '/" + target.value + "\'>http://' . Ayoola_Page::getDefaultDomain() . Ayoola_Application::getUrlPrefix() . '/" + target.value + "</a></span>";
							}  
							else
							{
							//	element.innerHTML = "<span class=\'badnews\'>Please enter a valid profile URL in the space provided... (e.g. MyStyle) </span>";  
							}
							target.parentNode.insertBefore( element, target.nextSibling );
						}
					'
				);
			}
			else
			{
				Application_Javascript::addCode
				(
					'
						ayoola.addShowProfileUrl = function( target )
						{
							var element = document.getElementById( "element_to_show_profile_url" );
							element = element ? element : document.createElement( "div" );
							element.id = "element_to_show_profile_url";
							var a = false;
							if( target.value )
							{
								a = true;
							}
							if( a )
							{
								element.innerHTML = "<span class=\'\' style=\'font-size:x-small\'>Site URL would be: <a target=\'_blank\' href=\'http://" + target.value + ".' . Ayoola_Application::getDomainName() . '\'>http://" + target.value + ".' . Ayoola_Application::getDomainName() . '</a></span>";
							}  
							else
							{
							//	element.innerHTML = "<span class=\'badnews\'>Please enter a valid profile URL in the space provided... (e.g. MyStyle) </span>";  
							}
							target.parentNode.insertBefore( element, target.nextSibling );
						}
					'
				);
			}
			$fieldset->addElement( array( 'name' => 'profile_url', 'id' => 'profile_url_field', 'label' => static::$_urlName, 'onchange' => 'ayoola.addShowProfileUrl( this );', 'onfocus' => 'ayoola.addShowProfileUrl( this );', 'onkeyup' => 'ayoola.addShowProfileUrl( this );', 'placeholder' => 'e.g. MyPage', 'type' => 'InputText', 'value' => @$values['profile_url'] ) ); 
		//	$fieldset->addFilter( 'profile_url','Username' );  
			$fieldset->addRequirement( 'profile_url', array( 'NotEmpty' => array( 'badnews' => 'The profile URL cannot be left blank.', ), 'CharacterWhitelist' => array( 'badnews' => 'The allowed characters are lower case alphabets (a-z), numbers (0-9), underscore (_) and hyphen (-).', 'character_list' => '^0-9a-zA-Z-_', ), 'WordCount' => array( 4,20 ), 'DuplicateUser' => array( 'Username', 'username', 'badnews' => 'Someone else has already chosen "%variable%"', ) ) );
		//	$fieldset->addElement( array( 'name' => 'name', 'placeholder' => 'Give this page a name', 'type' => 'InputText', 'value' => @$values['name'] ) );   
			
		}
		$form->addFieldset( $fieldset );  
	
		$form->setFormRequirements( 'user-registration' );
		
		$this->setForm( $form );
	}
	// END OF CLASS
}
