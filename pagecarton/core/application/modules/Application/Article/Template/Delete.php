<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Template_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $
 */

/**
 * @see Application_Article_Template_Abstract
 */
 
require_once 'Ayoola/Menu/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Template_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Application_Article_Template_Delete extends Application_Article_Template_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 		
			if( ! $data = $this->getIdentifierData() ){ return false; } 
			$this->createConfirmationForm( 'Delete', 'Delete this post template, and all its associated files? This cannot be undone.' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $this->deleteDb( false ) ){ return false; }
			$this->setViewContent( 'Post template deleted successfully', true );
		}
		catch( Ayoola_Exception $e ){ return false; }
    } 
}
