<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
	public static $presetTypes = array( 'article' => 'Article', 'audio' => 'Audio', 'video' => 'Embed YouTube Video', 'quiz' => 'Online Quiz', 'poll' => 'Online Poll', 'download' => 'Downloadables', 'link' => 'External Web Link', 'event' => 'Event', 'product' => 'Product', 'service' => 'Service', 'book' => 'Book' );
	
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
     * 
     */
	public static function getMyAllowedPostTypes() 
    {
		
		//	Set Article Type
		$options = Application_Article_Type::getInstance();
		$options = $options->select();
		foreach( $options as $eachTypeKey => $eachType )
		{
			if( ! empty( $eachType['auth_level'] ) && ! Ayoola_Abstract_Table::hasPriviledge( $eachType['auth_level'] ) )
			{ 
				//	Current user not authorized to use this post type
				unset( $options[$eachTypeKey] );
				unset( Application_Article_Type_TypeAbstract::$presetTypes[$eachType['post_type_id']] );
			}
		}
		$filter = new Ayoola_Filter_SelectListArray( 'post_type_id', 'post_type');
		$options = $filter->filter( $options );
        $postTypesAvailable = Application_Article_Type_TypeAbstract::$presetTypes + $options;
        return $postTypesAvailable;
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
        if( ! empty( $_GET['post_type_id'] ) && empty( $values['post_type'] ) )
        {
            $values['post_type'] = $_GET['post_type_id'];
        }
                
        $fieldset->addElement( array( 'name' => 'post_type', 'label' => 'Type Name', 'title' => 'Enter post type name, e.g. Article', 'placeholder' => 'e.g. Article', 'type' => 'InputText', 'value' => @$values['post_type'], ) ); 

        $all = $this->getDbData();
        $presets = self::$presetTypes;
        foreach( $all as $each )
        {
            $presets[$each['post_type_id']] = $each['post_type'];
        }
        asort( $presets );
        $fieldset->addElement( array( 'name' => 'article_type', 'label' => 'Post Main Feature', 'title' => 'Choose the kind of post this is...', 'type' => 'Select', 'value' => @$values['article_type'], ), $presets );  

        $options = 	array( 
                            'multi-price' => 'Multiple Pricing', 
                            'subscription-options' => 'Subscription Options', 
                            'datetime' => 'Date and Time', 
                            'location' => 'Location', 
                            'audio' => 'Play Audio', 
                            'gallery' => 'Gallery Images', 
                            'privacy' => 'Privacy Options', 
                            'description' => 'Short Description', 
                            'cover-photo' => 'Cover Photo', 
                            'category' => 'Categories',  
                            'post-list' => 'Post List',  
                            );

        $options = $options + self::$presetTypes;
        asort( $options );

		//	preset values     
		$i = 0;
		//	Build a separate demo form for the previous group
		$featureForm = new Ayoola_Form( array( 'name' => 'preset...' )  );
		$featureForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
		$featureForm->wrapForm = false;
		do
		{
				
			$featureFieldset = new Ayoola_Form_Element; 
			$featureFieldset->allowDuplication = true;
			$featureFieldset->duplicationData = array( 'add' => '+ Add feature', 'remove' => '- Remove feature', 'counter' => 'preset_counter', );
			$featureFieldset->container = 'span';
		
			$featureFieldset->addElement( array( 'name' => 'post_type_options', 'label' => '', 'style' => 'width:45%;', 'type' => 'Select', 'multiple' => 'multiple', 'value' => @$values['post_type_options'][$i], ), array( '' => 'Select Feature' ) + $options ); 
			$featureFieldset->addElement( array( 'name' => 'post_type_options_name', 'label' => '', 'placeholder' => 'Field name suffix (optional)', 'style' => 'width:45%;', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['post_type_options_name'][$i], ) ); 

			$i++;
			$featureForm->addFieldset( $featureFieldset );
		}
		while( isset( $values['post_type_options'][$i] ) );    
		$fieldset->addElement( array( 'name' => 'xxxxx', 'type' => 'Html', 'value' => '', 'data-pc-element-whitelist-group' => 'post_type_options' ), array( 'html' => '<label style="display:block;">Other Post Type Features</label>' . $featureForm->view() . '', 'fields' => 'post_type_options,post_type_options_name' ) );	
        
        //	supplementary form for creating post
        $options = new Ayoola_Form_Table(); 
        $options = $options->select();
        require_once 'Ayoola/Filter/SelectListArray.php';
        $filter = new Ayoola_Filter_SelectListArray( 'form_name', 'form_title');
        $options = $filter->filter( $options );
        $fieldset->addElement( array( 'name' => 'supplementary_form', 'onchange' => 'ayoola.div.manageOptions( { database: "Ayoola_Form_Table", listWidget: "Ayoola_Form_List", values: "form_name", labels: "form_title", element: this } );', 'label' => 'Supplementary Creation form', 'type' => 'Select', 'value' => @$values['supplementary_form'] ), array( '' => 'Please select...' ) + $options + array( '__manage_options' => '[Manage Multi-Options]' ) ); 

        $fieldset->addElement( array( 'name' => 'post_type_custom_fields', 'label' => 'Supplementary Custom Fields', 'title' => 'Custom Fields for Post Type', 'placeholder' => 'e.g. brand, size, color ', 'type' => 'InputText', 'value' => @$values['post_type_custom_fields'], ) ); 
        
        //	Auth Level
		
		$authLevel = new Ayoola_Access_AuthLevel;
		$authLevel = $authLevel->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$authLevel = $filter->filter( $authLevel );
        $fieldset->addElement( array( 'name' => 'auth_level', 'label' => 'Who can create a post of this type', 'type' => 'SelectMultiple', 'value' => @$values['auth_level'] ? : array( 0 ) ), $authLevel ); 
        
        $fieldset->addElement( array( 'name' => 'view_auth_level', 'label' => 'Who can view a post of this type', 'type' => 'SelectMultiple', 'value' => @$values['view_auth_level'] ? : array( 0 ) ), $authLevel ); 

        $widgets = Ayoola_Object_Embed::getWidgets();
        
		$fieldset->addElement( array( 'name' => 'view_widget', 'label' => 'Widget to handle the view', 'type' => 'Select', 'onchange' => 'if( this.value == \'__custom\' ){ var a = prompt( \'Custom Widget Class Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }', 'value' => @$values['view_widget'] ? : '' ), array( '' => 'Application_Article_View (default)') + $widgets ); 

//		$i++;
        $fieldset->addLegend( $legend );
    //	$fieldset->wrapper = 'white-background';
        $fieldset->wrapper = 'white-content-theme-border';   

		//	preset values
		$i = 0;
		//	Build a separate demo form for the previous group
		$presetForm = new Ayoola_Form( array( 'name' => 'preset...' )  );
		$presetForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
		$presetForm->wrapForm = false;
		do
		{
				
			$presetFieldset = new Ayoola_Form_Element; 
			$presetFieldset->allowDuplication = true;
			$presetFieldset->duplicationData = array( 'add' => '+ Add preset field', 'remove' => '- Remove preset field', 'counter' => 'preset_counter', );
			$presetFieldset->container = 'span';
		//	$presetFieldset->wrapper = 'white-content-theme-border';
		
			$presetFieldset->addElement( array( 'name' => 'preset_keys', 'label' => '', 'placeholder' => 'key', 'style' => 'width:45%;', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['preset_keys'][$i], ) ); 
			$presetFieldset->addElement( array( 'name' => 'preset_values', 'label' => '', 'placeholder' => 'value', 'style' => 'width:45%;', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['preset_values'][$i], ) ); 

			$i++;
			$presetForm->addFieldset( $presetFieldset );
		}
		while( isset( $values['preset_keys'][$i] ) );    
		$fieldset->addElement( array( 'name' => 'xx', 'type' => 'Html', 'value' => '', 'data-pc-element-whitelist-group' => 'preset_keys' ), array( 'html' => '<label style="display:block;">Preset Fields</label>' . $presetForm->view() . '', 'fields' => 'preset_keys,preset_values' ) );	

		$form->addFieldset( $fieldset );   		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
