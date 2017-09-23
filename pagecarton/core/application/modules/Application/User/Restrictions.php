<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Restrictions
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: RequestOtherInfo.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Exception 
 */
 
require_once 'Application/User/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_Restrictions
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Restrictions extends Application_User_Abstract
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true; 
	
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
	//	if( ! $data = self::getIdentifierData() )
		{ 
		//	$data ; 
		}
	//	var_export( $data );
		$data = self::getInfo();
		$this->_objectTemplateValues = $data;
		if( ! @$this->_parameter['markup_template'] ) 
		{
			$this->_parameter['markup_template'] = 
			'				
				<p><strong>Disk Space</strong> <span style="font-size:smaller;">({{{storage_size_free}}} left)</span><br>
				<span style="font-size:smaller;">{{{posts_file_size}}} used out of {{{storage_size}}} quota</span></p>
				<div class="progress">
				<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{{storage_size_used_percentage}}}" class="progress-bar progress-bar-warning" role="progressbar" style="width: {{{storage_size_used_percentage}}}%;min-width: 2em;">{{{storage_size_used_percentage}}}%</div>
				</div>


				<p><strong>Posts</strong> <span style="font-size:smaller;">({{{posts_count_all_free}}} left)</span><br>
				 <span style="font-size:smaller;">{{{posts_count_all}}} posts created out of {{{max_allowed_posts}}} quota </span>
				</p>
				<div class="progress">
				<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{{posts_count_all_used_percentage}}}" class="progress-bar progress-bar-warning " role="progressbar" style="width: {{{posts_count_all_used_percentage}}}%; min-width: 2em;">{{{posts_count_all_used_percentage}}}%</div>
				</div>

				<p><strong>Private Posts</strong><span style="font-size:smaller;">({{{posts_count_private_free}}} left)</span><br>			 
				<span style="font-size:smaller;">{{{posts_count_private}}} private posts created out of {{{max_allowed_posts_private}}} quota</span>
				</p>

				<div class="progress">
				<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{{posts_count_private_used_percentage}}}" class="progress-bar progress-bar-warning " role="progressbar" style="width: {{{posts_count_private_used_percentage}}}%; min-width: 2em;">{{{posts_count_private_used_percentage}}}%</div>    
				</div>
			';
				
			if( self::hasPriviledge() )
			{
				$this->_parameter['markup_template'] .= '<p style="font-size:smaller;"><a rel="" onclick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/UserAccount/\' );">User Settings</a></p>';
			}
		}
		
	}
	
	
    /**
     * Return restriction info about a user
     * 
     */
	public static function getInfo( $username = null ) 
    {
		if( ! $username )
		{
			$userInfo = Ayoola_Application::getUserInfo();
			$username = $userInfo['username'];
		}
		$data = Ayoola_Access_Abstract::getAccessInformation( $username ) ? : array();
		
	//	var_export( $data );  
		if( $settings = Application_Settings_Abstract::getSettings( 'UserAccount' ) )
		{
		//	var_export( $settings );  
		//	$data = array_merge( $settings, $data );
		}
	//	var_export( $data );  
		$data['storage_size'] = @intval( $data['storage_size'] ? : $settings['storage_size'] );
		$data['max_allowed_posts'] = @intval( $data['max_allowed_posts'] ? : $settings['max_allowed_posts'] );    
		$data['max_allowed_posts_private'] = @intval( $data['max_allowed_posts_private'] ? : $settings['max_allowed_posts_private'] );
		$data['posts_file_size'] = @intval( $data['posts_file_size'] );
		$data['posts_count_all'] = @intval( $data['posts_count_all'] );
		$data['posts_count_private'] = @intval( $data['posts_count_private'] );
	//	var_export( $data['posts_file_size'] );
		if( empty( $data['posts_file_size'] ) )     
		{
			//	Calculate everything now
			$parameters = array( 'username_to_show' => $username );
			$class = new Application_Article_List( $parameters );
	//		$class->init();
		//	$allPosts = $this->getDbData();
			$class->setDbData();
			$allPosts = $class->getDbData();
		//	var_export( count( $allPosts ) );             
			foreach( $allPosts as $filename )
			{
				if( $values = @include $filename )
				{
					//	All these settings is entered when articles is saved.
					Application_Article_Abstract::updateProfile( $values );
			//		var_export( $values );
				}
			}
		//	var_export( $allPosts );
		}
	//	var_export( $data );  
		$filter = new Ayoola_Filter_FileSize();
//		$data['file_size'] = $filter->filter( $data['file_size'] );
		if( ! @$data['storage_size'] )
		{
			$data['storage_size_used_percentage'] = 100;
			$data['storage_size_free'] = 0;
		}
		else
		{
			$data['storage_size_free'] = $data['free_space'] = $data['storage_size'] - $data['posts_file_size'];
			$data['storage_size_used_percentage'] = ceil( ( $data['posts_file_size'] / $data['storage_size'] ) * 100 );
			$data['storage_size_free'] = $filter->filter( $data['storage_size_free'] );
			$data['posts_file_size'] = $filter->filter( $data['posts_file_size'] );
			$data['storage_size'] = $filter->filter( $data['storage_size'] );
		}
		// 
		if( ! @$data['max_allowed_posts'] )
		{
			$data['posts_count_all_used_percentage'] = 100;
			$data['posts_count_all_free'] = 0;
		}
		else
		{
			$data['posts_count_all_free'] = $data['max_allowed_posts'] - $data['posts_count_all'];
			$data['posts_count_all_used_percentage'] = ceil( ( $data['posts_count_all'] / $data['max_allowed_posts'] ) * 100 );
		}
		
		// 
		if( ! @$data['max_allowed_posts_private'] )
		{
			$data['posts_count_private_used_percentage'] = 100;
			$data['posts_count_private_free'] = 0;
		}
		else
		{
			$data['posts_count_private_free'] = $data['max_allowed_posts_private'] - $data['posts_count_private'];
			$data['posts_count_private_used_percentage'] = ceil( ( $data['posts_count_private'] / $data['max_allowed_posts_private'] ) * 100 );
		}
		return $data;
	}
	// END OF CLASS
}
