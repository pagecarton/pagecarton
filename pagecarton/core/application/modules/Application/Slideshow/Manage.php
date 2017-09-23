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
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( $this->updateDb( $values ) ){ $this->setViewContent( '<div class="boxednews goodnews">Slideshow saved successfully.</div>', true ); }
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
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$values['slideshow_images'] = unserialize( $values['slideshow_images'] );
		
	//	var_export( $values );
		
		$form->submitValue = $submitValue;
	//	$form->oneFieldSetAtATime = true;

		if( ! $data = self::getIdentifierData() ){ return false; }
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
		$data['image_limit'] = $data['image_limit'] ? : count( $values['slideshow_image'] );
		$data['image_limit'] = $data['image_limit'] ? : 2;
	//	var_export( $data['image_limit'] );
//O		var_export( $data );
		for( $i = 0; $i < $data['image_limit']; $i++ ) 
		{
			$fieldset = new Ayoola_Form_Element;
			$fieldset->allowDuplication = true;
			$fieldset->duplicationData = array( 'add' => 'Add slide below', 'remove' => 'Remove above slide', 'counter' => 'slide_counter', );
			$fieldset->wrapper = 'white-background';  
			$fieldset->container = 'span';

			{
				$fieldset->addElement( array( 'name' => 'slideshow_image', 'multiple' => 'multiple', 'data-document_type' => 'image', 'label' => 'Photo', 'type' => 'Document', 'value' => @$values['slideshow_images'][$i]['slideshow_image'] ? : @$values['slideshow_image'][$i], ) );
				// link
				@$fieldset->addElement( array( 'name' => 'image_link', 'multiple' => 'multiple', 'label' => 'Image Link', 'placeholder' => 'e.g. http://example.com/link/to/go/when/photo/is/clicked', 'type' => 'InputText', 'value' => @$values['slideshow_images'][$i]['image_link'] ? : @$values['image_link'][$i] ) );
				
				//	title  
			//	var_export( $values['image_title'] );
				$fieldset->addElement( array( 'name' => 'image_title', 'multiple' => 'multiple', 'placeholder' => 'Title for the image...', 'label' => 'Caption Title', 'type' => 'InputText', 'value' => @$values['slideshow_images'][$i]['image_title'] ? : @$values['image_title'][$i] ) );
				$fieldset->addRequirement( 'image_title', array( 'WordCount' => array( 0, 500 ) ) );
				
				//	description
				$fieldset->addElement( array( 'name' => 'image_description', 'multiple' => 'multiple', 'placeholder' => 'Briefly describe this image.', 'label' => 'Caption Description', 'type' => 'TextArea', 'value' => @$values['slideshow_images'][$i]['image_description'] ? : @$values['image_description'][$i] ) );
				$fieldset->addRequirement( 'image_description', array( 'WordCount' => array( 0, 1000 ) ) );  
				
			}
			$fieldset->addLegend( 'Slide <span name="slide_counter">' . ( $i + 1 ) . '</span> of <span name="slide_counter_total">' . ( $data['image_limit'] ? : 1 ) . ' (' . ( $values['slideshow_title'] ? : $values['slideshow_name'] ) . ')</span>' );
		//	var_export( __LINE__ );
			$form->addFieldset( $fieldset );
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
