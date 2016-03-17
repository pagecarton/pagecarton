<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Blog_Category
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Category.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Blog_Abstract
 */
 
require_once 'Application/Blog/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Blog_Category
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Blog_Category extends Application_Blog_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Category';

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
			if( ! $this->getDbData() ){ return $this->setViewContent( '<p>ERROR - No category to display</p>', true ); }
			$this->setViewContent( self::getXml(), true );
			$url = Ayoola_Page::appendQueryStrings( array( 'no_of_blogs_to_show' => 1000 ) );
			$this->setViewContent( '<p><a href="' . $url . '">All categories</a></p>' );
		}
		catch( Exception $e ){ return $this->setViewContent( 'Error with category package', true ); }
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
		$this->_xml = '<ul class="features clearfix">';
		$values = $this->getDbData();
	//	var_Export( $values );
		$i = 0; //	5 is our max blogs to show
		$j = is_numeric( @$_GET['no_of_blogs_to_show'] ) ? intval( $_GET['no_of_blogs_to_show'] ) : 5;
		while( $values && $i != $j )
		{
			$this->_xml .= '<li>';
			$i++;
			$data = array_map( 'strip_tags', array_shift( $values ) );
			
			//	content
			$url = Ayoola_Page::appendQueryStrings( array( 'category_id' => $data['category_id'] ), '/blog/' );
			$content = '<a href="' . $url . '"> ' . $data['category_name'] . ' </a>';
			$this->_xml .= @$_GET['category_id'] === $data['category_id'] ? "<h4>{$content}</h4>" : "<p>{$content}</p>";
			
			$this->_xml .= '</li>';
			
		}
		$this->_xml .= '</ul>';
	//	var_export( count( $value ) );
    } 
	// END OF CLASS
}
