<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
		$files = array_unique( Ayoola_Doc::getFilesRecursive( $directory, array( 'whitelist_extension' => self::$_imageExtensions ) ) );
		
		//	Show files uploaded normally
		if( self::getPercentageCompleted() == 100 )
		{
		//	$uploadedFiles = Ayoola_Doc_Table::getInstance()->select();
		//	$uploadedFiles = array_unique( array_column( $uploadedFiles, 'url', 'url' ) );
		//	$files = array_unique( $uploadedFiles + $files );
		}
		//	var_export( $uploadedFiles );  
	//	var_export( $directory );
	//	var_export( $this->getFilename() );
	//	asort( $files );
		$dirForCheck = dirname( $this->getFilename() );

		$done = array();
		$html = '<div class="pc-notify-info" style="text-align:center;">Click on any image to replace it! <a style="font-size:smaller;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Browser/">More in File Manager</a></div>';
		foreach( $files as $each )
		{
			$extension = array_pop( explode( ".", strtolower( $each ) ) );
			if( ! in_array( $extension, self::$_imageExtensions ) )
			{
				continue;
            }
			$uri = Ayoola_Doc::pathToUri( $each );
			
			$eachFile = Ayoola_Doc::getDocumentsDirectory() . $uri;
			if( ! empty( $done[$uri] ) )
			{
				continue;
			}
			if( strpos( $eachFile, 'documents/layout/' ) && strpos( $eachFile, $dirForCheck ) === false )
			{
				//	don't show if we are of a different theme
				continue;
			}
			$done[$uri] = true;
			$html .= '<a style="display:inline-block;xbackground:#fff;margin:10px;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Link/?image_url=' . $uri . '&crop=1\', \'page_refresh\' );" href="javascript:"><img alt="' . $uri . '" src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?max_width=100&max_height=60&url=' . $uri . '" ></a>';
		}
		return $html;
    } 
		
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
                if( ! $files = array_unique( Ayoola_Doc::getFilesRecursive( $dir ) ) )
                {
                    return 100;
                }
				$images = array();
				foreach( $files as $each )
				{
					$extension = explode( ".", strtolower( $each ) );
					$extension = array_pop( $extension );
					if( ! in_array( $extension, self::$_imageExtensions ) )
					{
						continue;
					}
					list( , $url ) = explode( '' . DS . 'application' . DS . 'documents' . DS . '', $each );
					$images[] = DS . $url;
				}
				if( $uploaded = Ayoola_Doc_Table::getInstance()->select( null, array( 'url' => $images ) ) )
				{
					$percentage = 100;
				}
			}
        }
        if( empty( $images ) )
        {
            $percentage = 100;
        }
		return $percentage;
	}
	// END OF CLASS
}
