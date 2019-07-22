<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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

			if( ! $identifierData = $this->getIdentifierData() )
			{ 
				if( @$_REQUEST['layout_name'] === 'novus' )
				{
					$identifierData['layout_name'] = 'novus';
					$this->_identifierData = $identifierData;
					$this->setFilename( $identifierData );
				}
				else
				{
					return false; 
				}
			}
			$this->createForm( 'Save', 'Editing "' . $identifierData['layout_name'] . '"', $identifierData );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
	//		htmlspecialchars_decode( var_export( $values ) );  

			
			if( ! $this->updateDb() )
			{ 
				$this->setViewContent(  '' . self::__( '<p class="badnews">Error: could not save layout template.</p>.' ) . '', true  ); 
				if( @$_REQUEST['layout_name'] === 'novus' )
				{

				}
				else
				{
					return false;   
				}
			}
			if( $this->updateFile() ){ $this->setViewContent(  '' . self::__( '<p class="boxednews goodnews">Theme file saved successfully.</p>' ) . '', true  ); }	
			
				$this->setViewContent(  self::__( '<p class="">
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=/layout/' . $identifierData['layout_name'] . '/template" class="pc-btn pc-btn-small">Edit Theme</a>
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?layout_name=' . $identifierData['layout_name'] . '" class="pc-btn pc-btn-small">Set as Default Theme</a>

				</p>' ) );
/*
			// save screenshot
			if( $values['screenshot'] )
			{
				$filename = dirname( $this->getMyFilename() ) . DS . 'screenshot';
				file_put_contents( $filename, $values['screenshot']);
			}  
*/
			// remove this so that screenshot don't get updated'   
			if( ! empty( $values['screenshot_url'] ) )
			{
				$file = Ayoola_Doc_Browser::getDocumentsDirectory() . $values['screenshot_url'];
				if( file_exists( $file ) )
				{
					$layoutDir = dirname( $this->getMyFilename() );
					$screenshot = $layoutDir . '/screenshot.jpg';
					copy( $file, $screenshot );
				}
			}  
			unset( $values['screenshot_url'] );
		
	//	$this->setViewContent( self::__( 'Layout saved successfully' ) );
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
