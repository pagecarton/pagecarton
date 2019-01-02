<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see Ayoola_Object_Interface_Viewable
 */
 
require_once 'Ayoola/Object/Interface/Viewable.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Page_Editor_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * Page details
     *
     * @var array
     */
	protected $_pageInfo;
	
    /**
     * Path to page files 
     *
     * @var array
     */
	protected $_pagePaths;
	
    /**
     * Page Id retrieved from GET array
     * @var int
     */
	protected $_pageId;
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Ayoola_Page_Page';
	
    /**
     * form values or values to use
     *
     * @var array
     */
	protected $_values;
	
    /**
     * Array of error messages
     *
     * @var array
     */
	protected $_badnews = array();

    /**
     * Available layouts in the structure
     *
     * @var array
     */
	protected $_layouts;
	protected $_layoutHash;
	
    /**
     * Need to hash so the element ID won't conflict in Js
     *
     * @param string
     * @return string
     */
    public static function hashSectionName( $sectionName )
    {
        return 'ay__' . $sectionName;
    } 
	
    /**
     * This method retrieves the available layout platforms
     *
     * @param void
     * @return array
     */
    public function getLayoutHash()
    {
		$this->_layoutHash ? : $this->setLayouts();
        return (array) $this->_layoutHash;
    } 
	
    /**
     * This method retrieves the available layout platforms
     *
     * @param void
     * @return array
     */
    public function getLayouts()
    {
		$this->_layouts ? null : $this->setLayouts( );
		ksort( $this->_layouts );
        return (array) $this->_layouts;
    } 
	
    /**
     * This method sets the available layout platforms and their values on files
     *
     * @param mixed
     */
    public function setLayouts()
    {
		$this->_layouts = array
		( 	'header' => null , 
			'footer' => null , 
			'rightbar' => null , 
			'leftbar' => null , 
			'middlebar' => null
		);
		foreach( $this->_layouts as $key => $value )
		{
			$this->_layouts[$key] = array( 'hash' => self::hashSectionName( $key ), 'view_parameters' => null, 'view_options' => null );
			$this->_layoutHash[$key] = self::hashSectionName( $key );
		}
    } 
	
    /**
     * This method sets the available layout platforms and their values on files
     *
     * @param mixed
     */
    public function setLayout( $layout, $value = null )
    {
        $this->_layouts[$layout] = $value;
    } 
	
    /**
     * This method returns the values property
     *
     * @param 
     * @return array
     */
    public function getValues() 
    {
        if( null === $this->_values )
		{
			$this->setValues();
		}
		return $this->_values;
    } 
	
    /**
     * This method sets the values to use. If the parameter is not set. The form values
     *
     * @param array
     * @return mixed
     */
    public function setValues( Array $values = array() )
    {
        if( ! $values ) // I may want to inject other values from my automated scripts.
		{
			if( $_POST && ! $this->_updateLayoutOnEveryLoad && ! $this->updateLayoutOnEveryLoad ) // updating
			{	
				$values = $_POST;    
			}
			else
			{
				$values = (array) $this->getValuesFromDataFile();
			}
		}
	//	var_export( $values );
		$this->_values = $values;
		if( $this->_values == array ( 0 => false, ) )
		{
			$this->_values = array();
		}
    } 
	
    /**
     * 
     *
     * @param void
     * @return array
     */
    public static function getDefaultPageFilesToUse( $url, $themeName = null )
    {
		$values = array();
		// Retrieve the previous layout data from the page data file
		
//		var_export( $paths );
		$rPaths = Ayoola_Page::getPagePaths( $url );
		
		//	first default content to determine now is the default layout saved content
		$themeName = $themeName ? : Application_Settings_Abstract::getSettings( 'Page', 'default_layout' );
		$pageThemeFileUrl = $url;
		if( $pageThemeFileUrl == '/' )
		{
			$pageThemeFileUrl = '/index';
		}
		$defaulThemeDataFile = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_json';
		if( $themeName && is_file( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $defaulThemeDataFile ) )
		{
			//	dont allow main page content slip here.
			$rPaths['data_json'] = $defaulThemeDataFile;
			$rPaths['data_php'] = null;
			$rPaths['include'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/include';
			$rPaths['template'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/template';
		}
		return $rPaths;
	}
	
    /**
     * Computes layout data using saved information
     *
     * @param void
     * @return array
     */
    public function getValuesFromDataFile()
    {
		$values = array();
		// Retrieve the previous layout data from the page data file
		
		
		if( ! $paths = $this->getPagePaths() )
		{
			return false;
		}
//		var_export( $paths );

		//	Get new relative paths
		$page = $this->getPageInfo();

	//	$rPaths = Ayoola_Page::getPagePaths( $page['url'] );
		
		//	first default content to determine now is the default layout saved content
		$themeName = Application_Settings_Abstract::getSettings( 'Page', 'default_layout' );
		$rPaths = self::getDefaultPageFilesToUse( $page['url'], $themeName );
 		$pageThemeFileUrl = $page['url'];
		if( $pageThemeFileUrl == '/' )
		{
			$pageThemeFileUrl = '/index';
		}
	//	var_export( $rPaths );
	//	$defaulThemeDataFile = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_json';
 		if( stripos( $page['url'], '/layout/' ) === 0 )
		{
			list(  , $themeName ) = explode( '/', trim( $page['url'], '/' ) );
			$oldPath = $rPaths['data_php'];
			$rPaths['data_php'] = 'documents/layout/' . $themeName . '/theme/data_php';
			$rPaths['data_json'] = 'documents/layout/' . $themeName . '/theme/data_json';

			if( ! empty( $_REQUEST['pc_page_editor_content_version'] ) )
			{
				$backupFile = 'documents/layout/' . $themeName . '/theme/data-backup' . DS . $_REQUEST['pc_page_editor_content_version'];
				if( is_file( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $backupFile ) )
				{
					$rPaths['data_json'] = $backupFile;
				}
			}
			
			if( ! is_file( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $rPaths['data_php'] ) )
			{
				$rPaths['data_php'] = $oldPath;
			}
		}
		elseif( $this->getPageEditorLayoutName() )
		{
			$themeName = strtolower( $this->getPageEditorLayoutName() );
			$file = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_json';
	//		if( is_file( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $file ) )
			{
				//	dont allow main page content slip here.
				$rPaths['data_json'] = $file;
				$rPaths['data_php'] = null;
				$rPaths['data_php'] = null;
			}
			if( ! empty( $_REQUEST['pc_page_editor_content_version'] ) )
			{
				$backupFile = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data-backup' . DS . $_REQUEST['pc_page_editor_content_version'];
				if( is_file( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $backupFile ) )
				{
					$rPaths['data_json'] = $backupFile;
				}
			}
		}
		elseif( ! empty( $_REQUEST['pc_page_editor_content_version'] ) )
		{
			$backupFile = self::getPageContentsBackupLocation( $page['url'] ) . DS . $_REQUEST['pc_page_editor_content_version'];
			if( is_file( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $backupFile ) )
			{
				$rPaths['data_json'] = $backupFile;
			}
		}
/* 		elseif( $themeName && is_file( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $defaulThemeDataFile ) )
		{
			//	dont allow main page content slip here.
			$rPaths['data_json'] = $defaulThemeDataFile;
			$rPaths['data_php'] = null;
			$rPaths['data_php'] = null;
		}
 */		//	now using json to store this data
//	var_export( $rPaths );
		$newFile = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $rPaths['data_json'];
	//	var_export( $newFile );
		
		if( is_file( $newFile ) )
		{
	//		var_export( file_get_contents( $newFile ) );
			$values = json_decode( file_get_contents( $newFile ), true );
	//		var_export( $values );
			return $values;
		}
		elseif( $this->getPageEditorLayoutName()  )
		{
			return false;
		}
		//	compatibility
		
	//	var_export( $rPaths );
		
		//	Get my localized file. else, we will have double content in theme of progenies
		$myRealFile = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $rPaths['data_php'];
		
	//	if( ! $dataDir = Ayoola_Loader::getFullPath( $rPaths['data'], array( 'prioritize_my_copy' => true ) ) )
	//	if( ! $dataDir = Ayoola_Loader::getFullPath( $rPaths['data_php'], array( 'prioritize_my_copy' => true ) ) )
		if( ! is_file( $myRealFile ) )
		{
			//	we may still have old data types
		//	return false;
		}

		@$values = include $myRealFile;
	//		var_export( $values );
		if( is_array( $values ) )
		{
			return $values;
		}
		   
		
		$myRealFile = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $rPaths['data'];
		if( ! is_file( $myRealFile ) )
		{
			//	We cant load empty file in XML. it throws error  
			return false;
		}
/* 		if( ! $dataDir = Ayoola_Loader::getFullPath( $rPaths['data'], array( 'prioritize_my_copy' => true ) ) )
		{
			return false;
		}
 */		require_once 'Ayoola/Xml.php';
		$xml = new Ayoola_Xml();
	//	var_export( $dataDir );
		$xml->load( $myRealFile );
	//	var_export( $values );
		$layoutData = (array) $xml->getCDataValues();
		if( @$layoutData['pageLayout'] ) 
		{
		//	var_export( $layoutData['pageLayout'] );
			$values = json_decode( $layoutData['pageLayout'], true );
		//	var_export( $values );
		//	var_export( "\r\n" );
		}
		else	//	Compatibility
		{
			foreach( $this->getLayouts() as $key => $value )
			{
				$objectCounter = 0;
				$hashSectionName = self::hashSectionName( $key );
				$numberedSectionName = $hashSectionName . $objectCounter;
				$layoutOnFile = array();
	//	var_export( $layoutData[$key . '_view_parameters'] );
				// 	viewParameters
				$layoutOnFile['view_parameters'] = ! empty( $layoutData[$key . '_view_parameters'] ) ? $layoutData[$key . '_view_parameters'] : null;

				//	viewOptions
				$layoutOnFile['view_options'] = ! empty( $layoutData[$key . '_view_options'] ) ? $layoutData[$key . '_view_options'] : null;				
				
				//var_export( $layoutOnFile );
				
				$this->setLayout( $key, $layoutOnFile );
				
				$dataForViewOption = _Array( $layoutOnFile['view_options'] );
				$dataForViewParameter = _Array( $layoutOnFile['view_parameters'] );
				
				foreach( $dataForViewParameter as $objectName => $eachViewParameter )
				{
					//var_export( $v );
					if( ! $objectName )
					{
						continue;
					}
					$objectCounter++;
					$values[$numberedSectionName] = $objectName;
					$values[$numberedSectionName . 'editable'] = $eachViewParameter;
					$values[$numberedSectionName . '_parameters'] = 'editable';
					if( isset( $dataForViewOption[$objectName] ) )
					{
						$values[$numberedSectionName . 'option'] = $dataForViewOption[$objectName];
						$values[$numberedSectionName . '_parameters'] .= ',option';
					}
				}
			}
		}
		return $values;
    } 
	
    /**
     * Retrieves the Page Path
     * 
     * @param
     * @return array
     */
    public function getPagePaths( $page = null )
    {
		if( null != $page )
		{
			$page = $this->setPagePaths( $page );
		}
		if( null === $this->_pagePaths )  
		{
			$this->setPagePaths(); // Singleton will not work because of sanitation of pages I am implementing
		}
		return $this->_pagePaths;
    } 
	
    /**
     * Sets the page path
     * 
     * @param null
     * @return null
     */
    public function setPagePaths( $page = null )
    {
		if( null === $page )
		{
			$page = $this->getPageInfo();
		}
		if( ! $page )
		{
			return false;
		}
		
		// Retrieve filenames
		// Retrieve filenames
		$paths = Ayoola_Page::getPagePaths( $page['url'] );
		foreach( $paths as $key => $path )
		{	
			require_once 'Ayoola/Loader.php';
			if( $filePath = Ayoola_Loader::checkFile( $path ) )
			{
				$paths[$key] = $filePath;
			}
		}
		return $this->_pagePaths = (array) $paths;
    } 
	
    /**
     * Retrieves the Page ID
     * 
     * @param
     * @return int
     */
    protected function getPageId()
    {
		if( null === $this->_pageId )
		{
			$this->setPageId();
		}
		return $this->_pageId;
		
    } 
	
    /**
     * sets and secures the Page ID
     * 
     * @param int
     * @return null
     */
    protected function setPageId( $id = null )
    {
		if( empty( $id ) )
		{
			$id = -1;
			if( ! empty( $_GET['page_id'] ) )
			{
				$id = $_GET['page_id'];
				$this->_dbWhereClause['page_id'] = $id;
			}
			//	using urls too
			if( ! empty( $_GET['url'] ) )
			{
				$this->_dbWhereClause['url'] = $_GET['url'];;
			}
		}
		else
		{
			$this->_dbWhereClause['page_id'] = $id; 
		}
		$this->_pageId = $id;
    } 
	
    /**
     * Retrieves the page info from the DB
     * @param void
     * @return array
     */
    public function setPageInfo( $whereClause = null )
    {

		if( null === $whereClause )
		{
			if( ! $this->_dbWhereClause )
			{
				$this->setPageId();
			}
			$whereClause = $this->_dbWhereClause;
		}
		
		//	We don't want themes saved in the pages table anymore
		if( stripos( $whereClause['url'], '/layout/' ) === 0 || $this->getPageEditorLayoutName() && ! $this->getParameter( 'auto_create_page' ) )
		{
			$this->_pageInfo = (array) $whereClause;
			return;
		}
		$table = Ayoola_Page_Page::getInstance();
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );
	//	var_export( $whereClause ); 
		if( ! $whereClause )
		{
			return false;
		}
		$data = $table->selectOne( null, $whereClause );
	//	var_export( $data );
		if( ! $data )
		{
			$this->setBadnews( __CLASS__ . '- No record was found in the database or DB error');
			$data = array();
		}
		$this->_pageInfo = (array) $data;
	} 
	
    /**
     * Retrieves the page info from the DB
     * @param void
     * @return boolean
     */
    public function getPageInfo( $pageId = null )
    {
	//	if( null != $pageId )
		{
	//		$this->setPageInfo( $pageId );
		}
	//	var_
		if( ! $this->_pageInfo ) 
		{
			$this->setPageInfo(); // Singleton will not work because of sanitation of pages I am implementing
		}
		return $this->_pageInfo;
	} 

    /**
     * 
     *
     * @param void
     * @return string
     */
    protected function getPageEditorLayoutName()
    {
		$themeName = $this->getParameter( 'page_editor_layout_name' ) ? : strtolower( @$_REQUEST['pc_page_editor_layout_name'] );
		return $themeName;
	}

		
    /**
     * Sets a badnews error message
     *
     * @param string
     * @return void
     */
    public function setBadnews( $error, $key = null )
    {	
		$key = (string) $key;
		if( ! is_string( $error ) )
		{	
			$this->setBadnews( 'Only a string error message is allowed' );
			return false;
		}

		return $this->_badnews[$key] = $error;
    }

    /**
     * returns an array of error msgs 
     *
     * @param void
     * @return array
     */
    public function getBadnews()
    {	
		$invalidData = $this->getForm()->getBadnews();
		$system = $this->_badnews;
		$merge = array_merge( $invalidData, $system );
		return $merge;
    }
	
    /**
     * 
     * 
     * @param string
     * @return string
     */
    public static function getPageContentsBackupLocation( $url )
    {
		return PAGE_PATH . DS . 'data-backup' . $url . '.backup';
	}

	// END OF CLASS
}
