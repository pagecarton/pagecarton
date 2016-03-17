<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Doc_Exception 
 */
 
require_once 'Ayoola/Doc/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Settings extends Application_Settings_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		$settings = unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		
		//	upload privileges
		$options = new Ayoola_Access_AuthLevel;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$options = $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'allowed_uploaders', 'label' => 'Pick user levels that can upload files on this website.', 'type' => 'Checkbox', 'value' => @$settings['allowed_uploaders'] ), $options );
		
		//	editing privileges
		$fieldset->addElement( array( 'name' => 'allowed_editors', 'label' => 'Pick user levels that can edit files on this website.', 'type' => 'Checkbox', 'value' => @$settings['allowed_editors'] ), $options );
		
		//	editing privileges
		$fieldset->addElement( array( 'name' => 'allowed_viewers', 'label' => 'Pick user levels that can use the file manager to view files on this website.', 'type' => 'Checkbox', 'value' => @$settings['allowed_viewers'] ), $options );
		
		//	Enable Personal Folder
		$options = array(
							'private_directory' => 'Enable distinct directories for users.', 
							'allow_profile_pictures' => 'Allow users to upload a profile picture.', 
							
						);
		$fieldset->addElement( array( 'name' => 'options', 'label' => 'Other options', 'type' => 'Checkbox', 'value' => @$settings['options'] ), $options );
		
		$fieldset->addLegend( 'Document Settings' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
