<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Wallet
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Wallet.php 4.17.2012 11.53 ayoola $ 
 */


/**
 * @category   PageCarton
 * @package    Application_Wallet
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

final class Application_Wallet extends Application_Wallet_Abstract
{

    /**
     *
     * @param int
     */
 //   protected $_ = '0.03';


	
    /**
     * Transfers funds from one wallet to the other
     * 
     * @param array Transfer Info
     * @return boolean 
     */
	public static function transfer( array $transferInfo )
    {
		try
		{
			//	Record the transaction first. 
			$table = new Application_Wallet_Transaction();
			$transferInfo['time'] = time();
			$table->insert( $transferInfo );
			 
			//	You cant send to self.
			if( @$transferInfo['from'] == @$transferInfo['to'] )
			{
				return false;
			}

			//	Get the info of the sender
			if( ! @$transferInfo['allow_ghost_sender'] )
			{
				if( ! $senderInfo = Ayoola_Access::getAccessInformation( $transferInfo['from'] ) )
				{
						return false;
				}
			
				//	Check funds
				if( @$senderInfo['wallet_balance'] < $transferInfo['amount'] )
				{
					return false;
				}
				else
				{
					$senderInfo['wallet_balance'] = $senderInfo['wallet_balance'] - $transferInfo['amount'];
                    $senderInfo['username'] = $transferInfo['from'];
                    Ayoola_Access_Localize::info( $senderInfo );
                    // deductions first for security reasons.
				}
			}
			
			//	Get the info of the reciever
			if( ! $receiverInfo = Ayoola_Access::getAccessInformation( $transferInfo['to'] ) )
			{
				return false;
			}
			
			//	Transfer
			$receiverInfo['wallet_balance'] = @$receiverInfo['wallet_balance'] + $transferInfo['amount'];
			
			//	save settings
            $receiverInfo['username'] = $transferInfo['to'];
            Ayoola_Access_Localize::info( $receiverInfo );
		
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = $transferInfo['amount'] . ' sent to ' . $transferInfo['to'];
			$mailInfo['body'] = '"' . var_export( $transferInfo, true ) . '"';
			try
			{
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }						
			return true;
		}
		catch( Exception $e )
		{ 

        }
	}
}
