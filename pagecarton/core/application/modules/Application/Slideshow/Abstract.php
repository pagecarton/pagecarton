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
	protected static $_accessLevel = array( 99, 98 ); 
	
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
			//	$url = 'http://' . Ayoola_Page::getDefaultDomain() . $url;
				$url = 'documents' . $url;
	//	self::v( $url );
				$url = Ayoola_Loader::getFullPath( $url, array( 'prioritize_my_copy' => true ) );;
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
		$name = $this->getParameter( 'slideshow_name' ) ? : @$_REQUEST['slideshow_name'];
		$name = $name ? : self::DEFAULT_NAME;
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
				$values = array( 'slideshow_name' => $name, 'slideshow_title' => $name, 'image_limit' => ( $this->getParameter( 'image_limit' ) ? : 2 ), 'sample_image' => $this->getParameter( 'sample_image' ) );
				
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
		$fieldset->addElement( array( 'name' => 'slideshow_title', 'label' => 'Slideshow Title', 'placeholder' => 'Title for the slideshow.', 'type' => 'InputText', 'value' => @$values['slideshow_title'] ) );
		$fieldset->addRequirement( 'slideshow_title', array( 'WordCount' => array( 3, 200 ) ) );
		
		$fieldset->addElement( array( 'name' => 'slideshow_description', 'label' => 'Slideshow Description', 'placeholder' => 'Briefly describe the slideshow.', 'type' => 'TextArea', 'value' => @$values['slideshow_description'] ) );
	//	$fieldset->addRequirement( 'slideshow_description', array( 'WordCount' => array( 0, 200 ) ) );
		
	//	$form->addFieldset( $fieldset );
		
	//	$fieldset = new Ayoola_Form_Element();		

		$fieldset->addElement( array( 'name' => 'width', 'label' => 'Dimensions (Width/Height)', 'placeholder' => 'Width e.g. 1500', 'type' => 'InputText', 'value' => @$values['width'] ) );
		$fieldset->addElement( array( 'name' => 'height', 'label' => '', 'placeholder' => 'Height e.g. 600', 'type' => 'InputText', 'value' => @$values['height'] ) );
	//	$fieldset->addElement( array( 'name' => 'timeout', 'label' => 'Time out in seconds', 'placeholder' => 'e.g. 600', 'type' => 'InputText', 'value' => @$values['timeout'] ) );
		    
		$options = array_combine( range( 2, 20 ), range( 2, 20 ) );
		$fieldset->addElement( array( 'name' => 'image_limit', 'label' => 'No of slides', 'placeholder' => 'Select the maximum number of images, slideshow can play.', 'type' => 'Select', 'value' => @$values['image_limit'] ? : 5 ), $options );
		$fieldset->addRequirement( 'image_limit', array( 'InArray' => $options ) );
		$options = array( 
							'upload' => 'Upload photos and captions', 
							'post' => 'Use Recent Posts',      
						);
		$fieldset->addElement( array( 'name' => 'slideshow_type', 'type' => 'Select', 'value' => @$values['slideshow_type'] ? : 'upload', ), $options );
		
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		

		switch( Ayoola_Form::getGlobalValue( 'slideshow_type' ) )
		{
			case 'upload':
		//		$fieldset->addLegend( 'Build a custom slideshow with new photos and captions' );
			break;
			case 'post':
			//	var_export( Ayoola_Form::getGlobalValue( 'slideshow_type' ) );
				$fieldset = new Ayoola_Form_Element();		
				$categoryInfo = new Application_Article_Category;
				$categoryInfo = $categoryInfo->getPublicDbData();
				$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
				$categoryInfo = array( '' => 'ALL' ) + $filter->filter( $categoryInfo );
				$fieldset->addElement( array( 'name' => 'slideshow_category_name', 'label' => 'Select appropriate post categories to use as slideshow images', 'type' => 'Select', 'value' => @$values['slideshow_category_name']  ), $categoryInfo );
		//		$fieldset->addRequirement( 'category_name', array( 'ArrayKeys' => $categoryInfo ) );
		
				$categoryInfo = new Application_Article_Type_ShowAll();
				$categoryInfo = $categoryInfo->getDbData();
				$filter = new Ayoola_Filter_SelectListArray( 'post_type_id', 'post_type');
				$categoryInfo = array( '' => 'ALL' ) + $filter->filter( $categoryInfo );
				$fieldset->addElement( array( 'name' => 'slideshow_article_type', 'label' => 'Select appropriate post types to use as slideshow images', 'type' => 'Select', 'value' => @$values['slideshow_article_type']  ), $categoryInfo );  
				if( self::hasPriviledge() )
				{
					$fieldset->addElement( array( 'name' => 'manage', 'label' => ' ', 'type' => 'Html', 'value' => null  ), array( 'html' => '<a class="pc-btn pc-btn-small"  rel="spotlight;changeElementId=' . get_class( $this ) . '" title="Manage Post Types" href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Application_Article_Type_List">Manage Post Types</a> <a  class="pc-btn pc-btn-small" rel="spotlight;changeElementId=' . get_class( $this ) . '" title="Manage categories" href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Access_AccessInformation_Editor?pc_profile_info_to_edit=post_categories">Manage categories</a>' ) );  
				}
				
		//		$fieldset->addRequirement( 'category_name', array( 'ArrayKeys' => $categoryInfo ) );
				$fieldset->addLegend( 'Use information on recent posts as slideshow images' );
				$form->addFieldset( $fieldset );
			break;
			
		}     
		
		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
