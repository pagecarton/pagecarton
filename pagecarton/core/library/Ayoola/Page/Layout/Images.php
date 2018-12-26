<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Images
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Images.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Images
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_Images extends Ayoola_Page_Layout_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Themes Images'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_imageExtensions = array( 'jpg', 'jepg', 'png', 'gif', 'bmp', 'ico', 'tiff', ); 
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{ 
			if( ! $this->getIdentifier() )
			{
				$this->_identifier[$this->getIdColumn()] = Ayoola_Page_Editor_Layout::getDefaultLayout();
			}
		}
		catch( Exception $e )
		{ 
			$this->_identifier[$this->getIdColumn()] = Ayoola_Page_Editor_Layout::getDefaultLayout();
		//	var_export( $this->_identifier );
		//	return false; 
		}
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->setViewContent( $this->showImages(), true );		
    } 
	
    /**
     * 
     * 
     */
	public function getImageFiles()
    {
		
	}
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function showImages()
    {		
		$directory = dirname( Ayoola_Loader::checkFile( $this->getFilename() ) );
		$files = array_unique( Ayoola_Doc::getFilesRecursive( $directory ) );
	//	var_export( $files );
	//	asort( $files );
		$data = array();
		$html = '<div class="pc-notify-info" style="text-align:center;">Click on any image to replace it! <a style="font-size:smaller;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Browser/">More in File Manager</a></div>';
		foreach( $files as $each )
		{
			$extension = array_pop( explode( ".", strtolower( $each ) ) );
			if( ! in_array( $extension, self::$_imageExtensions ) )
			{
				continue;
			}
			$uri = Ayoola_Doc::pathToUri( $each );
			//	var_export( $uri );
			$html .= '<a style="display:inline-block;xbackground:#fff;margin:10px;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Link/?image_url=' . $uri . '&crop=1\', \'page_refresh\' );" href="javascript:"><img alt="' . $uri . '" src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?max_width=100&max_height=60&url=' . $uri . '" ></a>';
		}
//		$data = self::sortMultiDimensionalArray( $data, 'filename' );
	//	$html .= ;

		return $html;
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
/* 	public function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$list->listTitle = self::getObjectTitle() . ': "' . $identifierData['layout_label'] . '"';
		
		
		$directory = dirname( Ayoola_Loader::checkFile( $this->getFilename() ) );
		$files = array_unique( Ayoola_Doc::getFilesRecursive( $directory ) );
//		var_export( $files );
	//	asort( $files );
		$data = array();
		foreach( $files as $each )
		{
			$extension = array_pop( explode( ".", strtolower( $each ) ) );
			switch( $extension )
			{
				case "jpg":
				case "jpeg":
				case "gif":
				case "png":
				case "bmp":
				case "ico":
				//	var_export( $extension );
				break;
				default:
					continue 2;
				break;
			}
			$uri = Ayoola_Doc::pathToUri( $each );
		//	var_export( $uri );
			$localPath = Ayoola_Loader::checkFile( DOCUMENTS_DIR . $uri );
			$data[] = array( 'path' => $uri, 'filename' => basename( $uri ), 'directory' => dirname( $uri ), 'full_path' => $each, 'local_path' => $localPath, 'my_own_copy' => $localPath == $each ? 'NO' : 'YES', );
		}
		$data = self::sortMultiDimensionalArray( $data, 'filename' );
		$list->setData( $data );
		$list->setKey( 'path' );  
	//	$list->setImagesOptions( array( 'Creator' => '<a class="goodnews" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Creator/" title="Create a new layout template">+</a>' ) );
		$list->setNoRecordMessage( 'No images in this theme.' );
		$list->createList(  
			array(
				'filename' => '<img alt="" src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?max_width=60&max_height=60&url=%KEY%" style="max-height:48px; vertical-align:middle;" > <br> %FIELD%  ',    
				'  ' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Link/?image_url=%KEY%&crop=1"> Replace </a>', 
				' ' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Delete/?uri=%KEY%"> X </a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
 */	
		
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
				$dir = DOCUMENTS_DIR . DS . 'layout' . DS . $defaultLayout . DS . 'template';
				$dir = dirname( Ayoola_Loader::checkFile( $dir ) );
				$files = array_unique( Ayoola_Doc::getFilesRecursive( $dir ) );
				$images = array();
				foreach( $files as $each )
				{
					$extension = array_pop( explode( ".", strtolower( $each ) ) );
					if( ! in_array( $extension, self::$_imageExtensions ) )
					{
						continue;
					}
					list( , $url ) = explode( '' . DS . 'application' . DS . 'documents' . DS . '', $each );
					$images[] = DS . $url;
				}
				if( $uploaded = Ayoola_Doc_Table::getInstance()->select( null, array( 'url' => $images ) ) )
				{
					$percentage += 100;
				}
			}
		}
	//	var_export( $uploaded );
	//	var_export( $files );
	//	var_export( $images );
 //   var_export( self::getUpdates() );
//    var_export( $themeInfoAll['dummy_search'] );
		return $percentage;
	}
	// END OF CLASS
}
