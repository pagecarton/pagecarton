<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_HashTag
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: HashTag.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_HashTag
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_HashTag extends Application_Article_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_HashTag';

    /**
     * The xml string
     * 
     * @var string
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
//		var_export( $articleSettings );
			if( ! Application_HashTag_Abstract::get( 'articles' ) ){ return $this->setViewContent( '<p class="badnews">There are no recent hash tags to display.</p>', true ); }
			$this->createConfirmationForm( '#Trending',  '' );
		//	$this->setViewContent( '<h4>Popular Tags:</h4>', true );
	//		$this->setViewContent( self::getXml() );
		}
		catch( Exception $e ){ return $this->setViewContent( 'Error with hashtag package', true ); }
	//	var_export( $this->getDbData() );
    } 
	
    /**
     * Returns the Xml
     * 
     * @return string
     */
	public function getXml()
    {
		if( is_null( $this->_xml ) ){ $this->setXml(); }
		return $this->_xml;
    } 
	
    /**
     * Sets the xml
     * 
     */
	public function setXml()
    {
		$form = $this->getForm()->view();
		$method = 'getTrending';
		if( $a = $this->getForm()->getValues() ){ $method = 'getAll'; }
		$values = Application_HashTag_Abstract::$method( 'articles' );
	//	var_export( $values );
		$values = array_keys( $values );
	//	var_export( $values );
	///	$this->_xml .= '<ul>' . $form . '';
	//	$this->_xml .= '<ul>' . $form . '</ul>';
		$this->_xml .= self::getHashTags( $values );
		$this->_xml .= $form;
//		$this->_xml .= '</ul>';
		//$this->_xml .= '' . $form . '</ul>';
	//	var_export( count( $value ) );
    } 
	// END OF CLASS
}
