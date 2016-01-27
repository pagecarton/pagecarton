<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    OpenSSL_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php date time username $
 */

/**
 * @see OpenSSL_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    OpenSSL_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class OpenSSL_List  extends OpenSSL_Abstract
{
 	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn = '';
	
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
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'List of OPENSSL key pairs on this Website';
		$list->setData( $this->getDbData() );
		$list->setListOptions( 
								array( 
							//			'Form Requirements' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/OpenSSL_Requirement_List/\' );" title="Manage Form Requirements.">Form Requirements </span>',
							//			'Form Data' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/OpenSSL_Inspect/\' );" title="Check form data.">Form Data </span>',
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no OPENSSL key pairs on this website.' );
		
		$list->createList
		(
			array(
				'encryption_name' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/OpenSSL_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/OpenSSL_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
				)
		);
		return $list;
    } 
}
