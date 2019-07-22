<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Extension_Import_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Import_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Extension_Import_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Import_Delete extends Ayoola_Extension_Import_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Remove plugin'; 
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$this->createDeleteForm( $data['extension_title'] );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	Disable extension
			$class = new Ayoola_Extension_Import_Status( array( 'switch' => 'off' ) );
			$class->init();
			
			//	remove files
			$dir = @constant( 'EXTENSIONS_PATH' ) ? Ayoola_Application::getDomainSettings( EXTENSIONS_PATH ) : ( APPLICATION_DIR . DS . 'extensions' );
			$dir = $dir . DS . ( $data['extension_name'] ? : 'avoid deleting all directories' );
	//		var_export( $dir );
			if( is_dir( $dir ) )
			{
				Ayoola_Doc::removeDirectory( $dir, true );
			}
			
			//delete from db	
			if( $this->deleteDb( false ) )
			{ 
				$this->setViewContent(  '' . self::__( '<p class="goodnews">Plugin deleted successfully</p>' ) . '', true  ); 
			} 
		}
		catch( Ayoola_Extension_Import_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
