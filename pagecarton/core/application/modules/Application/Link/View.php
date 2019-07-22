<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Link_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Link_Abstract
 */
 
require_once 'Application/Link/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Link_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Link_View extends Application_Link_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'link_name',  );

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
			if( ! $this->getIdentifierData() ){ return $this->setViewContent(  '' . self::__( 'Link Not Found' ) . '', true  ); }
			$this->setViewContent( self::getXml()->saveHTML(), true );
		}
		catch( Exception $e ){ return $this->setViewContent(  '' . self::__( 'Error with link package' ) . '', true  ); }
	//	var_export( $this->_xml );
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
		$data = $this->getIdentifierData();
		$this->_xml = new Ayoola_Xml();
		$table = $this->_xml->createElement( 'table' );
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		
		// title
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		$columnNode = $this->_xml->createElement( 'th' );
		$row->appendChild( $columnNode );
		$link = $this->_xml->createElement( 'a', $data['link_title'] );
		$link->setAttribute( 'href', '/link/view/get/link_name/' . $data['link_name'] . '/' );
		$columnNode->appendChild( $link );
		
		//	image
		$image = $this->_xml->createElement( 'img' );
		$image->setAttribute( 'src', $data['document_url'] );
		$image->setAttribute( 'height', '100px' );
		$image->setAttribute( 'align', 'right' );
		$image->setAttribute( 'alt', $data['link_title'] . "'s photo" );
		$columnNode = $this->_xml->createElement( 'td' );
		$columnNode->appendChild( $image );
		$row->appendChild( $columnNode );
		
		//	content
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
	//	$columnNode = $this->_xml->createElement( 'td', $data['link_content'] );
		$html = new Ayoola_Xml();
		$html->loadHTML( htmlspecialchars_decode( $data['link_content'] ) );
		$html = $this->_xml->importNode( $html->documentElement, true );
		$columnNode->appendChild( $html );
		$columnNode->setAttribute( 'colspan', 2 );
		$row->appendChild( $columnNode );
		
		//	date
		$filter = new Ayoola_Filter_Time();
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		$columnNode = $this->_xml->createElement( 'td', 'Created ' . $filter->filter( $data['link_creation_date'] ) );
		$columnNode->setAttribute( 'colspan', 1 );
		$row->appendChild( $columnNode );
		if( $data['link_modified_date'] )
		{
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'td', 'Modified ' . $filter->filter( $data['link_modified_date'] ) );
			$columnNode->setAttribute( 'colspan', 1 );
			$row->appendChild( $columnNode );
		}
	
	//	var_export( count( $value ) );
		$this->_xml->appendChild( $table );
    } 
	// END OF CLASS
}
