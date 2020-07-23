<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Object_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see 
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Object_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Object_Creator extends Ayoola_Object_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	var_export( __LINE__ );
		$this->createForm( 'Save', 'Add a new widget' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; } 
		$filter = new Ayoola_Filter_Name();
		$values['object_name'] = strtolower( $filter->filter( $values['class_name'] ) );
		if( $this->insertDb( $values ) ){ $this->setViewContent(  '' . self::__( '<p class="boxednews goodnews">Widget Created</p>' ) . '', true  ); }
    } 
	// END OF CLASS
}
