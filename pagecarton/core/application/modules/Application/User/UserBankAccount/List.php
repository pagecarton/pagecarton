<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_UserBankAccount_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserBankAccount_List extends Application_User_UserBankAccount_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( self::__( '<h4>Bank Accounts. <span class="goodnews"><a title="Add new Bank Account" rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserCreditCard_Creator/">+</a></span></h4>' ) );
		$this->setViewContent( $this->getList() );
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->showPagination = false;
	//	$list->listTitle = 'Saved Bank Account Information';
		$list->setData( $this->getDbData() );
		
	//	$this->setIdColumn( 'user_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no bank accounts saved in your account' );
		$list->createList(  
			array(
				'country' => null, 
				'bank' => null, 
				'symbol' => null, 
				'account_name' => null, 
				'account_number' => '<a title="Edit Account Information" rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserCreditCard_Editor/?' . $this->getIdColumn() . '=%KEY%&">%FIELD%</a>',
		//		'Set Email' => '<a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/userEmail/?' . $this->getIdColumn() . '=%KEY%">-</a>',
				
				'verified' => '<a title="Verify Account" href="' . Ayoola_Application::getUrlPrefix() . '/accounts/verify/get/mode/BankAccount/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'X' => '<a title="Delete" rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserCreditCard_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
