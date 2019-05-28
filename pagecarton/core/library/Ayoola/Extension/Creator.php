<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Extension_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Extension_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Creator extends Ayoola_Extension_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Build a new plugin'; 
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
		//	var_export( Ayoola_Page::getCurrentPageInfo( 'upload' ) );
			$this->createForm( 'Continue', 'Build a new plugin' );
		//	$this->setViewContent( $this->getForm()->view(), true );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '_';
			$values['extension_name'] = strtolower( $filter->filter( $values['extension_title'] ) );

			
			if( ! $this->insertDb( $values ) )
			{ 
				$this->setViewContent( '<p class="boxednews badnews">Error: could not create plugin.</p>.' ); 
				return false;
			}
			$this->setViewContent( '<p class="goodnews">Plugin built successfully. <a href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Extension_Download/?extension_name=' . $values['extension_name'] . '" class="">Download</a></p>', true );
//			$this->setViewContent( '' );
			
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
