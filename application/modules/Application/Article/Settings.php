<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php 02.05.2013 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Settings extends Application_Settings_Abstract
{
	
    /**
     * Allowed Extension
     *
     * @var array
     */
	protected static $_extensions = array( 'html' => '.html', 'php' => '.php', 'asp' => '.asp', 'xhtml' => '.xhtml' );
	
    /**
     * creates the form for creating and editing
     * 
     * return array
     */
	public function getExtensions( $key )
	{
		return $key ? self::$_extensions[$key] : self::$_extensions;
	}
	
    /**
     * creates the form for creating and editing
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
		
	//	self::v( $_POST );
		
		//	auth levels
		$authLevel = new Ayoola_Access_AuthLevel;
		$authLevel = $authLevel->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$authLevel = $filter->filter( $authLevel );
		$fieldset->addElement( array( 'name' => 'allowed_writers', 'required' => 'required', 'label' => 'Pick user levels that can write articles on this website', 'type' => 'Checkbox', 'value' => @$settings['allowed_writers'] ), $authLevel );
		$fieldset->addElement( array( 'name' => 'allowed_editors', 'label' => 'Pick user levels that can edit articles on this website', 'type' => 'Checkbox', 'value' => @$settings['allowed_editors'] ), $authLevel );
	//	$fieldset->addRequirement( 'allowed_writers', array( 'NotEmpty' => null ) );
	//		self::v( $settings['allowed_editors'] );
	//		self::v( $authLevel );
		
		//	extensions
		$options = self::$_extensions;
		$fieldset->addElement( array( 'name' => 'extension' , 'label' => 'URL extension', 'value' => @$settings['extension'], 'type' => 'Radio' ), $options );
		
		//	Allowed Categories
		$options = new Application_Category;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'category_id', 'category_label');
		$options = array( '' => 'Uncategorized' ) + $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'allowed_categories', 'label' => 'Allowed Categories <a rel="spotlight;" title="Manage Categories" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_List/"> Manage Categories</a>', 'value' => @$settings['allowed_categories'], 'type' => 'Checkbox' ), $options );
		
		//	WIDTH AND HEIGHT OF THE COVER PHOTO
		$fieldset->addElement( array( 'name' => 'cover_photo_width', 'label' => 'Width for Cover Photo', 'placeholder' => '900', 'value' => @$settings['cover_photo_width'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'cover_photo_height', 'placeholder' => '300', 'label' => 'Height for Cover Photo', 'value' => @$settings['cover_photo_height'], 'type' => 'InputText' ) ); 
		
		//	WIDTH AND HEIGHT OF THE COVER PHOTO 
		$fieldset->addElement( array( 'name' => 'category_photo_width', 'label' => 'Width for Category Photo', 'placeholder' => '900', 'value' => @$settings['category_photo_width'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'category_photo_height', 'placeholder' => '300', 'label' => 'Height for Category Photo', 'value' => @$settings['category_photo_height'], 'type' => 'InputText' ) );  
		
		//	Remove dates
		$options = array( 'no-date-in-url' => 'Remove date from URL (not recommended)' );
		$fieldset->addElement( array( 'name' => 'no-date-in-url', 'label' => 'URL Options', 'value' => @$settings['no-date-in-url'], 'type' => 'Checkbox' ), $options );
	//	$fieldset->addRequirement( 'options', array( 'ArrayKeys' => $options ) );
		
		//	Article URL
		$option = new Ayoola_Page_Page;
		$option = $option->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
		$option = array( '' => 'Default' ) + $filter->filter( $option );
		$fieldset->addElement( array( 'name' => 'post_url', 'label' => 'Post Url (Advanced)', 'description' => 'URL where "posts" are displayed.', 'type' => 'Select', 'value' => @$settings['post_url'] ), $option );
		$fieldset->addRequirement( 'post_url', array( 'InArray' => array_keys( $option )  ) );

		$fieldset->addLegend( 'Article Settings' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
