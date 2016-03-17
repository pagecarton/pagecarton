<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form_Settings extends Application_Settings_Abstract
{
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		$values = unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		
		//	Default Layout
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;
		$option = new Ayoola_Form_PageLayout;
		$option = $option->select( array( 'pagelayout_id', 'layout_name' ) );
	//	require_once 'Ayoola/Filter/SelectListArray.php';
	//	$filter = new Ayoola_Filter_SelectListArray( 'layout_name', 'layout_name');
	//	$option = $filter->filter( $option );
		$class = null;
		foreach( $option as $each )
		{
			$class = $each['layout_name'] === $values['default_layout'] ? 'defaultnews' : 'normalnews';
			$layouts[$each['layout_name']] = '
			<span style="display:inline-block;">
			<img name="layout_screenshot" width="100px" style="cursor:pointer;margin:0.5em;display:inline-block;max-width:100%;" onClick="ayoola.div.selectElement( this );"  src="/layout/' . $each['layout_name'] . '/screenshot.jpg" alt="' . $each['layout_name'] . '" title="' . $each['layout_name'] . '" class="' . $class . '" >
			</span>';
		//	$layouts[$each['layout_name']] = $each['layout_name'];
		}
		$fieldset->addElement( array( 'name' => 'default_layout', 'label' => '', 'type' => 'Radio', 'style' => 'display:none;', 'value' => @$values['default_layout'] ), $layouts );
		$fieldset->addRequirement( 'default_layout','InArray=>' . implode( ';;', array_keys( $layouts ) ) );
		
/* 		$option = new Ayoola_Access_AuthLevel;
		$option = $option->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$option = $filter->filter( $option );
		$fieldset->addElement( array( 'name' => 'auth_level', 'description' => 'Minimum access level for website.', 'type' => 'Select', 'value' => @$values['auth_level'] ), $option );
		$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $option )  ) );
		
		$option = new Ayoola_Form_Page;
		$option = $option->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
		$option = array( '' => 'Default' ) + $filter->filter( $option );
		$fieldset->addElement( array( 'name' => 'restrict_url', 'description' => 'Minimum access level for website.', 'type' => 'Select', 'value' => @$values['restrict_url'] ), $option );
		$fieldset->addRequirement( 'restrict_url', array( 'InArray' => array_keys( $option )  ) );

 */		$fieldset->addLegend( 'Choose a default layout template for this website. <a target="_blank" title="Download layout templates from the internet" href="https://www.google.com/search?q=ayoola+framework+layout+template+theme" class="boxednews centerednews badnews">Download more...</a> <a title="Create your own layout template" target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/page/layout/" class="boxednews centerednews goodnews">+</a>' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
