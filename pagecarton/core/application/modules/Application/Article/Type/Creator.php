<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract
 */
 
require_once 'Application/Category/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Creator extends Application_Article_Type_TypeAbstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Save', 'Add a new post type' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		
	//	var_export( $values );
		$values['post_type_id'] = @$values['post_type_id'] ? : $values['post_type'];
		if( ! empty( $_GET['post_type_id'] ) )
		{
			$values['post_type_id'] = $_GET['post_type_id'];
		}
	//	var_export( $values );
		$filter = new Ayoola_Filter_Name();
		$filter->replace = '-';
		$access = new Ayoola_Access();
		$values['post_type_id'] = trim( $filter->filter( strtolower( $values['post_type_id'] ) ) , '-' );
		if( ! $this->insertDb( $values ) )
		{ 
			return $this->setViewContent( $this->getForm()->view(), true ); 
		}
		$this->setViewContent( '<p>New post type saved successfully.</p>', true );
   } 
	// END OF CLASS
}
