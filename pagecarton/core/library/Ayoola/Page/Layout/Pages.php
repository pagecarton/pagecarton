<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Pages
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Pages.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Pages
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_Pages extends Ayoola_Page_Layout_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Theme Preset Pages'; 
		
    /**
     * 
     * 
     */
	public static function getPages( $themeName, $type = null )
    {
	//	if( ! $data = $this->getIdentifierData() ){ return array(); }
	//	$dir = dirname( $this->getMyFilename() );
		if( ! is_dir( $dir ) ) 
		{
			$globalFile = Ayoola_Loader::checkFile( 'documents/layout/' . $themeName . '/template' );
			if( ! is_file( $globalFile ) )
			{
				return array();
			}
			$dir = dirname( $globalFile );
		}
		$files = Ayoola_Doc::getFiles( $dir );
//		var_export( $files );
		$pages = array();
	//	sort( $files );
		foreach( $files as $each )
		{
			$ext = array_pop( explode( '.', $each ) );
			switch( $ext )
			{
				case 'html':
					$each = basename( $each );
					$url = array_shift( explode( '.', $each ) );
					$url = '/' . $url;
					$url = str_ireplace( array( '/index', '/home', ), array( '/', '/', ), $url );
					$pages[null][] = array( 'url' => '' . $url );
					$pages['list'][] = $url;
				break;
			}
		}
		return $pages[$type];
	}
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
	//		var_export( $files );
			if( ! $data = $this->getIdentifierData() ){ return false; }
		
	//		var_export( $this->getMyFilename() );
			if( ! $pages = self::getPages( $data['layout_name'] ) )
			{
		//		$pages[] = array( 'url' => '/' );
			}
			$pages = self::sortMultiDimensionalArray( $pages, 'url' );
	//		var_export( $pages );
			$list = new Ayoola_Paginator();
			$list->pageName = $this->getObjectName();
			$list->listTitle = self::getObjectTitle();
		//	$list->listTitle = $this->get;
		//	$table = $this->getDbTable();
		//	$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );   
			$list->setData( $pages );  
			$list->setKey( 'url' );  
			$list->setNoRecordMessage( 'This theme has no pages.' );  
		$list->setRowOptions( 
								array( 
										'Copy' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Layout_Pages_Copy/?url=%KEY%&layout_name=' . $data['layout_name'] . '\' );" style="">Copy to main %KEY% page</a>' ,
									) 
							);
			$list->createList(  
				array(
					'url' => '%FIELD%',   
					' ' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=%KEY%&pc_page_editor_layout_name=' . $data['layout_name'] . '\' );" style="">edit</a>',   
			//		'' => '',   
				)
			);
			$this->setViewContent( $list, true ); 
		
/*			$parameters = array();
			$parameters['pages'] = $pages;
			$class = new Ayoola_Page_List( $parameters );
			$this->setViewContent( $class->view(), true ); 
*/		}
		catch( Ayoola_Page_Layout_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
