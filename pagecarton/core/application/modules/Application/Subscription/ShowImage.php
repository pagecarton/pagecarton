<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_ShowImage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowImage.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_ShowImage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_ShowImage extends Application_Subscription_Detail
{

    /**
     * The wrapper
     * 
     * @var string
     */
	protected static $_parentTag = 'h2';
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = $this->getIdentifierData() ){ return null; }
		//	Ayoola_Doc::viewInLine( null, $data['document_url'] );
			$this->setViewContent( Ayoola_Doc::viewInLine( null, $data['document_url'] ), true );
		//	$this->setViewContent(  '' . self::__( '<img src="' . $data['document_url'] . '"/>' ) . '', true  );
		}
		catch( Exception $e ){ return; }
	//	var_export( $this->_xml );
    } 
	
	// END OF CLASS
}
