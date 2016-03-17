<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Template_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php date time username $
 */

/**
 * @see Application_Article_Template_Abstract
 */
 
require_once 'Ayoola/Menu/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Template_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Application_Article_Template_List  extends Application_Article_Template_Abstract
{
 	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn = 'template_label'; 
	
    /**
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Article_Template'; 
		
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
	//	$list->menuName = $this->getObjectName();
		$list->listTitle = 'List of Post Templates on this website';
		
	//	var_export( $this->getDbTable()->select() ); 
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );
		$list->setListOptions( 
								array( 
										'New Post Template' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Article_Template_Creator/\' );" title=""> New Post Template </a>',
									) 
							);
		$list->setNoRecordMessage( 'There are no Post templates on this application, please add one.' );
		
		$list->createList
		(
			array
			(
				'template_label' => '<a title="Click to edit" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Template_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>',
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Template_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>',
			)
		);
		return $list;
    } 
}
