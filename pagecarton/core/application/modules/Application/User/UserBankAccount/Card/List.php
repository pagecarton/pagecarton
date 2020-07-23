<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_Card_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_UserBankAccount_Card_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_Card_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserBankAccount_Card_List extends Application_User_UserBankAccount_Card_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( self::__( '<h4>Credit/Debit Cards. <span class="goodnews"><a title="Add new Credit/Debit Card" rel="spotlight;height=300px;width=300px;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserBankAccount_Creator/mode/Card/file/Creator/">+</a></span></h4>' ) );
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
	//	var_export( $this->getIdColumn() );
		$list->setData( $this->getDbData() );
		
	//	$this->setIdColumn( 'user_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no Credit/Debit cards saved in your account' );
		$list->createList(  
			array(
				'expiry_month' => null, 
				'expiry_year' => null, 
				'creditcardtype' => null, 
				'card_number' => '<a title="Edit Credit/Debit card Information" rel="spotlight;height=300px;width=300px;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserBankAccount_Creator/mode/Card/file/Editor/?' . $this->getIdColumn() . '=%KEY%&">%FIELD%</a>',
				
				'verified' => '<a title="Verify Account" href="' . Ayoola_Application::getUrlPrefix() . '/accounts/verify/get/mode/CreditCard/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'X' => '<a title="Delete" rel="spotlight;height=300px;width=300px;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_User_UserBankAccount_Creator/mode/Card/file/Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
