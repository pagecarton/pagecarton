<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Menu_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php date time username $
 */

/**
 * @see Ayoola_Menu_Abstract
 */
 
require_once 'Ayoola/Menu/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Menu_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Menu_List  extends Ayoola_Menu_Abstract
{
 	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn = 'template_label'; 

    /**	Whether to translate widget inner conetent
     *
     * @var bool
     */
	public static $translateInnerWidgetContent = true;
		
	
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
		$list->menuName = $this->getObjectName();
		$list->listTitle = 'List of Menus Templates on this website';
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );
		$list->setListOptions( 
								array( 
										'New Menu Template' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Menu_Creator/\' );" title=""> New Menu Template </a>',
									) 
							);
		$list->setNoRecordMessage( 'There are no menu templates on this application, please add one.' );
		
		require_once 'Ayoola/Menu.php';
		$list->createList
		(
			array
			(
				'template_label' => '<a title="Click to edit" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Menu_Editor/?' . $this->getIdColumn() . '=%KEY%">[%FIELD%]</a>',
				'<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Menu_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>',
			)
		);
		return $list;
    } 
}
