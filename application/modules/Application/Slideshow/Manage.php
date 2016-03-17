<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_Manage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Manage.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Slideshow_Abstract
 */
 
require_once 'Application/Slideshow/Abstract.php';


/**
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_Manage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Slideshow_Manage extends Application_Slideshow_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 

		//	$this->_createDefaultSlideshow();
			if( ! $data = self::getIdentifierData() ){ return false; }
	//		var_export( $data );
			$this->createForm( 'Save', 'Manage ' . $data['slideshow_name'], $data );
		//	$this->setViewContent( '<h2><span class="goodnews"> <a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Editor/?' . $this->getIdColumn() . '=' . $data['slideshow_name'] . '" title="Advanced settings for ' . $data['slideshow_name'] . '">-</a>  <a class="badnews" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Delete/?' . $this->getIdColumn() . '=' . $data['slideshow_name'] . '" title="Delete ' . $data['slideshow_name'] . '">x</a></span></h2>', true );
		//	$this->setViewContent( '' );
//			$this->setViewContent( '<h4>Select the images from the list.</h4>' );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
		//	var_export( $values );
			
			//	arrange the images
/* 			$images = array();
			for( $i = 0; $i < $data['image_limit']; $i++ ) 
			{
			//	if( ! $values['slideshow_image' . $i] ){ break; }
				$images[$i] = array( 'slideshow_image' => $values['slideshow_image' . $i], 'image_description' => @$values['image_description' . $i], 'image_link' => @$values['image_link' . $i], 'image_title' => @$values['image_title' . $i] );
				if( @is_array( $data['slideshow_requirements'] ) )
				{
					foreach( $data['slideshow_requirements'] as $each )
					{
						$images[$i][$each] = $values[$each . $i];  
					}
				}
				
			}
			$values['slideshow_images'] = serialize( $images );
 */		
			if( $this->updateDb( $values ) ){ $this->setViewContent( '<div class="boxednews normalnews">Slideshow saved successfully.</div>', true ); }
		}
		catch( Application_Slideshow_Exception $e ){ return false; }
    } 
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$values['slideshow_images'] = unserialize( $values['slideshow_images'] );
		
	//	var_export( $values );
		
		$form->submitValue = 'Continue...' ;
	//	$form->oneFieldSetAtATime = true;

