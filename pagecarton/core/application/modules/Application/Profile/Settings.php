<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php 02.05.2013 12.02am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_Settings extends Application_Settings_Abstract
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
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
	//	$settings = unserialize( @$values['settings'] );
		$settings = @$values['data'] ? : unserialize( @$values['settings'] );
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
		unset( $authLevel[97] );
		unset( $authLevel[98] );
		
		$fieldset->addElement( array( 'name' => 'allowed_writers', 'required' => 'required', 'label' => 'Who can create profiles?', 'type' => 'SelectMultiple', 'value' => @$settings['allowed_writers'] ), $authLevel );
		$fieldset->addElement( array( 'name' => 'allowed_editors', 'label' => 'Who can edit and manage all profiles?', 'type' => 'SelectMultiple', 'value' => @$settings['allowed_editors'] ), $authLevel );

				
		//	Allowed Categories
		$options = new Application_Category;
		$options = $options->select();
		foreach( $options as $key => $value )
		{
			if( ! $options[$key]['category_label'] )
			{
				$options[$key]['category_label'] = $options[$key]['category_name'];        
			}
		}
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
		$options = $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'allowed_categories', 'label' => 'Select site-wide categories available for users when creating profiles <a rel="spotlight;changeElementId=page_refresh" title="Manage Categories" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_List/"> Manage All Categories</a>', 'value' => @$settings['allowed_categories'], 'type' => 'Checkbox' ), $options );

		$fieldset->addLegend( 'Profile Settings' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
