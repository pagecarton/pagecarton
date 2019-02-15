<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Cron_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Cron_List extends PageCarton_Cron_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Cron Tasks'; 

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
		$list->setNoRecordMessage( 'There are no cron jobs created yet.' );
		
		$list->createList
		(
			array(
                    'Widget' => array( 'field' => 'class_name', 'value' =>  '%FIELD%' ), 
                    'Parameters' => array( 'field' => 'cron_parameters', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_JsonEncode' ), 
                    'Interval' => array( 'field' => 'cron_interval', 'value' =>  '%FIELD%', 'value_representation' =>  static::$_intervalOptions ), 
                    '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Cron_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
                    '   ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Cron_Run/?' . $this->getIdColumn() . '=%KEY%">run</a>', 
                    '  ' => array( 'value' =>  '<a style="font-size:smaller;" rel="shadowbox;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Cron_Run_Table_List/?' . $this->getIdColumn() . '=%KEY%">history</a>', 'filter' =>  '' ),
                    ' ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Cron_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
