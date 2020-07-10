<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Switch
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Switch.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Switch
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Switch extends Application_Article_Abstract
{
	/**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Post Switch'; 

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
            $articleSettings['allowed_editors'][] = 98;
			if(  ! self::hasPriviledge( $articleSettings['allowed_editors'] ) ){ return false; }
			$switch = @$_REQUEST['post_switch'] ? : 'featured';
			$switch = $this->getParameter( 'post_switch' ) ? : $switch;			
	//		@$data[$switch] = @$data[$switch] ? true :  $data[$switch];
			$filename = self::getFolder() . $data['article_url'];
			
			//	Use modified time to ensure theres no multiple entry.
			$time = filemtime( $filename );
		//	self::v( intval( @$_REQUEST['switch_change_time'] ) );
		//	self::v( $time );
			@$data[$switch] = $data[$switch] ? : false;
			if( intval( @$_REQUEST['switch_change_time'] ) === intval( $time ) )
			{
			//	self::v( $data[$switch] );
				//	switch
				switch( $data[$switch] )
				{
					case false:
						$data[$switch] = $switch;
                        $data['post_switches'][] = $switch;
                        $data['post_switches'] = array_unique( $data['post_switches'] );
					break;
					default:
						$data[$switch] = false;
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
							border: 2px solid #999999; border-radius: 0;
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
							content: "' . ( $this->getParameter( 'on_switch' ) ? : 'On' ) . '";
							padding-left: 10px;
							background-color: ' . ( Application_Settings_CompanyInfo::getSettings( 'Page', 'background_color' ) ? : '#34A7C1' ) . '; color: ' . ( Application_Settings_CompanyInfo::getSettings( 'Page', 'font_color' ) ? : '#FFFFFF' ) . ';
						}
						.onoffswitch-inner:after {
							content: "' . ( $this->getParameter( 'off_switch' ) ? : 'Off' ) . '";
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
					<div onClick="var a = \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . __CLASS__ . '\' +  \'?article_url=' . $data['article_url'] . '\&switch_change_time=' . $time . '\'; ayoola.xmlHttp.fetchLink( { url: a } ); this.onclick=null" class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" ' . ( @$data[$switch] ? 'checked' : '' ) . '>
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
