<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Backup_Exception 
 */
 
require_once 'Application/Backup/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Backup_Abstract extends Ayoola_Abstract_Table
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
     * Features to export into the backup
     * 
     * @var array
     */
	protected static $_exportList = array( '/application/databases/' => 'Databases', '/application/modules/' => 'Modules', '/application/module_files/' => 'Modules Files', '/application/pages/' => 'Page and Layout Templates', '/application/documents/' => 'Documents', '/library/' => 'Libraries' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'backup_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Backup_Backup';
	
    /**
     * Array of Backup Content
     * 
     * @var array
     */
	protected $_backup;
	
    /**
     * Available Backup Options
     * 
     * @var array
     */
	protected static $_availableBackupOptions;
	
    /**
     * Returns filename 
     * 
     */
	public static function getFilename( $name = null )
    {
		$directory = self::getBackupDirectory();
		Ayoola_Doc::createDirectory( $directory );
		$filter = new Ayoola_Filter_Name();
		$filter->replace = '-';
		$name = $filter->filter( $name );
		$domain = $filter->filter( Ayoola_Page::getDefaultDomain() ); 
		$name = $directory . DS . $name . '_CMF_' . $domain . '_' . time() . '.tar'; 
	//	var_export( $name );
		return $name;
	}
	
    /**
     * Returns the backup files directory
     * 
     */
	public static function getBackupDirectory()
    {
	//	return APPLICATION_DIR . DS . 'backup';
		return Ayoola_Application::getDomainSettings( APPLICATION_DIR ) . DS . 'backup';
	}
	
    /**
     * Returns an installer link
     * 
     * @return string
     */
	public static function getInstallerLink()
    {
		return Ayoola_Application::getUrlPrefix() . '/ayoola/framework/installer.tar.gz';  
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
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
        $form->submitValue = $submitValue;
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'backup_name', 'placeholder' => 'Name this Backup', 'type' => 'InputText', 'value' => @$values['backup_name'] ) );  
		}
	//	$link = ;
	//	$options = array( 'simple' => 'Simple Backup: creates an archive of my website for safe keep.', 'installer' => 'Installer: creates a archive of this website to install on another server. Some security settings are wiped out in the created archive. This archive will be available for download at <a target="_blank" href="' . self::getInstallerLink() . '">' . 'http://' . Ayoola_Page::getDefaultDomain() . self::getInstallerLink() . '</a>', 'export' => 'Export: creates a archive of your site that can be imported on another location.' );
		$options = array( 'simple' => 'Simple Backup: creates an archive of my website for safe keep.', 'installer' => 'Installer: creates a archive of this website to install on another server. Some security settings are wiped out in the created archive.', 'export' => 'Export: creates a archive of your site that can be imported on another location.' );   
		$fieldset->addElement( array( 'name' => 'backup_type', 'placeholder' => '', 'type' => 'Radio', 'value' => @$values['backup_type'] ? : 'simple' ), $options );
	//	if( Ayoola_Form::getGlobalValue( 'backup_type' ) === 'export' )
		{
			//	We allow options in export
		//	$options = array( 'databases', 'functions', 'modules', 'pages', 'documents', 'library' );
		//	$options = array_combine( self::$_exportList, self::$_exportList );
			$fieldset->addElement( array( 'name' => 'backup_export_list', 'label' => 'Select features to export', 'type' => 'Checkbox', 'value' => @$values['backup_export_list'] ? : array_keys( self::$_exportList ) ), self::$_exportList );
			$fieldset->addRequirement( 'backup_export_list', array( 'NotEmpty' => null ) );
		}
		if( Ayoola_Form::getGlobalValue( 'backup_type' ) === 'export' )
		{
			$fieldset->addElement( array( 'name' => 'export_expiry', 'label' => 'Please select the period when this file will be available for export', 'type' => 'Select', 'value' => @$values['export_expiry'] ? : array_keys( self::$_exportList ) ), array( '60' => '1 min', '3600' => '1 hr' ) );
			$fieldset->addRequirement( 'export_expiry', array( 'NotEmpty' => null ) );
		}
		$fieldset->addElement( array( 'name' => 'backup_description', 'placeholder' => 'Describe this Backup', 'type' => 'TextArea', 'value' => @$values['backup_description'] ) );
		
	//	var_export( Ayoola_Form::getGlobalValue( 'backup_type' ) );
	
		
		$fieldset->addRequirements( array( 'WordCount' => array( 0,300 ) ) );
		$fieldset->addFilters( array( 'trim' => null ) );
	//	$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
