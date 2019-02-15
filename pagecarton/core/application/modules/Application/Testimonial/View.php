<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Testimonial_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Testimonial_Abstract
 */
 
require_once 'Application/Testimonial/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Testimonial_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Testimonial_View extends Application_Testimonial_Abstract
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
			if( ! $this->getDbData() ){ $this->setViewContent( '<a class="badnews boxednews">No testimonials to view</a>' ); }
			$this->setViewContent( self::getXml()->saveHTML() );
			if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
			{
				$this->setViewContent( '<a class="badnews boxednews" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Testimonial_List/">Edit Testimonials</a>' );
			}
			$this->setViewContent( '<a class="goodnews boxednews" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Testimonial_Creator/">Add a testimonial</a>' );
		}
		catch( Exception $e ){ return $this->setViewContent( 'Error with Testimonial package', true ); }
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
		$this->_xml->appendChild( $table );
		$values = $this->getDbTable()->select( null, array( 'verified' => 1 ) );
	//	var_export( $values );
	//	var_export( $this->getParameter( 'max_testimanials' ) );
	
		if( $this->getParameter( 'shuffle' ) )
		{
			shuffle( $values );
		}
		$counter = 0;
		$template = null;
		$max = $this->getParameter( 'max_testimanials' ) ? : 2;
	//	var_export( $max );  
		if( ! $values )
		{
			//	Template is an empty string
			$template = ' ';
		}
		foreach( $values as $data )
		{
			// title  
			if( $counter == $max ){ break; }
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'th' );
			$row->appendChild( $columnNode );
			$link = $this->_xml->createElement( 'a', $data['full_name'] . ' - ' . $data['city'] );
	//		$link->setAttribute( 'href', $data['Testimonial_url'] );
			$columnNode->appendChild( $link );
			$newTestimonialLink = '/tools/classplayer/get/object_name/Application_Testimonial_Creator/';
			$editTestimonialLink = '';
			if( self::hasPriviledge() )
			{
				$editTestimonialLink = '/tools/classplayer/get/object_name/Application_Testimonial_List/';
			}
			
			$template .= self::replacePlaceholders( $this->getParameter( 'markup_template' ), $data + array( 'template_record_count' => $counter, 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'editor_link' => $editTestimonialLink, 'creator_link' => $newTestimonialLink, 'record_count' => $counter, ) );
/* 			//	image
			$row = $this->_xml->createElement( 'tr' ); 
			$row = $table->appendChild( $row );
			$image = $this->_xml->createElement( 'img' );
			$image->setAttribute( 'src', $data['Testimonial_image_url'] );
			$image->setAttribute( 'height', '100px' );
			$image->setAttribute( 'align', 'center' );
			$columnNode = $this->_xml->createElement( 'td' );
			$columnNode->appendChild( $image );
			$row->appendChild( $columnNode );
 */			
			//	content
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'td', $data['testimonial'] );
		//	$columnNode->setAttribute( 'colspan', 2 );
			$row->appendChild( $columnNode );
			$counter++;
		}

		//	update the markup template
		$this->_parameter['markup_template'] = $template;
//		var_export( $this->_parameter['markup_template'] );    
	//	var_export( count( $value ) );
    } 
	// END OF CLASS
}
