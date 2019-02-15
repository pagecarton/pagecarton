<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    OpenSSL
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $ 
 */

/**
 * @see OpenSSL_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton
 * @package    OpenSSL
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class OpenSSL
{ 
		
    /**
     * 
     *
     * @param string Encryption Name
     * @return void
     */	
    public static function encrypt( $data, $name )
    {
		$encryptionInfo = new OpenSSL_Table();
		if( ! $encryptionInfo = $encryptionInfo->selectOne( null, array( 'encryption_name' => $name ) ) )
		{
			throw new OpenSSL_Exception( $name . ' IS NOT A VALID ENCRYPTION NAME ON THIS WEBSITE.' );
		}
		switch( $encryptionInfo['encryption_type'] )
		{
			case 'RSA':
				
				openssl_public_encrypt( $data, $encrypted, $encryptionInfo['encryption_key']['public_key'] );
			//	openssl_public_encrypt( 'wedwd wed ', $encrypted, $encryptionInfo['encryption_key']['public_key'] );
		//		var_export( $data . "\r\n" );
			//	var_export( $encrypted . "\r\n" );
			//	var_export( $encryptionInfo );
			//	var_export( $encryptionInfo['encryption_key']['public_key'] . "\r\n" );
			break;
			default:
				$ivLength = openssl_cipher_iv_length( $encryptionInfo['encryption_type'] );
				$iv = openssl_random_pseudo_bytes( $ivLength );
			//	var_export( $encryptionInfo['encryption_key']['pre_shared_key'] );
			//	var_export( strlen( base64_decode( $encryptionInfo['encryption_key']['pre_shared_key'] ) ) );
				$result = openssl_encrypt( $data, $encryptionInfo['encryption_type'], base64_decode( $encryptionInfo['encryption_key']['pre_shared_key'] ), OPENSSL_RAW_DATA, $iv );
			//	var_export( strlen( $result ) . "\r\n" );
				$resultEncoded = base64_encode( $result );
				$ivEncoded = base64_encode( $iv );
			//	$result = base64_decode( $result );
				
				$encryptedEncoded = $ivEncoded . ':' . $resultEncoded;
				$encrypted = $iv . '' . $result;
				
				//	try to decrypt to ensure encryption was performed well.
				$ivToDecrypt = substr( $encrypted, 0, $ivLength );
			//	var_export( 0 - $ivLength );
				$dataToDecrypt = substr( $encrypted, $ivLength );				
				$decrypted = openssl_decrypt( $dataToDecrypt, $encryptionInfo['encryption_type'], base64_decode( $encryptionInfo['encryption_key']['pre_shared_key'] ), OPENSSL_RAW_DATA, $ivToDecrypt );
				if( $decrypted !== $data )
				{
					exit( 'ERROR' );
				}
			//	$encrypted = base64_encode( $iv ) . ':' . base64_encode( $encrypted );
			//	var_export( strlen( $iv ) . "\r\n" );
			//	var_export( strlen( $ivToDecrypt ) . "\r\n" );
			//	var_export( strlen( $result ) . "\r\n" );
			//	var_export( strlen( $dataToDecrypt ) . "\r\n" );
			//	var_export( $decrypted . "\r\n" );
			//	var_export( strlen( $encrypted ) . "\r\n" );
			//	var_export( strlen( bin2hex( $encrypted ) ) );
			//	var_export( bin2hex( $encrypted ) );
				$encrypted = $encryptedEncoded;   
			break;
		}
		return $encrypted;
	}
		
    /**
     * 
     *
     * @param string Encryption Name
     * @return void
     */	
    public static function decrypt( $data, $name )
    {
		$encryptionInfo = new OpenSSL_Table();
		if( ! $encryptionInfo = $encryptionInfo->selectOne( null, array( 'encryption_name' => $name ) ) )
		{
			throw new OpenSSL_Exception( $name . ' IS NOT A VALID ENCRYPTION NAME ON THIS WEBSITE.' );
		}
		//		var_export(  $encryptionInfo );
		//		exit();
		switch( $encryptionInfo['encryption_type'] )
		{
			case 'RSA':
				openssl_public_encrypt( $data, $decrypted, $encryptionInfo['encryption_key']['public_key'] );
			//	openssl_public_encrypt( 'wedwd wed ', $encrypted, $encryptionInfo['encryption_key']['public_key'] );
		//		var_export( $data . "\r\n" );
			//	var_export( $encrypted . "\r\n" );
			//	var_export( $encryptionInfo );
			//	var_export( $encryptionInfo['encryption_key']['public_key'] . "\r\n" );
			break;
			default:
				$ivLength = openssl_cipher_iv_length( $encryptionInfo['encryption_type'] );
				list( $iv, $data ) = explode( ':', $data );
				$iv = base64_decode( $iv );
				$data = base64_decode( $data );
				$decrypted = openssl_decrypt( $data, $encryptionInfo['encryption_type'], base64_decode( $encryptionInfo['encryption_key']['pre_shared_key'] ), OPENSSL_RAW_DATA, $iv );
			//	var_export(  $iv );
			//	var_export( strlen( $iv ) . "\r\n" );
			//	var_export( strlen( $data ) . "\r\n" );
			//	var_export(  $data );
			//	var_export(  $decrypted );
			//	var_export(  $encryptionInfo['encryption_type'] );
			//	exit();
			//	$decrypted = openssl_decrypt( $data, $encryptionInfo['encryption_type'], base64_decode( $encryptionInfo['encryption_key']['pre_shared_key'] ), OPENSSL_RAW_DATA, $iv );
				
			//	$decrypted = $iv . '' . $result;
			//	$encrypted = base64_encode( $iv ) . ':' . base64_encode( $encrypted );
			//	var_export(  $iv . "\r\n" );
			//	var_export(  $data . "\r\n" );
			//	var_export(  $decrypted . "\r\n" );
			//	exit();
		//		var_export(  $encryptionInfo );
			//	var_export( strlen( $iv ) . "\r\n" );
			//	var_export( strlen( $encrypted ) . "\r\n" );
			//	var_export( strlen( bin2hex( $encrypted ) ) );
			//	var_export( bin2hex( $encrypted ) );
			break;
		}
		return $decrypted;
	}
}
