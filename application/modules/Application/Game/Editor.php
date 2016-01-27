<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @Game   Ayoola
 * @package    Application_Game_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Game_Abstract
 */
 
require_once 'Application/Game/Abstract.php';


/**
 * @Game   Ayoola
 * @package    Application_Game_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Game_Editor extends Application_Game_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Edit', 'Edit ' . $data['Game_title'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent( 'Game edited successfully', true ); }
		}
		catch( Application_Game_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
