<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Slideshow_Abstract  
 */
 
require_once 'Application/Slideshow/Abstract.php';


/**
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Slideshow_View extends Application_Slideshow_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init() 
    {
		try
		{ 

		//	self::v( $this->getParameter() );
			$this->_createDefaultSlideshow();
		//	var_export( $html );
		//	if( ! $data = self::getIdentifierData() ){ return false; }
			if( ! $data = self::getIdentifierData() )
			{ 
			//	var_export( $data );
				$this->_parameter['markup_template'] = null;
				return false; 
			}
		//	var_export( $data );
			if( empty( $data['slideshow_name'] ) )
			{ 
			//	var_export( $data['slideshow_images'] );
				$this->_parameter['markup_template'] = null;
				return false; 
			}
		//	var_export( $data );
			$data['image_limit'] = $this->getParameter( 'image_limit' ) ? : ( @$data['image_limit'] ? : 12 );
			$data['slideshow_images'] = unserialize( @$data['slideshow_images'] ) ? : array();
			if( $this->getParameter( 'sample_image_as_demo' ) && empty( $data['slideshow_images'] ) )
			{
				//	Demo image 
				$data['slideshow_images'] = array();
				@$data['slideshow_images'][] = array( 'slideshow_image' => $data['sample_image'], 'image_description' => $data['image_description'], 'image_link' => 'javascript:;' );  
			}
			//	Using template?
			$data['height'] = $data['height'] ? : 700;
			$data['width'] = $data['width'] ? : 1800;
			if( $this->getParameter( 'template_name' ) )
			{
				$options = new Application_Slideshow_Template;
				$options = $options->selectOne( null, array( 'template_name' => $this->getParameter( 'template_name' ) ) );
				$options['javascript_code'] = Ayoola_Abstract_Playable::replacePlaceholders( $options['javascript_code'], $data + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
			//	markup_template_namespace
				$this->setParameter( ( $options ? : array() ) + array( 'markup_template_namespace' => $this->getParameter( 'template_name' ) ) );
				if( @$options['javascript_files'] )
				{
					foreach( $options['javascript_files'] as $each )
					{
						Application_Javascript::addFile( $each );
					}
				}
				if( @$options['javascript_code'] )
				{
				//	foreach( $options['javascript_files'] as $each )
					{
						Application_Javascript::addCode( $options['javascript_code'] );
					}
				}
				if( @$options['css_code'] )
				{
				//	foreach( $options['javascript_files'] as $each )
					{
						Application_Style::addCode( $options['css_code'] ); 
					}
				}
				if( @$options['css_files'] )
				{
					foreach( @$options['css_files'] as $each )
					{
						Application_Style::addFile( $each );
					}
				}
			//	var_export( $this->getParameter() );
			}
			$linkToEdit = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/?object_name=Application_Slideshow_Editor&slideshow_name=' . @$data['slideshow_name'] . '';
			$prefix = $this->getParameter( 'markup_template_prefix' );
			$image = $this->getParameter( 'markup_template' );
			$suffix = $this->getParameter( 'markup_template_suffix' );
			
			//	
			$prefix = self::replacePlaceholders( $prefix, $data + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'link_to_edit_slideshow' => $linkToEdit, ) ); 
			$suffix = self::replacePlaceholders( $suffix, $data + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'link_to_edit_slideshow' => $linkToEdit, ) ); 
			
			$html = $prefix;    
			$i = 0;
			
			$allImages = array();
	//		$defaultInfo = array( 'slideshow_title' => $data['slideshow_title'], 'slideshow_description' => $data['slideshow_description'], );
			$defaultInfo = array();
									//		var_export(  $data  );
			$limit = $data['image_limit'];

			switch( @$data['slideshow_type'] )
			{
				case 'post':
					$class = new Application_Article_ShowAll( array( 'category_name' => @$data['slideshow_category_name'], 'article_types' => @$data['slideshow_article_type'], 'no_of_post_to_show' => @$data['image_limit'], ) + $this->getParameter() );     
				//	var_export( $class->_objectData );
					$info = $class->_objectData;
					$info = self::sortMultiDimensionalArray( $info, $this->getParameter( 'sort_column' ) ? : 'article_creation_date' );
					krsort( $info );
					$i = 0;
			//		var_export( array( 'category_name' => $data['slideshow_category_name'], 'article_types' => $data['slideshow_article_type'], 'no_of_post_to_show' => $data['image_limit'], ) );
					foreach( $info as $key => $each )
					{
				//	var_export( $i );
						$each = is_array( $each ) ? $each : @include $each;
						$slideInfo = array();
						$slideInfo['record_count'] = $i;
						$slideInfo['slideshow_image'] = Ayoola_Application::getUrlPrefix() . $each['document_url'];
						$slideInfo['image_link'] = $each['article_url'];
						$slideInfo['image_title'] = $each['article_title'];
						$slideInfo['image_description'] = $each['article_description'];  
						
						$allImages[] = $slideInfo + $defaultInfo;
				//		$each['record_count'] = $i;
						$html .= self::replacePlaceholders( $this->getParameter( 'markup_template_' . $i ) ? : $image, $slideInfo + (array) $this->getParameter( 'slideshow_defaults' ) + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'link_to_edit_slideshow' => $linkToEdit, ) );
						if( ++$i > $limit ){ break; }
						$html .= $this->getParameter( 'markup_template_median' );
					}
				//	var_export( $html );
				break;
				default:
					if( $data['slideshow_image'] )
					{
						foreach( $data['slideshow_image'] as $key => $each )     
						{
							
							$slideInfo = array();
							$slideInfo['record_count'] = $i;
							$slideInfo['slideshow_image'] = Ayoola_Application::getUrlPrefix() . $data['slideshow_image'][$key];
							$slideInfo['image_link'] = $data['image_link'][$key] ? : 'javascript:;';
						//	$slideInfo['image_title'] = $data['image_title'][$key] ? : $data['slideshow_title'];
							$slideInfo['image_title'] = $data['image_title'][$key] ? : '';
							$slideInfo['image_description'] = $data['image_description'][$key] ? : '';
							$allImages[] = $slideInfo + $defaultInfo;
					//		$each['record_count'] = $i;
							$html .= self::replacePlaceholders( $this->getParameter( 'markup_template_' . ++$i ) ? : $image, $slideInfo + (array) $this->getParameter( 'slideshow_defaults' ) + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'link_to_edit_slideshow' => $linkToEdit, ) );
							if( ++$i > $limit ){ break; }
							$html .= $this->getParameter( 'markup_template_median' );
						}
					}
					elseif( $data['slideshow_images'] )
					{
						foreach( $data['slideshow_images'] as $key => $each )
						{
					//		var_export(  $each  );
							$each['record_count'] = $i;
							$allImages[] = $each + $defaultInfo;
							$html .= self::replacePlaceholders( $this->getParameter( 'markup_template_' . ++$i ) ? : $image, $each + (array) $this->getParameter( 'slideshow_defaults' ) + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'link_to_edit_slideshow' => $linkToEdit, ) );  
							if( ++$i > $limit ){ break; }
							$html .= $this->getParameter( 'markup_template_median' );
						}
					}
				break;
			}
			$html = trim( $html, $this->getParameter( 'markup_template_median' ) ) . $suffix;  
	//		var_export( $html );
		//	var_export( $data['slideshow_images'] );
			if( empty( $data['slideshow_images'] ) )
			{
				//	Dont show markup when its empty 
			//	$html = null;
			}
			$allImages = ( is_array( $allImages ) ? $allImages : array() ) + ( @is_array( $data['slideshow_images'] ) ? $data['slideshow_images'] : array() );
		//	var_export( $allImages );
			$this->_objectData = $allImages;
			$this->_objectTemplateValues = $allImages;

			//	delete the markup template
			
			//	detect if we should use our generated markup_template or retain whats there
			//	var_export( stripos( $this->getParameter( 'markup_template' ), '{{{slideshow_image}}}{{{0}}}' ) );
			//	var_export( $this->getParameter( 'markup_template' ) );
			if( stripos( $this->getParameter( 'markup_template' ), '{{{slideshow_image}}}{{{array_key_count}}}' ) === false && stripos( $this->getParameter( 'markup_template' ), '{{{slideshow_image}}}{{{0}}}' ) === false )  
			{
				$this->_parameter['markup_template'] = $html;
			}
			elseif( $this->getParameter( 'markup_template' ) )
			{
				//	We are doing {{{slideshow_image}}}{{{0}}}
				//	Lets clean template for slideshow_title , etc
			//	$this->_parameter['markup_template'] = self::replacePlaceholders( $this->getParameter( 'markup_template' ), $data + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'link_to_edit_slideshow' => $linkToEdit, ) ); 
			}
			$this->_parameter['markup_template_prefix'] = null;
			$this->_parameter['markup_template_suffix'] = null;
		//	var_export( $this->getParameter( 'markup_template' ) );  
		//	
		//	var_export( $html );   
			
	//		$this->setViewContent( $html );
			if( self::hasPriviledge() && ! $this->getParameter( 'hide_editor_link' ) )
			{
			//	$this->setViewContent( '<button class="badnews boxednews centerednews blocknews" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $linkToEdit . '\' );" href="javascript:">Click to edit this slideshow images: "' . @$data['slideshow_name'] . '".</button>' );
			}
		//	var_export( $data );
		}
		catch( Application_Slideshow_Exception $e ){ return false; }
    } 
	
	
    /**
     * This method returns the _classOptions property
     *
     * @param void
     * @return array
     */
    public function getClassOptions()
    {
		if( null === $this->_classOptions )
		{
			$this->setClassOptions();
		}
		return (array) $this->_classOptions;
    } 	
	
    /**
     * This method sets the _classOptions property to a value
     *
     * @param array
     * @return void
     */
    public function setClassOptions()
    {
		foreach( $this->getDbData() as $value )
		{
			@$this->_classOptions[$value['slideshow_name']] = $value['slideshow_title'];
		}
    } 	
	
    /**
     * This method return the value of _viewOption property
     *
	 * @return mixed
     */
    public function getViewOption()
    {
		return $this->_viewOption;
    } 	
	
    /**
     * This method sets the _viewOption property to a value
     *
     * @param mixed The Value for the ViewableObject
     * @return string
     */
    public function setViewOption( $value )
    {
		//var_export( $value );
		$this->_viewOption = $value;
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
		$options = $object['class_name'];
		$options = new $options( array( 'no_init' => true ) );
//		$options = array();
		$options = (array) $options->getClassOptions();
//		$options = (array) $options->getClassOptions();
		$html .= '<span style=""> Show  </span>';
		$html .= '<select data-parameter_name="slideshow_name"><option value="">Default</option>';
		foreach( $options as $key => $value ) 
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] ); 
			if( @$object['slideshow_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . ( $value ? : $key ) . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> slides </span>';
		$html .= '<span style=""> in </span>';
		
		$options = new Application_Slideshow_Template;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'template_name', 'template_label');
		$options = $filter->filter( $options );
		
		$html .= '<select data-parameter_name="template_name">'; 
					
		foreach( $options as $key => $value )
		{ 
		//	var_export( $value );
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['template_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . ( $value ? : $key ) . '</option>';     
		}
		$html .= '</select>';
		$html .= ' template. ';
		return $html;
	}
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public function getStatusBarLinks( $object )
    {
		return '<a title="Manage Slideshows" class="title_button" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_List/\' );">Manage Slideshows</a>';
	}
	// END OF CLASS
}
