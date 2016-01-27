<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Slideshow_Abstract
 */
 
require_once 'Application/Slideshow/Abstract.php';


/**
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Slideshow_Creator extends Application_Slideshow_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Create', 'Create a Slideshow' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		
		$filter = new Ayoola_Filter_Name();
		$values['slideshow_name'] = $filter->filter( $values['slideshow_title'] );
		
		if( ! $this->insertDb( $values ) ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent( '<div class="boxednews goodnews"  style="clear:both;">Slideshow settings created successfully.</div>', true ); 
		switch( $values['slideshow_type'] )
		{
			case 'post':
				$this->setViewContent( '<a href="/post/create?article_type=photo&category=' .  $values['category_name'] . '" class="boxednews pc-bg-color">Add new post</a>' ); 
			break;
		//	case 'upload':
			default:
				$this->setViewContent( '<a href="/tools/classplayer/get/object_name/Application_Slideshow_Manage/?slideshow_name=' .  $values['slideshow_name'] . '" class="boxednews pc-bg-color" >Add photos</a>' ); 
			break;
		}

	//	$this->getCallToAction( '<p>Slideshow created successfully</p>', true );
   } 
	// END OF CLASS
}
