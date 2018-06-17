<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
		$allThemes ? $this->setViewContent( $this->createList( $allThemes ) ) : null;		
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
		$list->listTitle = 'Preset themes';
		$list->noRowClass = true;
		$list->noOptionsColumn = true;
		$list->noHeader = true;
	//	krsort( $data );
		$list->setData( $data );  
		$list->setListOptions( 
								array( 
										'Creator' => ' ' 
									) 
							);
		$list->setRowOptions( 
								array( 
										'<a style="" title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" href="javascript:;"> Edit Theme</a>',
										'Images' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Images/?' . $this->getIdColumn() . '=%KEY%\' );" title="Update theme pictures">Pictures</a>' ,
										'Pages' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Pages/?' . $this->getIdColumn() . '=%KEY%\' );" title="Manage Theme Pages">Pages</a>' ,
										'Default' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?' . $this->getIdColumn() . '=%KEY%\' );" title="Make this the default site theme">Make Default</a>' ,
										'Preview' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Page_Layout_Preview/?' . $this->getIdColumn() . '=%KEY%\' );" title="Preview">Preview</a>' ,
									) 
							);
		$list->setKey( $this->getIdColumn() );  
		$list->createList(  
			array(
				' ' => array( 'field' => 'layout_label', 'value' => '
				<div style="-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;margin:0 0 2em 0;">
				<div  class="pc_theme_parallax_background" style="background-image:     linear-gradient(      rgba( 0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url(' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=%KEY%&x=' . rand() . ');    background-position: 0 0; background-attachment: scroll;  min-height:200px;">
				<span style="margin-right:1em;font-size:x-large;">%FIELD%</span>
					
				</div>
				<div style="font-size:small;text-transform:uppercase;padding:1em 2em 1em 2em; background:     linear-gradient(      rgba(50, 50, 50, 0.7),      rgba( 50, 50, 50, 0.7)    );  color: #fff !important; ">
				<span class="pc-btn-parent pc-btn-small-parent">%PC-TABLES-ROW-OPTIONS%</span>
				</div>				
				</div>				
				' ),   
			//	'   ' => '', 
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
		$list->listTitle = 'My themes';
	//	$list->noRowClass = true;
		$list->noOptionsColumn = true;
		$list->noHeader = true;
	//	$table = $this->getDbTable();
	//	$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );   
	//	krsort( $data );       
		$list->setData( $data );  
		$list->setListOptions( 
								array( 
								//		'Sanitize' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Sanitize/\' );" title="Sanitize all pages. Recreate all page templates.">Sanitize Pages </span>',  
										'Upload' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Creator/layout_type/upload/\', \'' . $this->getObjectName() . '\' );" title="Upload new theme">Upload New Theme</a>',
										'Browse' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Repository\', \'' . $this->getObjectName() . '\' );" title="Upload new theme">Browse Themes</a>',
								//		'Browse' => '<a target="_blank" href="http://themes.pagecarton.org" title="Download new theme">Find more themes...</a>',
										'Creator' => ' ' 
									) 
							);
		$list->setRowOptions( 
								array( 
										'<a style="" title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" href="javascript:;"> Edit Theme</a>',
										'Code' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="Code">Code</a>' ,
										'Images' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Images/?' . $this->getIdColumn() . '=%KEY%\' );" title="Update theme pictures">Pictures</a>' ,
										'Pages' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Pages/?' . $this->getIdColumn() . '=%KEY%\' );" title="Manage Theme Pages">Pages</a>' ,
										'Links' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Links/?' . $this->getIdColumn() . '=%KEY%\' );" title="Manage Theme Links">Links</a>' ,
										'Export' => '<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Export/?' . $this->getIdColumn() . '=%KEY%" title="Export Theme">Export</a>' ,
							//			'Export' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Export/?' . $this->getIdColumn() . '=%KEY%\' );" title="Export Theme">Export</a>' ,
										'Default' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?' . $this->getIdColumn() . '=%KEY%\' );" title="Make this the default site theme">Make Default</a>' ,
										'Preview' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Page_Layout_Preview/?' . $this->getIdColumn() . '=%KEY%\' );" title="Preview">Preview</a>' ,
										'Delete' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Delete/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="Delete">Delete</a>' ,
									) 
							);
		$list->setKey( $this->getIdColumn() );  
		$list->setNoRecordMessage( 'You have not added any theme yet.' );  
		$list->createList(  
			array(
				' ' => array( 'field' => 'layout_label', 'value' => '
				<div style="-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;margin:0 0 2em 0;">
				<div  class="pc_theme_parallax_background" style="background-image:     linear-gradient(      rgba( 0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url(' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=%KEY%&x=' . rand() . ');    background-position: 0 0; background-attachment: scroll;  min-height:200px;">
				<span style="margin-right:1em;font-size:x-large;">%FIELD%</span>
					
				</div>
				<div style="font-size:small;text-transform:uppercase;padding:1em 2em 1em 2em; background:     linear-gradient(      rgba(50, 50, 50, 0.7),      rgba( 50, 50, 50, 0.7)    );  color: #fff !important; ">
				<span class="pc-btn-parent pc-btn-small-parent">%PC-TABLES-ROW-OPTIONS%</span>
				</div>				
				</div>				
				' ),   
			//	'   ' => '', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
