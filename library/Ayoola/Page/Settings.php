<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Settings
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
 * @package    Ayoola_Page_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Settings extends Application_Settings_Abstract
{
	
    /**
     * Calls this after every successful settings change
     * 
     */
	public static function callback()
    {
	//	var_export( __LINE__ );  
		$class = new Ayoola_Page_Editor_Sanitize(); 
		$class->sanitize(); 
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
		$values = unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		
		
		//	Default Layout
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		
		$option = new Ayoola_Page_PageLayout;
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
			<img name="layout_screenshot" width="100px" style="cursor:pointer;margin:0.5em;display:inline-block;max-width:100%;" onClick="this.parentNode.parentNode.click(); ayoola.div.selectElement( this ); "  src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?&layout_name=' . $each['layout_name'] . '" alt="' . $each['layout_name'] . '" title="' . $each['layout_name'] . '" class="' . $class . '" > 
			</span>';
		//	$layouts[$each['layout_name']] = $each['layout_name'];
		}
		$fieldset->addElement( array( 'name' => 'default_layout', 'required' => 'required', 'label' => 'Select a template', 'title' => 'Select this', 'type' => 'Radio', 'style' => 'display:none;', 'value' => @$values['default_layout'] ), $layouts );
		$fieldset->addRequirement( 'default_layout','InArray=>' . implode( ';;', array_keys( $layouts ) ) );
		
		
		
/* 		$option = new Ayoola_Access_AuthLevel;
		$option = $option->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$option = $filter->filter( $option );
		$fieldset->addElement( array( 'name' => 'auth_level', 'description' => 'Minimum access level for website.', 'type' => 'Select', 'value' => @$values['auth_level'] ), $option );
		$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $option )  ) );
		
		$option = new Ayoola_Page_Page;
		$option = $option->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
		$option = array( '' => 'Default' ) + $filter->filter( $option );
		$fieldset->addElement( array( 'name' => 'restrict_url', 'description' => 'Minimum access level for website.', 'type' => 'Select', 'value' => @$values['restrict_url'] ), $option );
		$fieldset->addRequirement( 'restrict_url', array( 'InArray' => array_keys( $option )  ) );

 */		$fieldset->addLegend( 'Choose a default layout template for this website. <a target="_blank" title="Download layout templates from the internet" href="https://www.google.com/search?q=ayoola+framework+layout+template+theme" class="boxednews centerednews badnews">Download more...</a> <a title="Create your own layout template" target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/page/layout/" class="boxednews centerednews goodnews">+</a>' );
 
		//	Personalization
		Application_Javascript::addFile( '/js/objects/mcColorPicker/mcColorPicker.js' );
		Application_Style::addFile( '/js/objects/mcColorPicker/mcColorPicker.css' );
		$fieldset->addElement( array( 'name' => 'background_color', 'label' => 'Background color', 'placeholder' => '#FFBB33', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['background_color'] ) );
/* 		$fieldset->addElement( array( 'name' => 'background_image', 'label' => 'Background Image', 'placeholder' => 'e.g. http://domain.tld/path/to/file.jpg', 'type' => 'Hidden', 'value' => @$values['background_image'] ) );
		$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'background_image' ) : 'background_image' );
	//	var_export( $link );
		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['background_image'] ? : $this->getGlobalValue( 'background_image' ) ), 'field_name' => $fieldName, 'field_name_value' => 'url', 'preview_text' => 'Background Image', 'call_to_action' => 'Change Background Image' ) ) ) ); 
 */		
	//	$form->addFieldset( $fieldset );  
		
	//	if( $this->getGlobalValue( 'background_color' ) || $this->getGlobalValue( 'background_image' ) )
		{
		//	$fieldset = new Ayoola_Form_Element;
			$fieldset->addElement( array( 'name' => 'font_color', 'label' => 'Color of Fonts', 'placeholder' => '#FF0033', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['font_color'] ) ); 
			$fieldset->addElement( array( 'name' => 'link_color', 'label' => 'Color of Links', 'placeholder' => '#FF00EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color'] ) ); 
			$fieldset->addElement( array( 'name' => 'link_color_active', 'label' => 'Color of Links (Active)', 'placeholder' => '#CC44EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color_active'] ) ); 
			$fieldset->addElement( array( 'name' => 'link_color_hover', 'label' => 'Color of Links Text(Hover)', 'placeholder' => '#CC44EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color_hover'] ) ); 
			$fieldset->addElement( array( 'name' => 'link_color_hover_background', 'label' => 'Color of Links Background (Hover)', 'placeholder' => '#CC44EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color_hover_background'] ) ); 
			$fieldset->addElement( array( 'name' => 'link_color_visited', 'label' => 'Color of Visited Links', 'placeholder' => '#CC44EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color_visited'] ) );
		//	$fieldset->addRequirement( 'font_color', array( 'NotEmpty' => null  ) );
			$form->addFieldset( $fieldset );
		}
		 
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
