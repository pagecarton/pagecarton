<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_ManageImage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ManageImage.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Slideshow_Abstract 
 */
 
require_once 'Application/Slideshow/Abstract.php';


/**
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_ManageImage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Slideshow_ManageImage extends Application_Slideshow_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 

			$this->_createDefaultSlideshow();
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Save', 'Manage ' . $data['slideshow_name'], $data );
			$this->setViewContent( '<h4>Editing images on "' . $data['slideshow_name'] . '".</h4>', true );
			$this->setViewContent( '<p>NOTE: You can select from uploaded <a rel="spotlight;height=300px;width=300px;classPlayerUrl=/tools/classplayer/get/object_name/Ayoola_Doc_Upload/;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/document/">images in application documents. Click here to upload a new image.</a> </p>' );
			$this->setViewContent( '<p> <a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Editor/?' . $this->getIdColumn() . '=' . ( $this->_viewParameter ? : self::DEFAULT_NAME ) . '" title="Advanced settings for ' . $data['slideshow_name'] . '">#</a> | <a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Delete/?' . $this->getIdColumn() . '=' . ( $this->_viewParameter ? : self::DEFAULT_NAME ) . '" title="Delete ' . $data['slideshow_name'] . '">x</a></p>' );
			$this->setViewContent( '<h4>Select the images from the list.</h4>' );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	arrange the images
			$image = array();
			for( $i = 1; $i < $data['image_limit']; $i++ )
			{
				if( ! $values['slideshow_image' . $i] ){ break; }
				$images[$i] = array( 'slideshow_image' => $values['slideshow_image' . $i], 'image_description' => $values['image_description' . $i] );
			}
			$values['slideshow_images'] = serialize( $images );
			if( $this->updateDb( $values ) ){ $this->setViewContent( 'Slideshow saved successfully.', true ); }
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
		$values['slideshow_images'] = unserialize( $values['slideshow_images'] );
		$form->submitValue = 'Save' ;
	//	$form->oneFieldSetAtATime = true;

		//	Retrieve the list of the pictures in the documents Table		
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
		if( ! $data = self::getIdentifierData() ){ return false; }
		for( $i = 1; $i < $data['image_limit']; $i++ )
		{
			$fieldset = new Ayoola_Form_Element;
			$fieldset->placeholderInPlaceOfLabel = true;
			$fieldset->addElement( array( 'name' => 'slideshow_image' . $i, 'type' => 'Select', 'value' => $values['slideshow_images'][$i]['slideshow_image'] ), array( 'Select an image...' ) + $doc );
	//		var_export( $values['slideshow_images'][$i] );
			$fieldset->addRequirement( 'slideshow_image' . $i, array( 'InArray' => array_keys( $doc )  ) );
			$fieldset->addElement( array( 'name' => 'image_description' . $i, 'placeholder' => 'Briefly describe this image.', 'label' => 'Description', 'type' => 'TextArea', 'value' => $values['slideshow_images'][$i]['image_description'] ) );
			$fieldset->addRequirement( 'image_description' . $i, array( 'WordCount' => array( 0, 200 ) ) );
			$fieldset->addLegend( 'Image ' . $i );
		$form->addFieldset( $fieldset );
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
