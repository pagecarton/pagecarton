<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Category_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Category_Abstract
 */
 
require_once 'Application/Category/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Category_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Category_Editor extends Application_Category_Abstract
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
				if( ! @$_REQUEST['category_name'] || ! @$_REQUEST['auto_create_category'] )
				{
				//	var_export( $_REQUEST['category_name'] );
					return false; 
				}
				else
				{
					$data = $_REQUEST;
					$data['category_label'] = @$data['category_label'] ? : ucwords( implode( ' ', explode( '-', $data['category_name'] ) ) );
					if( @$_REQUEST['parent_category'] )
					{
						$data['parent_category'] = $data['parent_category'] ? : array();
						$data['parent_category'] += (array) $_REQUEST['parent_category'];
					}
					$this->getDbTable()->insert( $data );
					$data = array();
					$data['category_name'] = $_REQUEST['category_name'];
				}
			}
					$data['category_label'] = @$data['category_label'] ? : ucwords( implode( ' ', explode( '-', $data['category_name'] ) ) );
			$this->createForm( 'Save', 'Update ' . $data['category_label'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }

	//		var_export( $values );
			if( @$_REQUEST['parent_category'] )
			{
				
				$values['parent_category'] = @$values['parent_category'] ? : $data['parent_category'];
				$values['parent_category'] = $values['parent_category'] ? : array();
			//	var_export( $values['parent_category'] );
				if( is_scalar( $_REQUEST['parent_category'] ) )
				{
					$_REQUEST['parent_category'] = array_map( 'trim', explode( ',', $_REQUEST['parent_category'] ) );
				}
			//	var_export( $_REQUEST['parent_category'] );
				$values['parent_category'] = array_merge( $values['parent_category'], $_REQUEST['parent_category'] );
			//	var_export( $values['parent_category'] );
			}
		//	var_export( $values );
			if( $this->updateDb( $values ) ){ $this->setViewContent( '<p class="goodnews">Category edited successfully</p>', true ); }
		//	$this->createForm( 'Save', 'Update ' . $data['category_label'], $data );
			$this->setViewContent( $this->getForm()->view() );
		}
		catch( Application_Category_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
