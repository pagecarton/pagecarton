<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_AutoCreator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: AutoCreator.php date time username $
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_AutoCreator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Page_AutoCreator extends Ayoola_Page_Abstract
{
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{
			if( self::hasPriviledge() )
			{
				$this->setViewContent( '<span onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Creator/?url=' . Ayoola_Application::getRuntimeSettings( 'url' ) . '\' );" class="goodnews boxednews">Create this "' . Ayoola_Application::getRuntimeSettings( 'url' ) . '" page now</span>' );
			}
		}
		catch( Exception $e ){ return false; }		
    } 
	
}
