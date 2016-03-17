<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @Game   Ayoola
 * @package    Application_Game_New
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: New.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Game_Abstract
 */
 
require_once 'Application/Game/Abstract.php';


/**
 * @Game   Ayoola
 * @package    Application_Game_New
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Game_New extends Application_Game_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 1;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$myInfo = Ayoola_Access::getAccessInformation();
	//	$this->_objectTemplateValues['message'] = 'You have successfully started a new game.';
		$this->createConfirmationForm( 'Start',  'Start a new game for : "'  . $myInfo['display_name'] . '"' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
	//	Ayoola_Application::$GLOBAL;
		if( $myInfo['access_level'] != 5 )
		{
			$this->_objectTemplateValues['message'] = 'You are not running a player account. Only players can play a game.';
	//		$this->setViewContent( , true );
	//		return false;
		}
		elseif( $myInfo['wallet_balance'] < 70 )
		{
			$this->_objectTemplateValues['message'] = 'You have insufficient balance to start a new game. <a href="/object/name/Application_Wallet_Fund/?amount=70">Add funds</a>';
		}
		elseif( $myInfo['game_challengee'] )
		{
			$filename = Application_Profile_Abstract::getProfilePath( $myInfo['game_challengee'] );
			$opponentInfo = @include $filename;
			$this->_objectTemplateValues['message'] = 'You have recently challenged "' . $opponentInfo['display_name'] . '". You have to play this game before you can proceed.';
		}
		elseif( $myInfo['game_opponent'] )
		{
			$filename = Application_Profile_Abstract::getProfilePath( $myInfo['game_opponent'] );
			$opponentInfo = @include $filename;
			$this->_objectTemplateValues['message'] = 'You have already been scheduled to play with "' . $opponentInfo['display_name'] . '". You have to play this game before you can proceed to another game.';
		}
		elseif( $myInfo['game_level'] )
		{
		//	$this->setViewContent( '', true );
			$this->_objectTemplateValues['message'] = 'You are already playing a game. Please continue with the games you are playing.';
		}
		else
		{
			
			//	Start new game
			$filename = Application_Profile_Abstract::getProfilePath( $myInfo['profile_url'] );
			if( $profileInfo = @include $filename )
			{
				$profileInfo['game_level'] = 1;
				$profileInfo['game_start_time'] = time();
				
				// bill the user
				// send to the admin
				$transferInfo['to'] = 'joywealth';
				$transferInfo['from'] = Ayoola_Application::getUserInfo( 'username' );
				$transferInfo['amount'] = '70';
				$transferInfo['notes'] = 'New Padmood Game for "' . $myInfo['display_name'] . '"' ;
				Application_Wallet::transfer( $transferInfo );

				Application_Profile_Abstract::saveProfile( $profileInfo );
				$this->_objectTemplateValues['message'] = 'You have successfully started a new game.';
			}
		}				
		if( $this->_objectTemplateValues['message'] )
		{
			$this->setViewContent( '<p><strong> Starting a new game for "' . $myInfo['display_name'] . '" </p></strong>', true );
			$this->setViewContent( '<p> ' . $this->_objectTemplateValues['message'] . ' </p>' );
		}
		else
		{
			$this->setViewContent( '<p> ERROR - Failed to create a new game. </p>', true );
			$this->setViewContent( $this->getForm()->view() );
		}
   } 
	// END OF CLASS
}
