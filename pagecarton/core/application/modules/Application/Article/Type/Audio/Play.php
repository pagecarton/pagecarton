<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Audio_Play
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Audio.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract  
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Audio_Play
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Audio_Play extends Application_Article_Type_Audio
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = ''; 

    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		if( ! $data = $this->getParameter( 'data' ) )
		{
			$data = $this->getIdentifierData();
		}
		if( ! self::isDownloadable( $data ) )
		{
			return false;
		}
		//	Log into the database 
		$table = Application_Article_Type_Audio_Table::getInstance();
		$table->insert( array(
								'username' => Ayoola_Application::getUserInfo( 'username' ),
								'article_url' => $data['article_url'],
								'timestamp' => time(),
						) 
		);
		static::getDownloadContent( $data );


    } 
	
	
	
	// END OF CLASS
}
