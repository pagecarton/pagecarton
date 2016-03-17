<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_SimplePay_Callback
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Callback.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Checkout_Callback
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_SimplePay_Callback
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_SimplePay_Callback extends Application_Subscription_Checkout_Callback
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {		
	//	if( ! $identifier = $this->getIdentifier() ){ return false; }
	
		/* 	
			Simplepay notification method is rather insecure. 
			I need to perform the following processes to improve the security.
		*/
	//	$simplePayUrl = 'ayoola'; //	debugging
	//	$action= $_POST["action"];
	//	$pid= $_POST["pid"];
	//	$buyer= $_POST["buyer"];
	//	$total= $_POST["total"];
	//	$comments= $_POST["comments"];
		@$referer= $_POST["referer"];
	//	$customid= $_POST["customid"];
		@$transaction_id=$_POST["transaction_id"];
	//	$val=@json_encode($_POST);
		$simplePayUrl = 'www.simplepay4u.com';
		$ipList = gethostbynamel( $simplePayUrl );
	//	var_export( $ipList );
		if( ! in_array( $_SERVER['REMOTE_ADDR'], $ipList ) )
		{
			//	The notification is not from simplepay
	//		die( 'INVALID NOTIFICATION' ); 
		}
		$response['order_status'] = 'Payment Successful';
	//	$response['order_id'] = $_POST['customid'];
	//	$response['order_random_code'] = $_POST['transaction_id'];
		$response['order_id'] = @$_REQUEST['order_id'];
		$response['order_id'] = $response['order_id'] ? : @$_REQUEST['customid'];
		$response['order_random_code'] = @$_REQUEST['transaction_id'];
		$stages = Application_Subscription_Checkout::$checkoutStages;
		// For Loging $val can be used
	//	if($referer=="https://simplepay4u.com" || $referer=="https://www.simplepay4u.com") //1st level checking
		{

				if(!empty($transaction_id)) //2nd level checking
				{
						/*** Server side verification. Your server is communicating with Simplepay Server Internally**/
						$simplepay_url="http://sandbox.simplepay4u.com/processverify.php";
						$simplepay_url="https://simplepay4u.com/processverify.php"; // Live URL
						$curldata["cmd"]="_notify-validate";
						foreach ($_REQUEST as $key =>  $value)
						{
							if ($key != 'view'&&  $key != 'layout')
							{
							  $value = urlencode ($value);
							  $curldata[$key]=$value;
							}
						}
						$handle=curl_init();
						curl_setopt($handle, CURLOPT_URL, $simplepay_url);
						curl_setopt($handle, CURLOPT_POST, 1);
						curl_setopt($handle, CURLOPT_POSTFIELDS, $curldata);
						curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($handle, CURLOPT_TIMEOUT, 90);
						$result=curl_exec($handle);
						curl_close($handle);
						if( 'VERIFIED' == trim($result) )
						{

							//  IMPLEMENTATION CODE & aPPLICATION lOGIC WILL GO HER TO UPDATE THE ACCOUNT
							//	if it was successful, switch to it.
							if( $_POST['SP_TRANSACTION_ERROR_CODE'] === 'SP0000' && $_POST['SP_TRANSACTION_ERROR'] === 'SUCCESS' )
							{
					//			$response['order_status'] = 'Payment Successful';
							}
							//	Notify Admin
							$mailInfo = array();
							$mailInfo['subject'] = 'SimplePay Payment Notification';
							$mailInfo['body'] = '"' . var_export( $_POST + $response, true ) . '"';
							try
							{
							//	var_export( $newCart );
								@Ayoola_Application_Notification::mail( $mailInfo );
							}
							catch( Ayoola_Exception $e ){ null; }
						//	file_put_contents( 'C:\exams.txt', var_export( $_POST, true ) );
							if( $_POST['SP_TRANSACTION_ERROR_CODE'] === 'SP0000' )
							{
						//		$response['order_status'] = 'Payment Successful';
							}
							if( $_POST['SP_TRANSACTION_ERROR'] === 'SUCCESS' )
							{
						//		$response['order_status'] = 'Payment Successful';
							}
							$this->processResponse( $response );
							
							
						//	var_export( $stages );
						//	var_export( $response );
						}

				}
		}
		
	//	$this->processResponse( $response );
	} 
	// END OF CLASS
}
