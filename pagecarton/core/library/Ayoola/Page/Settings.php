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

class Ayoola_Page_Settings extends PageCarton_Settings
{
	
    /**
     * Calls this after every successful settings change
     * 
     */
	public static function callback()
    {
 		$defaultPages = array( '/', '/post/view', '/widgets', '/account', '/account/signin', '/404', '/posts', '/search', '/cart', '/profile', );	
		  
		$table = Ayoola_Page_Page::getInstance();
	//	unset( $_POST );
		foreach( $defaultPages as $page )    
		{
			try
			{
				//	if its still a system page, delete and create again
				//	this is causing problems deleting the home page
			//	if( $table->select( null, array( 'url' => $page, 'system' => '1' ) ) )
				{
			//		$table->delete( array( 'url' => $page, 'system' => '1' ) );
				}
		//		var_export( $page );   
			//	continue;
				//	create this page if not available.
				//	must initialize each time so that each page can be handled.
				$class = new Ayoola_Page_Editor_Sanitize( array( 'no_init' => true, 'auto_create_page' => true ) );  

				$response = $class->sourcePage( $page );
		//		var_export( $response );   
			}
			catch( Exception $e )
			{
				null;
			}
		}
		//	copy page content from theme
		$themeName = Ayoola_Page_Editor_Layout::getDefaultLayout();
	//	var_export( $themeName );
	//	$pages = Ayoola_Page_Layout_Pages::getPages( $themeName, 'list' );
	//	foreach( $pages as $url )
		{
			// dont autocreate page again because its creating too many pages
		//	Ayoola_Page_Layout_Pages_Copy::this( $url, $themeName );
		}


 	//	var_export( __LINE__ );  
		$class2 = new Ayoola_Page_Editor_Sanitize(); 
		$class2->sanitize( $themeName ); 
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
	//	$values = unserialize( @$values['settings'] );
	//	$settings = unserialize( @$values['settings'] );
	//	$settings = @$values['data'] ? : unserialize( @$values['settings'] );
		$values = @$values['data'] ? : unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );  
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		
		
		//	Default Layout
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		
		$option = new Ayoola_Page_PageLayout;
		$option = $option->select( array( 'pagelayout_id', 'layout_name', 'layout_label' ) );
	//	require_once 'Ayoola/Filter/SelectListArray.php';
	//	$filter = new Ayoola_Filter_SelectListArray( 'layout_name', 'layout_name');
	//	$option = $filter->filter( $option );
		$class = null; 
	//	var_export( $option );
		array_unshift( $option, array( 'layout_name' => 'bootstrapbasic', 'layout_label' => 'PageCarton Default', ) );   
		foreach( $option as $each )
		{
			$class = $each['layout_name'] === Ayoola_Page_Editor_Layout::getDefaultLayout() ? 'defaultnews' : 'normalnews';  
		//	$layouts[$each['layout_name']] = '
		//	<div class="' . $class . '" name="layout_screenshot" onClick="this.parentNode.parentNode.click(); ayoola.div.selectElement( { element: this, selectMultiple: false } ); " style="display:inline-block;text-align:center;padding:3em 2em 3em 2em; xwidth:100px; overflow:hidden;  background:     linear-gradient(      rgba(0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url(\'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=' . ( $each['layout_name'] ) . '\');  background-size: cover;  color: #fff !important; cursor:pointer; ">
		//	' . $each['layout_label'] . '
		//	</div>';
			$layouts[$each['layout_name']] = '
					<div style="cursor:pointer;" class="pc_inline_block ' . $class . '" name="layout_screenshot" onClick="this.parentNode.parentNode.click(); ayoola.div.selectElement( { element: this, selectMultiple: false } ); ">
						<span style="font-size:20px;"><img height="100" alt="" src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_IconViewer/?url=/layout/' . $each['layout_name'] . '/screenshot.jpg&max_width=900&max_height=600;" ></span>
						<br>
						<div  class="pc_give_space" style="height:2em;overflow:hidden;"> ' . $each['layout_label'] . ' </div>
					</div>
				';
//			$layouts[$each['layout_name']] = $each['layout_name'];
		}
		$fieldset->addElement( array( 'name' => 'default_layout', 'label' => 'Default Theme', 'title' => 'Select this', 'type' => 'Radio', 'style' => 'display:none;', 'value' => @$values['default_layout'] ), $layouts );
		$fieldset->addRequirement( 'default_layout','InArray=>' . implode( ';;', array_keys( $layouts ) ) );
		
		
		
		$fieldset->addLegend( 'Customize Site Pages' );
 
		//	Personalization
		Application_Javascript::addFile( '/js/objects/mcColorPicker/mcColorPicker.js' );
		Application_Style::addFile( '/js/objects/mcColorPicker/mcColorPicker.css' );
		$fieldset->addElement( array( 'name' => 'background_color', 'label' => 'Background color', 'style' => 'max-width:300px;', 'placeholder' => '#FFBB33', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['background_color'] ) );
	//	$fieldset->addElement( array( 'name' => 'background_image', 'label' => 'Background Image', 'placeholder' => 'e.g. http://domain.tld/path/to/file.jpg', 'type' => 'Hidden', 'value' => @$values['background_image'] ) );
	//	$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'background_image' ) : 'background_image' );
	//	var_export( $link );
	//	$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['background_image'] ? : $this->getGlobalValue( 'background_image' ) ), 'field_name' => $fieldName, 'field_name_value' => 'url', 'preview_text' => 'Background Image', 'call_to_action' => 'Change Background Image' ) ) ) ); 
 		
	//	$form->addFieldset( $fieldset );  
		
	//	if( $this->getGlobalValue( 'background_color' ) || $this->getGlobalValue( 'background_image' ) )
		{
		//	$fieldset = new Ayoola_Form_Element;
 			$fieldset->addElement( array( 'name' => 'font_color', 'label' => 'Color of Fonts', 'style' => 'max-width:300px;', 'placeholder' => '#FF0033', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['font_color'] ) ); 
		//	$fieldset->addElement( array( 'name' => 'link_color', 'label' => 'Color of Links', 'placeholder' => '#FF00EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color'] ) ); 
		//	$fieldset->addElement( array( 'name' => 'link_color_active', 'label' => 'Color of Links (Active)', 'placeholder' => '#CC44EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color_active'] ) ); 
		//	$fieldset->addElement( array( 'name' => 'link_color_hover', 'label' => 'Color of Links Text(Hover)', 'placeholder' => '#CC44EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color_hover'] ) ); 
		//	$fieldset->addElement( array( 'name' => 'link_color_hover_background', 'label' => 'Color of Links Background (Hover)', 'placeholder' => '#CC44EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color_hover_background'] ) ); 
		//	$fieldset->addElement( array( 'name' => 'link_color_visited', 'label' => 'Color of Visited Links', 'placeholder' => '#CC44EE', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['link_color_visited'] ) );
 		//	$fieldset->addRequirement( 'font_color', array( 'NotEmpty' => null  ) );
			$form->addFieldset( $fieldset );
		}
		 
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
		
    /**
     * 
     * 
     */
	public static function getPercentageCompleted()
    {
		$percentage = 0;
		if( $defaultLayout = Application_Settings_CompanyInfo::getSettings( 'Page', 'default_layout' ) )
		{
			if( Ayoola_Page_PageLayout::getInstance()->selectOne( null, array( 'layout_name' => $defaultLayout ) ) )
			{
				$percentage += 100;
			}
		}
		return $percentage;
	}
	// END OF CLASS
}
