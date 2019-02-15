<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract
 */
 
require_once 'Application/Category/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Editor extends Application_Article_Type_TypeAbstract
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
			//	if( ! @$_REQUEST['category_name'] || ! @$_REQUEST['auto_create_category'] )
				{
				//	var_export( $_REQUEST['category_name'] );
					return false; 
				}
			}
			$this->createForm( 'Save', 'Edit "' . $data['post_type'] . '" post type' , $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent( '<p>Post type saved successfully</p>', true ); }
		}
		catch( Application_Article_Type_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
