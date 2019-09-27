<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Verify_Email
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Email.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Verify_Abstract
 */
 
require_once 'Application/User/Verify/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Verify_Email
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Verify_Email extends Application_User_Verify_Abstract
{

    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'x' );

    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			
			$identifier = explode( ':', base64_decode( $this->getIdentifier( 'x' ) ) );
		//	var_export( $identifier );
			//	debugging
		//	$identifier = array( '232324', 'ayoola.falola@yahoo.com', 12434456 );
			if( count( $identifier ) !== 3 )
			{
				throw new Ayoola_Api_Exception( 'INVALID VERIFICATION CODE FORMAT.' );
			}
			@$identifier = array( 'hash' => $identifier[0], 'email' => $identifier[1], 'email_verification_code' => $identifier[2] );
			
			//	VALIDATE EMAIL
			$validator = new Ayoola_Validator_EmailAddress();
			if( ! $validator->validate( $identifier['email'] ) )
			{
				throw new Ayoola_Api_Exception( 'MALFORMED E-MAIL ADDRESS.' );
			}
			$message = 'Invalid Information. The link might have expired. Please check your email for a new verification link. You may as well try again later.';
			do
			{
				//	Method depends on the user storage
				if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
				{
					$database = 'cloud';
				}
				$saved = false;
				switch( $database )
				{
					case 'cloud':
						$response = Application_User_Verify_Email_Api::send( $identifier );
				//		var_export( $response );
						if( ! is_array( $response['data'] ) )
						{
							$message = $response;
							break 2;
						}
						$data = $response['data'];
					break;
					case 'relational':
						//	verify the hash
						$hash = self::getHash( $identifier['email'] . ':' . $identifier['email_verification_code'] );
					//	var_export( $hash );
					//	var_export( $identifier );
						if( $hash !== $identifier['hash'] )
						{
							throw new Ayoola_Api_Exception( 'HASH ERROR.' );
						}
						if( ! $data = $this->getDbTable()->selectOne( null, strtolower( implode( ', ', $this->_otherTables ) ), array( 'email' => $identifier['email'] ) ) )
						{
							break 2;
						}
						if( $data['email_verification_status'] )
						{
							$message = 'Thank you! E-mail address has been previously verified.';
							break 2;
						}
						
						if( $data['email_verification_code'] != $identifier['email_verification_code'] )
						{
							//	Refresh the code for security
							Application_User_Verify::resetVerificationCode( $userInfo );
							break 2;
						}
						//	Set status
						$table = new Application_User_UserEmail();
						$table->update( array( 'email_verification_status' => 1 ), array( 'email' => $data['email'] ) );
						
						//	Set Application User status
						$table = new Ayoola_Application_ApplicationUserSettings();
						$table->update( array( 'verified' => 1 ), array( 'user_id' => $data['user_id'] ) );
					break;
				
				}
				$mailInfo = new Application_User_NotificationMessage;
				$mailInfo = $mailInfo->selectOne( null, array( 'subject' =>  'E-mail Verified' ) );
				$mailInfo['to'] = $data['email'];
				$data['domainName'] = Ayoola_Page::getDefaultDomain();
				$mailInfo['from']  = "\"{$data['domainName']} Accounts\" <accounts@{$data['domainName']}>\r\n";

				$mailInfo = self::replacePlaceholders( $mailInfo, $data );
				$this->setViewContent( self::__( '<p>Thank you for taking your time to verify your e-mail address. Email verification has been completed successfully. Many more services have been unlocked for you.</p>	' ) );
			//	var_export( $mailInfo );
				@$this->sendMail( $mailInfo );
				return true;
			}
			while( false );
			$this->setViewContent( self::__( '<p>' . $message . '</p>' ) );
		//	var_export( 'j' );
		}
		catch( Exception $e ){ return false; }
    } 
	
    /**
     * Returns the hash
     * 
     */
	public static function getHash( $code )
    {
		//	Renew hash daily
		return Ayoola_Captcha::getHash( array( 'name' => $code, 'browser' => false, 'daily' => true ) );
	}
	
    /**
     * Resets the verification code for security
     * 
     */
	public static function resetVerificationCode( array $verificationInfo )
    {
	//	var_export( $verificationInfo );
	//	sleep( 10 ); // slow down spammers
		if( empty( $verificationInfo['email'] ) )
		{
			throw new Application_User_Verify_Exception( 'AN EMAIL IS REQUIRED TO RESET VERIFICATION CODE' );
		}
		if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
		{
			$database = 'cloud';
		}
		switch( $database )
		{
			case 'cloud':
			//	throw new Application_User_Verify_Exception( 'YOU CANNOT RESET VERIFICATION CODE FROM THIS APPLICATION.' );
			break;
			case 'relational':
				//	You are welcome
			break;
		
		}
		$table = new Application_User_UserEmail();
		$code = self::getVerificationCode();
		$table->update( array( 'email_verification_code' => $code ), array( 'email' => $verificationInfo['email'] ) );
		
		//	Send an e-mail
		
		//	Sign the query string
		$code = $verificationInfo['email'] . ':' . $code;
		$hash = self::getHash( $code );
		$code = urlencode( base64_encode( $hash . ':' . $code ) );
		$link = 'http://' . Ayoola_Page::getDefaultDomain() . '/accounts/verify/?mode=email&x=' . $code;
	//	$verificationInfo['activationCode'] = $code;
	//	var_export( $link );
		$verificationInfo['link'] = $link;
		$mailInfo = new Application_User_NotificationMessage;
		$mailInfo = $mailInfo->selectOne( null, array( 'subject' =>  'E-mail Verification' ) );
		$mailInfo['to'] = $verificationInfo['email'];
		$domain = Ayoola_Page::getDefaultDomain();
		$mailInfo['from']  = "\"{$domain} Accounts\" <verification@{$domain}>\r\n";

		$mailInfo = self::replacePlaceholders( $mailInfo, $verificationInfo );
		@self::sendMail( $mailInfo );
	}
	// END OF CLASS
}
