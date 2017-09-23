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
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try{ $this->setIdentifier(); }
		catch( Ayoola_Page_Layout_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->setViewContent( $this->getList(), true );		
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
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$list->listTitle = self::getObjectTitle() . ': "' . $identifierData['layout_label'] . '"';
		
		
		$directory = dirname( Ayoola_Loader::checkFile( $this->getFilename() ) );
	//	var_export( $directory );
	//	var_export( Ayoola_Doc::getFilesRecursive( $directory ) );
		
		//	Sort to remove files that are not images
/* 		$sortFunction = create_function
		( 
			'$filePath', 
			'
			$extension = array_pop( explode( ".", strtolower( $filePath ) ) );
			switch( $extension )
			{
				case "jpg":
				case "jpeg":
				case "gif":
				case "png":
				case "bmp":
					
				//	var_export( $extension );
					
					return $filePath;
				break;
				default:
					return false;
				break;
				return false;
				
			}
			'
		); 
 */		
	//	$files = Ayoola_Doc::getFilesRecursive( $directory, array( 'key_function' => $sortFunction ) );
		$files = Ayoola_Doc::getFilesRecursive( $directory );
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
				'filename' => '<img alt="" src="' . Ayoola_Application::getUrlPrefix() . '%KEY%" style="max-height:48px; vertical-align:middle;" > <br> %FIELD%  ',    
		//		'full_path' => '%FIELD%',      
		//		'local_path' => '%FIELD%',    
		//		'my_own_copy' => '%FIELD%',    
				'  ' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Link/?image_url=%KEY%"> Replace </a>', 
				' ' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Delete/?uri=%KEY%"> X </a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
