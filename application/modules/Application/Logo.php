<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Logo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Logo.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Logo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Logo extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = 99;
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{ 
			$this->setViewContent( '<h2>Website Logo</h2>' );
			$this->setViewContent( '<p>Right here, you will be able to view or modify the logo that would be displayed accross all pages on the website.</p>' );
		//	$this->setViewContent( $this->getForm()->view() ); 
		//	$this->setViewContent( Ayoola_Page_Editor_Image::viewInLine( array( 'image_url' => '/img/logo.png', 'crop' => true, ) ) ); 
			$this->setViewContent( Ayoola_Page_Editor_Image::viewInLine( array( 'image_url' => '/img/logo.png', 'ignore_width_and_height' => true, ) ) ); 
		//	$this->setViewContent( $this->getXml()->saveHTML() ); 
/* 			if( ! $values = $this->getForm()->getValues() ){ return false; }
			$logo = new Ayoola_Doc_Document;
			$logo = $logo->selectOne( null, array( 'document_id' => $values['document_id'] ) );
			$documentsDir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . DOCUMENTS_DIR;
			$documentUrl = $documentsDir . str_ireplace( '/', DS, $logo['document_url'] );
			$logoUrl = $documentsDir . str_ireplace( '/', DS, '/img/logo.png' );
			Ayoola_Doc::createDirectory( dirname( $logoUrl ) );
		//	var_export( $logo );
			copy( $documentUrl, $logoUrl );
 */		}
		catch( Ayoola_Exception $e ){ return false; }
	}
	
    /**
     * Returns the options available for the Logo
     * 
     */
	public function getXml()
    {
//		var_export( $info );

		$xml = new Ayoola_Xml();
		$table = $xml->createElement( 'table' );
		$table  = $xml->appendChild( $table );
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		
		//	Show the name of the Logo and upload logo
		$data = $xml->createElement( 'th', 'Change Logo' );
	//	$data->setAttribute( 'colspan', 2 );
		$data  = $row->appendChild( $data );
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
	//	$data = $xml->createHTMLElement( 'td', null );
	//	$data->setAttribute( 'colspan', 2 );
		$data  = $row->appendChild( $data );
		
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		$data = $xml->createElement( 'th', 'Current Application Logo' );
	//	$data->setAttribute( 'colspan', 2 );
		$data  = $row->appendChild( $data );
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		$data = $xml->createHTMLElement( 'td', '<img src="/img/logo.png?rand=' . rand() . '" />' );
		$data  = $row->appendChild( $data );
		
		return $xml;
	}
	
    /**
     * Creates the form to select which Logo to view
     * 
     */
	public function createForm()
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element();	

		//	Retrieve the list of the pictures in the documents Table		
		require_once 'Ayoola/Doc.php';		
		$doc = new Ayoola_Doc_Document;
		$doc = $doc->select();
		$filter = new Ayoola_Filter_FileExtention();
		$allowedExtentions = array( 'jpg', 'gif', 'png', );
		$option = array();
		foreach( $doc as $key => $each )
		{
			if( ! in_array( $filter->filter( $each['document_url'] ), $allowedExtentions  ) )
			{ 
				unset( $doc[$key] ); 
				continue;
			}
			$option[$each['document_id']] = '<img title="' . $each['document_name'] . '" style="max-height:60px;" src="' . $each['document_url'] . '" />';
		}
	//	var_export( $option );
/* 		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'document_id', 'document_name' );
		$doc = $filter->filter( $doc );	
 */		$fieldset->addElement( array( 'name' => 'document_id', 'label' => 'Choose a Logo', 'description' => 'Select image to use as logo', 'type' => 'Radio', 'value' => @$values['document_id'] ), $option );
		$fieldset->addRequirement( 'document_id', array( 'InArray' => array_keys( $option )  ) );
		unset( $doc );
		$fieldset->addElement( array( 'name' => 'Logo', 'type' => 'Submit', 'value' => 'Change Logo' ) );
		$fieldset->addLegend( 'Change Application Logo' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    }
	// END OF CLASS
}
