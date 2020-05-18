<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $ 
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Page_Creator extends Ayoola_Page_Abstract
{
	
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Create a page'; 
	
    /**
     * Attempts to delete a file
     *
     * @param void
     * @return null
     */
    protected function _rollbackFile()
    {
		$files = $this->getPageFilesPaths();
		foreach( $files as $file )
		{
			if( is_file( $file ) )
			{
                Ayoola_File::trash( $file );
				Ayoola_Doc::removeDirectory( basename( $file ) );
			}
		}
    } 
	
    /**
     * Attempts to delete a record from the db
     *
     * @param void
     * @return mixed
     */
    public function _rollbackDb()
    {
		$table = $this->getDbTable();
		$values = $this->_form->getValues();
		
		return $table->delete( array( 'url' => $values['url'] ) );
		
    } 
	
    /**
     * Attempts to clean-up the process incase something goes wrong along the line
     * So as to create a clean error free interface 
     * 
     * @param void
     * @return boolean
     */
    public function rollback()
    {
        // DB
		$this->_rollbackDb();
		$this->_rollbackFile();
    } 
	
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
			$this->createForm( 'Continue..', 'Create a new page' );
			$this->setViewContent( $this->getForm()->view() );
		//	self::v( $_POST );
			if( ! $values = $this->getForm()->getValues() OR ! $values['url'] ){ return false; }
        //    self::v( $this->getForm()->getbadnews() );
            
			//	Default settings 
			$values['auth_level'] = (array) ( isset( $values['auth_level'] ) ? $values['auth_level'] : 0 );
		//	self::v( $values );
		//	return false;
		
			if( empty( $values['system'] ) )
			{
				//	Notify Admin
				$mailInfo = array();
				$mailInfo['subject'] = 'A new page created';
				$mailInfo['body'] = 'A new page have been created on your application with the following information: "' . self::arrayToString( $values ) . '". 
				
				Preview the page on: http://' . Ayoola_Page::getDefaultDomain() . Ayoola_Application::getUrlPrefix() . $values['url'] . '';
				try
				{
			//		var_export( $mailInfo );
					@Ayoola_Application_Notification::mail( $mailInfo );
				}
				catch( Ayoola_Exception $e ){ null; }
			}
			
			self::resetCacheForPage( $values['url'] ); 
			$isLayoutPage = stripos( $values['url'], '/layout/' ) === 0;
			if( $isLayoutPage )
			{
				//	Only admin should be able to view template files
				$values['auth_level'] = array( 99 );
			}
			if( ! $this->insertDb( $values ) ){ return false; }
			
			//	let's allow only page editor create this files
			//	the themes are also created this way... we don't want that
	
				//	once page is created, let's have blank content
				$page = new Ayoola_Page_Editor_Sanitize();
				$page->refresh( $values['url'] );

				$this->setViewContent( self::__( '<p class="goodnews">Page created successfully. It is not yet accessible until you add content.</p>' ), true  );   
				$this->setViewContent( self::__( '<p>
																		<a target="_blank" class="pc-btn" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor_Layout/?url=' . $values['url'] . '">Add Content <i class="fa fa-edit pc_give_space"></i></a>
																		<a target="_blank" class="pc-btn"" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Menu_Edit_Creator/?url=' . $values['url'] . '"> Add to site navigation <i class="fa fa-cog pc_give_space"></i></a>
																		<a target="_blank" class="pc-btn"" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor/?url=' . $values['url'] . '"> Settings <i class="fa fa-cog pc_give_space"></i></a>
				</p>' ) ); 
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true  );
		}
    } 
	
    /**
     * 
     * @param array
     * @return string
     */
    public static function getLayoutTemplateFilePath( array $pageInfo )
    {
		require_once 'Ayoola/Filter/LayoutIdToPath.php';
		$filter = new Ayoola_Filter_LayoutIdToPath;
		if( ! Ayoola_Loader::checkFile( @$pageInfo['pagelayout_filename'] ) )	//	Compatibility
		{
			$pageInfo['pagelayout_filename'] = $filter->filter( $pageInfo['layout_name'] );
		}
		if( ! $filePath = Ayoola_Loader::checkFile( $pageInfo['pagelayout_filename'] ) )
		{ 
			if( $defaultLayout = Application_Settings_Abstract::getSettings( 'Page', 'default_layout' ) )
			{
				$filePath = Ayoola_Loader::checkFile( $filter->filter( $defaultLayout ) );
			}
		}
		return $filePath;
	}
 	
    /**
     * Creates the file
     * For the page and for template
     * @param void
     * @return boolean
     */
    public function _createFile()
    {
		//	creates the the page data file
	//	if( ! $this->_createXml( true ) ){ return false; }
	//	if( ! $this->insertDb() ){ return false; }
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$table = Ayoola_Page_Page::getInstance();
		$pageInfo = $table->selectOne( null, array( 'url' => $values['url'] ) );

		//	var_export( $pageInfo );
		$pageInfo['pagelayout_filename'] = self::getLayoutTemplateFilePath( $pageInfo );
		$default = self::getDefaultPageFiles( @$values['default_url'] );
		
		if( ! @$values['default_url'] )
		{
			//	if we are not cloning, use the template instead.
			$default['template'] = $pageInfo['pagelayout_filename'] ? : $default['template'];
			
			//	clear all placeholders
			$default['template'] = preg_replace( '/{?@@@([.]*)@@@}?/', '', $default['template'] );   
		}
	//	$values['default_url'] = $values['default_url'] == '/' ? '' : $values['default_url'];
		$files = $this->getPageFilesPaths();
	//	var_export( $pageInfo );
	//	var_export( $values );
	//	var_export( $default );
		require_once 'Ayoola/Loader.php';  
		foreach( $files as $key => $file )
		{			
			//	Create the Directory
			Ayoola_Doc::createDirectory( dirname( $file ) );
			if( $filePath = Ayoola_Loader::checkFile( $default[$key] ) ){ $default[$key] = $filePath; }
			if( ! is_file( $file ) )
			{
				if( ! Ayoola_File::putContents( $file, preg_replace( '/{?[%@]{2,3}([a-zA-Z1-9]{3,18})[%@]{2,3}}?/', '', @file_get_contents( $default[$key] ) ) ) )
				{
					// If copying fail, open new file
					if( false === Ayoola_File::putContents( $file, '' ) )
					{						
						// Attempts a rollback
						$this->rollback();
						return false;
					}
				}
			}
		
		}
		return true;
	} 
}
