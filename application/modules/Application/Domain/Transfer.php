<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert		Ayoola
 * @package    	Application_Domain_Transfer
 * @copyright  	Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    	http://pagecarton.com/about/license
 * @version    	$Id: Transfer.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Exception 
 */
 
require_once 'Application/Domain/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Transfer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Transfer extends Application_Domain_Registration
{
	
    /**
     * 
     * 
     */
	public function init()
    {
		try
		{
		//	var_export( $this->getObjectStorage()->retrieve() );
			$parameters = $this->getObjectStorage()->retrieve();
			if( ! empty( $parameters['suggestions'] ) ){ $this->setParameter( $parameters ); }
			else
			{
				return;
			}
		//	$this->setParameter( array( 'suggestions' => array( 'abc.com' ) ) );
		//	$this->setViewContent( '<h2>Avalaible domain name(s)</h2>' );
			$this->createForm( 'Transfer', 'Transfer domain name(s)' );
			$this->setViewContent( $this->getForm()->view() );
		//	var_export( $this->getObjectStorage()->retrieve() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $values );
			$this->subscribe( $values );
			
		}
		catch( Exception $e ){ return false; }		
		
    } 
	// END OF CLASS
}
