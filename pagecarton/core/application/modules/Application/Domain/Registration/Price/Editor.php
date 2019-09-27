<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Price_Abstract
 */
 
require_once 'Application/Domain/Registration/Price/Abstract.php';


/**
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration_Price_Editor extends Application_Domain_Registration_Price_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() )
			{ 
				//	Autogenerate price item
				$class = new Application_Domain_Registration_Price_Creator();
				$class->fakeValues = $this->getIdentifier();
				$class->initOnce();
			//	var_export( $class->fakeValues );
			//	var_export( $class->view() );
				self::setIdentifierData();
				if( ! $data = self::getIdentifierData() )
				{ 
					return false; 
				}
			}
			$this->createForm( 'Save', 'Edit price for ' . $data['extension'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent(  '' . self::__( 'Item edited successfully' ) . '', true  ); }
		}
		catch( Application_Domain_Registration_Price_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
