<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Domain_UserSiteManager
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: UserSiteManager.php Friday 6th of July 2018 11:58PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_UserSiteManager extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'My Sites'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected $_idColumn = 'profile_url'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected $_tableClass = 'Application_Profile_Table'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            if( ! self::hasPriviledge() )
            {
                $this->_dbWhereClause['username'] = Ayoola_Application::getUserInfo( 'username' );
                $this->_dbWhereClause['user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
            }
            $this->setViewContent( $this->getList() );		
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
        }
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
        $data = $this->getDbData();
        $userDir = Application_Profile_Abstract::getProfileFilesDir( Ayoola_Application::getUserInfo( 'username' ) ) . DS . 'application';
  //      var_export( $userDir );
        if( is_dir( $userDir ) )
        {
            $data[] = array( 'profile_url' => strtolower( Ayoola_Application::getUserInfo( 'username' ) ) ) + Ayoola_Application::getUserInfo();
        }
		$list->setData( $data );
		$list->setListOptions( 
								array( 
										    'Creator' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_Creator/?subdomain=1\', \'' . $this->getObjectName() . '\' );" title="">Create a new site</a>',    
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not created any site yet' );
		
		$list->createList
		(
			array(
                    'site' => array( 'field' => 'profile_url', 'value' =>  '<a target="_blank" href="http://%FIELD%.' . Ayoola_Application::getDomainName() . '">http://%FIELD%.' . Ayoola_Application::getDomainName() . '</a> ', 'filter' =>  '' ), 
                    '   ' => array( 'field' => 'profile_url', 'value' =>  '<a target="_blank" href="http://%FIELD%.' . Ayoola_Application::getDomainName() . '/pc-admin">Admin Panel</a> ', 'filter' =>  '' ), 
                    'Added' => array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
           //         '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_UserDomain_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
                    array( 'field' => 'profile_url', 'value' =>  '<a style="font-size:smaller;" rel="shadowbox;" href="http://%FIELD%.' . Ayoola_Application::getDomainName() .  '/tools/classplayer/get/name/PageCarton_NewSiteWizard">New Website Wizard</a>' ), 
                    ' ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
