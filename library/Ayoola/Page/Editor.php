<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php date time ayoola $
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';  


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Page_Editor extends Ayoola_Page_Abstract
{
	
    /**
     * This method starts the chain for update
     *
     * @param void
     * @return null
     */
    public function init()
    {
		try
		{
			//	allow for sending url as identifier
			if( @$_GET['url'] )
			{
				$this->_identifierKeys = array( 'url' );
			}
			if( ! $data = self::getIdentifierData() )
			{ 
				return false; 
			}
	 //		var_export( $data ); 
			$this->createForm( 'Edit Page', 'Edit ' . $data['url'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( $_POST );
			$isLayoutPage = stripos( $data['url'], '/layout/' ) === 0;
		//		var_export( $isLayoutPage );
		//	var_export( $this->getForm()->getValues() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	PREVENT EDITOR FROM STILL PARADING THE OLD TEMPLATE
			@$values['layout_name'] = $values['layout_name'];
		//	var_export( $isLayoutPage );
			if( $isLayoutPage )
			{
				//	Only admin should be able to view template files
				$values['auth_level'] = array( 99 );
			}
			
			if( ! $this->updateDb( $values ) ){ return false; }
			self::resetCacheForPage( $data['url'] );
			
	//		var_export( $data );
			$this->setViewContent( 'Page Option Edited Successfully', true ); 
			
/* 			require_once 'Ayoola/Xml.php';
			$data['url'] = rtrim( $data['url'], '/' );
			$this->setPageFilesPaths( $data['url'] );
			$files = $this->getPageFilesPaths();
			$filename = $files['data'];
			$xml = new Ayoola_Xml();
			$xml->load( $filename );
			$xml->arrayAsCData( $values );
			$xml->save( $filename );
 */			//	if( $this->_createXml() ){ $this->setViewContent( 'Page Option Edited Successfully', true ); }
		}
		catch( Exception $e )
		{ 
		//	return false; 
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		}
		
    } 
}
