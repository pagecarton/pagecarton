<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup_Upload
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Upload.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Backup_Abstract
 */
 
require_once 'Application/Backup/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup_Upload
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Upload extends Application_Backup_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( $this->getForm()->view() );		
	//	if( $this->upload() ){ $this->setViewContent( 'Backup Uploaded Successfully', true ); }
		if( $this->upload( $this->getParameter( 'local_file' ) ) ){ $this->setViewContent( 'Backup uploaded successfully.', true ); }
		else{ $this->getForm()->setBadnews( 'Error while Uploading Backup File' ); }
		return true;
    } 
		
    /**
     * Upload
     * 
     */
	protected function upload( $fileToUpload = null )
    {
	//		var_export( $fileToUpload );
		try
		{
			$tempName = $fileToUpload;
	//	var_export( $_FILES );
			if( is_null( $tempName ) )
			{ 
				if( ! $values = $this->getForm()->getValues() ){ return false; }
				@$_FILES['backup_file']  = $_FILES['backup_file']['name']  ? : $_FILES[Ayoola_Form::hashElementName( 'backup_file' )];
				if( ! $_FILES['backup_file']['name'] ){ throw new Application_Backup_Exception( 'NO FILE WAS SELECTED.' ); }
				$filename = $_FILES['backup_file']['name'];
				$tempName = $_FILES['backup_file']['tmp_name'];
				$type = $_FILES['backup_file']['type'];
				$filter = 'Ayoola_Filter_FileExtention';
				$filter = new $filter();
				$extention = $filter->filter( $filename );
				if( $extention != 'gz' || ! is_uploaded_file( $tempName ) ){ throw new Application_Backup_Exception( 'Invalid Backup File' ); }
			}
	//	var_export( $extention );
			$phar = 'Ayoola_Phar_Data';
			if( is_null( $fileToUpload ) )
			{ 
				move_uploaded_file( $tempName, $tempName . '.gz' ); 
				$tempName = $tempName . '.gz';
			}
		//	var_export( $tempName );
			$backup = new $phar( $tempName );

			$information = file_get_contents( $backup['backup_information'] );
			$information = unserialize( $information );
				//	var_export( $information );
			$newFilename = self::getFilename( ) . '.gz';
			$information['backup_filename'] = $newFilename;
		//	$this->setViewContent( $newFilename );
				//	var_export( $information );
			if( $this->getDbTable()->insert( $information ) )
			{
				$fileToUpload = $tempName;
				rename( $fileToUpload, $newFilename );
			}
		}
  		catch( Exception $e )
		{
			$this->getForm()->setBadnews( 'Invalid Backup File' );
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );		
		//	$this->setViewContent( $extention );		
			return false;
		} 
 	//	var_export( $information );
		return true;
    } 
	
    /**
     * creates the form
     * 
     */
	public function createForm()
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'enctype' => 'multipart/form-data' ) );
 //       $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'backup_file', 'description' => 'Select a backup file to upload. Upload will not work if there is a backup with the same name as the uploaded backup', 'type' => 'File' ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => 'Upload', 'type' => 'Submit' ) );
		$fieldset->addLegend( 'Upload Backup' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
