<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Domain_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Exception 
 */
 
require_once 'Application/Domain/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Domain_Abstract extends Ayoola_Abstract_Table
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'domain_id' );
		
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Domain';
	
    /**
     * Overides the parent method to prevent subdomain from domain administration
     *
     * @param Ayoola_Dbase_Table_Interface
     */
    protected function setDbTable( Ayoola_Dbase_Table_Interface $table = null )
    {		
		$domainDir = Ayoola_Application::getDomainSettings( APPLICATION_DIR );
		$personalDir = Application_Domain_Abstract::getSubDomainDirectory( Ayoola_Page::getDefaultDomain() );
	//	var_export( $domainDir );
	//	var_export( $personalDir );
		if( $domainDir != APPLICATION_DIR && $domainDir != $personalDir )
		{
		//	throw new Application_Domain_Exception( 'DOMAIN ADMINISTRATION NOT ALLOWED FROM THIS SUB-DOMAIN.' );
		}
		parent::setDbTable( $table );
		return $this->_dbTable;
    } 
	
    /**
     */
	public function resetDefaultDomain()
    {
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( @$values['domain_default'] ){ $this->getDbTable()->update( array( 'domain_default' => '' ) ); }
		
		//	Notify Admin
		$mailInfo = array();
		$mailInfo['subject'] = 'Domain Information Altered';
		$mailInfo['body'] = 'The domain table have been altered: Here is the domain information: "' . var_export( $values, true ) . '". 
		
		Domain options are available on: http://' . Ayoola_Page::getDefaultDomain() . '/ayoola/domain/.
		';
		try
		{
		//	var_export( $newCart );
			Ayoola_Application_Notification::mail( $mailInfo );
		}
		catch( Ayoola_Exception $e ){ null; }
    } 
	
    /**
     * 
     */
	public static function getSubDomainDirectory( $subDomain = null )
    {
		if( is_null( $subDomain ) ){ $subDomain = Ayoola_Application::getDomainSettings( 'sub_domain' ); }
		if( empty( $subDomain ) ){ return null; }  
	//	if( empty( $subDomain ) ){ throw new Application_Domain_Exception( 'NO SUBDOMAIN SPECIFIED.' ); }
	//	$oldPath = APPLICATION_PATH . DS . 'sub_domain' . DS . str_ireplace( 'www.', '', strtolower( $subDomain ) );
	
		//	Use another means to seek the old path. 
	
		$oldPath = dirname( APPLICATION_DIR ) . DS . 'application'  . DS . 'sub_domain' . DS . str_ireplace( 'www.', '', strtolower( $subDomain ) ) . @constant( 'PC_PATH_PREFIX' );
//		var_export( ! is_dir( $oldPath ) );    
		if( ! is_dir( $oldPath ) )
		{
			$oldPath = APPLICATION_DIR . DS . 'application'  . DS . 'sub_domain' . DS . str_ireplace( 'www.', '', strtolower( $subDomain ) ) . @constant( 'PC_PATH_PREFIX' );  
		}
		@$newPath = PC_BASE . DS . 'sites' . DS . str_ireplace( 'www.', '', strtolower( $subDomain ) ) . @constant( 'PC_PATH_PREFIX' );
		
		$pathToUse = $oldPath;
		    
		//	compatibility, send to new path
	//	var_export( PC_BASE );
	//	var_export( defined( 'PC_BASE' ) );
//		var_export( is_dir( $oldPath )  );
//		var_export( ! is_dir( $newPath )  );  
//		var_export( $newPath );  
//		var_export( $oldPath );  
		if( defined( 'PC_BASE' ) && is_dir( $oldPath ) && ! is_dir( $newPath ) )
		{
		//	var_export( $newPath );
			mkdir( $newPath, 0777, true );
			Ayoola_Doc::recursiveCopy( $oldPath, $newPath );
			rename( $oldPath, $oldPath . '.old' );
			$pathToUse = $newPath;
		}
		elseif( is_dir( $newPath ) )
		{
			$pathToUse = $newPath;
		}
		return $pathToUse;
	}
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => $this->getObjectName() ) );
        $form->submitValue = 'Save';
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		if( ! $values )
		{
			$fieldset->addElement( array( 'name' => 'domain_name', 'label' => 'Domain Name', 'type' => 'InputText', 'value' => @$values['domain_name'] ) );
			$fieldset->addFilter( 'domain_name', array( 'DomainName' => null ) );   
			$fieldset->addRequirement( 'domain_name', array( 'WordCount' => array( 2, 100 ) ) ); 
		}
	//	$fieldset->addRequirement( 'directory', array( 'WordCount' => array( 2, 100 ) ) );
		
		
		//	Parent Domain
	//	$option = array( 'No', 'Yes' );
	//	$fieldset->addElement( array( 'name' => 'sub_domain', 'label' => 'Is this a sub-domain?', 'type' => 'Radio', 'value' => @$values['sub_domain'] ), $option );
	//	if( Ayoola_Form::getGlobalValue( 'sub_domain' ) )
		{
/* 			$option = new Application_Domain;
			$option = $option->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'domain_name', 'domain_name');
			$option = $filter->filter( $option );
			$fieldset->addElement( array( 'name' => 'domain_parent', 'label' => 'Parent Domain', 'description' => 'Select parent domain, if this is a subdomain.', 'type' => 'Checkbox', 'value' => @$values['domain_parent'] ), $option );
			$fieldset->addRequirement( 'domain_parent', array( 'InArray' => array_keys( $option ) ) );
 */		}
