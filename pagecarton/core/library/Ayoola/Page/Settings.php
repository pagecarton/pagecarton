<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
                //	continue;
				//	create this page if not available.
				//	must initialize each time so that each page can be handled.
                Ayoola_Application::$appNamespace .= microtime();
				$sanitizeClass = new Ayoola_Page_Editor_Sanitize( array( 'no_init' => true, 'url' => $page, 'auto_create_page' => true ) );  
                if( ! $response = $sanitizeClass->sourcePage( $page ) )
                {
                    //  Auto create
                //    $table->insert( array( 'url' => $page, 'system' => '1' ) );
                }
                Ayoola_Application::$appNamespace .= microtime();
				if( $table->select( null, array( 'url' => $page, 'system' => '1' ) ) )
				{
					$parameters = array( 
											'fake_values' => array( 'auto_submit' => true ),
											'url' => $page,
					);
					$class = new Ayoola_Page_Delete( $parameters );
                    $class->init();

                    //  upgrade cache
				//	Application_Cache_Clear::viewInLine();
				//	$deleted = $table->delete( array( 'url' => $page, 'system' => '1' ) );
				//	var_export( $page );   
				//	var_export( $class->view() );   
				}
				elseif( $table->select( null, array( 'url' => $page ) ) )
				{

                }
                Ayoola_Application::$appNamespace .= microtime();

				$response = $sanitizeClass->sourcePage( $page );

				// sanitize so it could refresh with latest template
			//	$class = new Ayoola_Page_Editor_Sanitize();

				//	create this page if not available.
				$sanitizeClass->refresh( $page );	     		
			//	self::v( $page );   
			//	self::v( $response );   
		//		var_export( $response );   
			}
			catch( Exception $e )
			{
            //    echo $e->getMessage();
				null;
			}
		}
	//	return false;
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
		
		$table = new Ayoola_Page_PageLayout;
        $table = $table::getInstance( $table::SCOPE_PRIVATE );
        $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
        $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
        $option = $table->select( array( 'pagelayout_id', 'layout_name', 'layout_label' ) ); 
		$class = null; 
		foreach( $option as $each )
		{
			$class = $each['layout_name'] === Ayoola_Page_Editor_Layout::getDefaultLayout() ? 'defaultnews' : 'normalnews';  
			$layouts[$each['layout_name']] = '
					<div style="cursor:pointer;" class="pc_inline_block ' . $class . '" name="layout_screenshot" onClick="this.parentNode.parentNode.click(); ayoola.div.selectElement( { element: this, selectMultiple: false } ); ">
						<span style="font-size:20px;"><img height="100" alt="" src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_IconViewer/?url=/layout/' . $each['layout_name'] . '/screenshot.jpg&max_width=850&max_height=540;" ></span>
						<br>
						<div class="xpc_give_space" style="padding:1em;height:2em;overflow:hidden;background:#eee;color:#000;"> ' . $each['layout_label'] . ' </div>
					</div>
				';
//			$layouts[$each['layout_name']] = $each['layout_name'];
		}
		$fieldset->addElement( array( 'name' => 'default_layout', 'label' => 'Default Theme', 'title' => 'Select this', 'type' => 'Select', 'style' => 'xdisplay:none;', 'value' => @$values['default_layout'] ), $layouts );
	//	$fieldset->addRequirement( 'default_layout','InArray=>' . implode( ';;', array_keys( $layouts ) ) );
		
		
		
		$fieldset->addLegend( 'Customize Site Pages' );
 
		//	Personalization
		Application_Javascript::addFile( '/js/objects/mcColorPicker/mcColorPicker.js' );
		Application_Style::addFile( '/js/objects/mcColorPicker/mcColorPicker.css' );
		$fieldset->addElement( array( 'name' => 'background_color', 'label' => 'Background color', 'style' => 'max-width:300px;', 'placeholder' => '#FFBB33', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['background_color'] ) );
		
		{
 			$fieldset->addElement( array( 'name' => 'font_color', 'label' => 'Color of Fonts', 'style' => 'max-width:300px;', 'placeholder' => '#FF0033', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['font_color'] ) ); 
			$form->addFieldset( $fieldset );
		}
		 
		$this->setForm( $form );
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
