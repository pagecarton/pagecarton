<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
		$myPages = $table->select( null, array( 'system' => 0 ), array( 'worsssk-arwrouddddnss00d-1-333' => true ) );

//		var_export( $myPages );
		
		$myPages = self::sortMultiDimensionalArray( $myPages, $key );
	//	$list->listTitle = 'My Pages'; 
	//	$this->setViewContent( '<h3>My Pages</h3>' );		
		$this->setViewContent( $this->createList( $myPages, 'My Pages' ) );
	
	//	var_export( $myPages );
	//	$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );

		//	Other pages.
		
		$table = Ayoola_Page_Page::getInstance();
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );  
		if( $otherThemes = $table->select( null, array( 'system' => 1 ) ) )
		{
			$otherThemes = self::sortMultiDimensionalArray( $otherThemes, $key );
		//	$list->listTitle = 'Other Pages';
		//	$this->setViewContent( '<h3>Other Pages</h3>' );		
			$this->setViewContent( $this->createList( $otherThemes, 'Other Pages' ) );
		}
	//	var_export( $otherThemes );
	//	var_export( $allThemes );

	//	$this->setViewContent( '<h3>PAGE OPTIONS:</h3>' );		
	//	$this->setViewContent( '<h4><a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Creator/" title="Create a new page">+</a> | <a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Sanitize/" title="Sanitize all pages. Recreate all page templates.">o</a></h4>' );		
//		$this->setViewContent( $this->getList() );		
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
	//	$list->listTitle = self::getObjectTitle();   
		$list->listTitle = $title;  
		
		//	remove themes
//		$data = $data ? : $this->getDbData();
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
		//	$list->setData( $data );
		//	$list->setData( $this->getDbTable->select( null, array() ) );
			$list->setListOptions( 
									array( 
											'Create Home' => '<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=/\' );" title="Edit Home Page">Edit Home Page</a>',
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
		
/* 		// _Array function will convert this to array for me
		$options = "<div>
						<a class='pc-btn' href='" . Ayoola_Application::getUrlPrefix() . "/tools/classplayer/get/object_name/Ayoola_Page_Editor/?" . $this->getIdColumn() . "=%KEY%'>Copy Page Contents</a>
					</div>";
 */		require_once 'Ayoola/Page.php';
		$list->setRowOptions( 
								array( 
										'Delete' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Delete/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="">Delete Page</a>' ,
										'Options' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" title="">Page Options</a>' ,
										'Copy' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Copy/?origin=%KEY%\' );" title="">Copy Page</a>' ,
										'Preview' => '<a href="javascript:;" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '%KEY%\' );" title="">Preview Page</a>' ,
									) 
							);

		$list->createList(  
			array(
				'url' => '<span>%FIELD%</span>', 
//				'url' => '<span style="font-size:smaller;">' . Ayoola_Application::getUrlPrefix() . '</span><span>%FIELD%</span> <a style="font-size:smaller;" title="Preview" onClick="ayoola.spotLight.showLinkInIFrame( \'http://' . DOMAIN . '' . Ayoola_Application::getUrlPrefix() . '%FIELD%\' );" href="javascript:;">preview</a>', 
				'title' => '%FIELD%', 
//				'title' => '%FIELD% <a style="font-size:smaller;" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor/?' . $this->getIdColumn() . '=%KEY%"> options</a>', 
				' ' => '<a rel="" href="javascript:;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?' . $this->getIdColumn() . '=%KEY%\', \'' . $this->getObjectName() . '\' );" >Edit</a>', 
		//		'   ' => '<a rel="" href="javascript:" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Copy/?origin=%KEY%\' );">Copy</a>', 
		//		'  ' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		return $list;
    } 
}
