<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Doc_Upload
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Upload.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Ayoola_Doc_Abstract
 */
 
require_once 'Ayoola/Doc/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Doc_Upload
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Upload extends Ayoola_Doc_Abstract
{
	
    /**
     * constructor
     * 
     */
/* 	public function __construct( $file = null )
    {
	//	if( ! is_null( $file ) ){ $this->upload( $file ); }
		parent::__construct( array( 'option' => $file ) );
    } 
 */		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	$this->createForm();
		$this->setViewContent( $this->getForm()->view() );		
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( $url = $this->upload() )
		{ 
			$this->setViewContent( "<p>Document Uploaded Successfully.</p>", true );
			$this->setViewContent( "<p><a href='$url'>Right-click to copy link or click to view file</a></p>" ); 
			$this->setViewContent( $this->getForm()->view() );		
			
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = 'Document upload successful.';
			$mailInfo['body'] = 'A new document have been uploaded on your application with the following information: "' . self::arrayToString( $values ) . '". 
			
			Here is the link to the newly uploaded document: http://' . Ayoola_Page::getDefaultDomain() . $url . '/
			';
			try
			{
				Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
		}
		else
		{
			$this->setViewContent( "<p>Document Upload not Successful.</p>" );
		}
	//	if( $this->upload() ){ null; }
	//	else{ $this->getForm()->setBadnews( 'Error while Uploading Document' ); }
		return true;
    } 
		
    /**
     * Upload
     * 
     */
	protected function upload( $fileToUpload = null )
    {
		try
		{
		//	var_export( __LINE__ );
	//	var_export( $this->getForm()->getValues() );
		//	var_export( $_FILES );
			@$_FILES['document_file']  = $_FILES['document_file']['name']  ? : $_FILES[Ayoola_Form::hashElementName( 'document_file' )];
			if( ! $_FILES['document_file']['name'] ){ throw new Ayoola_Doc_Exception( 'NO FILE WAS SELECTED.' ); }
	//	var_export( $_FILES );
			$tempName = $fileToUpload;
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( is_null( $tempName ) )
			{ 
				$filename = $_FILES['document_file']['name'];
			//	var_export( strlen( $filename ) );
				$tempName = $_FILES['document_file']['tmp_name'];
				$type = $_FILES['document_file']['type'];
				$filter = 'Ayoola_Filter_FileExtention';
				$filter = new $filter();
				$extention = $filter->filter( $filename );
				if( ! is_uploaded_file( $tempName ) ){ throw new Ayoola_Doc_Exception( 'FILE UPLOAD FAILED (STAGE 1).' ); }
			}
			$newFilename = $tempName . basename( $filename );
			move_uploaded_file( $tempName, $newFilename );
			$tempName = $newFilename;
			$tempName2 = $newFilename;
	//	var_export( $tempName );
			
			//	Check if it is an allowed document
			$document = 'Ayoola_Doc';
	//		var_export( $tempName );
			$document = new $document( array( 'option' => $tempName ) );
		//	$document->setAdapter( $filename );
			$document->getAdapter()->getLoaders();
			
			if( $values['document_name'] ){ $filename = $values['document_name'] . '.' . $extention; }
			$filename = strlen( $filename ) > 40 ? substr( $filename, 0, 40 ) . $extention : $filename;
			$values['document_name'] = $filename = str_ireplace( ' ', '_', $filename );
			$newFilename = self::getFilename( basename( $filename ), trim( $extention, '.' ) );
			$url = str_ireplace( self::getDocumentsDirectory(), '', $newFilename ); 
			$url = str_ireplace( DS, '/', $url ); 
			$values = array( 'document_filename' => $newFilename, 'document_name' => $filename, 'document_url' => $url );
			if( $this->getDbTable()->insert( $values ) )
			{
				if( is_null( $fileToUpload ) ){ rename( $tempName, $newFilename ); }
				else{ rename( $fileToUpload, $newFilename ); }
			}
			@unlink( $tempName );
		}
		catch( Exception $e )
		{
			@unlink( $tempName );
		//	echo $e->getMessage();
		//	$this->getForm()->setBadnews( 'Invalid document' );
			$this->getForm()->setBadnews( $e->getMessage() );
	//		$this->getForm()->setBadnews( $tempName2 );
			$this->setViewContent( $this->getForm()->view(), true );		
			return false;
		}
	//	var_export( $information );
		return '' . Ayoola_Application::getUrlPrefix() . '' . $values['document_url'];
    } 
	
    /**
     * creates the form
     * 
     */
	public function createForm( $submitValue = null, $legend = NULL, array $values = NULL )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'enctype' => 'multipart/form-data' ) );
		$form->submitValue = 'Upload';
		$fieldset = new Ayoola_Form_Element;
//		$fieldset->addElement( array( 'name' => 'MAX_FILE_SIZE', 'type' => 'Hidden', 'value' => 10737 ) );
		$fieldset->addElement( array( 'name' => 'document_name', 'description' => 'Choose a name for this document', 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'document_file', 'description' => 'Select a document to upload. ', 'type' => 'File' ) );
	//	$fieldset->addElement( array( 'name' => __CLASS__, 'value' => 'Upload', 'type' => 'Submit' ) );
		$fieldset->addFilter( 'document_name', array( 'Name' => null, 'MaxChar' => array( 40 ) ) );
		$fieldset->addLegend( 'Upload Document' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
