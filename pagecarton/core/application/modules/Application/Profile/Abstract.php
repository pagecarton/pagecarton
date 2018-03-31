<?php
/**
 * PageCarton Content Management System 
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
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
	public static function getProfilePath( $url )
    {
		return self::getProfileDir( $url ) . '.profile';  
	}
	
    /**
     * Save the profile
     * 
     */
	public static function saveProfile( $values )
    {
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
			return self::getProfileTable()->update( $values, array( 'profile_url' => strtolower( $values['profile_url'] ) ) );
		}
		else
		{
			return self::getProfileTable()->insert( $values );
		}
/*
		$path = self::getProfilePath( $values['profile_url'] );
		Ayoola_Doc::createDirectory( dirname( $path ) );
		$previousValues = @include $path;
		if( is_array( $previousValues ) )
		{
			$values = array_merge( $previousValues, $values );
		}
		return file_put_contents( $path, '<?php return ' . var_export( $values, true ) . ';' );
*/	}

    /**
     * 
     * 
     */
	public static function getProfileTable()
    {
		if( ! self::$_profileTable )
		{
			self::$_profileTable = new Application_Profile_Table();
		}
		return self::$_profileTable;
	}

    /**
     * 
     * 
     */
	public static function getProfileInfo( $profileUrL )
    {
		$profileUrL = strtolower( $profileUrL );
		if( $profileData = self::getProfileTable()->selectOne( null, array( 'profile_url' => $profileUrL ) ) )
		{
			$profileData = $profileData['profile_data'];
		}
		//	var_export( $profileUrL );
	//		var_export( $profileData );
		$table = Ayoola_Access_AuthLevel::getInstance();
		if( $profileData['access_level'] != 1 && $profileData['access_level'] != 0 )
		{
			$authInfo = $table->selectOne( null, array( 'auth_level' => $profileData['access_level'] ) );
	//		var_export( $authInfo );
	//		var_export( $profileData );
			$profileData +=  is_array( $authInfo ) ? $authInfo : array();
		}
		switch( $profileData['access_level'] )
		{
			case 1:
	//			$profileData['auth_name'] = 'Standard';
			break;
		}
		//	var_export( array( 'profile_url' => $profileUrL ) );
		//	var_export( self::getProfileTable()->select() );
		
		if( ! $profileData )
		{
			$filename = self::getProfilePath( $url );
			$profileData = @include $profileUrL;
		}
		return $profileData;
	}

    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData()
    {
		// Comes from a file
		if( ! $data = $this->getParameter( 'data' ) )
		{
			$url = @$_GET['profile_url'] ? : Ayoola_Application::$GLOBAL['profile_url'];  
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
	public function createForm( $submitValue, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'id' => $this->getObjectName() . @$_REQUEST['access_level'] . @$values['profile_url'], 'data-not-playable' => true ) );      
      //  $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = false;
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
		//	$option = array( Ayoola_Page::getDefaultDomain() => 'http://' . Ayoola_Page::getDefaultDomain() );
//			$fieldset->addElement( array( 'name' => 'domain', 'style' => 'max-width:20%;', 'placeholder' => 'Profile Domain (Default)', 'label' => 'Choose a profile URL e.g. MyStyle', 'disabled' => 'disabled', 'type' => 'InputText', 'value' => 'http://' . Ayoola_Page::getDefaultDomain() . '/' ), $option );
			
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
							element.innerHTML = "<span class=\'\' style=\'font-size:x-small\'>The profile URL will be: <a href=\'http://' . Ayoola_Page::getDefaultDomain() . Ayoola_Application::getUrlPrefix() . '/" + target.value + "\'>http://' . Ayoola_Page::getDefaultDomain() . Ayoola_Application::getUrlPrefix() . '/" + target.value + "</a></span>";
						}  
						else
						{
						//	element.innerHTML = "<span class=\'badnews\'>Please enter a valid profile URL in the space provided... (e.g. MyStyle) </span>";  
						}
						target.parentNode.insertBefore( element, target.nextSibling );
					}
				'
			);
			$fieldset->addElement( array( 'name' => 'profile_url', 'style' => 'max-width:50%;', 'label' => 'Handle', 'onchange' => 'ayoola.addShowProfileUrl( this );', 'onfocus' => 'ayoola.addShowProfileUrl( this );', 'onkeyup' => 'ayoola.addShowProfileUrl( this );', 'placeholder' => 'Enter your profile handle here...', 'type' => 'InputText', 'value' => @$values['profile_url'] ) ); 
		//	$fieldset->addFilter( 'profile_url','Username' );
			$fieldset->addRequirement( 'profile_url', array( 'NotEmpty' => array( 'badnews' => 'The profile URL cannot be left blank.', ), 'CharacterWhitelist' => array( 'badnews' => 'The allowed characters are lower case alphabets (a-z), numbers (0-9), underscore (_) and hyphen (-).', 'character_list' => '^0-9a-zA-Z-_', ), 'WordCount' => array( 4,20 ), 'DuplicateUser' => array( 'Username', 'username', 'badnews' => 'Someone else has already chosen "%variable%"', ) ) );
		//	$fieldset->addElement( array( 'name' => 'name', 'placeholder' => 'Give this page a name', 'type' => 'InputText', 'value' => @$values['name'] ) );
			
		}
		//	Profile picture
		$fieldset->addElement( array( 'name' => 'display_picture', 'data-document_type' => 'image', 'label' => 'Display Picture', 'type' => 'Document', 'value' => @$values['display_picture'], ) ); 
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
			$account->addElement( array( 'name' => 'access_level', 'label' => 'Profile type', 'type' => 'Select', 'required' => 'required', 'value' => ( @$values['access_level'] ? : $this->getParameter( 'access_level' ) ) ), $options );  
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
		$accessLevel = @$_REQUEST['access_level'] ? : $this->getGlobalValue( 'access_level' );
		$customForm = false;
		if( $accessLevel )
		{
			$authLevel = new Ayoola_Access_AuthLevel;
			$authLevel = $authLevel->selectOne( null, array( 'auth_level' => $accessLevel ) );
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
			$form->addFieldset( $fieldset ); 
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
			$fieldset->addElement( array( 'name' => 'display_name', 'placeholder' => 'e.g. John Smith', 'type' => 'InputText', 'value' => @$values['display_name'] ? : trim( $userInfo['firstname'] . ' ' . $userInfo['lastname'] ) ) );
			$fieldset->addRequirement( 'display_name', array( 'NotEmpty' => array( 'badnews' => 'Please choose a display name', ), 'WordCount' => array( 2, 50 ) ) );
			$fieldset->addElement( array( 'name' => 'profile_description', 'placeholder' => 'Enter your profile description here...', 'type' => 'TextArea', 'value' => @$values['profile_description'] ) );
			$fieldset->addLegend( $legend );
			$form->addFieldset( $fieldset ); 
		}
		else
		{
			$form->addFieldset( $fieldset ); 
			foreach( $customForms as $each ) 
			{
				$each->addLegend( $legend );
				$form->addFieldset( $each );
			}
		}

		//	Profile picture
//		$fieldset = new Ayoola_Form_Element; 
	
		//	Cover photo
	//	$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'display_picture' ) : 'display_picture' );
	//	var_export( $link );
	//	$fieldset->addElement( array( 'name' => 'display_picture', 'label' => 'Profile Picture', 'placeholder' => 'Choose a profile picture...', 'type' => 'Document', 'value' => @$values['display_picture'] ) );  
	//	$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['display_picture'] ? : $this->getGlobalValue( 'display_picture' ) ), 'field_name' => $fieldName, 'width' => '160', 'height' => '160', 'crop' => true, 'field_name_value' => 'url', 'preview_text' => 'Display Picture', 'call_to_action' => 'Change picture' ) ) ) ); 
	//	$fieldset->addLegend( "Choose a profile picture..." );
/* 		$class = new Ayoola_Access_AccountRequired();
		if( $fieldsets = $class->getForm()->getFieldsets() )
		{
			foreach( $fieldsets as $each ) 
			{
				$form->addFieldset( $each );  
			}
		}
			var_export( $fieldsets );
 */		$form->setFormRequirements( 'user-registration' );
		
		$this->setForm( $form );
	}
	// END OF CLASS
}