/* 		//	Retrieve the list of the pictures in the documents Table		
		require_once 'Ayoola/Doc.php';		
		$doc = new Ayoola_Doc_Document;
		$doc = $doc->select();
		$filter = new Ayoola_Filter_FileExtention();
		$allowedExtentions = array( 'jpg', 'gif', 'png', );
		foreach( $doc as $key => $each )
		{
			if( ! in_array( $filter->filter( $each['document_url'] ), $allowedExtentions  ) ){ unset( $doc[$key] ); }
		}
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'document_url', 'document_name' );
		$doc = $filter->filter( $doc );	
 */		if( ! $data = self::getIdentifierData() ){ return false; }
	//	var_export( $data );
		$width  = null;
		$height = null;
		$crop = null;
		if( empty( $data['width'] ) || empty( $data['height'] ) )
		{
			if( ! empty( $data['sample_image'] ) && is_string( $data['sample_image'] ) )
			{
				if( $imageInfo = self::getImageInfo( $data['sample_image'] ) )
				{
					if( ! empty( $imageInfo['width'] ) && ! empty( $imageInfo['height'] ) && @in_array( 'crop', $data['slideshow_options'] ) )
					{ 
						$width  = $imageInfo['width'];
						$height = $imageInfo['height'];
						$crop = true; 
					}
				}
		//	var_export($imageInfo ); 
		//	var_export( $width );    
		//	var_export( $height );
			}  
		}
		else
		{
			$width  = $data['width'];
			$height = $data['height'];
			$crop = true; 
		}
	//	Application_Javascript::addFile( '/js/objects/ckeditor/ckeditor.js' );
	
		//	now lets remove the limit because we have allowed cloning
		$data['image_limit'] = count( $values['slideshow_image'] ) ? : $data['image_limit'];
		$data['image_limit'] = $data['image_limit'] ? : 2;
	//	var_export( $data['image_limit'] );
		for( $i = 0; $i < $data['image_limit']; $i++ ) 
		{
			$fieldset = new Ayoola_Form_Element;
			$fieldset->allowDuplication = true;
			$fieldset->duplicationData = array( 'add' => 'Add new slide', 'remove' => 'X', );
			$fieldset->container = 'span';
		//	if( ! is_array( $data['slideshow_requirements'] ) || in_array( 'slideshow_image', $data['slideshow_requirements'] ) )
			{
		//		$fieldset->placeholderInPlaceOfLabel = true;
			//	$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'slideshow_image' . $i ) : 'slideshow_image' . $i );
			//	$link = '/ayoola/thirdparty/Filemanager/index.php?field_name=' . $fieldName;
			//	$fieldset->addElement( array( 'name' => 'x' . $i, 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => @$values['slideshow_images'][$i]['slideshow_image'] ? : $data['sample_image'], 'field_name' => $fieldName, 'width' => $width, 'height' => $height, 'crop' => $crop, 'field_name_value' => 'url' ) ) ) );
			//	$fieldset->addElement( array( 'name' => 'y' . $i, 'type' => 'Html' ), array( 'html' => '<span class="boxednews centerednews" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $link . '\' ); return true;" href="javascript:">Click from previous images...</span>' ) );
			//	$fieldset->addElement( array( 'name' => 'slideshow_image' . $i, 'label' => 'Image URL', 'type' => 'Hidden', 'placeholder' => 'http://example.com/path/to/image.jpg', 'value' => @$values['slideshow_images'][$i]['slideshow_image'] ) );
		//		var_export( $values['slideshow_images'][$i] );
			//	$fieldset->addRequirement( 'slideshow_image' . $i, array( 'WordCount' => array( 3, 300 )  ) );
		//		$fieldset->addRequirement( 'slideshow_image' . $i, array( 'InArray' => array_keys( $doc )  ) );
		//		$fieldset->addElement( array( 'name' => 'slideshow_image', 'multiple' => 'multiple', 'data-document_type' => 'image', 'label' => 'Photo', 'type' => 'Document', 'value' => @$values['slideshow_images'][$i]['slideshow_image'] ? : @$values['slideshow_image'][$i], ) );
			}

			{
				$fieldset->addElement( array( 'name' => 'slideshow_image', 'multiple' => 'multiple', 'data-document_type' => 'image', 'label' => 'Photo', 'type' => 'Document', 'value' => @$values['slideshow_images'][$i]['slideshow_image'] ? : @$values['slideshow_image'][$i], ) );
				// link
				@$fieldset->addElement( array( 'name' => 'image_link', 'multiple' => 'multiple', 'label' => 'Image Link.', 'placeholder' => 'e.g. http://example.com/link/to/go/when/photo/is/clicked', 'type' => 'InputText', 'value' => @$values['slideshow_images'][$i]['image_link'] ? : @$values['image_link'][$i] ) );
				
				//	title
			//	var_export( $values['image_title'] );
				$fieldset->addElement( array( 'name' => 'image_title', 'multiple' => 'multiple', 'placeholder' => 'Title for the image...', 'label' => 'Caption Title', 'type' => 'InputText', 'value' => @$values['slideshow_images'][$i]['image_title'] ? : @$values['image_title'][$i] ) );
				$fieldset->addRequirement( 'image_title', array( 'WordCount' => array( 0, 500 ) ) );
				
				//	description
				$fieldset->addElement( array( 'name' => 'image_description', 'multiple' => 'multiple', 'placeholder' => 'Briefly describe this image.', 'label' => 'Caption Description', 'type' => 'TextArea', 'value' => @$values['slideshow_images'][$i]['image_description'] ? : @$values['image_description'][$i] ) );
				$fieldset->addRequirement( 'image_description', array( 'WordCount' => array( 0, 1000 ) ) );  
				
			}
	//		$editLink = '<span class=""> <a class="boxednews badnews" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Editor/?' . $this->getIdColumn() . '=' . $data['slideshow_name'] . '" title="Advanced settings for ' . $data['slideshow_name'] . '">-</a>  <a class="boxednews badnews" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Delete/?' . $this->getIdColumn() . '=' . $data['slideshow_name'] . '" title="Delete ' . $data['slideshow_name'] . '">x</a></span>';
		//	$fieldset->addLegend( 'Slide (' . ( $i + 1 ) . ' of ' . ( $data['image_limit'] ) . ') - "' . ( $data['slideshow_title'] ? : $data['slideshow_name'] ) . '"' . $editLink );
		//	$fieldset->addLegend( 'Slide Photo ' . ( $i + 1 ) . ' of ' . ( $data['image_limit'] ) . '' );
			$fieldset->addLegend( 'New Slide Photo ' );
		//	var_export( __LINE__ );
			$form->addFieldset( $fieldset );
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
