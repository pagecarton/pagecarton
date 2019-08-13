<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_Order_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_Order_List extends Application_Domain_Order_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	  protected static $_objectTitle = 'Domain Orders';   

    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
        if( ! self::hasPriviledge() )
        {
          $this->_dbWhereClause['username'] = Ayoola_Application::getUserInfo( 'username' );
          $this->_dbWhereClause['user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
        }
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
        $listOptions = 	array( 
                                'Creator' => self::hasPriviledge() ? 
                                ( '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Order_List/\' );" title="">Add domain to list</a>' ) :  
                                ( '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Registration/\', \'' . $this->getObjectName() . '\' );" title="">Register new Domain Name</a>' ),    
        );

		$list->setListOptions( $listOptions );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not registered any domain yet.' );
		
		$list->createList
		(
			array(
                    'domain_name' => array( 'field' => 'domain_name', 'value' =>  '%FIELD%' ), 
                    'active' => array( 'field' => 'active', 'header' => 'Status', 'value' =>  '%FIELD%', 'value_representation' =>  array( '0' => '<i class="fa fa-close"></i>', '1' => '<i class="fa fa-check"></i>' ), 'filter' =>  '' ), 
                    array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ),
                    '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Order_DNS/?' . $this->getIdColumn() . '=%KEY%">DNS</a>', 
                    '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Order_Email/?' . $this->getIdColumn() . '=%KEY%">Emails</a>', 
                    '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Order_Editor/?' . $this->getIdColumn() . '=%KEY%">contact</a>', 
                    '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_UserDomain_Creator/?' . $this->getIdColumn() . '=%KEY%">site</a>', 
                    '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Order_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
