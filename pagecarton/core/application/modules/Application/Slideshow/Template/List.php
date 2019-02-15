<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Slideshow_Template_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php date time username $
 */

/**
 * @see Application_Slideshow_Template_Abstract
 */
 
//require_once 'Ayoola/Slideshow/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Slideshow_Template_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Application_Slideshow_Template_List  extends Application_Slideshow_Template_Abstract
{
 	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn = 'template_label'; 
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		$this->setViewContent( $this->getList() );		
    } 
	
    /**
     * Paginate the list with Ayoola_Paginator
     * @see Ayoola_Paginator
     */
    protected function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->slideshowName = $this->getObjectName();
		$list->listTitle = 'List of Slideshows Templates on this website';
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );   
		$list->setListOptions( 
								array( 
										'New Slideshow Template' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Template_Creator/\' );" title=""> New Slideshow Template </a>',
									) 
							);
		$list->setNoRecordMessage( 'There are no slideshow templates on this application, please add one.' );
		
		$list->createList
		(
			array
			(
				'template_label' => '<a title="Click to edit" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Template_Editor/?' . $this->getIdColumn() . '=%KEY%">[%FIELD%]</a>',
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Slideshow_Template_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>',
			)
		);
		return $list;
    } 
}
