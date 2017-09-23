<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Blog_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Blog_Exception 
 */
 
require_once 'Application/Blog/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Blog_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Blog_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'blog_name' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Blog';
	
    /**
     * Options forced on a created blog
     * 
     * @var array
     */
	protected static $_forcedOptions;
	
    /**
     * Options forced on a created blog
     * 
     * @var array
     */
	protected static $_notEnabledMessage = 'This article is currently not enabled. It may still be awaiting approval. Please check back later';
	
	
    /**
     * returns the blog folder
     * 
     */
	public static function getFolder()
    {
		return Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES .  DS . 'blogs';
	}
	
    /**
     * Returns _dbData
     * overides parent to set this to public
     * 
     * return array
     */
	public function getDbData()
    {
		if( is_null( $this->_dbData ) ){ $this->setDbData(); }
		return $this->_dbData;
    } 
	
    /**
     * returns the blog path
     * 
     */
	public static function getFilePath( $path, $filename )
    {
		$path = str_ireplace( self::getFolder(), '', $path );
		return self::getFolder() . $path . DS . $filename;
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
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'enctype' => 'multipart/form-data' ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->addElement( array( 'name' => 'blog_title', 'placeholder' => 'Enter a title for the article here...', 'type' => 'InputText', 'value' => @$values['blog_title'] ) );
		$fieldset->addElement( array( 'name' => 'blog_content', 'description' => 'Enter the article here', 'rows' => '10', 'placeholder' => 'Enter content here', 'type' => 'TextArea', 'value' => @$values['blog_content'] ) );
		$fieldset->addElement( array( 'name' => 'blog_description', 'placeholder' => 'Enter the article description here...', 'type' => 'TextArea', 'value' => @$values['blog_description'] ) );
		$fieldset->addElement( array( 'name' => 'blog_tags', 'placeholder' => 'Enter blog tags separated by comma', 'type' => 'InputText', 'value' => @$values['blog_tags'] ) );

		$date = is_null( $values ) ? 'blog_creation_date' : 'blog_modified_date';
		$fieldset->addElement( array( 'name' => $date, 'type' => 'Hidden' ), array() );	
		$fieldset->addElement( array( 'name' => 'enabled', 'type' => 'Select', 'value' => @$values['enabled'] ), array( 'No', 'Yes' ) );
		$options = new Application_Category;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'category_id', 'category_name');
		$options = array( 10009 => 'Uncategorized' ) + $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'category_id', 'label' => 'Category', 'type' => 'Select', 'value' => @$values['category_id'] ), $options );
		$fieldset->addRequirement( 'category_id', array( 'Int' => null, 'InArray' => array_keys( $options )  ) );
		unset( $options );
		
		$authLevel = new Ayoola_Access_AuthLevel;
		$authLevel = $authLevel->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$authLevel = $filter->filter( $authLevel );
		$fieldset->addElement( array( 'name' => 'auth_level', 'type' => 'Select', 'value' => @$values['auth_level'] ), $authLevel );
		$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $authLevel )  ) );
		unset( $authLevel );
		
		//	Add a picture to blog		
		require_once 'Ayoola/Doc.php';		
		$doc = new Ayoola_Doc_Document;
		$doc = $doc->select();
		$filter = new Ayoola_Filter_FileExtention();
		$allowedExtentions = array( 'jpg', 'gif', 'png', );
	//	var_export( $doc );
		foreach( $doc as $key => $each )
		{
			if( ! in_array( $filter->filter( $each['document_url'] ), $allowedExtentions  ) ){ unset( $doc[$key] ); }
		}
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'document_id', 'document_name' );
		$doc = $filter->filter( $doc );	
		$fieldset->addElement( array( 'name' => 'document_id', 'label' => 'Cover picture', 'type' => 'Select', 'value' => @$values['document_id'] ), $doc );
		$fieldset->addRequirement( 'document_id', array( 'InArray' => array_keys( $doc )  ) );
		unset( $doc );

		$fieldset->addFilter( $date, array( 'DefiniteValue' => time() ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirement( 'blog_content', array( 'WordCount' => array( 100,100000 ) ) );
		$fieldset->addRequirement( 'blog_title', array( 'WordCount' => array( 6,1000 ) ) );
		$fieldset->addRequirement( 'blog_description', array( 'WordCount' => array( 0,2000 ) ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
