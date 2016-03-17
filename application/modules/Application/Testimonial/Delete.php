<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Testimonial   Ayoola
 * @package    Application_Testimonial_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Testimonial_Abstract
 */
 
require_once 'Application/Testimonial/Abstract.php';


/**
 * @Testimonial   Ayoola
 * @package    Application_Testimonial_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Testimonial_Delete extends Application_Testimonial_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete ' . $data['testimonial'],  'Delete Testimonial' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Testimonial deleted successfully', true ); }
		}
		catch( Application_Testimonial_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
