<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Download_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Download_Abstract
 */
 
require_once 'Application/Download/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Download_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Download_View extends Application_Download_Abstract
{
		
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
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
			if( ! $this->getDbData() ){ return $this->setViewContent( '<p class="noRecord">No Downloads to View</p>', true ); }
			$this->setViewContent( self::getXml()->saveHTML(), true );
		}
		catch( Exception $e ){ return $this->setViewContent( 'Error with Download package', true ); }
	//	var_export( $this->getDbData() );
    } 
	
    /**
     * Returns the Xml
     * 
     * @return Ayoola_Xml
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
		$this->_xml = new Ayoola_Xml();
		$table = $this->_xml->createElement( 'ul' );
		$this->_xml->appendChild( $table );
		$values = $this->getDbData();
		shuffle( $values );
		$counter = 0;
		foreach( $values as $data )
		{
			$list = $this->_xml->createElement( 'li' );
			$list->setAttribute( 'style', 'list-style:none;' );
			$list = $table->appendChild( $list );

			// title
			$row = $this->_xml->createElement( 'h4' );
			$row = $list->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'span' );
			$row->appendChild( $columnNode );
			$link = $this->_xml->createElement( 'a', $data['Download_title'] );
			$link->setAttribute( 'href', $data['Download_url'] );
			$columnNode->appendChild( $link );
					
			//	image
			$row = $this->_xml->createElement( 'span' );
			$row = $list->appendChild( $row );
			$image = $this->_xml->createElement( 'img' );
			$image->setAttribute( 'src', $data['Download_image_url'] );
			$image->setAttribute( 'style', 'float:left;max-height:50px;max-width:40%;margin-right:5px;' );
			$image->setAttribute( 'align', 'center' );
			$columnNode = $this->_xml->createElement( 'span' );
			$columnNode->appendChild( $image );
			$row->appendChild( $columnNode );

			//	content
			$row = $this->_xml->createElement( 'span' );
			$row = $list->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'span', $data['Download_content'] );
		//	$columnNode->setAttribute( 'colspan', 2 );
			$row->appendChild( $columnNode );
			
			$counter++;
			if( $counter == 2 ){ break; }
		}
		if( self::hasPriviledge() )
		{
			$list = $this->_xml->createElement( 'li' );
			$list->setAttribute( 'style', 'list-style:none;' );
			$list = $table->appendChild( $list );

			// title
			$row = $this->_xml->createElement( 'h4' );
			$row = $list->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'span' );
			$row->appendChild( $columnNode );
			$link = $this->_xml->createElement( 'a', 'Edit Download Placements' );
			$link->setAttribute( 'rel', 'spotlight;width=300px;height=300px;' );
			$link->setAttribute( 'href', '/tools/classplayer/get/object_name/Application_Download_List/' );
			$columnNode->appendChild( $link );
		}
	//	var_export( count( $value ) );
    } 
	// END OF CLASS
}
