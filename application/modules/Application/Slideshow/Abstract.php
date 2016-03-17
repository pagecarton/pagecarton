<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Slideshow_Exception  
 */
 
require_once 'Application/Slideshow/Exception.php';


/**
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Slideshow_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99; 
	
    /**
     * Default Slideshow
     * 
     * @var string
     */
	const DEFAULT_NAME = 'SlideShow-1';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'slideshow_name' );
	
    /**
     * Id Column
     * 
     * @var string
     */
	protected $_idColumn = 'slideshow_name';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Slideshow';
	
    /**
     * Returns a db data
     * 
     */
	public function getPublicDbData()
    {
		$this->_createDefaultSlideshow(); //	This will inject the default identifier
		$data = $this->getDbTable()->selectOne( null, $this->getIdentifier() );		
					//	var_export( $data );
		return $data ? unserialize( $data['slideshow_images'] ) : array(); 
	} 
	
    /**
     * Get information about an image
     * 
     */
	public static function getImageInfo( $url )
    {
		$info = array();
		switch( $url[0] )
		{
			case '/':
				$url = 'http://' . Ayoola_Page::getDefaultDomain() . $url;
			break;
		}
	//	self::v( $url );
		if( $imageString = @file_get_contents( $url ) )
		{
		//	self::v( $imageString );
			//	Sample image means we must crop the image to meet the required size  
			try
			{
				if( function_exists( 'imagecreatefromstring' ) )
				{
					$manipulator = new ImageManipulator();
					$manipulator->setImageString( $imageString );
				//	self::v( $manipulator );
					$info['width']  = $manipulator->getWidth();
					$info['height'] = $manipulator->getHeight();
				}
			}
			catch( Exception $e )
			{
			//	echo $e->getMessage();
				//$this->setViewContent( $e->getMessage() ); 
				return false; 
			}
		}
	//	var_export( $url );
	//	var_export( $imageString ); 
	//	var_export( $width );
	//	var_export( $height );
		return $info;
	} 
	
    /**
     * Incase the identifer is not set, this method creates a default slideshow.
     * 
     */
	protected function _createDefaultSlideshow()
    {
		$name = $this->getParameter( 'slideshow_name' ) ? : self::DEFAULT_NAME;
	//	var_export( $name );
		try
		{ 
			$this->getIdentifier(); 
			if( ! $data = self::getIdentifierData() )
			{ 
				throw new Application_Slideshow_Exception( 'SLIDESHOW NOT AVALABLE' );
			}

		}
		catch( Ayoola_Exception $e )
		{
			try
			{
			//	var_export( $name );
				$values = array( 'slideshow_name' => $name, 'image_limit' => ( $this->getParameter( 'image_limit' ) ? : 2 ), 'sample_image' => $this->getParameter( 'sample_image' ) );
				
				$values = array_merge( $this->getParameter( 'default_details' ) ? : array(),  $values );
				
			//	var_export( $values );
				$this->getDbTable()->insert( $values );
			//	var_export( $name );
			}
			catch( Ayoola_Exception $e )
			{ 
			//	$this->getForm()->setBadnews( 'Could not add a new e-mail address.' );
		//		echo $e->getMessage();
			//	$this->setViewContent( $e->getMessage(), true );
			}
			$this->_identifier = array( 'slideshow_name' => $name );
			self::setIdentifierData();
		}
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
		$form->submitValue = 'Continue...' ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element; 
		
		$options = array( 
							'post' => 'POST: Use information on recent posts as slideshow images',      
							'upload' => 'UPLOAD: Build a custom slideshow with new photos and captions', 
						);
		$fieldset->addElement( array( 'name' => 'slideshow_type', 'type' => 'Radio', 'value' => @$values['slideshow_type'] ? : 'post', ), $options );
		if( ! $values )
		{
			$fieldset->addElement( array( 'name' => 'slideshow_title', 'label' => 'Default Title', 'placeholder' => 'Title for the slideshow.', 'type' => 'InputText', 'value' => @$values['slideshow_title'] ) );
			$fieldset->addRequirement( 'slideshow_title', array( 'WordCount' => array( 3, 200 ) ) );
	//		$fieldset->addElement( array( 'name' => 'slideshow_name', 'label' => 'Name', 'placeholder' => 'Give the new slideshow a name', 'type' => 'InputText', 'value' => @$values['slideshow_name'] ) );
	//		$fieldset->addRequirement( 'slideshow_name', array( 'WordCount' => array( 2, 30 ) ) );   
		}
		
//		$fieldset->addElement( array( 'name' => 'slideshow_description', 'label' => 'Default Description', 'placeholder' => 'Briefly describe the slideshow.', 'type' => 'TextArea', 'value' => @$values['slideshow_description'] ) );
	//	$fieldset->addRequirement( 'slideshow_description', array( 'WordCount' => array( 0, 200 ) ) );
		
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		
		if( ( is_array( Ayoola_Form::getGlobalValue( 'article_options' ) ) && in_array( 'slideshow_requirements', Ayoola_Form::getGlobalValue( 'article_options' ) ) ) )
		{

		}     
		$fieldset = new Ayoola_Form_Element();		
			//	var_export( Ayoola_Form::getGlobalValue( 'slideshow_type' ) );
/* 				if( $values['sample_image'] AND $imageInfo = Application_Slideshow_Abstract::getImageInfo( $values['sample_image'] ) )
		{
		//	var_export( $imageInfo );
			if( ! empty( $imageInfo['width'] ) && ! empty( $imageInfo['height'] ) )
			{ 
				$values['width'] = $values['width'] ? :  $imageInfo['width']; 
				$values['height'] =  $values['height'] ? :  $imageInfo['height']; 
			}
		}
*/
		$fieldset->addElement( array( 'name' => 'width', 'label' => 'Dimensions', 'placeholder' => 'Width e.g. 1500', 'type' => 'InputText', 'value' => @$values['width'] ) );
		$fieldset->addElement( array( 'name' => 'height', 'label' => '', 'placeholder' => 'Height e.g. 600', 'type' => 'InputText', 'value' => @$values['height'] ) );
		$fieldset->addElement( array( 'name' => 'timeout', 'label' => 'Time out in seconds', 'placeholder' => 'e.g. 600', 'type' => 'InputText', 'value' => @$values['timeout'] ) );
		
		$options = array_combine( range( 2, 20 ), range( 2, 20 ) );
		$fieldset->addElement( array( 'name' => 'image_limit', 'label' => 'No of slides', 'placeholder' => 'Select the maximum number of images, slideshow can play.', 'type' => 'Select', 'value' => @$values['image_limit'] ), $options );
		$fieldset->addRequirement( 'image_limit', array( 'InArray' => $options ) );
		
		//	Cover photo
//		$fieldset->addElement( array( 'name' => 'sample_image', 'data-document_type' => 'image', 'label' => 'Sample Image', 'type' => 'Document', 'value' => @$values['sample_image'], ) );
		
		$options = array( 
							'crop' => 'Crop excess image width or height when uploading image.', 
					//		'slideshow_requirements' => 'Information needed to display the Slideshow', 
						//	'post' => 'Information needed to display the Slideshow', 
						);
		$fieldset->addElement( array( 'name' => 'slideshow_options', 'type' => 'Checkbox', 'value' => @$values['slideshow_options'] ? : array( 'crop' ), ), $options );

		switch( Ayoola_Form::getGlobalValue( 'slideshow_type' ) )
		{
			case 'upload':
				$fieldset->addLegend( 'Build a custom slideshow with new photos and captions' );
			break;
			case 'post':
			//	var_export( Ayoola_Form::getGlobalValue( 'slideshow_type' ) );
				$categoryInfo = new Application_Article_Category;
				$categoryInfo = $categoryInfo->getPublicDbData();
				$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
				$categoryInfo = array( '' => 'ALL' ) + $filter->filter( $categoryInfo );
				$fieldset->addElement( array( 'name' => 'category_name', 'label' => 'Select appropriate post categories to use as slideshow images', 'type' => 'Select', 'value' => @$values['category_name']  ), $categoryInfo );
				$fieldset->addRequirement( 'category_name', array( 'ArrayKeys' => $categoryInfo ) );
				$fieldset->addLegend( 'Use information on recent posts as slideshow images' );
			break;
			
		}     
		$form->addFieldset( $fieldset );
		
		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
