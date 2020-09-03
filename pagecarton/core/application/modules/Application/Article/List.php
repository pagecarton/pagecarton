<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_List extends Application_Article_ShowAll
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Posts';      
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( $this->getList() );
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = self::getObjectTitle();
	//	var_export( $this->getDbData() );
		$data = $this->getDbData();
	//	krsort( $data );
	//	var_export( $each );
		foreach( $data as $key => $each )
		{
		//	var_export( $each );
			$data[$key] = self::loadPostData( $each );  
		//	$data[$key] = include $each;
			if( empty( $data[$key] ) )
			{
				unset( $data[$key] );
			}
		}
		
		$list->setData( $data );
		$list->setListOptions( 
								array( 
										'Settings' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Articles/\' );" title="">Post Options</a>',
										'Type' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Type_List/\' );" title="">Post Types</a>',
										'Category' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_List/\' );" title="">Categories</a>',
										'Profiles' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_List/\' );" title="">User Profiles</a>',
										'Styles' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Template_List/\' );" title="">Post Display Styles</a>',
							//			'Creator' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_New/\' );" title="">Create new post</a>' 
									) 
							);
		$this->setIdColumn( 'article_url' );
		$list->setKey( 'article_url' );
		$list->setNoRecordMessage( 'You have not writen any post yet' );
		$list->createList(  
			array(
				'title' => array( 'field' => 'article_title', 'value' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '%KEY%">Preview</a>' ), 
				'type' => array( 'field' => 'article_type', 'value' => '%FIELD%' ), 
				'by' => array( 'field' => 'username', 'value' => '%FIELD%' ),   
				array( 'field' => 'article_title', 'value' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Editor/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>' ), 
				'<a title="Delete" rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
