<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    OpenSSL_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $
 */

/**
 * @see OpenSSL_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton
 * @package    OpenSSL_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class OpenSSL_Delete extends OpenSSL_Abstract
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
			$this->createConfirmationForm( 'Delete', 'Delete this encryption keys"' . $data['encryption_name'] . '" and all its associated files? This cannot be undone.' );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( $data ); 
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $this->deleteDb() );
		//	var_export( $values );
			if( $this->deleteDb( false ) )
			{ 
				$this->setViewContent(  '' . self::__( 'Encryption keys deleted successfully' ) . '', true  ); 
			} 
		}
		catch( Exception $e )
		{ 
		//	return false; 
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}
    } 
}
