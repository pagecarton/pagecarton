<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_ContactUs_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_ContactUs_Abstract
 */
 
require_once 'Application/ContactUs/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_ContactUs_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_ContactUs_View extends Application_ContactUs_Abstract
{
	
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
			if( ! $data = $this->getIdentifierData() ){ return $this->setViewContent( 'Message Not Found', true ); }
			$pageInfo = array(
				'title' => $data['contactus_subject'] . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' )
			);
			Ayoola_Page::setCurrentPageInfo( $pageInfo );
			$this->setViewContent( self::getXml()->saveHTML(), true );
			$update = array( 'contactus_last_view_date' => time() );
			if( ! $data['contactus_first_view_date'] ){ $update['contactus_first_view_date'] = time(); }
			@$this->updateDb( $update );
		}
		catch( Exception $e ){ return $this->setViewContent( 'Error with contactus package', true ); }
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
		
		// sender info
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		
		if( $data['contactus_creator_user_id'] )
		{
			$columnNode = $this->_xml->createElement( 'td', 'User ID: ' . $data['contactus_creator_user_id'] );
			$row->appendChild( $columnNode );
		}
		$columnNode = $this->_xml->createElement( 'td', 'Name: ' . $data['contactus_firstname'] . ' ' . $data['contactus_lastname'] );
		$row->appendChild( $columnNode );
		$columnNode = $this->_xml->createElement( 'td', 'Email: ' . $data['contactus_email'] );
		$row->appendChild( $columnNode );
		$columnNode = $this->_xml->createElement( 'td', 'Phone Number: ' . $data['contactus_phone_number'] );
		$row->appendChild( $columnNode );
		
		// subject
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		$columnNode = $this->_xml->createElement( 'th', $data['contactus_subject'] );
		$columnNode->setAttribute( 'colspan', 4 );
		$row->appendChild( $columnNode );
			
		// body message
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		$columnNode = $this->_xml->createElement( 'td', $data['contactus_message'] );
		$columnNode->setAttribute( 'colspan', 4 );
		$row->appendChild( $columnNode );
		
		//	date
		$filter = new Ayoola_Filter_Time();
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		$columnNode = $this->_xml->createElement( 'td', 'Recieved ' . $filter->filter( $data['contactus_creation_date'] ) );
		$columnNode->setAttribute( 'colspan', 4 );
		$row->appendChild( $columnNode );
		if( $data['contactus_first_view_date'] )
		{
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'td', 'First Viewed ' . $filter->filter( $data['contactus_first_view_date'] ) );
			$columnNode->setAttribute( 'colspan', 4 );
			$row->appendChild( $columnNode );
		}
		if( $data['contactus_last_view_date'] )
		{
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'td', 'Last Viewed ' . $filter->filter( $data['contactus_last_view_date'] ) );
			$columnNode->setAttribute( 'colspan', 4 );
			$row->appendChild( $columnNode );
		}
	
	//	var_export( count( $value ) );
		$this->_xml->appendChild( $table );
    } 
	// END OF CLASS
}
