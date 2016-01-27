<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Blog_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Blog_Abstract
 */
 
require_once 'Application/Blog/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Blog_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Blog_ShowAll extends Application_Blog_Abstract
{

    /**
     * The Options Available as a Viewable Object
     * This property makes it possible to use this same class
     * To serve all menu available on the site
     * 
     * @var array
     */
	protected $_classOptions;

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
	//	var_export( 'ewe' );
		try
		{
			if( isset( $_GET['category_id'] ) )
			{
				$this->_dbWhereClause = array( 'category_id' => $_GET['category_id'] );
			//	$this->setViewContent( '<p>Showing articles from ', true );
			}
			if( ! $this->getDbData() ){ return $this->setViewContent( '<p class="badnews">Articles have not yet been published on this website. Please check back later.</p>', true ); }
			$this->setViewContent( self::getXml(), true );
			$this->setViewContent( '<p><a href="?no_of_blogs_to_show=1000">Show all blogs</a></p>' );
		}
		catch( Exception $e ){ return $this->setViewContent( '<p>Error with blog package.</p>', true ); }
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
	//	krsort( $values );
		$i = 0; //	5 is our max blogs to show
		$j = 5; //	5 is our max blogs to show
	//	var_export( $this->_viewOption );
		$this->_viewOption = intval( $this->_viewOption );
		$j = $this->_viewOption ? : $j;
		$j = is_numeric( @$_GET['no_of_blogs_to_show'] ) ? intval( $_GET['no_of_blogs_to_show'] ) : $j;
	//	var_export( $j );
		while( $values && $i != $j )
		{
			$this->_xml .= '<li style="list-style-type: none;">';
			$i++;
			$data = @array_map( 'strip_tags', array_shift( $values ) );
			
			//	title
			$this->_xml .= '<h2><a href="' . Ayoola_Application::getUrlPrefix() . '/' . strtolower( $data['blog_name'] ) . '/">' . $data['blog_title'] . '</a></h2>';
			
			// image
			$this->_xml .= '<img style="width:100px;" src="' . Ayoola_Doc::uriToDedicatedUrl( $data['document_url'] ) . '" alt="' . $data['blog_title'] . "'s photo" . '"/>';
			
			//	content
		//	var_export( $data );
			$this->_xml .= '<p style="">' . $data['blog_description'] .  ' <a href="' . Ayoola_Application::getUrlPrefix() . '/' . strtolower( $data['blog_name'] ) . '/"> more... </a> ' . '</p>';
			
			//	date
			$filter = new Ayoola_Filter_Time();
			$data['blog_modified_date'] = $data['blog_modified_date'] ? 'Modified ' . $filter->filter( $data['blog_modified_date'] ) : 'Created ' . $filter->filter( $data['blog_creation_date'] );
			$this->_xml .= '<p>' . $data['blog_modified_date'] . '</p>';
			
			$this->_xml .= '</li>';
			
		}
		$this->_xml .= '</ul>';
	//	var_export( count( $value ) );
    } 
	
    /**
     * This method returns the _classOptions property
     *
     * @param void
     * @return array
     */
    public function getClassOptions()
    {
		if( null === $this->_classOptions )
		{
			$this->_classOptions = range( 1, 10 );
		}
		return array_combine( $this->_classOptions, $this->_classOptions );;
    } 	
	// END OF CLASS
}
