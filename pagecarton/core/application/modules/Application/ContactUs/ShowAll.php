<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_ContactUs_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_ContactUs_Abstract
 */
 
require_once 'Application/ContactUs/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_ContactUs_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_ContactUs_ShowAll extends Application_ContactUs_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Contact Form Feedbacks'; 

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
		//	$this->setViewContent( '<h2>' . self::getObjectTitle() . '</h2>', true );
			if( ! $this->getDbData() )
			{ 
				return $this->setViewContent( '<div class="noRecord">No one has left a message using the contact form yet.</div>' ); 
				
				return false;
			}
			$this->setViewContent( self::getXml()->saveHTML() );
		}
		catch( Exception $e ){ return $this->setViewContent( 'Error with contact us package', true ); }
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
		$table = $this->_xml->createElement( 'table' );
		$values = $this->getDbData();
	//	krsort( $values );
		$noToShow = 5;
		$count = 0;
/*
		$row = $this->_xml->createElement( 'tr' );
		$table->setAttribute( 'class', 'pc-table' );
		$row = $table->appendChild( $row );
 		$columnNode = $this->_xml->createHTMLElement( 'td', 'Contact Messages' );
		$row->appendChild( $columnNode );
		$link = $this->_xml->createElement( 'a', 'Show All' );
		$link->setAttribute( 'href', '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_ContactUs_List/' );
		$link->setAttribute( 'rel', 'spotlight;' );
		$columnNode->appendChild( $link );
 */		foreach( $values as $data )
		{
			$count++;
			
			// subject
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createHTMLElement( 'th', $data['contactus_subject'] );
			$row->appendChild( $columnNode );
			
			// body message
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'td', $data['contactus_message'] );
			$row->appendChild( $columnNode );
			$link = $this->_xml->createElement( 'a', ' - Show Full Message' );
			$link->setAttribute( 'href', '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_ContactUs_View/contactus_id/' . strtolower( $data['contactus_id'] ) . '/' );
			$link->setAttribute( 'rel', 'spotlight;' );
			$columnNode->appendChild( $link );
			
			//	date
			$filter = new Ayoola_Filter_Time();		
			if( $data['contactus_last_view_date'] )
			{
				$row = $this->_xml->createElement( 'tr' );
				$row = $table->appendChild( $row );
				$columnNode = $this->_xml->createElement( 'td', 'Last Viewed ' . $filter->filter( $data['contactus_last_view_date'] ) );
				$columnNode->setAttribute( 'colspan', 1 );
				$row->appendChild( $columnNode );
			}
			else
			{
				$row = $this->_xml->createElement( 'tr' );
				$row = $table->appendChild( $row );
				$columnNode = $this->_xml->createElement( 'td', 'Recieved ' . $filter->filter( $data['contactus_creation_date'] ) );
				$columnNode->setAttribute( 'colspan', 1 );
				$row->appendChild( $columnNode );
			}
			if( $count >= $noToShow ){ break; }
		}
		$row = $this->_xml->createElement( 'tr' );
		$table->setAttribute( 'class', 'pc-table' );
		$row = $table->appendChild( $row );
 		$columnNode = $this->_xml->createElement( 'td' );
		$row->appendChild( $columnNode );
		$link = $this->_xml->createElement( 'a', 'Show All Messages' );
		$link->setAttribute( 'href', '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_ContactUs_List/' );
		$link->setAttribute( 'rel', 'spotlight;' );
		$columnNode->appendChild( $link );
	//	var_export( count( $value ) );
		$this->_xml->appendChild( $table );
    } 
	// END OF CLASS
}
