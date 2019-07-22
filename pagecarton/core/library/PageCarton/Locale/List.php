<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Locale_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Locale_List extends PageCarton_Locale_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	  protected static $_objectTitle = 'Locale List';   

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
										'<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_Settings/\' );" title="">Locale Settings</a>',    
										'<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_Translation_AutoPopulateWords/\' );" title="">Build Words</a>',    
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No data added to this table yet.' );
		$default = array(
            PageCarton_Locale_Settings::retrieve( 'default_locale' ) => '<i class="fa fa-check"></i>',
            'pc_paginator_default' => '<a href="javascript:" rel="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_Settings/?default_locale=%FIELD%\', \'' . $this->getObjectName() . '\' );" title="Make this the default locale">Make Default</a>',

    );
    
		$list->createList
		(
			array(
                    'Locale Name' => array( 'field' => 'locale_name', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'Native Name' => array( 'field' => 'native_name', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'Locale Code' => array( 'field' => 'locale_code', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'Default' => array( 'field' => 'locale_code', 'header' =>  ' ', 'value' =>  '%FIELD%', 'value_representation' => $default ), 
                    array( 'field' => 'locale_code', 'value' =>  '<a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_OriginalString_List/?locale_code=%FIELD%">translations</a>', 'filter' =>  '' ), 
                    '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
                    ' ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
