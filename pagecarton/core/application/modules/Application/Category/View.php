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
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'View Category Info';      
	
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
			$this->_objectTemplateValues = array();
			$parentCategory = $this->getParameter( 'parent_category' );
			if( is_numeric( $this->getParameter( 'pc_module_url_values_parent_category_offset' ) ) )
			{
				if( @array_key_exists( $this->getParameter( 'pc_module_url_values_parent_category_offset' ), $_REQUEST['pc_module_url_values'] ) )
				{
					$parentCategory = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_parent_category_offset' ) )];
				}
			}
			if( ! $data = self::getIdentifierData() )
			{ 
			//	var_export( $data );
				if( ! self::hasPriviledge( array( 98 ) ) )
				{
				//	var_export( $data );
					$this->_parameter['markup_template'] = null;
					return false; 
				}
				else
				{
				//	;
				$category = $this->getParameter( 'category_name' );
		//		if( $this->getParameter( 'allow_dynamic_category_selection' ) )
				{
					if( is_numeric( $this->getParameter( 'pc_module_url_values_category_offset' ) ) )
					{
						if( @array_key_exists( $this->getParameter( 'pc_module_url_values_category_offset' ), $_REQUEST['pc_module_url_values'] ) )
						{
							$category = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_category_offset' ) )];
							if( $category == 'category' )
							{
								$category = @$_REQUEST['category'];
							}

						}
						elseif( $this->getParameter( 'pc_module_url_values_request_fallback' ) && @$_REQUEST['category'] )
						{
							//	Allow request to define value
							$category = $_REQUEST['category'];
						}
					//	var_export( $category );
					}
					elseif( $this->getParameter( 'allow_dynamic_category_selection' ) && @$_REQUEST['category'] )
					{
						$category = $_REQUEST['category'];
					}
					if( is_numeric( $this->getParameter( 'pc_module_url_values_post_type_offset' ) ) && @array_key_exists( $this->getParameter( 'pc_module_url_values_post_type_offset' ), $_REQUEST['pc_module_url_values'] ) )
					{
						$postType = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_post_type_offset' ) )];
					//	var_export( $category );
					}
					elseif( @$_REQUEST['post_type'] )
					{
						$postType = $_REQUEST['post_type'];
					}
				}
			//	var_export( $this->getParameter( 'pc_module_url_values_category_offset' ) );
			//	var_export( $this->getParameter( 'parent_category' ) );
					
					if( $category )
					{
						$data = array( 
										'category_label' => '"' . ucfirst( $category ) . '" category not found', 
										'category_name' => $category, 
										'post_type' => @$postType, 
										'category_description' => '', 
										'category_url' => '' . Ayoola_Application::getUrlPrefix() . '/object/name/Application_Category_Editor/?category_name=' . $category . '&auto_create_category=1&parent_category=' . $parentCategory,  
										);
						$data['auto_create_link'] = '<a href="' . $data['category_url'] . '">Create "' . $category . '" category!</a>';
						$data['update_url'] = $data['category_url'];
						$data['delete_url'] = 'javascript:';
					}
					else
					{
					//	var_export( $data );
						$this->_parameter['markup_template'] = null;
						return false; 
					}
				}
			}
			else
			{
				if( self::hasPriviledge( array( 98 ) ) )
				{

					$updateLink = '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_Editor/category_name/' . $data['category_name'] . '/?&parent_category=' . $parentCategory . '';
					$deleteLink = '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_Delete/category_name/' . $data['category_name'] . '/';
					$adminOptions .= '<span class="" >
											<a class="" title="Edit ' . $data['category_label'] . '" rel="spotlight;" href="' . $updateLink . '"> Update Category Info </a> | 
											<a class="" title="Delete ' . $data['category_label'] . '" rel="spotlight;" href="' . $deleteLink . '/"> Delete Category </a>
										</span>';
					$this->_objectData['update_url'] = $updateLink;
					$this->_objectData['delete_url'] = $deleteLink;
					$this->_objectData['edit_link'] = $adminOptions;
					$data['update_url'] = $updateLink;
					$data['delete_url'] = $deleteLink;
					$data['edit_link'] = $adminOptions;
					
			//		$data['category_description'] = 
				}
			} 
	//		var_export( $data );  
			$data['category_label'] = $data['category_label'] ? : ucwords( $data['category_name'] );
			$data['category_url'] = @$data['category_url'] ? : '' . Application_Article_Abstract::getPostUrl() . '/category/' . $data['category_name'] . '/'; 
			$html .= '<h1><a title="' . $data['category_label'] . '" href="' . $data['category_url'] . '">' . $data['category_label'] . '</a>' . $adminOptions . '</h1>
			
			';
		//	var_export( $data );
	//		$this->_objectTemplateValues['category_url'] = 
		//	if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['cover_photo'] ) )
			{
		//		$data['cover_photo'] = $image;
				$data['cover_photo_html'] = '<img style="max-width:100%;" src="' . $image . '" alt="" title="Cover photo for ' . $data['category_label'] . '" />';
				$photoWithLink = 	'<a title="' . $data['category_label'] . '" href="' . $data['category_url'] . '">
								' . $data['cover_photo_html'] . '
							</a>';
				$html .= $photoWithLink;
				$this->_objectTemplateValues['cover_photo_with_link'] = $photoWithLink;   
							
			}
			
			//	Add the category to title and description?
			if( $this->getParameter( 'build_meta_data' ) )
			{
				$pageInfo = array(
					'description' => Ayoola_Page::getCurrentPageInfo( 'description' ) . $data['category_description'] ,
					'title' => trim( $data['category_label'] . ' ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
				);
				//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
				Ayoola_Page::setCurrentPageInfo( $pageInfo );
			}
			$html .= $data['category_description'] ? '<blockquote><p>' . $data['category_description'] . '</p></blockquote>' : null;


            $html = '<div  class="pc_theme_parallax_background" style="background:     linear-gradient(      rgba(0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url(\'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?url=' . ( $data['cover_photo'] ) . '\');  ">';
            $html .= $this->getParameter( 'css_class_of_inner_content' ) ? '<div class="' . $this->getParameter( 'css_class_of_inner_content' ) . '">' : null;
            $html .= '<h1>' . $data['category_label'] . '</h1>';
            $html .= $data['category_description'] ? '<br><br><p>' . $data['category_description'] . '</p>' : null;
            $html .= self::hasPriviledge( array( 99, 98 ) ) ? '<br><br><p style="font-size:x-small;">
			<a  style="color:inherit;text-transform:uppercase;" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $data['update_url']  . '\', \'page_refresh\' );" href="javascript:">[Update Category]</a>
			<a  style="color:inherit;text-transform:uppercase;" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $data['delete_url']  . '\', \'page_refresh\' );" href="javascript:">[Delete Category]</a>
			
			</p>' : null;
            $html .= $this->getParameter( 'css_class_of_inner_content' ) ? '</div>' : null;
            $html .= '</div>';
   //         $this->setViewContent( $html ); 
			

			$this->setViewContent( $html, true );
			$this->_objectTemplateValues += $data;
		}
		catch( Application_Category_Exception $e )
		{ 
			return false; 
		}
    } 
    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData( $identifier = NULL )
    {
		$category = $this->getParameter( 'category_name' );
		$data = array(); 
	//	var_export( $this->getParameter( 'allow_dynamic_category_selection' ) );
	//	var_export( $this->getParameter( 'pc_module_url_values_category_offset' ) );

//		if( $this->getParameter( 'allow_dynamic_category_selection' ) )
		{
			if( is_numeric( $this->getParameter( 'pc_module_url_values_category_offset' ) ) )
			{
				if( @array_key_exists( $this->getParameter( 'pc_module_url_values_category_offset' ), $_REQUEST['pc_module_url_values'] ) )
				{
					$category = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_category_offset' ) )];
					if( $category == 'category' )
					{
						$category = @$_REQUEST['category'];
					}
//		var_export( $category );
				}
				elseif( $this->getParameter( 'pc_module_url_values_request_fallback' ) && @$_REQUEST['category'] )
				{
					//	Allow request to define value
					$category = $_REQUEST['category'];
				}
			//	var_export( $category );
			}
			elseif( $this->getParameter( 'allow_dynamic_category_selection' ) && @$_REQUEST['category'] )
			{
				$category = $_REQUEST['category'];
			}
			if( is_numeric( $this->getParameter( 'pc_module_url_values_post_type_offset' ) ) )
			{
				if( @array_key_exists( $this->getParameter( 'pc_module_url_values_post_type_offset' ), $_REQUEST['pc_module_url_values'] ) )
				{
					$postType = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_post_type_offset' ) )];
				}
			//	var_export( $postType ); 
			}
			elseif( @$_REQUEST['post_type'] )
			{
				$postType = $_REQUEST['post_type'];
			}
		}
		if( ! $category )
		{

		}
		else
		{
			$data = $this->getDbTable()->selectOne( null, array( 'category_name' => $category ) ) ? : array();
	//	var_export( $data );
			
			if( $data && @$postType )
			{
				@$data['post_type'] = $postType;  
			}
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
		$options = array( '' => 'Select Category' ) + $filter->filter( $options );
		$html .= '<span style=""> Show Category Info of </span>';
		
		$html .= '<select data-parameter_name="category_name">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['category_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> or Dectect from </span>';
		
		$html .= '<select data-parameter_name="pc_module_url_values_category_offset">';
		$options = range( 0, 5 ) ;
		foreach( $options as $key => $value )
		{ 
			$options[$key] = 'URL Offset ' . $value;
		}
	//	$firstOptions = 	
		$options[''] = 'Select URL offset';
		$options['?'] = 'Query Strings';
		ksort( $options );  

		if( @$object['allow_dynamic_category_selection'] && ! isset( $object['pc_module_url_values_category_offset'] ) )
		{
			$object['pc_module_url_values_category_offset'] = '?';
		}
	//	$html .= '<option value="">Select URL offset</option>';
	//	$html .= '<option value="?">Query Strings</option>';
		foreach( $options as $key => $value )
		{ 
	//		$value = 'URL Offset ' . $value;
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['pc_module_url_values_category_offset'] === strval( $key ) ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';

		return $html;
	}
	// END OF CLASS
}
