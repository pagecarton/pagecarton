<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Type_TypeAbstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Type_Exception 
 */
 
require_once 'Application/Article/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Type_TypeAbstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Article_Type_TypeAbstract extends Ayoola_Abstract_Table
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'post_type_id' );
	
    /**
     * Preset Post types 
     * 
     * @var array
     */
	public static $presetTypes = array( 'article' => 'Article', 'audio' => 'Audio', 'video' => 'Embed YouTube Video', 'quiz' => 'Online Quiz', 'poll' => 'Online Poll', 'download' => 'Downloadables', 'link' => 'External Web Link', 'event' => 'Event', 'product' => 'Product', 'service' => 'Service', 'book' => 'Book', );
	
    /**
     * Id Column
     * 
     * @var string
     */
	protected $_idColumn = 'post_type_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Article_Type';

    /**
     * 
     * param string Post Type to checj
     * return array Default Values
     */
	public static function getOriginalPostTypeInfo( $postType ) 
    {
		$table = Application_Article_Type::getInstance();
		if( $postTypeInfo = $table->selectOne( null, array( 'post_type_id' => $postType ) ) )
		{
			return $postTypeInfo;
		}
		return false;
	}
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null ) 
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true; 
	//	$types = array( 'post' => 'Normal Post', 'article' => 'Article', 'audio' => 'Audio', 'video' => 'Video', 'quiz' => 'Online Quiz', 'poll' => 'Online Poll', 'download' => 'Downloadables', 'link' => 'Link', 'event' => 'Event', 'product' => 'Product', 'service' => 'Service', 'book' => 'Book', );          
	//	var_export( $settings );
	//	do
		{		
			$fieldset->addElement( array( 'name' => 'post_type', 'label' => 'Type Name', 'title' => 'Enter post tyoe name, e.g. Article', 'placeholder' => 'e.g. Article', 'type' => 'InputText', 'value' => @$values['post_type'], ) ); 
			$fieldset->addElement( array( 'name' => 'article_type', 'label' => 'Post is similar to', 'title' => 'Choose the kind of post this is...', 'type' => 'Select', 'value' => @$values['article_type'], ), self::$presetTypes );  


            $options = 	array( 
                                'article' => 'Article', 
                                'video' => 'Video Embed URL', 
                                'download' => 'Download URL', 
                                'product' => 'Price', 
                                'multi-price' => 'Multiple Pricing', 
                                'datetime' => 'Date and Time', 
                                'location' => 'Location', 
                                'audio' => 'Play Audio', 
                                'gallery' => 'Gallery Images', 
                                );

			$fieldset->addElement( array( 'name' => 'post_type_options', 'label' => 'Other Options available to post type', 'title' => '', 'type' => 'Checkbox', 'value' => @$values['post_type_options'], ), $options );      
            
            //	supplementary form for creating post
            $options = new Ayoola_Form_Table(); 
            $options = $options->select();
            require_once 'Ayoola/Filter/SelectListArray.php';
            $filter = new Ayoola_Filter_SelectListArray( 'form_name', 'form_title');
            $options = $filter->filter( $options );
            $fieldset->addElement( array( 'name' => 'supplementary_form', 'label' => 'Supplementary Creation form', 'type' => 'Select', 'value' => @$values['supplementary_form'] ), array( '' => 'Please select...' ) + $options ); 

	//		$i++;
			$fieldset->addLegend( $legend );
		//	$fieldset->wrapper = 'white-background';
			$fieldset->wrapper = 'white-content-theme-border';   
		}
	//	while( isset( $settings['post_types'][$i] ) );    

		$form->addFieldset( $fieldset );		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
