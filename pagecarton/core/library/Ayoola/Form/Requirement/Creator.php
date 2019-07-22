<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Ayoola_Form_Requirement_Abstract
 */
 
require_once 'Ayoola/Form/Requirement/Abstract.php';


/**
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form_Requirement_Creator extends Ayoola_Form_Requirement_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Create', 'Create a Form Requirement' );
		$this->setViewContent(  '' . self::__( '<blockquote>Creating a form requirement offers a way of dynamically requesting for frequently needed information, like e-mail address in other forms. The require information must be submitted by a user before the form is finally submitted.</blockquote>' ) . '', true  );
		$this->setViewContent( $this->getForm()->view() );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$values['requirement_name'] = $values['requirement_label'];
		$filter = new Ayoola_Filter_Name();
		$filter->replace = '-';
		$access = new Ayoola_Access();
		$values['requirement_name'] = trim( $filter->filter( strtolower( $values['requirement_name'] ) ) , '-' );
		if( ! $this->insertDb( $values ) ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent(  '' . self::__( 'Form Requirement created successfully.' ) . '', true  );
   } 
	// END OF CLASS
}
