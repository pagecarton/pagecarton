<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Type_Exception 
 */
 
require_once 'Application/Article/Exception.php';
  

/**
 * @category   PageCarton
 * @package    Application_Article_Type_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Article_Type_Abstract extends Application_Article_Abstract
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'post_type_id' );
	
    /**
     * Id Column
     * 
     * @var string
     */
	protected $_idColumn = 'post_type_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Article_Type';
		
    /**
     * Form to display Download
     * 
     */
	public function getDownloadContent( $data )
    {
		foreach( self::getHooks() as $class )
		{
			$class::hook( $this, __FUNCTION__, $data );
		}

		if( ! self::isDownloadable( $data ) )
		{
			return false;
		}

		//	Download
		if( @$data['download_url'] )
		{
			if( $data['download_url'][0] === '/' )
			{
				//	this is still a local file we can load with Ayoola_Doc
				$path =  $data['download_url'];
			}
			else
			{
				header( 'Location: ' . $data['download_url'] );
				exit();
			}
		}
		elseif( @$data['download_path'] )
		{
			$path = APPLICATION_DIR . $data['download_path'];
		//	self::v( $path );
		}
		elseif( @$data['download_base64'] )
		{
			$result = self::splitBase64Data( $data['download_base64'] );
			
			//	https://chrisjean.com/generating-mime-type-in-php-is-not-magic/
			if ( function_exists( 'finfo_open' ) && function_exists( 'finfo_file' ) && function_exists( 'finfo_close' ) ) 
			{
				$f = finfo_open();
				$type = finfo_buffer( $f, $result['data'], FILEINFO_MIME_TYPE );
			}
			elseif ( function_exists( 'getimagesizefromstring' ) ) 
			{
				$fileInfo = getimagesizefromstring( $result['data'] );
				$type = $fileInfo['mime'];
			}
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '-';
			$customName = substr( trim( $filter->filter( @$data['display_name'] . '_' . $data['article_title'] ) , '-_' ), 0, 70 ) . '.' . array_pop( explode( '/', $type ) );
			
			header('Content-Description: File Transfer');
			header( 'Content-Type: ' . $type );
			header( 'Content-Disposition: attachment; filename=' . $customName );
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		//	header( 'Content-Length: ' . strlen( $result['data'] ) );
			ob_clean();
			flush();
			echo $result['data'];
		}
		//	Handle encryption
		switch( @$_SERVER['HTTP_AYOOLA_PLAY_MODE'] ) 
		{
			case 'ENCRYPTION':
			case 'JSON':
			//	$this->_objectData = $data; 
			break;
			default:
				if( @$path )
				{
				//	var_export( $path );
					$document = new Ayoola_Doc( array( 'option' => $path ) ); 
					$document->download();
				}
				exit();
			break;
		}
	}

    /**
     * 
     * param string Post Type to checj
     * return array Default Values
     */
	public static function getOriginalPostTypeInfo( $postType ) 
    {
		$table = Application_Article_Type::getInstance();
		if( $postTypeInfo = $table->selectOne( null, array( 'post_type_id' => $postType ) ) )
		{
			return $postTypeInfo;
		}
		return false;
	}
	// END OF CLASS
}
