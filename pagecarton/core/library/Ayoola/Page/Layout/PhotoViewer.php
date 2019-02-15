<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_PhotoViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PhotoViewer.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_PhotoViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_PhotoViewer extends Ayoola_Page_Layout_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
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
			if( ! $data = self::getIdentifierData() )
			{ 
		//	var_export( $data );
	
			}
			$url = '/layout/' . $data['layout_name'] . '/screenshot.jpg';
		//	$newFilename = dirname( $this->getMyFilename() ) . DS . 'screenshot';
			Application_IconViewer::viewInLine( array( 'url' => $url ) );
			exit();
			

			//	revert this to normal file
		}
		catch( Application_Exception $e )
		{ 
		//	$this->getForm()->setBadnews( $e->getMessage() );
		//	$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	
	// END OF CLASS
}
