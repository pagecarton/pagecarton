<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_Editor extends Ayoola_Page_Layout_Abstract
{	
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()  
    {
		try{ $this->setIdentifier(); }
		catch( Ayoola_Page_Layout_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->createForm( 'Edit Layout', 'Editing "' . $identifierData['layout_name'] . '"', $identifierData );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
//		htmlspecialchars_decode( var_export( $values ) );
		//	Update Screenshot
		$screenshot = Ayoola_Doc::getDocumentsDirectory() . $values['screenshot'];
		if( is_file( $screenshot ) )
		{
			$screenshot = file_get_contents( $screenshot );
			file_put_contents( Ayoola_Doc::getDocumentsDirectory() . '/layout/' . $identifierData['layout_name'] . '/screenshot.jpg', $screenshot );
		}

		
		if( ! $this->updateDb() )
		{ 
			$this->setViewContent( '<p class="badnews">Error: could not save layout template.</p>.', true ); 
			return false;
		}
		if( $this->updateFile() ){ $this->setViewContent( '<p class="boxednews goodnews">Layout file edited successfully.</p>', true ); }		
	//	$this->setViewContent( 'Layout saved successfully' );
    } 
	// END OF CLASS
}
