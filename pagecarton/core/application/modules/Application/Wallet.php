<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Wallet
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Wallet.php 4.17.2012 11.53 ayoola $ 
 */


/**
 * @category   PageCarton CMS
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
					//	throw new Application_Exception( 'INVALID SENDER: ' . $transferInfo['from'] );
						return false;
				}
			
				//	Check funds
				if( @$senderInfo['wallet_balance'] < $transferInfo['amount'] )
				{
			//		throw new Application_Exception( 'INSUFFICIENT FUNDS: YOU NEED ADDITIONAL ' . ( $transferInfo['amount'] - @$senderInfo['wallet_balance'] ) );
					return false;
				}
				else
				{
					$senderInfo['wallet_balance'] = $senderInfo['wallet_balance'] - $transferInfo['amount'];
					$senderInfo['username'] = $transferInfo['from'];
				//	var_export( $senderInfo );
				//	var_export( $senderInfo );
					Ayoola_Access::setAccessInformation( $senderInfo ); // deductions first for security reasons.
				}
			}
			
			
			//	Get the info of the reciever
			if( ! $receiverInfo = Ayoola_Access::getAccessInformation( $transferInfo['to'] ) )
			{
		//		throw new Application_Exception( 'INVALID RECEIVER: ' . $transferInfo['to'] );
				return false;
			}
			
			//	Transfer
			$receiverInfo['wallet_balance'] = @$receiverInfo['wallet_balance'] + $transferInfo['amount'];
			
			//	save settings
			$receiverInfo['username'] = $transferInfo['to'];
			Ayoola_Access::setAccessInformation( $receiverInfo );
		//	var_export( $receiverInfo );
		
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = $transferInfo['amount'] . ' sent to ' . $transferInfo['to'];
			$mailInfo['body'] = '"' . var_export( $transferInfo, true ) . '"';
			try
			{
			//	var_export( $newCart );
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
			
			
			return true;
		}
		catch( Exception $e )
		{ 
		
			$this->setViewContent( '<p class="badnews boxednews">' . $e->getMessage() . '</p>', true ); 
			$this->setViewContent( '<p class="badnews boxednews">Error with Wallet package</p>' ); 
		}
	}
}
