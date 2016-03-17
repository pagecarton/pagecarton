<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Publish
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Publish.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Publish
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Publish extends Application_Article_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
		//	var_export( Application_HashTag_Abstract::get( 'articles' ) );
			
			//	Only the owner can edit or priviledged user can edit
			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::isOwner( $data['user_id'] ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ) ){ return false; }
			@$data['publish'] =  @in_array( 'publish', @$data['article_options'] ) ? '1' :  $data['publish'];
			$filename = self::getFolder() . $data['article_url'];
			
			//	Use modified time to ensure theres no multiple entry.
			$time = filemtime( $filename );
		//	var_export( intval( @$_REQUEST['switch_publish'] ) );
		//	var_export( $time );
			if( @$_REQUEST['switch_publish'] && intval( @$_REQUEST['switch_publish'] ) === intval( $time ) )
			{
				//	switch
				switch( intval( $data['publish'] ) )
				{
					case 0:
						$data['publish'] = 1;
					break;
					case 1:
						$data['publish'] = 0;
						if( @in_array( 'publish', @$data['article_options'] ) )
						{
							$data['article_options'] = array_combine( $data['article_options'], $data['article_options'] );
							unset( $data['article_options']['publish'] );
							$data['article_options'] = array_values( $data['article_options'] );
						}
					break;
				}				
				self::saveArticle( $data );
				
				//	Refresh time
				$time = filemtime( $filename );
			}
		//	$linkToSwitch = '';
			
			$html = '
					<style>
						.onoffswitch {
							position: relative; max-width: 500px;
							-webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
						}
						.onoffswitch-checkbox {
							display: none;
						}
						.onoffswitch-label {
							display: block; overflow: hidden; cursor: pointer;
							border: 2px solid #999999; border-radius: 20px;
						}
						.onoffswitch-inner {
							display: block; width: 200%; margin-left: -100%;
							transition: margin 0.3s ease-in 0s;
						}
						.onoffswitch-inner:before, .onoffswitch-inner:after {
							display: block; float: left; width: 50%; height: 33px; padding: 0; line-height: 33px;
							font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
							box-sizing: border-box;
						}
						.onoffswitch-inner:before {
							content: "Published";
							padding-left: 10px;
							background-color: ' . ( Application_Settings_CompanyInfo::getSettings( 'Page', 'background_color' ) ? : '#34A7C1' ) . '; color: ' . ( Application_Settings_CompanyInfo::getSettings( 'Page', 'font_color' ) ? : '#FFFFFF' ) . ';
						}
						.onoffswitch-inner:after {
							content: "Not Published";
							padding-right: 10px;
							background-color: #EEEEEE; color: #999999;
							text-align: right;
						}
						.onoffswitch-switch {
							display: block; width: 18px; margin: 7.5px;
							background: #FFFFFF;
							position: absolute; top: 0; bottom: 0;
							right: 137px;
							border: 2px solid #999999; border-radius: 20px;
							transition: all 0.3s ease-in 0s; 
						}
						.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
							margin-left: 0;
						}
						.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
							right: 0px; 
						}
					</style>
			
					<div onClick="window.location.search = window.location.search + \'&switch_publish=' . $time . '\'" class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" ' . ( $data['publish'] ? 'checked' : '' ) . '>
						<label class="onoffswitch-label" for="myonoffswitch">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>
					';
			$this->setViewContent( $html );
		}
		catch( Application_Article_Exception $e )
		{ 
		//	$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $e->getMessage(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
