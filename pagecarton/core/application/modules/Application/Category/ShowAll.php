<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_Category_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Category_Abstract
 */
 
require_once 'Application/Category/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_Category_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Category_ShowAll extends Application_Article_ShowAll
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Site Categories'; 

    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;

    /**
     * Post Categories
     *
     * @var array
     */
	protected static $_postCategories;
	
		
    /**
     * The method does the whole Class Process
     * 
     */
	public static function getChildren( $categoryId )
    {
 		//	Choose the kind of categories to show
		$children = array();
	   if( $categoryId )
		{
			switch( gettype($categoryId ) )
			{
				case 'array':
				//	Do nothing
				break;
				case 'string':
					$categoryId = array_map( 'trim', explode( ',', $categoryId ) );
				default:
				break;
			}
			//	Categories
			$table = Application_Category::getInstance();
			
			if( $categoryInfo = $table->select( null, array( 'category_name' => @$categoryId ) ) )
			{
				//	self::v( $categoryInfo );
				foreach( $categoryInfo as $each )
				{
					$children += (array) $each['child_category_name'];
				}
				
			}
		//	self::v( $categoryInfo );
			
			if( $categoryInfo = $table->select( null, array( 'parent_category_name' => $categoryId ) ) )
			{
				foreach( $categoryInfo as $each )
				{
					$children[] = $each['category_name'];
				}
			}
			
			if( $categoryInfo = $table->select( null, array( 'parent_category' => $categoryId ) ) )
			{
				foreach( $categoryInfo as $each )
				{
					$children[] = $each['category_name'];
				}
			}
	//		self::v( $categoryInfo );
	//		self::v( $table->select() );

		}
		return $children;
    } 
		
    /**
     * The method does the whole Class Process
     * 
     */
	public function setDbData( array $data = null )
    {
		@$categoryId = $this->getParameter( 'category' ) ? : $categoryId;
		@$categoryId = $this->getParameter( 'category_id' ) ? : $categoryId;
		@$categoryId = $this->getParameter( 'category_name' ) ? : $categoryId;
		if( is_numeric( $this->getParameter( 'pc_module_url_values_parent_category_offset' ) ) )
		{
			if( @array_key_exists( $this->getParameter( 'pc_module_url_values_parent_category_offset' ), $_REQUEST['pc_module_url_values'] ) )
			{
				$categoryId = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_parent_category_offset' ) )];
			}
		}
		//	switch templates off
	//	$this->_parameter['markup_template'] = null; 
		if( ! $children = self::getChildren( $categoryId ) )
		{
			$this->_dbWhereClause['parent_category'] = null;
		}
		else
		{
			$this->_dbWhereClause['category_name'] = $children;
		}
		$data = Application_Category::getInstance()->select( null, $this->_dbWhereClause );
		$this->_dbData = $data;
    } 
			
    /**
     * 
     */
	public static function sanitizeData( &$data )
    {
	//	var_export( $data );
		$data['not_real_post'] = true; 
		$data['document_url'] = $data['cover_photo'];
		$data['article_title'] = $data['category_label'] ? : $data['category_name']; 
		$storage = self::getObjectStorage( array( 'id' => 'total_no_of_posts' . $data['category_name'], 'device' => 'File', 'time_out' => 94600, ) );
		$data['total_no_of_posts'] = $storage->retrieve();
		if( ! is_numeric( $data['total_no_of_posts'] ) )
		{

			$data['total_no_of_posts'] = Application_Article_Table::getInstance()->select( null, array( 'category_name' => $data['category_name'] ) );
	//		var_export( $data['total_no_of_posts'] );
			$data['total_no_of_posts'] = count( $data['total_no_of_posts'] );
			$storage->store( $data['total_no_of_posts'] );
		}
		$data['total_no_of_posts'] = $data['total_no_of_posts'] ? : 0; 
		if( ! $data['article_title'] )
		{
			$data = false;
		}
		$data['article_url'] = '' . self::getPostUrl() . '?category=' . $data['category_name']; 
		$data['article_description'] = $data['category_description']; 
		$data['publish'] = '1'; 
		$data['auth_level'] = '0';   
	//	$data['allow_raw_data'] = true; 
	}

     /**
     * This method returns the _classOptions property 
     *
     * @param void
     * @return array
     */
    public static function getPostCategories()
    {
		if( null === self::$_postCategories )
		{
			//	Defaults to all categories available
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			$options = new Application_Category;
	////		var_export( $articleSettings );
			if( $articleSettings['allowed_categories'] )
			{
				if( ! self::$_postCategories = $options->select( null, array( 'category_name' => $articleSettings['allowed_categories'] ) ) )
				{
					self::$_postCategories = array();
				}
			}
			else
			{
				if( ! self::$_postCategories = $options->select() )
				{
					self::$_postCategories = array();
				}
			}
		//	self::$_postCategories$options;

		}
		return self::$_postCategories;
	}
	// END OF CLASS
}
