<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Doc_Exception 
 */
 
require_once 'Ayoola/Doc/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Doc_Abstract extends Ayoola_Abstract_Table
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
     * File Ext Allowed to be uploaded
     * 
     * @var array
     */
	protected static $_allowedExtentions = array(  );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'document_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Doc_Document';
	
    /**
     * Returns random dir name so that more than 9 files is not located in a single folder 
     * 
     */
	public static function getRandomDirectory( $directory )
    {
		do
		{
			$directory = $directory . DS . rand( 0, 9 );
			Ayoola_Doc::createDirectory( $directory );
			$filesInDirectory = Ayoola_Doc::getFiles( $directory );
			$filesInDirectory = count( $filesInDirectory );
		}
		while( $filesInDirectory > 9 );
		return $directory;
	}
	
    /**
     * Returns filename 
     * 
     */
	public static function getFilename( $name, $folder = null )
    {
		$directory = self::getDocumentsDirectory();
		if( ! is_null( $folder ) ){ $directory = $directory . DS . $folder; }
		$directory = self::getRandomDirectory( $directory );
		$name = $directory . DS . $name;
		return $name;
	}
	
    /**
     * Returns the Doc files directory
     * 
     */
	public static function getDocumentsDirectory()
    {
		return Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . DOCUMENTS_DIR;
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
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'document_name', 'description' => 'Name this Doc', 'type' => 'InputText', 'value' => @$values['document_name'] ) );
		
	//	$fieldset->addElement( array( 'name' => 'Doc_options', 'description' => 'Select what to back up', 'type' => 'selectMultiple', 'value' => @$values['Doc_options'] ), self::getAvailableDocOptions() );
		$fieldset->addRequirements( array( 'WordCount' => array( 1,200 ) ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
