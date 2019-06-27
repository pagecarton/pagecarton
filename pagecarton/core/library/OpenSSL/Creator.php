<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    OpenSSL_Creator
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
 * @package    OpenSSL_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class OpenSSL_Creator extends OpenSSL_Abstract
{ 
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{
			$this->createForm( 'Continue..', 'Create a new encryption key pairs' );
			$this->setViewContent( $this->getForm()->view() );
		//	self::v( $_POST );
/* $ciphers             = openssl_get_cipher_methods();
$ciphers_and_aliases = openssl_get_cipher_methods(true);
$cipher_aliases      = array_diff($ciphers_and_aliases, $ciphers);

//print_r($ciphers);

//print_r($cipher_aliases);
print_r($ciphers_and_aliases);
 */			if( ! $values = $this->getForm()->getValues() ){ return false; }
			switch( $values['encryption_type'] )
			{
				case 'RSA':
					if( ! $values['private_key'] && ! $values['public_key'] )
					{
						$config = array(
							"digest_alg" => "sha512",
							"private_key_bits" => 4096,
							"private_key_type" => OPENSSL_KEYTYPE_RSA,
						);
						   
						// Create the private and public key
						$res = openssl_pkey_new($config);

						// Extract the private key from $res to $privKey
						openssl_pkey_export($res, $privKey);

						// Extract the public key from $res to $pubKey
						$pubKey = openssl_pkey_get_details($res);
						$pubKey = $pubKey["key"];

					//	$data = 'plaintext data goes here';

						// Encrypt the data to $encrypted using the public key
						openssl_public_encrypt($data, $encrypted, $pubKey);

						// Decrypt the data using the private key and store the results in $decrypted
					//	openssl_private_decrypt($encrypted, $decrypted, $privKey);

					//	echo $decrypted;
						$values['encryption_key']['private_key'] = $privKey;
						$values['encryption_key']['public_key'] = $pubKey;
					}
					else
					{
						$values['encryption_key']['private_key'] = $values['private_key'];
						$values['encryption_key']['public_key'] = $values['public_key'];
					}
				break;
				default:
					//	get the key length
					if( ! @$values['pre_shared_key'] )
					{
						list( $name, $bits, $type ) = explode( '-', $values['encryption_type'] );
						$values['encryption_key']['pre_shared_key'] = base64_encode( openssl_random_pseudo_bytes( $bits / 8 ) );
					}
					else
					{
						$values['encryption_key']['pre_shared_key'] = $values['pre_shared_key'];
					}
				break;
			}
		//	var_export( $values );
		//	if( ! $this->insertDb() ){ return false; }
			if( $this->insertDb( $values ) )
			{ 
				$this->setViewContent( '<span class="boxednews goodnews centerednews">Encryption keys created successfully. </span><br>', true ); 
				$this->setViewContent( self::__( '<pre class="boxednews normalnews centerednews">' . var_export( $values['encryption_key'], true ) . '</pre><br>' ) ); 
			//	$this->setViewContent( self::__( '<pre class="boxednews normalnews centerednews">' . $values['public_key'] . '</pre><br>' ) ); 
			}
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( self::__( '<p class="blockednews badnews centerednews">Error with article package.</p>' ) ); 
		}
    } 
}
