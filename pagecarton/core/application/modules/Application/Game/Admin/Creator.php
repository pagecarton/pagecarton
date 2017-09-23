<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Game   Ayoola
 * @package    Application_Game_Admin_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Game_Admin_Abstract
 */
 
require_once 'Application/Game/Abstract.php';


/**
 * @Game   Ayoola
 * @package    Application_Game_Admin_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Game_Admin_Creator extends Application_Game_Admin_Abstract
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
	//	Ayoola_Application::$GLOBAL;
		$myInfo = Ayoola_Access::getAccessInformation();
		$auth = Ayoola_Form::hashElementName( @$myInfo['profile_url'] . @Ayoola_Application::$GLOBAL['profile_url'] );
		if( @$_GET['game_challenge_auth'] === $auth && $auth  )
		{
			if( $myInfo['access_level'] != 5 )
			{
				$this->_objectTemplateValues['message'] = 'You are not running a player account. Only players can challenge other players.';
		//		$this->setViewContent( , true );
		//		return false;
			}
			elseif( $myInfo['profile_url'] == Ayoola_Application::$GLOBAL['profile_url'] )
			{
				$this->_objectTemplateValues['message'] = 'Sorry, you are not allowed to challenge yourself.';
		//		$this->setViewContent( , true );
		//		return false;
			}
			elseif( Ayoola_Application::$GLOBAL['access_level'] != 5 )
			{
				$this->_objectTemplateValues['message'] = '"' . Ayoola_Application::$GLOBAL['display_name'] . '" is not running a player account. Only players can be challenged.';
		//		$this->setViewContent( , true );
		//		return false;
			}
			elseif( ! $myInfo['game_level'] )
			{
				$this->_objectTemplateValues['message'] = 'You are not currently in the game. You need to enrol in a new game.';
		//		$this->setViewContent( , true );
		//		return false;
			}
			elseif( $myInfo['game_challengee'] )
			{
				$filename = Application_Profile_Abstract::getProfilePath( $myInfo['game_challengee'] );
				$opponentInfo = @include $filename;
			//	$this->setViewContent( '<p></p>', true );
				$this->_objectTemplateValues['message'] = 'You have recently challenged "' . $opponentInfo['display_name'] . '". You have to play this game before you can proceed.';
			}
			elseif( $myInfo['game_opponent'] )
			{
				$filename = Application_Profile_Abstract::getProfilePath( $myInfo['game_opponent'] );
				$opponentInfo = @include $filename;
			//	$this->setViewContent( '<p>You have already been scheduled to play with "' . $opponentInfo['display_name'] . '". You have to play this game before you can proceed to another game.</p>', true );
				$this->_objectTemplateValues['message'] = 'You have already been scheduled to play with "' . $opponentInfo['display_name'] . '". You have to play this game before you can proceed to another game.';
			}
			elseif( Ayoola_Application::$GLOBAL['game_level'] === $myInfo['game_level'] )
			{
			//	$this->setViewContent( '', true );
				$this->_objectTemplateValues['message'] = '"' . Ayoola_Application::$GLOBAL['display_name'] . '" is not currently in the your game level. Please choose a player in the your game level.';
			}
			else
			{
				
				//	Add challenger to the list
				$filename = Application_Profile_Abstract::getProfilePath( Ayoola_Application::$GLOBAL['profile_url'] );
				if( $profileInfo = @include $filename )
				{
					$profileInfo['game_challengers'] = is_array( $profileInfo['game_challengers'] ) ? : array();
					array_push( $profileInfo['game_challengers'], $myInfo['profile_url'] );
					Application_Profile_Abstract::saveProfile( $profileInfo );
				}
				
				//	Lock challengee
				$filename = Application_Profile_Abstract::getProfilePath( $myInfo['profile_url'] );
				if( $profileInfo = @include $filename )
				{
					$profileInfo['game_challengee'] = Ayoola_Application::$GLOBAL['profile_url'];
					$profileInfo['game_challengee_time'] = time();
					Application_Profile_Abstract::saveProfile( $profileInfo );
				}
				
				//	Save in the DB
				$table = new Application_Game();
				$table->insert( array( 'home_player' => Ayoola_Application::$GLOBAL['profile_url'], 'away_player' => $myInfo['profile_url'], 'time' => time() ) );
				$this->_objectTemplateValues['message'] = 'You have successfully challenged "' . Ayoola_Application::$GLOBAL['display_name'] . '".';
			}
		}
		else
		{
		//	$this->setViewContent( '', true );
			$this->_objectTemplateValues['game_challenge_auth'] = $auth;
			$this->_objectTemplateValues['message'] = 'Are you sure you want to challenge "' . Ayoola_Application::$GLOBAL['display_name'] . '" to a game?';
		}
		return false;
	
				
		
		
	//	$this->setViewContent( '<p>You have successfully challenged ' . Ayoola_Application::$GLOBAL['profile_url'] . ' </p>', true );
   } 
	// END OF CLASS
}
