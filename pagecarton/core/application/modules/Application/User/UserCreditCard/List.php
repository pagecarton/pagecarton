<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserCreditCard_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_UserCreditCard_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserCreditCard_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserCreditCard_List extends Application_User_UserCreditCard_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( self::__( '<h4>Credit/Debit Cards. <span class="goodnews"><a title="Add new Credit/Debit Card" rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/userCreditCardCreator/">+</a></span></h4>' ) );
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
				'card_number' => '<a title="Edit Credit/Debit card Information" rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/userCreditCardEditor/?' . $this->getIdColumn() . '=%KEY%&">%FIELD%</a>',
		//		'Set Email' => '<a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/userEmail/?' . $this->getIdColumn() . '=%KEY%">-</a>',
				
				'verified' => '<a title="Verify Account" href="' . Ayoola_Application::getUrlPrefix() . '/accounts/verify/get/mode/CreditCard/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'<a title="Delete" rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/userCreditCardDelete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
