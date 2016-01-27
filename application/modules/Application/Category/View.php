<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Category_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Category_Abstract
 */
 
require_once 'Application/Category/Abstract.php';   


/**
 * @category   PageCarton CMS
 * @package    Application_Category_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Category_View extends Application_Category_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'category' );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
		//	var_export( $data );
			$html = null;
			$adminOptions = null;
	//		include_once 'D:\\Documents\\Avalanche\\Jamb Questions\\AutomationCode.php';
			$this->_objectTemplateValues = array();
			if( ! $data = self::getIdentifierData() )
			{ 
				if( ! self::hasPriviledge() )
				{
				//	var_export( $data );
					return false; 
				}
				elseif( $categoryName = ( @$_REQUEST['category'] ? : $this->getParameter( 'category' ) ) )
				{
				//	;
				//	var_export( $data );
					
					$data = array( 
									'category_label' => 'Click to create "' . $categoryName . '" category', 
									'category_name' => $categoryName, 
									'category_description' => '', 
									'category_url' => '/object/name/Application_Category_Editor/?category_name=' . $categoryName . '&auto_create_category=1', 
									);
				}
				else
				{
				//	var_export( $data );
					return false; 
				}
			}
			else
			{
				if( self::hasPriviledge() )
				{
					$adminOptions .= '<span class="boxednews greynews" >
										<a class="" title="Add a new sub category" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_Creator/parent_category_name/' . $data['category_name'] . '/"> + </a> 
										<a class="badnews" title="Edit ' . $data['category_label'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_Editor/category_name/' . $data['category_name'] . '/"> - </a>
										<a class="badnews" title="Delete ' . $data['category_label'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_Delete/category_name/' . $data['category_name'] . '/"> x </a>
									</span>';
					$this->_objectData['edit_link'] = $adminOptions;
					$this->_objectTemplateValues['edit_link'] = $adminOptions;
				}
			} 
	//		var_export( $data );  
			$data['category_url'] = @$data['category_url'] ? : '' . Application_Article_Abstract::getPostUrl() . '/category/' . $data['category_name'] . '/'; 
			$html .= '<h1><a title="' . $data['category_label'] . '" href="' . $data['category_url'] . '">' . $data['category_label'] . '</a>' . $adminOptions . '</h1>
			
			';
		//	var_export( $data );
	//		$this->_objectTemplateValues['category_url'] = 
			if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['cover_photo'] ) )
			{
				$data['cover_photo'] = $image;
				$photoWithLink = 	'<a title="' . $data['category_label'] . '" href="' . $data['category_url'] . '">
								<img style="max-width:100%;" src="' . $image . '" alt="" title="Cover photo for ' . $data['category_label'] . '" />
							</a>';
				$html .= $photoWithLink;
				$this->_objectTemplateValues['cover_photo_with_link'] = $photoWithLink; 
							
			}
			$html .= $data['category_description'] ? '<blockquote><p>' . $data['category_description'] . '</p></blockquote>' : null;
			$this->setViewContent( $html, true );
			$this->_objectTemplateValues += $data;
		}
		catch( Application_Category_Exception $e ){ return false; }
    } 
    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData()
    {
		$category = $this->getParameter( 'category_name' );
		if( $this->getParameter( 'allow_dynamic_category_selection' ) && @$_REQUEST['category'] )
		{
			$category = $_REQUEST['category'];
		}
		$data = array(); 
/* 		var_export( $this->getParameter( 'allow_dynamic_category_selection' ) );
		var_export( $_REQUEST['category'] );
		var_export( $category );
 */		if( ! $category )
		{
			//	Showing Random Info from allowed categories from POST   
/* 			$class = new Application_Article_Category();
			$data = $class->getPublicDbData();
			$data = $data[array_rand( $data )];
 */		}
		else
		{
			$data = $this->getDbTable()->selectOne( null, array( 'category_name' => $category ) );
		}
		$this->_identifierData = $data;
    } 
	
    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		@$object['option'] = $object['option'] ? : $object['view_option'];
	//	$html .= "<span data-parameter_name='view' >{$object['view']}</span>";
		
		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object
	//	var_export( $object );
		$options = new Application_Category;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
		$options = array( 0 => '*Random*' ) + $filter->filter( $options );
		$html .= '<span style=""> Show info of </span>';
		
		$html .= '<select data-parameter_name="category_name">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['category_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> category. </span>';
		$html .= '<label style=""> Allow dynamic selection: </label>';
		
		$html .= '<select data-parameter_name="allow_dynamic_category_selection">';
		$options = array( 'No', 'Yes' );
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['allow_dynamic_category_selection'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		if( static::$_editableTitle )
		{
			$html .= '<a href="javascript:;" title="' . static::$_editableTitle . '" onclick="ayoola.div.makeEditable( this.nextSibling ); this.nextSibling.style.display=\'block\';"> edit </a>';
				//	var_export( $object );
			$html .= '<span data-parameter_name="editable" style="padding:1em;display:none;" onclick="this.nextSibling.style.display=\'block\';">' . @$object['editable'] . '</span>';
			$html .= '<a href="javascript:;" style="display:none;" title="' . static::$_editableTitle . '" onclick="this.previousSibling.style.display=\'none\';this.style.display=\'none\';"> hide </a>';
		}
		$html .= '<button onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Category_List/\' );">Manage Categories</button>';
		return $html;
	}
	// END OF CLASS
}
