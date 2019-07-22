<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Domain_Registration_Price_Abstract
 */
 
require_once 'Application/Domain/Registration/Price/Abstract.php';


/**
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration_Price_Creator extends Application_Domain_Registration_Price_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Save', 'Add a new item to the domain price list' );
		$this->setViewContent( $this->getForm()->view() );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
	//	var_export( $values );
		if( ! $this->insertDb( $values ) ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent(  '' . self::__( 'A new item added to the domain price list.' ) . '', true  );
    } 
	// END OF CLASS
}
