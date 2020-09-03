<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php date time username $
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';  


/**
 * @category   PageCarton
 * @package    Ayoola_Page_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Page_List  extends Ayoola_Page_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Pages'; 
	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn = 'url';
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		$table = Ayoola_Page_Page::getInstance();
//		$table = $this->getDbTable();
	//	var_export( $table->select() );
		$key = "url";
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );  
		$myPages = $table->select( null, array( 'system' => 0 ), array( 'worsssk22edew-arwrouddddnss00d-1-333' => true ) );

	//	var_export( $myPages );
		
		$myPages = self::sortMultiDimensionalArray( $myPages, $key );
	//	$list->listTitle = 'My Pages'; 
	//	$this->setViewContent( self::__( '<h3>My Pages</h3>' ) );		
		$this->setViewContent( $this->createList( $myPages, 'My Pages' ) );
	
	//	var_export( $myPages );
	//	$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );

		//	Other pages.
		
		$table = Ayoola_Page_Page::getInstance();
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );  

		if( @$_REQUEST['list_all_pages'] )
		{

			$themeInfo = array();
			$themeInfo['layout_name'] = Ayoola_Page_Editor_Layout::getDefaultLayout();
	
			$allPages = Ayoola_Page::getAll( $themeInfo );
		//	var_export( $allPages );
			$all = array();
			foreach( $allPages as $eachPage )
			{
				if( ! $eachPage )
				{
					continue;
				}
			//	var_export( $eachPage );
			//	var_export( Ayoola_Page::getInfo( $eachPage ) );
				if( $info = Ayoola_Page::getInfo( $eachPage ) )
				{
					$all[] = $info;
				}
			}
				$this->setViewContent( $this->createList( $all, 'All Pages' ), true );
		}
	
    } 
	
    /**
     * Paginate the list with Ayoola_Paginator
     * @see Ayoola_Paginator
     */
    protected function createList( $data = null, $title = null )
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = $title;  
		
		//	remove themes
		foreach( $data as $key => $value )
		{
			if( stripos( $value['url'], '/layout/' ) === 0 )
			{
				unset( $data[$key] );
			}
		}
		$list->setData( $data );
		if( $title === 'My Pages' )
		{
			$list->showSearchBox = true;
			$list->setListOptions( 
									array( 
											'Create Home' => '<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=/\' );" title="Edit Home Page">Edit Home Page</a>',
											'<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_List/?list_all_pages=1\' );" title="">All Pages</a>',
											'<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Settings/\' );" title="">Page Settings</a>',
										) 
								);
		}
		else
		{
			$list->setListOptions( 
									array( 
											'Creator' => ' ',
										) 
								);
		}
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No web page created yet.' );
		
		require_once 'Ayoola/Page.php';
		$list->setRowOptions( 
								array( 
										'<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="">Edit Page Layout</a>' ,
										'<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="">Page Settings</a>' ,
										'<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Copy/?origin=%KEY%\' );" title="">Copy to Another Page</a>' ,
										'<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_Creator/?url=%KEY%\' );" title="">Add page to site navigation</a>' ,
										'<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '%KEY%\' );" title="">Preview</a>' ,
										'<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Delete/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title=""><i class="fa fa-trash" aria-hidden="true"></i></a>' ,
									) 
							);

		$list->createList(  
			array(
			'url' => '<span style="font-size:smaller;"></span><span>%FIELD%</span>', 
			array( 'field' => 'title', 'value' =>  '%FIELD% ', 'label' =>  '', 'filter' =>  'Ayoola_Filter_HtmlSpecialChars' ), 
			)
		);
		return $list;
    } 
}
