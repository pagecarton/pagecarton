<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Wrapper_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see 
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Wrapper_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Object_Wrapper_Creator extends Ayoola_Object_Wrapper_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Create Wrapper', 'Create a new Wrapper' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$filter = new Ayoola_Filter_Name();
		$filter->replace = '-';
		$values['wrapper_name'] = strtolower( $filter->filter( $values['wrapper_label'] ) );
		if( $this->insertDb( $values ) )
		{ 
			$this->setViewContent( '<span class="boxednews normalnews centerednews">A new wrapper has been created successfully.</span>', true ); 
		}
    } 
	// END OF CLASS
}
