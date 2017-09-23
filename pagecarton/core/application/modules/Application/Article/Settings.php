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
	//	var_export( $authLevel );
		ksort( $authLevel );
	//	var_export( $authLevel );
		unset( $authLevel[97] );
		unset( $authLevel[98] );
		
		$fieldset->addElement( array( 'name' => 'allowed_writers', 'required' => 'required', 'label' => 'Who can create posts?', 'type' => 'SelectMultiple', 'value' => @$settings['allowed_writers'] ), $authLevel );
		$fieldset->addElement( array( 'name' => 'allowed_editors', 'label' => 'Who can edit and manage all posts?', 'type' => 'SelectMultiple', 'value' => @$settings['allowed_editors'] ), $authLevel );
		
/* 		$i = 0;
		//	Build a separate demo form for the previous group
		$innerForm = new Ayoola_Form( array( 'name' => 'access_level' )  );
		$innerForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
		$innerForm->wrapForm = false;
		
		do
		{
			
			//	Put the questions in a separate fieldset
			$innerFildset = new Ayoola_Form_Element; 
			$innerFildset->allowDuplication = true;
			$innerFildset->wrapper = 'white-content-theme-border';   
		//	$innerFildset->wrapper = 'white-background';
			$innerFildset->duplicationData = array( 'add' => 'New User-Group-Restriction', 'remove' => 'Remove Above User-Group-Restriction', 'counter' => 'user_group_counter', );
			$innerFildset->container = 'span';
			   
			$innerFildset->addElement( array( 'name' => 'user_group_restrictions', 'label' => 'User Group', 'type' => 'Select', 'multiple' => 'multiple', 'value' => @$values['user_group_restrictions'][$i] ), $authLevel );
			$innerFildset->addElement( array( 'name' => 'storage_size', 'label' => 'Storage Size (in bytes)', 'placeholder' => 'e.g. 1024', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['storage_size'][$i] ) );
			$innerFildset->addElement( array( 'name' => 'max_allowed_posts', 'label' => 'Maximum Allowed Posts', 'placeholder' => 'e.g. 100', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['max_allowed_posts'][$i] ) );
			$innerFildset->addElement( array( 'name' => 'max_allowed_posts_private', 'label' => 'Maximum Allowed Private Posts', 'placeholder' => 'e.g. 5', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['max_allowed_posts_private'][$i] ) );
									
			$i++;
			$innerFildset->addLegend( 'User-Group-Specific Restrictions  <span name="user_group_counter">' . $i . '</span> of <span name="user_group_counter_total">' . ( ( count( @$values['user_group_restrictions'] ) ) ? : 1 ) . '</span>' );			   			
			$innerForm->addFieldset( $innerFildset );     
		//	self::v( $i );  
		}
		while( isset( $values['user_group_restrictions'][$i] ) );
		
		
		
		//	add previous categories if available
		$fieldset->addElement( array( 'name' => 'group', 'type' => 'Html', 'value' => '' ), array( 'html' => $innerForm->view(), 'fields' => 'user_group_restrictions,storage_size,max_allowed_posts,max_allowed_posts_private' ) );
 */		
		
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
		$fieldset->addElement( array( 'name' => 'allowed_categories', 'label' => 'Select site-wide categories available for users when creating posts <a rel="spotlight;" title="Manage Categories" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_List/"> Manage Categories</a>', 'value' => @$settings['allowed_categories'], 'type' => 'Checkbox' ), $options );
		
		$fieldset->addLegend( 'Post Settings' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
