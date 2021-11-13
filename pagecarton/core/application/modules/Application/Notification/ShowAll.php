<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Notification_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Notification_ShowAll extends Application_Notification_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	  protected static $_objectTitle = 'Notifications';   

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
        //var_export( Ayoola_Application::getUserInfo( 'email' ) );
		$list->setData( $this->getDbTable()->select( null, array( 'to' => Ayoola_Application::getUserInfo( 'email' ) ) ) );
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
                //    'username' => array( 'field' => 'username', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     
                //    'from' => array( 'field' => 'from', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     
                     array( 'field' => 'body', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     
                 //   'to' => array( 'field' => 'to', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     
                  //  'cc' => array( 'field' => 'cc', 'value' =>  '%FIELD%', 'filter' =>  '' ),                     'bcc' => array( 'field' => 'bcc', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                     array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
                 //   '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Notification_Editor/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>', 
                    '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Notification_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
