<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @Status   Ayoola
 * @package    Application_Status_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Status_Abstract
 */
 
require_once 'Application/Status/Abstract.php';


/**
 * @Status   Ayoola
 * @package    Application_Status_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Status_Creator extends Application_Status_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'save', 'Leave a feedback/testimonial' );
		$this->setViewContent( $this->getForm()->view(), true );
	//	if( $this->getForm()->getValues() ){ return false; }
		if( ! $this->insertDb() ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent(  '' . self::__( '<p>Feedback saved successfully.</p>' ) . '', true  );
   } 
	// END OF CLASS
}
