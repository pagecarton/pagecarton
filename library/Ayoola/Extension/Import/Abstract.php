<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Import_Exception 
 */
 
require_once 'Ayoola/Page/Layout/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Extension_Import_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = 99;
	
    /**
     * 
     *
     * @var string
     */
	protected $_idColumn = 'extension_name';  
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'extension_name' );
 		
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Extension_Import_Table';
	
	
    /**
     * creates the form for creating and editing subscription package
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
	
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;

		do 
		{
			$options = array( 
								'new' => 'Upload new extension',
								'update' => 'Update existing extension',
							);
			$fieldset->addElement( array( 'name' => 'import_type', 'label' => 'What are you trying to do?', 'required' => 'required', 'type' => 'Radio', 'value' => @$values['import_type'] ? : 'new' ), $options );
			$fieldset->addRequirement( 'import_type', array( 'ArrayKeys' => $options + array( 'badnews' => 'Please select what you are trying to do...' ) ) );
			if( $this->getGlobalValue( 'import_type' ) === 'update' )
			{
				$option = new Ayoola_Extension_Import_Table;
				$option = $option->select();
				require_once 'Ayoola/Filter/SelectListArray.php';
				$filter = new Ayoola_Filter_SelectListArray( 'extension_name', 'extension_title');
				$option = $filter->filter( $option );
				ksort( $option );
				$fieldset->addElement( array( 'name' => 'extension_name', 'required' => 'required', 'label' => 'Select Extension to Update', 'type' => 'Select', 'value' => @$values['extension_name'] ), $option );
				if( $option )     
				{
					$fieldset->addRequirement( 'extension_name', array( 'ArrayKeys' => $option + array( 'badnews' => 'Please select extension to update' )  ) );
				}
			//	$fieldset->addElement( array( 'name' => 'upload', 'label' => 'Extension File (.tar.gz archive)', 'data-allow_base64' => true, 'data-document_type' => 'application', 'type' => 'Document', 'value' => @$values['upload'] ) );
			}
			else
			{
			}
		}
		while( false );
		$fieldset->addFilters( array( 'Trim' => null ) );
	//	$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		
		$fieldset = new Ayoola_Form_Element();
		$fieldset->addElement( array( 'name' => 'upload', 'label' => 'Extension File (.tar.gz archive)', 'data-allow_base64' => true, 'data-document_type' => 'application', 'type' => 'Document', 'value' => @$values['upload'] ) );
		$form->addFieldset( $fieldset );
		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
