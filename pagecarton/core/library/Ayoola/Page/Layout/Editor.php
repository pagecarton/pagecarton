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
		try
		{

			if( ! $identifierData = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Save', 'Editing "' . $identifierData['layout_name'] . '"', $identifierData );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
	//		htmlspecialchars_decode( var_export( $values ) );  

			
			if( ! $this->updateDb() )
			{ 
				$this->setViewContent( '<p class="badnews">Error: could not save layout template.</p>.', true ); 
				return false;
			}
			if( $this->updateFile() ){ $this->setViewContent( '<p class="boxednews goodnews">Theme file saved successfully.</p>', true ); }	
			
			$this->setViewContent( '<p class="">
			<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?layout_name=' . $identifierData['layout_name'] . '" class="pc-btn pc-btn-small">Edit Codes Again</a>
			<a href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/page/edit/layout/?url=/layout/' . $identifierData['layout_name'] . '/template" class="pc-btn pc-btn-small">Launch Theme Editor</a>
			<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Page/?layout_name=' . $identifierData['layout_name'] . '" class="pc-btn pc-btn-small">Change Default Theme</a>
			
			</p>' );

			// save screenshot
			if( $values['screenshot'] )
			{
				$filename = dirname( $this->getMyFilename() ) . DS . 'screenshot';
				file_put_contents( $filename, $values['screenshot']);
			}  

			// remove this so that screenshot don't get updated'   
			if( empty( $values['screenshot_url'] ) )
			{
				unset( $values['screenshot_url'] );
			}  
		
	//	$this->setViewContent( 'Layout saved successfully' );
		}
		catch( Exception $e )
		{ 
		//	var_export( $e->getTraceAsString());
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
