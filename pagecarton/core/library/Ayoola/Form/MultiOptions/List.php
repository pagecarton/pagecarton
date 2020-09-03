<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_MultiOptions_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Form_MultiOptions_List extends Ayoola_Form_MultiOptions_Abstract
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
    $table = $this->getTableClass();
    $table = $table::getInstance( $table::SCOPE_PRIVATE );
    $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
    $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
    $list->setData(  $table->select() );
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
                    'multioptions_title' => array( 'field' => 'multioptions_title', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'multioptions_name' => array( 'field' => 'multioptions_name', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'db_table_class' => array( 'field' => 'db_table_class', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    array( 'field' => 'db_table_class', 'value' =>  '<a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/%FIELD%_List">Manage Data</a>', 'filter' =>  '' ), 
                    'values_field' => array( 'field' => 'values_field', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'label_field' => array( 'field' => 'label_field', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'Added' => array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
                    '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_MultiOptions_Editor/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>', 
                    ' ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_MultiOptions_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
