<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Download   Ayoola
 * @package    Application_Download_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Download_Exception 
 */
 
require_once 'Application/Download/Exception.php';


/**
 * @Download   Ayoola
 * @package    Application_Download_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Download_Abstract extends Ayoola_Abstract_Table
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'download_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Download';
	
	
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
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'Download_title', 'description' => 'Title of the Download', 'type' => 'InputText', 'value' => @$values['Download_title'] ) );
		$fieldset->addElement( array( 'name' => 'Download_content', 'description' => 'Add content to the Download', 'type' => 'TextArea', 'value' => @$values['Download_content'] ) );
		$fieldset->addElement( array( 'name' => 'Download_url', 'description' => 'Link this Download to a web address', 'type' => 'InputText', 'value' => @$values['Download_url'] ) );
		$fieldset->addElement( array( 'name' => 'Download_image_url', 'description' => 'Use an image for this Download', 'type' => 'InputText', 'value' => @$values['Download_image_url'] ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirement( 'Download_title', array( 'WordCount' => array( 2, 100 ) ) );
		$fieldset->addRequirement( 'Download_content', array( 'WordCount' => array( 10, 200 ) ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
