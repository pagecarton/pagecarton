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
	public static function callback( $previousData, $newData )
    {
		$themeName = Ayoola_Page_Editor_Layout::getDefaultLayout();

        if( $previousData['data']['default_layout'] === $newData['data']['default_layout'] )
        {
            //return false;
        }

		if( isset( $newData['data']['options'] ) && in_array( 'regenerate_default_pages', $newData['data']['options'] ) )
		{
			$defaultPages = Ayoola_Page_Editor_Sanitize::$defaultPages;	
			foreach( $defaultPages as $page )
			{
				if( Ayoola_Page_Layout_Pages::isSetUpCorrectly( $page, $themeName ) )
				{
					//  we have default layout, 
					//  no need to sanitize pages
					//  default layout will do that later
	
					continue;
				}
	

				$pageInfo = Ayoola_Page_Page::getInstance()->selectOne( null, array( 'url' => $page ) );
				$pageSanitizer = new Ayoola_Page_Editor_Sanitize();

				if( empty( $pageInfo ) )
				{
					//	create a fresh page
					$pageSanitizer->sourcePage( $page );
					Ayoola_Application::$appNamespace .= $page;
				}
				elseif( empty( $pageInfo['system'] ) )
				{
					continue;
				}

				

				//  Why are we deleting sef?
				//  $class = new Ayoola_Page_Delete( $parameters );

				//    We need to delete to enable refresh of default pages during upgrade
				//    We only need to delete saved page files.
				//    To avoid complications of deleting whole page and creating again
				$pagePaths = Ayoola_Page::getPagePaths( $page );
				foreach( $pagePaths as  $pageFile )
				{
					$myPageFile = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS .  $pageFile;
					$corePageFile = APPLICATION_PATH . DS .  $pageFile;
					if( is_file( $corePageFile ) )
					{
						Ayoola_Doc::createDirectory( dirname( $myPageFile ) );
						copy( $corePageFile, $myPageFile );
						//  unlink( $pageFile );
					}
				}
				
				$pageSanitizer->refresh( $page );
			}	
		}
		
		//	copy page content from theme
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
		$values = @$values['data'] ? : unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );  
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		
		
		//	Default Layout
		$fieldset = new Ayoola_Form_Element;
		
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
		}

		$fieldset->addElement( array( 'name' => 'default_layout', 'label' => 'Default Theme', 'title' => 'Select this', 'type' => 'Select', 'style' => 'xdisplay:none;', 'value' => @$values['default_layout'] ), $layouts );
		
		
		
		$fieldset->addLegend( 'Customize Site Pages' );
 
		//	Personalization
		Application_Javascript::addFile( '/js/objects/mcColorPicker/mcColorPicker.js' );
		Application_Style::addFile( '/js/objects/mcColorPicker/mcColorPicker.css' );

		$fieldset->addElement( array( 'name' => 'background_color', 'label' => 'Theme color', 'style' => 'max-width:300px;', 'placeholder' => '#FFBB33', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['background_color'] ) );
		
		
 		//$fieldset->addElement( array( 'name' => 'font_color', 'label' => 'Color of Fonts', 'style' => 'max-width:300px;', 'placeholder' => '#FF0033', 'type' => 'InputText', 'class' => 'color', 'value' => @$values['font_color'] ) );

		$themeOptions = array(
			'regenerate_default_pages' => 'Regenerate Default Pages'
		);

 		$fieldset->addElement( array( 'name' => 'options', 'label' => 'Theme Options', 'type' => 'Checkbox', 'value' => @$values['options'] ), $themeOptions );

		$form->addFieldset( $fieldset );
		
		 
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
