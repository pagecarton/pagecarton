<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_CommentBox_Table_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_CommentBox_Table_List extends Application_CommentBox_Table_Abstract
{
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
		$list->listTitle = self::getObjectTitle();
		$list->setData( $this->getDbData() );
		$list->setListOptions( 
								array( 
							//			'Sub Domains' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_SubDomainList/\' );" title="">Sub Domains</a>',    
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No data added to this table yet.' );
		
		$list->createList
		(
			array(
                    'comment' => array( 'field' => 'comment', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'url' => array( 'field' => 'url', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'article_url' => array( 'field' => 'article_url', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'profile_url' => array( 'field' => 'profile_url', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'display_name' => array( 'field' => 'display_name', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'email' => array( 'field' => 'email', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'website' => array( 'field' => 'website', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'creation_time' => array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'parent_comment' => array( 'field' => 'parent_comment', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'hidden' => array( 'field' => 'hidden', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'enabled' => array( 'field' => 'enabled', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'approved' => array( 'field' => 'approved', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'Added' => array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
                    '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_CommentBox_Table_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
                    ' ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_CommentBox_Table_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
