<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_LayoutIdToPath
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: LayoutIdToPath.php 10-25-2011 3.25pm ayoola $
 */

/**
 * @see Ayoola_Filter_Interface
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_LayoutIdToPath
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
 
class Ayoola_Filter_LayoutIdToPath implements Ayoola_Filter_Interface
{

    /**
     * 
     *
     * @var array
     */
	protected $_pageInfo;

    public function __construct( $pageInfo = null)
	{
		$this->_pageInfo = $pageInfo;
	}

    public function filter( $value )
	{
		$table = new Ayoola_Page_PageLayout();
		
		//	Integer value means a layout ID is sent, otherwise Layout Name
	//		var_export( $checkInt );
		if( is_numeric( $value ) )
		{
			
		//	var_export( $value );
			$value = $table->selectOne( null, array( 'pagelayout_id' => $value ) );
			@$value = $value['layout_name'];
		//	var_export( $value );
		}
 		else
		{
		//	$value = $value ? : 'default';
		//	var_export( $value );
			$isLayoutPage = stripos( $this->_pageInfo['url'], '/layout/' );
			if( $isLayoutPage === 0 )
			{
				
				list(  ,$value ) = explode( '/', trim( $this->_pageInfo['url'], '/' ) );
			//	var_export( $value );
			}
		
			$dir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS;
			$layoutData = $table->selectOne( null, array( 'layout_name' => $value ) );
		//	var_export( $layoutData );
			
			//	Allow a template editor that is similar to a page editor by allowing a page "/layout/$layoutName/template
			$url = '/layout/' . strtolower( @$layoutData['layout_name'] ) . '/template';
			
			$pagePaths = Ayoola_Page::getPagePaths( $url );
			$PAGE_INCLUDE_FILE = Ayoola_Loader::checkFile( $pagePaths['include'] );
			$PAGE_TEMPLATE_FILE = Ayoola_Loader::checkFile( $pagePaths['template'] );
			
			if( $PAGE_INCLUDE_FILE && $PAGE_TEMPLATE_FILE && $this->_pageInfo && $isLayoutPage !== 0 )
			{
				$temIncludeFile = $dir . $pagePaths['include'] . '.tmp';
			//	if( ! is_file( $temIncludeFile ) )
				{
					//	Must always refresh because we are using file_get_contents on file that could change
					//	Must include the include file because there is no closing php tag
					file_put_contents( $temIncludeFile, "<?php include_once '{$PAGE_INCLUDE_FILE}'; ?>" . ' ' . file_get_contents( $PAGE_TEMPLATE_FILE ) );
				}
				$value = $temIncludeFile;     
				return $value;
			}
/* 			elseif( $isLayoutPage === 0 )
			{
				
				list(  ,$value ) = explode( '/', trim( $this->_pageInfo['url'], '/' ) );
			//	var_export( $value );
			}
 */			if( ! is_file( $dir . @$layoutData['pagelayout_filename'] ) )
			{
				//	Use the parent layout file
				if( ! $value ){ return false; }
				//	var_export( $value );
				$class = new Ayoola_Page_Layout_Creator();
				$class->setFilename( array( 'layout_name' => $value ) );
				$value = $class->getFilename();
			//	var_export( $value );
				
			}
			else
			{
				//	compatibility
				$value = $layoutData['pagelayout_filename'];
			}
		//	var_export( $this->_pageInfo['url'] );
		//	var_export( $url );
		}
		
	//	var_export( $value );
		return $value;
	}
 
}
