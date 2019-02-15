<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_User_Referral_Cookie
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Cookie.php 4.17.2012 7.55am ayoola $ 
 */

/**
 * @see Help_Exception 
 */
 
require_once 'Application/ContactUs/Exception.php';


/**
 * @category   PageCarton
 * @package    Application_User_Referral_Cookie
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Referral_Cookie extends Ayoola_Abstract_Table
{	
		
    /**
     * Process
     * 
     */
	public function init()
    {
			if( empty( $_COOKIE['pc_referrer'] ) && empty( $_REQUEST['pc_referrer'] ) && ( ! $this->getParameter('referrer_key') || empty( $_REQUEST[$this->getParameter('referrer_key')] ) ) &&  empty( Ayoola_Application::$GLOBAL['username'] ) )    
			{
				return false;
			}
			@$referrer = $_REQUEST['pc_referrer'] ? : $_COOKIE['pc_referrer'];
			$referrer = $this->getParameter('referrer_key') ? : $referrer;
			$referrer = @Ayoola_Application::$GLOBAL['username'] ? : $referrer;
			if( ! $userInfo = Ayoola_Access::getAccessInformation( $referrer ) )
			{
				return false;
			}
			$this->_objectData[] = $userInfo;
			$this->_objectTemplateValues[] = $userInfo;
			
			//	Expires in 30 days
			setcookie( 'pc_referrer', $referrer, time() + (86400 * 30), "/" );
			
			
	} 
	
// END OF CLASS
}
