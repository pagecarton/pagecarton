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
					$this->getDbTable()->insert( $_REQUEST );
					$data = array();
					$data['category_name'] = $_REQUEST['category_name'];
				}
			}
			$this->createForm( 'Edit', 'Edit ' . $data['category_name'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent( 'Category edited successfully', true ); }
		}
		catch( Application_Category_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
