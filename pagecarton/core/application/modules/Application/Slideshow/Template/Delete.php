<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Slideshow_Template_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $
 */

/**
 * @see Application_Slideshow_Template_Abstract
 */
 
//require_once 'Ayoola/Slideshow/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Slideshow_Template_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Application_Slideshow_Template_Delete extends Application_Slideshow_Template_Abstract
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
			$this->createConfirmationForm( 'Delete', 'Delete this slideshow template, "' . $data['template_name'] . '" and all its associated files? This cannot be undone.' );
			$this->setViewContent( $this->getForm()->view(), true );
			
			
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			//	Removing from DB is done late to avoid orphan links
			if( ! $this->deleteDb( false ) )
			{	
				return false;
			}
			$this->setViewContent( 'Slideshow deleted successfully', true );
		}
		catch( Ayoola_Exception $e ){ return false; }
    } 
}
