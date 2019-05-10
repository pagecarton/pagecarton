<?php
/**
 * PageCarton
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
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init() 
    {
		try
		{ 

		//	self::v( $this->getParameter() );
		//	$this->_createDefaultSlideshow();
		//	var_export( $html );
		//	if( ! $data = self::getIdentifierData() ){ return false; }
			if( ! $data = self::getIdentifierData() )
			{ 
				$this->_identifier = array( 'slideshow_name' => $this->getParameter( 'slideshow_name' ) );
				self::setIdentifierData();
				if( ! $data = self::getIdentifierData() )
				{ 
				//	var_export( $data );
				//	$this->_parameter['markup_template'] = null;
				//	if( self::hasPriviledge( 98 ) )
					{
						$data = $this->_identifier;
					}
				//	return false; 
				}
			}
	//		var_export( $data );
	//		var_export( $this->getParameter() );
			if( empty( $data['slideshow_name'] ) )
			{ 
			//	var_export( $data['slideshow_images'] );
				$this->_parameter['markup_template'] = 'Slideshow name not set';
				return false; 
			}
		//	var_export( $data );
			//	Using template?
			@$data['width'] = $this->getParameter( 'image_width' ) ? : ( $data['width'] ? : 2100 );
			@$data['height'] = $this->getParameter( 'image_height' ) ? : ( $data['height'] ? : 700 );     
			$data['image_limit'] = $this->getParameter( 'image_limit' ) ? : ( @$data['image_limit'] ? : 12 );
		//	$data['slideshow_images'] = unserialize( @$data['slideshow_images'] ) ? : array();
			if( empty( $data['slideshow_image'] ) )
//			if( $this->getParameter( 'sample_image_as_demo' ) && empty( $data['slideshow_images'] ) )
			{
				//	Demo image 
				$data['slideshow_image'][] = '/img/placeholder-image.jpg';
				$data['image_title'][] = 'Sunt aliquip cupidatat sit officia nulla.';
				$data['image_description'][] = 'Duis est esse est voluptate consectetur sit dolor consequat tempor. ';
				$data['image_link'][] = 'javascript:;';

				$data['slideshow_image'][] = '/img/placeholder-image.jpg';
				$data['image_title'][] = 'Sunt aliquip cupidatat sit officia nulla.';
				$data['image_description'][] = 'Duis est esse est voluptate consectetur sit dolor consequat tempor. ';
				$data['image_link'][] = 'javascript:;';

				$data['slideshow_image'][] = '/img/placeholder-image.jpg';
				$data['image_title'][] = 'Sunt aliquip cupidatat sit officia nulla.';
				$data['image_description'][] = 'Duis est esse est voluptate consectetur sit dolor consequat tempor. ';
				$data['image_link'][] = 'javascript:;';
//				@$data['slideshow_images'][] = array( 'slideshow_image' => '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer?url=/img/abstract.jpg&max_width=' . $data['width'] . '&max_height=' . $data['height'] . '', 'image_description' => 'Duis est esse est voluptate consectetur sit dolor consequat tempor. ', 'image_title' => 'Ea sunt adipisicing reprehenderit nostrud aliqua amet culpa dolore sint..', 'image_link' => 'javascript:;' );  
			}
			$template = $this->getParameter( 'template_name' );
			if( ! $this->getParameter( 'markup_template' ) )
			{
				$template = $template ? : 'NivoSlider';
			}
			
	//		$template = $this->getParameter( 'template_name' ) ? : 'NivoSlider';
	//		var_export( $template );    
//			var_export( $data );    
	//		var_export( $this->getParameter( 'template_name' ) );    
	//		var_export( $this->getParameter( 'markup_template' ) );    
			if( $template )
			{
				$options = new Application_Slideshow_Template;
				$options = $options->selectOne( null, array( 'template_name' => $template ) );
				$options['javascript_code'] = Ayoola_Abstract_Playable::replacePlaceholders( $options['javascript_code'], $data + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
			//	markup_template_namespace
				$this->setParameter( ( $options ? : array() ) + array( 'markup_template_namespace' => $template ) );
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
			switch( $data['slideshow_name'] )
			{
				case 'pc_page_images':
			//		var_export( Ayoola_Application::$GLOBAL['images'] );
			//		var_export( Ayoola_Application::$GLOBAL['document_url'] );
			//		var_export( $data );
					$data['image_description'] = array();
					if( Ayoola_Application::$GLOBAL['post']['images'] && ( Ayoola_Application::$GLOBAL['post']['images'][0] ) )
					{
						$data['slideshow_image'] = Ayoola_Application::$GLOBAL['post']['images'];
					}
					elseif( Ayoola_Application::$GLOBAL['post']['document_url'] )
					{
						$data['slideshow_image'] = (array) Ayoola_Application::$GLOBAL['post']['document_url'];
					}
				break;
			}

			switch( @$data['slideshow_type'] )
			{
				case 'post':
					$parameters = array( 
											'category_name' => @$data['slideshow_category_name'], 
											'cover_photo_width' => @$data['width'], 
											'cover_photo_height' => @$data['height'], 
											'article_types' => @$data['slideshow_article_type'], 
											'no_of_post_to_show' => @$data['image_limit'], 
											) 
											+ $this->getParameter();
				//	var_export( $parameters );
					$class = new Application_Article_ShowAll( $parameters );     
				//	var_export( $class->_objectData );
					$info = $class->_objectData;
				//	var_export( $info );

			//		$info = self::sortMultiDimensionalArray( $info, $this->getParameter( 'sort_column' ) ? : 'article_creation_date' );
			//		var_export( $this->getParameter( 'sort_column' ) ? : 'article_creation_date' );
			//		var_export( $info );
			//		krsort( $info );
			//		var_export( $info );
					$i = 0;
			//		var_export( array( 'category_name' => $data['slideshow_category_name'], 'article_types' => $data['slideshow_article_type'], 'no_of_post_to_show' => $data['image_limit'], ) );
					foreach( $info as $key => $each )
					{
				//	var_export( $i );
					//	var_export( $each );
						if( ! is_array( $each ) || empty( $each['document_url'] ) )
						{
							continue;
						}
						$each = is_array( $each ) ? $each : @include $each;
						$slideInfo = array();
						$slideInfo['record_count'] = $i;
						$slideInfo['slideshow_image'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer?url=' . $each['document_url']  . '&max_width=' . $data['width'] . '&max_height=' . $data['height'] . '';
				//		var_export( $each['document_url'] );
				//		var_export( $slideInfo['slideshow_image'] );
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
							if( empty( $data['slideshow_image'][$key] ) )
							{
								continue;
							}
							
							$slideInfo = array();
							$slideInfo['record_count'] = $i;
							$filename = Ayoola_Loader::checkFile( 'documents' . $data['slideshow_image'][$key] );
						//	var_export( $filename );    
							$slideInfo['slideshow_image'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer?url=' . $data['slideshow_image'][$key] . '&max_width=' . $data['width'] . '&max_height=' . $data['height'] . '&document_time=' . filemtime( $filename ) . '';
							$slideInfo['image_link'] = $data['image_link'][$key] ? : 'javascript:;';
							$slideInfo['image_title'] = $data['image_title'][$key] ? : '';
							$slideInfo['image_description'] = $data['image_description'][$key] ? : '';
							$allImages[] = $slideInfo + $defaultInfo;
					//		$each['record_count'] = $i;
							$html .= self::replacePlaceholders( $this->getParameter( 'markup_template_' . ++$i ) ? : $image, $slideInfo + (array) $this->getParameter( 'slideshow_defaults' ) + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'link_to_edit_slideshow' => $linkToEdit, ) );
					//		if( ++$i > $limit ){ break; }
							$html .= $this->getParameter( 'markup_template_median' );
						}
					}
	//				elseif( $data['slideshow_images'] )
					{
/*						foreach( $data['slideshow_images'] as $key => $each )
						{
					//		var_export(  $each  );
							$each['record_count'] = $i;
							$allImages[] = $each + $defaultInfo;
							$html .= self::replacePlaceholders( $this->getParameter( 'markup_template_' . ++$i ) ? : $image, $each + (array) $this->getParameter( 'slideshow_defaults' ) + array( 'template_instance_count' => static::$_counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'link_to_edit_slideshow' => $linkToEdit, ) );  
							if( ++$i > $limit ){ break; }
							$html .= $this->getParameter( 'markup_template_median' );
						}
*/					}
				break;
			}
			$html = trim( $html, $this->getParameter( 'markup_template_median' ) ) . $suffix;  
	//		var_export( $html );
		//	var_export( $data['slideshow_images'] );
		//	if( empty( $data['slideshow_images'] ) )
			{
				//	Dont show markup when its empty 
			//	$html = null;
			}
	//		$allImages = ( is_array( $allImages ) ? $allImages : array() ) + ( @is_array( $data['slideshow_images'] ) ? $data['slideshow_images'] : array() );
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
			if( self::hasPriviledge( array( 99, 98 ) ) && ! $this->getParameter( 'hide_editor_link' ) && $this->getParameter( 'show_editor_link' ) )
			{
				$editButton = '<div style="text-align:center;"> <button class="pc-btn boxednews centerednews blocknews" onclick="ayoola.spotLight.showLinkInIFrame( \'' . $linkToEdit . '\' );" href="javascript:">Manage this slideshow</button></div>';
				$this->_parameter['markup_template'] .= $editButton;  
			//	$this->setViewContent( $editButton );
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
    public static function getHTMLForLayoutEditor( & $object )
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
		$newName = 'slideshow_' . time();
		if( empty( $object['slideshow_name'] ) )  
		{
			$object['slideshow_name'] = $newName; 
			$object['new_slideshow'] = $newName; 
		}
		$slideshowPresent = false;
		$options['pc_page_images'] = 'Page Images';
		$html .= '<span style="">Show  </span>';
		$html .= '<select data-parameter_name="slideshow_name">
		<option value="' . $newName . '">New Slideshow</option>';
		foreach( $options as $key => $value ) 
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] ); 
			if( @$object['slideshow_name'] == $key )
			{
				$slideshowPresent = true;
				$html .= ' selected = selected '; 
			}
			$html .=  '>' . ( $value ? : $key ) . '</option>';  
		}
		if( empty( $slideshowPresent ) )
		{
			$html .= '<option value="' . $object['slideshow_name'] . '" selected = selected>' . $object['slideshow_name'] . '</option> '; 
		}
		$html .= '</select>';
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
		$html .= ' style. ';
		return $html;
	}
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public static function getStatusBarLinks( $object )
    {
		return '<a title="Manage Slideshows" class="title_button" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_List/\' );">Manage Slideshows</a>';
	}
	// END OF CLASS
}
