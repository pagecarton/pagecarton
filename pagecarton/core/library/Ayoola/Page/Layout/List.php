<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_List extends Ayoola_Page_Layout_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Themes'; 
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
	//	$this->setViewContent( '<h3>My themes</h3>' );		
		$table = $this->getDbTable();
	//	var_export( $table->select() );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );  
		$allThemes = $table->select( null, null, array( 'worsssk-arwrouddddnss00d-1-333' => true ) );
	//	var_export( $allThemes );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );

		$myThemes = $table->select( null, null, array( 'workww---acrrwwwosssuwdnd-1-333' => true ) );
	//	var_export( $myThemes );
	//	var_export( $allThemes );

		
		foreach( $allThemes as $key => $value )
		{
			if( in_array( $value, $myThemes ) )
			{
				unset( $allThemes[$key] );
			}
		}
	//	var_export( $allThemes );
		
	//	$otherThemes = array_diff( $myThemes, $allThemes );
	//	var_export( $otherThemes );
		$this->setViewContent( $this->createPrivateList( $myThemes ), true );		
	//	$this->setViewContent( '<h3>All Themes</h3>' );		
		if( empty( $_GET['mini_info'] ) )
		{
			$allThemes ? $this->setViewContent( $this->createList( $allThemes ) ) : null;		
		}
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList( $data = array() )
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'Default Themes';
//		$list->noRowClass = true;
//		$list->noOptionsColumn = true;
//		$list->noHeader = true;
	//	krsort( $data );
		$list->setData( $data );  
		$list->setListOptions( 
								array( 
										'Creator' => ' ' 
									) 
							);
	//	if( ! empty( $_GET['mini_info'] ) )
		{

			$list->setRowOptions( 
									array( 
											'<a style="" title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" href="javascript:;"> Edit Theme</a>',
											'Images' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Images/?' . $this->getIdColumn() . '=%KEY%\' );" title="Update theme pictures">Pictures</a>' ,
											'<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_ReplaceText/?' . $this->getIdColumn() . '=%KEY%\' );" title="">Text Update</a>' ,
											'Pages' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Pages/?' . $this->getIdColumn() . '=%KEY%\' );" title="Manage Theme Pages">Pages</a>' ,
											'Default' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?' . $this->getIdColumn() . '=%KEY%\' );" title="Make this the default site theme">Make Default</a>' ,
											'Preview' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/widgets/name/Ayoola_Page_Layout_Preview/?' . $this->getIdColumn() . '=%KEY%\' );" title="Preview">Preview</a>' ,
										) 
								);
		}
		$list->setKey( $this->getIdColumn() );  
		$default = array(
				Ayoola_Page_Editor_Layout::getDefaultLayout() => '<i class="fa fa-check"></i>',
				'pc_paginator_default' => '<a href="javascript:" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="Make this the default site theme">Make Default</a>',

		);
		$list->createList(  
			array(
					array( 'field' => 'layout_label', 'header' => 'Theme Name', 'value' => '%FIELD%' ),   
			//		array( 'field' => 'layout_name', 'header' => '', 'value' => '<a style="" title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" href="javascript:;"> Edit Theme</a>' ),   
					array( 'field' => 'layout_name', 'header' => 'Default', 'value' => '%FIELD%', 'value_representation' => $default ),
			)
		);
		//var_export( $list );
		return $list;
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createPrivateList( $data = array() )
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->crossColumnFields = true;
		$list->listTitle = 'My Themes';
	//	$list->noRowClass = true;
	//	$list->noOptionsColumn = true;
	//	$list->noHeader = true;
	//	$table = $this->getDbTable();
	//	$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );   
	//	krsort( $data );       
		$list->setData( $data );  
		$list->setListOptions( 
								array( 
										'Creator' => ' ' 
									) 
							);
		if( empty( $_GET['mini_info'] ) )
		{


			$list->setListOptions( 
									array( 
									//		'Sanitize' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Sanitize/\' );" title="Sanitize all pages. Recreate all page templates.">Sanitize Pages </span>',  
											'Browse' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Repository\', \'' . $this->getObjectName() . '\' );" title="">Browse More Themes</a>',
											'Upload' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Creator/layout_type/upload/\', \'' . $this->getObjectName() . '\' );" title="Upload new theme">Upload New Theme</a>',
									//		'Browse' => '<a target="_blank" href="http://themes.pagecarton.org" title="Download new theme">Find more themes...</a>',
		//									'Creator' => ' ' 
											'Creator' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Creator/layout_type/plain_text/\', \'' . $this->getObjectName() . '\' );" title="Upload new theme">Create HTML theme</a>',
										) 
								);
			$list->setRowOptions( 
									array( 
											'<a style="" title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" href="javascript:;"> Edit Theme</a>',
											'Code' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="Code">Code</a>' ,
											'Images' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Images/?' . $this->getIdColumn() . '=%KEY%\' );" title="Update theme pictures">Pictures</a>' ,
											'<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_ReplaceText/?' . $this->getIdColumn() . '=%KEY%\' );" title="">Text Update</a>' ,
											'Pages' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Pages/?' . $this->getIdColumn() . '=%KEY%\' );" title="Manage Theme Pages">Pages</a>' ,
											'Links' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Links/?' . $this->getIdColumn() . '=%KEY%\' );" title="Manage Theme Links">Links</a>' ,
											'Export' => '<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Export/?' . $this->getIdColumn() . '=%KEY%" title="Export Theme">Export</a>' ,
								//			'Export' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Export/?' . $this->getIdColumn() . '=%KEY%\' );" title="Export Theme">Export</a>' ,
											'Default' => '<a rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?' . $this->getIdColumn() . '=%KEY%\' );" title="Make this the default site theme">Make Default</a>' ,
											'Preview' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/widgets/name/Ayoola_Page_Layout_Preview/?' . $this->getIdColumn() . '=%KEY%\' );" title="Preview">Preview</a>' ,
											'Update' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Page_Layout_Repository?title={{{%layout_label%}}}&layout_type=upload&install={{{%article_url%}}}&update={{{%article_url%}}}\' );" title="Preview">Re-install</a>',
											'Delete' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Delete/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="Delete">Delete</a>' ,
										) 
								);
		}
		$list->setKey( $this->getIdColumn() );  
		$list->setNoRecordMessage( 'You have not added any theme yet.' );  
		$default = array(
				Ayoola_Page_Editor_Layout::getDefaultLayout() => '<i class="fa fa-check"></i>',
				'pc_paginator_default' => '<a href="javascript:" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="Make this the default site theme">Make Default</a>',

		);

		$list->createList(  
			array(
					array( 'field' => 'layout_label', 'header' => 'Theme Name', 'value' => '%FIELD%' ),   
				//	array( 'field' => 'layout_name', 'header' => '', 'value' => '<a style="" title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" href="javascript:;"> Edit Theme</a>' ),   
					array( 'field' => 'layout_name', 'header' => 'Default', 'value' => '%FIELD%', 'value_representation' => $default ),
			)
		);
		//var_export( $list );
		return $list;
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
