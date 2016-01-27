<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    OpenSSL_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php date time ayoola $
 */

/**
 * @see OpenSSL_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';  


/**
 * @category   PageCarton CMS
 * @package    OpenSSL_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class OpenSSL_Editor extends OpenSSL_Abstract
{
	
    /**
     * This method starts the chain for update
     *
     * @param void
     * @return null
     */
    public function init()
    {
		try
		{
	 //		var_export( $data ); 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Edit encryption keys', 'Edit "' . $data['encryption_name'] . '"', $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( $_POST );
		//	var_export( $this->getForm()->getValues() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			
			if( ! $this->updateDb( $values ) ){ return false; }
			
	//		var_export( $data );
			$this->setViewContent( 'Encryption keys edited Successfully', true ); 
		}
		catch( Exception $e )
		{ 
		//	return false; 
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		}
		
    } 
}
