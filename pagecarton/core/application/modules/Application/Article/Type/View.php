<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Type_View
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php Monday 27th of November 2017 01:11PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Article_Type_View extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'View Article-Type Information'; 

	
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
			if( ! $data = self::getIdentifierData() )
			{ 

            }
            @$data['category_label'] = $data['post_type'] ? : $data['category_label'];
            $html = '<div  class="pc_theme_parallax_background" style="background:     linear-gradient(      rgba(0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url(\'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?url=' . ( $data['cover_photo'] ) . '\');  ">';
            $html .= '<h1>' . $data['category_label'] . '</h1>';
            $html .= $data['category_description'] ? '<br><br><p>' . $data['category_description'] . '</p>' : null;
            $html .= self::hasPriviledge( array( 99, 98 ) ) ? '<br><br><p style="font-size:x-small;">
			<a  style="color:inherit;text-transform:uppercase;" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $data['update_url']  . '\', \'page_refresh\' );" href="javascript:">[Update Category]</a>
			<a  style="color:inherit;text-transform:uppercase;" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $data['delete_url']  . '\', \'page_refresh\' );" href="javascript:">[Delete Category]</a>
			
			</p>' : null;
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
	public function setIdentifierData()
    {
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
        elseif( $this->getParameter( 'post_type' ) )
        {
            $postType = $this->getParameter( 'post_type' );
        }
		
		if( empty( $postType ) )
		{

		}
		else
		{
		    $table = Application_Article_Type::getInstance();
			$data = $table->selectOne( null, array( 'post_type_id' => $postType ) ) ? : array();
            if( ! empty( $data['post_type_id'] ) )
            {
                $table = Application_Category();
                $categoryInfo = $table->selectOne( null, array( 'category_name' => $data['post_type_id'] ) ) ? : array();
                $data += $categoryInfo;
            }

		    $this->_identifierData = $data;
	//	var_export( $data );
		}
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
		$options = new Application_Article_Type;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'post_type_id', 'post_type');
		$options = array( '' => 'Select Post Type' ) + $filter->filter( $options );
		$html .= '<span style=""> Show Category Info of </span>';
		
		$html .= '<select data-parameter_name="post_type">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['post_type'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> or Dectect from </span>';
		
		$html .= '<select data-parameter_name="pc_module_url_values_post_type_offset">';
		$options = range( 0, 5 ) ;
		foreach( $options as $key => $value )
		{ 
			$options[$key] = 'URL Offset ' . $value;
		}
	//	$firstOptions = 	
		$options[''] = 'Select URL offset';
		$options['?'] = 'Query Strings';
		ksort( $options );  
		if( @$object['allow_dynamic_category_selection'] )
		{
			$object['pc_module_url_values_post_type_offset'] = '?';
		}
	//	$html .= '<option value="">Select URL offset</option>';
	//	$html .= '<option value="?">Query Strings</option>';
		foreach( $options as $key => $value )
		{ 
	//		$value = 'URL Offset ' . $value;
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['pc_module_url_values_post_type_offset'] === strval( $key ) ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		return $html;
	}
	// END OF CLASS
}
