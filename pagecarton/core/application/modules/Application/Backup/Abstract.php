<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
	protected static $_exportList = array( '/application/databases/' => 'Databases', '/application/modules/' => 'Widgets', '/application/module_files/' => 'Posts', '/application/pages/' => 'Pages', '/application/documents/' => 'Documents', '/library/' => 'Libraries' );
	
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
		return $name;
	}
	
    /**
     * Returns the backup files directory
     * 
     */
	public static function getBackupDirectory()
    {
		return Ayoola_Application::getDomainSettings( APPLICATION_DIR ) . DS . 'backup';
	}
	
    /**
     * Returns an installer link
     * 
     * @return string
     */
	public static function getInstallerLink()
    {
		return '/ayoola/framework/installer.tar.gz';  
	}
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = NULL, array $values = NULL )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
        $form->submitValue = $submitValue;
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'backup_name', 'placeholder' => 'Name this Backup', 'type' => 'Hidden', 'value' => @$values['backup_name'] ) );  

            //	We allow options in export
            $fieldset->addRequirement( 'backup_export_list', array( 'NotEmpty' => null ) );
		}
		$options = array( 
                            'simple' => 'Simple Backup', 
                            'installer' => 'Installer', 
                            'export' => 'Create Export Link' ); 
        if( ( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) === APPLICATION_PATH || Ayoola_Page::getDefaultDomain() == 'updates.pagecarton.org' ) && self::hasPriviledge() )
        {
            if( ! empty( $values['backup_name'] ) )
            {
                $fieldset->addElement( array( 'name' => 'export_expiry', 'label' => 'Link Expiry', 'type' => 'Select', 'value' => @$values['export_expiry'] ? : array_keys( self::$_exportList ) ), array( '3600' => '1 hr', '36000' => '10 hrs' ) );
                $fieldset->addRequirement( 'export_expiry', array( 'NotEmpty' => null ) );
            }
            else
            {
                unset( $options['export'] );
            }
        } 
        else
        {
            unset( $options['installer'] );  
            if( empty( $values['backup_name'] ) )
            {
                $fieldset->addElement( array( 'name' => 'backup_export_list', 'label' => 'Back up Content', 'type' => 'Checkbox', 'value' => @$values['backup_export_list'] ? : array_keys( self::$_exportList ) ), self::$_exportList );

                $multisites = PageCarton_MultiSite_Table::getInstance();
                if( $multisites = $multisites->select() ) 
                {
                    require_once 'Ayoola/Filter/SelectListArray.php';
                    $filter = new Ayoola_Filter_SelectListArray( 'directory', 'directory' );
                    $multisites = $filter->filter( $multisites );
                    $fieldset->addElement( array( 'name' => 'backup_export_multisites', 'label' => 'Back up multisites', 'type' => 'Checkbox', 'value' => @$values['backup_export_multisites'] ? : $multisites ), $multisites );
                }
    
                unset( $options['export'] );
            }
            else
            {
                {
                    $fieldset->addElement( array( 'name' => 'export_expiry', 'label' => 'Link Expiry', 'type' => 'Select', 'value' => @$values['export_expiry'] ? : array_keys( self::$_exportList ) ), array( '3600' => '1 hr', '36000' => '10 hrs' ) );
                    $fieldset->addRequirement( 'export_expiry', array( 'NotEmpty' => null ) );
                }
               unset( $options['simple'] );
            }
        } 
		$fieldset->addElement( array( 'name' => 'backup_type', 'label' => 'Mode', 'type' => 'Select', 'value' => @$values['backup_type'] ? : 'simple' ), $options );
		$fieldset->addElement( array( 'name' => 'backup_description', 'placeholder' => 'Describe this Backup', 'type' => 'TextArea', 'value' => @$values['backup_description'] ) );
		$fieldset->addRequirements( array( 'WordCount' => array( 0,300 ) ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
