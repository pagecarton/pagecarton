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
        if( ! Ayoola_Application::getUserInfo( 'username' ) )
        {
            return false;
        }
		if( ! is_null( self::$_myProfiles ) )
		{
			return self::$_myProfiles;
		}
        $table = Application_Profile_Table::getInstance();

        //  some username were stored with mixed cases
        $access = new Ayoola_Access();
        $userInfo = $access->getUserInfo();


        $profileUser = array(
            Ayoola_Application::getUserInfo( 'username' ),
            $userInfo['username']
        );
        $profiles = $table->select( null, array( 'username' => $profileUser ) );
        foreach( $profiles as $profileInfo )
        {
            self::$_myProfiles[] = $profileInfo['profile_url'];
        }
		return self::$_myProfiles;
	}
	
    /**
     * 
     * 
     */
	public static function getMyDefaultProfile()
    {
		$profile = Ayoola_Application::getUserInfo( 'profile_url' );
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
		if( $profileData = $table->selectOne( null, array( 'profile_url' => $profileUrL ), array( 'work-around-to-avoid-stupid-cache' ) ) )
		{
			//	main table data should be there because 
			//	values like display_name is absent in inner data
            $profileData = ( is_array( $profileData['profile_data'] ) ? $profileData['profile_data'] : array()  ) 
                            + ( is_array( $profileData ) ? $profileData : array()  );
		}
		$table = Ayoola_Access_AuthLevel::getInstance();
		if( @$profileData['access_level'] != 1 && @$profileData['access_level'] != 0 )
		{
			$authInfo = $table->selectOne( null, array( 'auth_level' => $profileData['access_level'] ) );
			$profileData +=  is_array( $authInfo ) ? $authInfo : array();
        }
        if( $userInfo = Application_User_Abstract::getUserInfo( $profileData['user_id'] ) )
        {
			$profileData +=  is_array( $userInfo ) ? $userInfo : array();
        }
		switch( @$profileData['access_level'] )
		{
			case 1:

            break;
		}
        if( @$profileData['profile_url'] && @$profileData['display_name'] )
		{
			if( ! @$profileData['display_picture'] )
			{
            	$profileData['display_picture'] = '/img/placeholder-image.jpg';
			}
			if( ! @$profileData['display_name'] )
			{

            }
			if( ! @$profileData['profile_description'] )
			{
                
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

			$url = $profileUrl[$this->getIdColumn()] ? : ( @$_GET['profile_url'] ? : ( Ayoola_Application::$GLOBAL['profile']['profile_url'] ? : Ayoola_Application::$GLOBAL['post']['profile_url'] ) );  

			$data = self::getProfileInfo( $url );
		}

		if( ! $data  
			|| ( ! @$data['publish'] && ! self::isOwner( @$data['user_id'] ) )   
			|| ! self::hasPriviledge( $data['auth_level'] )
		)
		{

		}
		$this->_identifierData = $data;
    } 
	  
	//	This is to implement the abstract method of the parent class. Not all inheriting classes needs a form
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'id' => $this->getObjectName() . @$_REQUEST['access_level'] . @$values['profile_url'], 'data-not-playable' => true ) );      

		$form->oneFieldSetAtATime = false;
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;
        $authTable = new Ayoola_Access_AuthLevel;
        $authTable->getDatabase()->getAdapter()->setAccessibility( $authTable::SCOPE_PRIVATE );
        $authTable->getDatabase()->getAdapter()->setRelationship( $authTable::SCOPE_PRIVATE );
		if( ! static::isSubDomain() )
		{
			
			//	Profile type
			$authLevel = $authTable->select( null, null, array( 'xx' => 'ss') );
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
				$account->addElement( array( 'name' => 'access_level', 'label' => 'Profile Category', 'onchange' => 'location.search+=\'&access_level=\'+ this.value;', 'type' => 'Select', 'required' => 'required', 'value' => ( @$values['access_level'] ? : $this->getParameter( 'access_level' ) ) ), array( 'Select Profile Type' ) + $options );  
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
            elseif( self::hasPriviledge( 98 ) AND $authLevel = $authTable->select()  )
            {
				$account = new Ayoola_Form_Element;
                
                $options = array();
                foreach( $authLevel as $each )
                {
                    $options[$each['auth_level']] =  "{$each['auth_name']}";
                }
                unset( $options[99], $options['99'], $options[98], $options['98'], $options[97], $options['97'], $options[0], $options['0'] );
                $account->addElement( array( 'name' => 'access_level', 'label' => 'Profile Category', 'onchange' => 'location.search+=\'&access_level=\'+ this.value;', 'type' => 'Select', 'required' => 'required', 'value' => ( @$values['access_level'] ? : $this->getParameter( 'access_level' ) ) ), array( 'Select Profile Type' ) + $options );  
				$form->addFieldset( $account ); 

            }
			$accessLevel = ( @$_REQUEST['access_level'] ? : $this->getGlobalValue( 'access_level' ) ) ? : $values['access_level'];
			$customForm = false;
			if( $accessLevel )
			{
				$authLevel = $authTable->selectOne( null, array( 'auth_level' => $accessLevel ) );
				if( ! empty( $authLevel['additional_forms'] ) && is_array( $authLevel['auth_options'] ) && in_array( 'attach_forms', $authLevel['auth_options'] ) ) 
				{

					foreach( $authLevel['additional_forms'] as $formName )
					{
						//	 We could use this values later in the Form Viewer
						is_array( $values ) ? Ayoola_Form::setDefaultValues( $values ) : null;
						$class = new Ayoola_Form_View( array( 'form_name' => $formName, 'default_values' => $values ) );
						if( ! $class->getIdentifierData() )
						{  

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

				}
				$customForms = $class->getForm()->getFieldsets();   
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

							}
							target.parentNode.insertBefore( element, target.nextSibling );
						}
					'
				);
			}
			$fieldset->addElement( array( 'name' => 'profile_url', 'id' => 'profile_url_field', 'label' => static::$_urlName, 'onchange' => 'ayoola.addShowProfileUrl( this );', 'onfocus' => 'ayoola.addShowProfileUrl( this );', 'onkeyup' => 'ayoola.addShowProfileUrl( this );', 'placeholder' => 'e.g. MyPage', 'type' => 'InputText', 'value' => @$values['profile_url'] ) ); 
            $fieldset->addRequirement( 'profile_url', array( 'NotEmpty' => array( 'badnews' => 'The profile URL cannot be left blank.', ), 'CharacterWhitelist' => array( 'badnews' => 'The allowed characters are lower case alphabets (a-z), numbers (0-9), underscore (_) and hyphen (-).', 'character_list' => '^0-9a-zA-Z-_', ), 'WordCount' => array( 4,50 ), 'DuplicateUser' => array( 'Username', 'username', 'badnews' => 'Someone else has already chosen "%variable%"', ) ) );
            if( static::isSubDomain() )
            {
                $fieldset->addFilter( 'profile_url', array( 'Transliterate' => null, 'CharacterWhitelist' => array( 'character_list' => '^\w\-\/', 'replace' => '-', ) ) );
            }
            else
            {
                $fieldset->addFilter( 'profile_url', array( 'Transliterate' => null, 'CharacterWhitelist' => array( 'character_list' => '^\w\-\/', 'replace' => '_', ) ) );
            }

			
		}
		$profileSettings = Application_Profile_Settings::retrieve(); 
		if( @$profileSettings['allowed_categories'] )
		{

			$categoryTable = Application_Category::getInstance();
			$siteCategories = $categoryTable->select( null, array( 'category_name' => $profileSettings['allowed_categories'] ) );

			//	Now allowing users to create their own personal categories
			
			//	Get information about the user access information
			$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
			$siteCategories = $filter->filter( $siteCategories );
			$fieldset->addElement( array( 'name' => 'category_name', 'label' => 'Select Category ' . $addCategoryLink, 'type' => 'SelectMultiple', 'value' => @$values['category_name']  ), $siteCategories ? : array() );
		}
		$form->addFieldset( $fieldset );  
	
		$form->setFormRequirements( 'user-registration' );
		
		$this->setForm( $form );
	}
	// END OF CLASS
}
