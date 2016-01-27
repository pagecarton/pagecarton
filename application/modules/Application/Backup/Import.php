<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup_Import
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Import.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Backup_Abstract
 */
 
require_once 'Application/Backup/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup_Import
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Import extends Application_Backup_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//		var_export( $fileToUpload );
		try
		{
			$this->setViewContent( $this->getForm()->view() );		
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			switch( $values['import_option'] ) 
			{
				case 'import_from_local_device':
					$tempName = tempnam( sys_get_temp_dir(), '' );
					
				break;
				case 'upload_from_other_sites':
					$tempName = tempnam( sys_get_temp_dir(), '' ) . '.tar.gz';
					
					file_put_contents( $tempName, file_get_contents( $values['backup_url'] ) );
				//	var_export( filesize( $tempName ) );
				//	$tempName = $values['backup_url'];
				break;

			}
		//	var_export( $tempName );
			$phar = 'Ayoola_Phar_Data';
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
			$this->setViewContent( '<div class="boxednews goodnews">Success! "' .$information['backup_name']. '" imported.</div>', true );
		}
  		catch( Exception $e )
		{
			$this->getForm()->setBadnews( 'Invalid Backup File' );
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );		
		//	$this->setViewContent( $extention );		
			return false;
		} 
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
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
	//	$form->submitValue = '' ;
	//	if( ! @$_REQUEST['access_level'] )
		{
			$fieldset = new Ayoola_Form_Element;
			$options = array( 
								'import_from_local_device' => 'Upload from local device',
								'upload_from_other_sites' => 'Import from an existing website',
			
			);
			$fieldset->addElement( array( 'name' => 'import_option', 'label' => 'Select a import location', 'type' => 'Radio', 'required' => 'required', 'value' => ( @$values['import_option'] ) ), $options );  
			$fieldset->addRequirement( 'import_option', array( 'InArray' => array_keys( $options )  ) );
			$fieldset->addLegend( 'Where will you be importing from?' );
			unset( $authLevel );
			$fieldset->addElement( array( 'name' => __CLASS__, 'value' => 'Continue...', 'type' => 'Submit' ) );
			$form->addFieldset( $fieldset ); 
		}
		$fieldset = new Ayoola_Form_Element;
		switch( $this->getGlobalValue( 'import_option' ) ) 
		{
			case 'import_from_local_device':
				$fieldset->addLegend( 'Enter the backup URL of the backup you wish to import' );
				$fieldset->addElement( array( 'name' => 'backup_base64', 'style' => '', 'placeholder' => 'Enter backup URL', 'label' => '', 'type' => 'Document', 'data-allow_base64' => true, 'data-document_type' => 'application', 'value' => @$values['backup_base64'] ) );
			break;
			case 'upload_from_other_sites':
				$fieldset->addLegend( 'Enter the backup URL of the backup you wish to import' );
				$fieldset->addElement( array( 'name' => 'backup_url', 'style' => '', 'placeholder' => 'Enter backup URL', 'label' => '', 'type' => 'InputText', 'value' => @$values['backup_url'] ) );
			break;

		}
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => 'Import', 'type' => 'Submit' ) );
		$fieldset->addLegend( 'Import Backup' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
