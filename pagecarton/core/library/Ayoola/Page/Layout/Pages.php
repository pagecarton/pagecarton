<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
	protected $_idColumn = 'url';  
	
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Edit Theme'; 
		
    /**
     * 
     * 
     */
	public static function getPagePaths( $themeName, $pageThemeFileUrl = null )
    {
        $fPaths = array();
    //    $themeName = strtolower( $data['layout_name'] );
		if(  $pageThemeFileUrl === '/' )
		{
			$pageThemeFileUrl = '/index';
		}
        $fPaths['include'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/include';
        $fPaths['template'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/template';
		$fPaths['data_json'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_json';
		return $fPaths;
	}
		
    /**
     * 
     * 
     */
	public static function getPageFile( $themeName, $pageThemeFileUrl = null )  
    {
     //   $fPaths = array();
	//    $themeName = strtolower( $data['layout_name'] );
		$pageThemeFileUrl = trim( $pageThemeFileUrl, '/' );


	//	var_export( $pageThemeFileUrl );
        $fPath = 'documents/layout/' . $themeName . '/' . ( $pageThemeFileUrl ? : 'index' ) . '.html';
		return $fPath;
	}
		
    /**
     * 
     * 
     */
	public static function getPages( $themeName, $type = null )
    {
	//	if( ! $data = $this->getIdentifierData() ){ return array(); }
	//	$dir = dirname( $this->getMyFilename() );
	//	if( ! is_dir( $dir ) ) 
		{
			$globalFile = Ayoola_Loader::checkFile( 'documents/layout/' . $themeName . '/template' );
			if( ! is_file( $globalFile ) )
			{
				return array();
			}
			$dir = dirname( $globalFile );
		}
        $ext = array( 'html', 'htm' );
    //        $files = array_unique( Ayoola_Doc::getFilesRecursive( $dir, array( 'whitelist_basename' => $basename ) ) );
		$files = Ayoola_Doc::getFiles( $dir, array( 'whitelist_extension' => $ext ) );
//		var_export( $files );   
		$pages = array();   
	//	sort( $files );
		foreach( $files as $each )
		{
			$ext = explode( '.', $each );
			$ext = array_pop( $ext );
			switch( $ext )
			{
				case 'html':
					$each = basename( $each );
					$each = explode( '.', $each );
					$url = array_shift( $each );
					$url = '/' . $url;
					$url = str_ireplace( array( '/index', '/home', ), array( '/', '/', ), $url );
					$pages[null][] = array( 'url' => '' . $url, 'title' => ucwords( array_pop( explode( '/', str_replace( '-', ' ', $url ) ) ) ) );
					$pages['list'][] = $url;
					$pages['list-url'][$url] = $url;
				break;
			}
		}
		$pages[$type] ? sort( $pages[$type] ) : null;
		return $pages[$type] ? : array();
	}
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
		//	var_export( $this->setIdentifier() );
			if( ! $this->getIdentifier() )
			{
				$this->_identifier['layout_name'] = Ayoola_Page_Editor_Layout::getDefaultLayout();
			}
		//	var_export( $this->_identifier );
			if( ! $data = $this->getIdentifierData() ){ return false; }
		//	var_export( $data );
		
	//		var_export( $this->getMyFilename() );
			if( ! $pages = self::getPages( $data['layout_name'] ) )
			{
		//		$pages[] = array( 'url' => '/' );
			}
            $pages = self::sortMultiDimensionalArray( $pages, 'url' );
            $pages[0]['title'] = 'Home Page';
		//	var_export( $pages );
			$list = new Ayoola_Paginator();
			$list->pageName = $this->getObjectName();
			$list->listTitle = sprintf( self::__( 'Edit "%s" theme content' ), $data['layout_label'] );
			$list->deleteClass = 'Ayoola_Page_Layout_Pages_Delete';
		//	$list->listTitle = $this->get;
		//	$table = $this->getDbTable();
		//	$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );   
			$list->setData( $pages );  
			$list->setKey( 'url' );  
			$list->setNoRecordMessage( 'This theme has no pages.' );  
            $list->setListOptions( 
                                    array( 
                                            '<a style="" title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/' . $this->_identifier['layout_name'] . '/template\' );" href="javascript:;">Main Theme Layout</a>',
                                            '<a style="" title="Edit with a WYSIWYG editor" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=/layout/' . $this->_identifier['layout_name'] . '/template\' );" href="javascript:;">Main HTML Code</a>',
                                            'Images' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Images/?' . $this->getIdColumn() . '=' . $this->_identifier['layout_name'] . '\' );" title="Update theme pictures">Images</a>' ,
                                            '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_ReplaceText/?' . $this->getIdColumn() . '=' . $this->_identifier['layout_name'] . '\' );" title="">Static Texts</a>' ,
                                    ) 
                                );
		    $list->setRowOptions( 
								array( 
										'Copy' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Layout_Pages_Copy/?url=%KEY%&layout_name=' . $data['layout_name'] . '\' );" style="">Copy to main site page</a>' ,
										'Preview' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '%KEY%' . ( '?pc_page_layout_name=' . $data['layout_name'] ) . '\' );" style="">Preview Page </a>' ,
										'Code' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Layout_Pages_Code/?url=%KEY%&layout_name=' . $data['layout_name'] . '\' );" style="">Page HTML Code</a>' ,
										'Duplicate' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Layout_Pages_Duplicate/?url=%KEY%&layout_name=' . $data['layout_name'] . '\', \'page_refresh\' );" style="">Duplicate page</a>' ,
										'Delete Saved Content' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Layout_Pages_ClearContent/?url=%KEY%&layout_name=' . $data['layout_name'] . '\' );" style="">Clear Saved Content</a>' ,
										'Delete' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Layout_Pages_Delete/?url=%KEY%&layout_name=' . $data['layout_name'] . '\', \'page_refresh\' );" style="">x</a>' ,
									) 
							);
			$list->createList(  
				array(
					'title' => '%FIELD%',   
					'url' => '%FIELD%',   
					' ' => '<a href="javascript:" class="" name="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=%KEY%&pc_page_editor_layout_name=' . $data['layout_name'] . '\' );" style="">edit layout</a>',   
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