//		else
		{  
			//	Default Domain
/* 			$option = array( 0 => 'Standard Domain', 1 => 'Default Domain' );
			$fieldset->addElement( array( 'name' => 'domain_default', 'label' => 'Domain Type', 'type' => 'Select', 'value' => @$values['domain_default'] ), $option );
			$fieldset->addRequirement( 'domain_default', array( 'InArray' => array_keys( $option ) ) );
 */			
			//	Default Domain
			$option = array( 
								'standard_domain' => 'Standard Domain: Add an independent domain with its own settings.', 
								'primary_domain' => 'Primary Domain: In the case of the pressense of multiple domains on this application, this domain would be the default domain.', 
								'sub_domain' => 'Sub Domain: Add a subdomain to an existing domain names. e.g. subdomain.domain.tld ' 
							);
			
			//	Compatibility
			@$values['domain_type'] = @$values['domain_type'] ? : ( Ayoola_Form::getGlobalValue( 'domain_name' ) == Ayoola_Page::getDefaultDomain() ? 'primary_domain' : 'standard_domain' );
			$fieldset->addElement( array( 'name' => 'domain_type', 'label' => 'Domain Type', 'type' => 'Radio', 'value' => @$values['domain_type']  ), $option );
			$fieldset->addRequirement( 'domain_type', array( 'InArray' => array_keys( $option ) ) );   
			
			//	Domain Options
			$option = array( 
								'custom_directory' => 'Choose a custom directory for this domain', 
								'redirect' => 'Forward this domain to another location.', 
								'ssl' => 'Enforce SSL (Dont select this unless you have installed SSL on the server.)', 
								'user_subdomains' => 'Allow users to have their own sub domains like username.domain.tld (experimental)'   
								);
			$fieldset->addElement( array( 'name' => 'domain_options', 'label' => 'Domain Options', 'type' => 'Checkbox', 'value' => @$values['domain_options'] ), $option );
		//	$fieldset->addRequirement( 'domain_options', array( 'InArray' => array_keys( $option ) ) );
		
		}
		$fieldset->addElement( array( 'name' => 'enforced_destination', 'placeholder' => 'e.g. ' . DOMAIN, 'label' => 'OPTIONAL: By default, www.domain.tld and domain.tld displays the same content; you can define a default domain here so every other variants forward to it. If you enter wwww.domain.tld here, when users type domain.tld on the address bar, they are redirected to www.domain.tld', 'type' => 'InputText', 'value' => @$values['enforced_destination']  ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		if( is_array( Ayoola_Form::getGlobalValue( 'domain_options' ) ) && in_array( 'custom_directory', Ayoola_Form::getGlobalValue( 'domain_options' ) ) )
		{
			//	Custom Directory
			$fieldset = new Ayoola_Form_Element;
		//	var_export( APPLICATION_DIR );
		//	var_export( APPLICATION_PATH );
			$option = array( str_replace( array( APPLICATION_DIR, DS ), array( '', '/' ), APPLICATION_PATH . DS . 'domain' ) => str_replace( array( DS ), array( '/' ), APPLICATION_PATH . DS . 'domain' ) );
		//	$option = array_combine( $option, $option );
			$fieldset->addElement( array( 'name' => 'path', 'disabled' => 'disabled', 'style' => 'max-width:20%;', 'label' => 'Choose a path to save files for this domain', 'type' => 'InputText', 'value' => '/pagecarton/sites/' ), $option );
	//		$fieldset->addRequirement( 'path', array( 'ArrayKeys' => $option ) );
		//	$fieldset->addElement( array( 'name' => 'directory', 'style' => 'max-width:50%;', 'label' => '', 'placeholder' => '/directory', 'type' => 'InputText', 'value' => ( @$values['directory'] ? : ( '' . str_replace( '.', '_', Ayoola_Form::getGlobalValue( 'domain_name' ) ? : $values['domain_name'] ) ) ) ) );     
			$fieldset->addElement( array( 'name' => 'application_dir', 'style' => 'max-width:50%;', 'label' => '', 'placeholder' => '/directory', 'type' => 'InputText', 'value' => ( @$values['application_dir'] ? : ( '' . str_replace( '.', '_', Ayoola_Form::getGlobalValue( 'domain_name' ) ? : $values['domain_name'] ) ) ) ) );     
		//	$fieldset->addFilter( 'directory','Uri' );
		//	$fieldset->addRequirement( 'application_dir', array( 'CharacterWhitelist' => array( 'badnews' => 'The allowed characters are lower case alphabets (a-z), numbers (0-9), underscore (_) and hyphen (-).', 'character_list' => '^0-9a-z-_\/', ), 'WordCount' => array( 5, 50 ) ) );
	//		$fieldset->addElement( array( 'name' => 'application_dir', 'type' => 'Hidden', 'value' => @$values['application_dir'] ) ); 
		//	$fieldset->addFilter( 'application_dir', array( 'DefiniteValue' => Ayoola_Form::getGlobalValue( 'path' ) . Ayoola_Form::getGlobalValue( 'directory' ) ) );
			
			$fieldset->addFilters( array( 'trim' => null ) );
			$fieldset->addLegend( 'Set a custom directory to save appplication files for this new domain name' );
			$form->addFieldset( $fieldset );
		}
		if( is_array( Ayoola_Form::getGlobalValue( 'domain_options' ) ) && in_array( 'redirect', Ayoola_Form::getGlobalValue( 'domain_options' ) ) )
		{
			//	Custom Directory
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addElement( array( 'name' => 'redirect_destination', 'style' => '', 'placeholder' => 'e.g. www.example.com', 'type' => 'InputText', 'value' => @$values['redirect_destination'] ) );
			$fieldset->addElement( array( 'name' => 'redirect_code', 'style' => '', 'placeholder' => 'e.g. 301', 'type' => 'InputText', 'value' => @$values['redirect_code'] ) );
			$fieldset->addRequirement( 'redirect_destination', array( 'NotEmpty' => null ) );
		//	$fieldset->addRequirement( 'redirect_code', array( 'NotEmpty' => null ) );
			$fieldset->addLegend( 'Forward this domain to another domain' );
			$form->addFieldset( $fieldset );
		}
		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
