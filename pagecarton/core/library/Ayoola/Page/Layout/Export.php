<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Export
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Export.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Export
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_Export extends Ayoola_Page_Layout_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = $this->getIdentifierData() ){ return false; }
		
		//	var_export( $this->getFilename() );
			$this->getFilename();
		//	$this->createConfirmationForm( 'Export', 'Are you sure you want to export "' . $data['layout_name'] . '"? This will create an archive that could be used to duplicate this template on another application.' );
		//	$this->setViewContent( $this->getForm()->view(), true);
		//	if( ! $values = $this->getForm()->getValues() ){ return false; }
			$response = $this->buildArchive();
			if( true === $response )
			{ 
		//		$this->setViewContent(  '' . self::__( 'Layout export successfully. You may <a href="' . Ayoola_Application::getUrlPrefix() . '/layout/' . $data['layout_name'] . '/' . $data['layout_name'] . '.tar.gz">download</a> it now or copy the download link.' ) . '', true  ); 
			} 
			else
			{
				$this->setViewContent(  '' . self::__( '<p>Layout cannot be exported</p>' ) . '', true  ); 
				$this->setViewContent( self::__( '<p>' . $response . '</p>' ) ); 
			}
		}
		catch( Ayoola_Page_Layout_Exception $e ){ return false; }
    } 
	
    /**
     * Export the layout file
     * 
     */
	protected function buildArchive()
    {
		$directory = dirname( $this->getMyFilename() );
		if( ! is_dir( $directory ) )
		{
			return $directory . ' is not a valid directory.';
		}
		$filename = $directory . DS . basename( $directory ) . '.tar';
		
		//	remove previous files
		@unlink( $filename );
		@unlink( $filename . '.gz' );
		if( ! $data = $this->getIdentifierData() ){ return false; }
		
		$filter = new Ayoola_Filter_Name();
		$filter->replace = '-';
	//	$customName = substr( trim( $filter->filter( @$data['layout_name'] . '_' . microtime() ), '-' ), 0, 70 );
		$filename = CACHE_DIR . DS . $data['layout_name'] . '.tar';
		
		//	remove previous files
		@unlink( $filename );
		@unlink( $filename . '.gz' );
//	var_export( $path );
//		var_export( $data['document_url_base64'] );
		

		$phar = 'Ayoola_Phar_Data';
		$export = new $phar( $filename  );
		$export->startBuffering();  
	//	var_export( $directory );
		$export->buildFromDirectory( $directory );
		$export['layout_information'] = serialize( $this->getIdentifierData() );
		$export->stopBuffering();
	//	unlink( $filename . '.gz' );
	//	var_export( $filename );
	//	exit();		
		$export->compress( Ayoola_Phar::GZ ); 
		unset( $export );
		$phar::unlinkArchive( $filename );
		
		//	download
		header( 'Content-Type: application/x-gzip' . '' );
		$document = new Ayoola_Doc( array( 'option' => $filename . '.gz' ) ); 
		$document->download();
		
//		var_export( $data['download_base64'] ); 
//	var_export( $path ); 
		exit();
    } 
	// END OF CLASS
}
