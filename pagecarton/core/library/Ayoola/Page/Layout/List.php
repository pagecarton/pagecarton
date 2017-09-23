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
		$list->listTitle = 'Other themes';
	//	$table = $this->getDbTable();
	//	$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
	//	var_export( $table->select() );
		krsort( $data );
		$list->setData( $data );
		$list->setKey( $this->getIdColumn() );  
		$list->setNoRecordMessage( 'No other themes available.' );
		$list->setListOptions( 
								array( 
										'Creator' => ' ' 
									) 
							);
		$list->setRowOptions( 
								array( 
										'Images' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Images/?' . $this->getIdColumn() . '=%KEY%\' );" title="Update theme pictures">Pictures</a>' ,
										'Pages' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Pages/?' . $this->getIdColumn() . '=%KEY%\' );" title="Manage Theme Pages">Pages</a>' ,
									) 
							);
		$list->createList(  
			array(
				' ' => array( 'field' => 'layout_label', 'value' => '<div class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" style="text-align:center;padding:3em 1em 3em 1em;xmargin:1.5em 1em 1.5em 1em; xwidth:100px; overflow:hidden;  background:     linear-gradient(      rgba( 255, 255, 255, 0.2),      rgba(255, 255, 255, 0.2)    ),    url(' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=%KEY%);  background-size: cover;  color: #000 !important; cursor:pointer; ">%FIELD%</div>' ),   
		//		'  ' => '<a title="Edit with a plain text editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?' . $this->getIdColumn() . '=%KEY%\' );" href="javascript:;">Edit</a>', 
				'   ' => '<div style="text-align:center;"><a title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" href="javascript:;">Edit</a></div>', 
		//		'    ' => '<a title="Export template" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Export/?' . $this->getIdColumn() . '=%KEY%\' );" href="javascript:;">Export</a>', 
		//		'     ' => '<a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
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
		$list->listTitle = self::getObjectTitle();
		$list->listTitle = 'My themes';
	//	$table = $this->getDbTable();
	//	$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );   
		krsort( $data );
		$list->setData( $data );  
		$list->setListOptions( 
								array( 
								//		'Sanitize' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Sanitize/\' );" title="Sanitize all pages. Recreate all page templates.">Sanitize Pages </span>',  
										'Settings' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Page/\' );" title="Advanced Page Settings.">Change Site Theme</a>' ,
										'Upload' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Creator/layout_type/upload/\', \'' . $this->getObjectName() . '\' );" title="Upload new theme">Install New Theme</a>',
										'Creator' => ' ' 
									) 
							);
		$list->setRowOptions( 
								array( 
										'Code' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?' . $this->getIdColumn() . '=%KEY%\' );" title="Code">Edit Code</a>' ,
										'Images' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Images/?' . $this->getIdColumn() . '=%KEY%\' );" title="Update theme pictures">Change Pictures</a>' ,
										'Pages' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Pages/?' . $this->getIdColumn() . '=%KEY%\' );" title="Manage Theme Pages">Theme Pages</a>' ,
										'Export' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Export/?' . $this->getIdColumn() . '=%KEY%\' );" title="Export Theme">Export Theme</a>' ,
										'Default' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?' . $this->getIdColumn() . '=%KEY%\' );" title="Make this the default site theme">Make Default</a>' ,
										'Preview' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Page_Layout_Preview/?' . $this->getIdColumn() . '=%KEY%\' );" title="Preview">Preview</a>' ,
										'Delete' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Delete/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="Export Theme">Delete Theme</a>' ,
									) 
							);
		$list->setKey( $this->getIdColumn() );  
		$list->setNoRecordMessage( 'You have not added any theme yet.' );  
		$list->createList(  
			array(
				' ' => array( 'field' => 'layout_label', 'value' => '<div class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" style="text-align:center;padding:3em 1em 3em 1em;xmargin:1.5em 1em 1.5em 1em; xwidth:100px; overflow:hidden;  background:     linear-gradient(      rgba( 255, 255, 255, 0.2),      rgba(255, 255, 255, 0.2)    ),    url(' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=%KEY%);  background-size: cover;  color: #000 !important; cursor:pointer; ">%FIELD%</div>' ),   
		//		' ' => '<a title="%KEY%" href="javascript:;"><img src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=%KEY%" style="max-height:50px;float:left;" alt="%KEY%" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?' . $this->getIdColumn() . '=%KEY%\' );" /></a>', 
				'   ' => '<div style="text-align:center;"><a title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/%KEY%/template\' );" href="javascript:;">Edit</a></div>', 
	//			'      ' => '<a title="Edit with a plain text editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?' . $this->getIdColumn() . '=%KEY%\' );" href="javascript:;">Code</a>', 
			//	'  ' => '<a title="Edit theme images" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Images/?' . $this->getIdColumn() . '=%KEY%\' );" href="javascript:;">Images</a>', 
	//			'    ' => '<a title="Export template" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Export/?' . $this->getIdColumn() . '=%KEY%\' );" href="javascript:;">Export</a>', 
	//			'     ' => '<a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
