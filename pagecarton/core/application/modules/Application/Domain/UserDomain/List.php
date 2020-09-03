<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_UserDomain_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_UserDomain_List extends Application_Domain_UserDomain_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	  protected static $_objectTitle = 'My Linked Domains';   

 
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
        $this->_dbWhereClause['username'] = strtolower( Ayoola_Application::getUserInfo( 'username' ) );
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
		$list->setListOptions( 
								array( 
										    'Creator' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Order_List/\', \'' . $this->getObjectName() . '\' );" title="">My Domains</a>',    
										    '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_UserDomain_Creator/?external_domain=1\', \'' . $this->getObjectName() . '\' );" title="">Link External Domain</a>',    
										    'Register' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Registration/\', \'' . $this->getObjectName() . '\' );" title="">Register Domain Name</a>',    
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No domain has been linked to this account yet.' );
		
		$list->createList
		(
			array(
                    'domain' => array( 'field' => 'domain_name', 'value' =>  '<a target="_blank" href="http://%FIELD%/">%FIELD%</a> ', 'filter' =>  '' ), 
              //      'user_id' => array( 'field' => 'user_id', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
             //       'username' => array( 'field' => 'username', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'subdomain' => array( 'field' => 'profile_url', 'value' =>  '%FIELD%.' . Ayoola_Application::getDomainName(), 'filter' =>  '' ), 
              //      'expiry' => array( 'field' => 'expiry', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'Added' => array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
                    '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_UserDomain_Editor/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>', 
                    ' ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_UserDomain_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
