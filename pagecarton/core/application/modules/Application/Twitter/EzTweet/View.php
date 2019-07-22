<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Twitter_EzTweet_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: EzTweet.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Twitter_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Twitter/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Twitter_EzTweet_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Twitter_EzTweet_View extends Application_Twitter_Abstract
{
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
	//	trigger_error( 'called' );
		$code = '$(document).ready(function(){
				jQuery(function($){
					$("#twitterbody").tweet({
					  join_text: "auto",
					  username: "' . @array_shift( array_map( "trim", explode( ",", Application_Twitter_Abstract::getSettings( "twitter_username" ) ) ) ) . '",
					  avatar_size: 48,
					  count: 3,
					  auto_join_text_default: "",
					  auto_join_text_ed: "",
					  auto_join_text_ing: "",
					  auto_join_text_reply: "",
					  auto_join_text_url: "",
					  loading_text: "Loading Tweets..."
					});
				});  });';
		Application_Javascript::addCode( $code, array( 'onload' => true ) );
	//	Application_Javascript::addFile( 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array( 'js_mode' => true ) );
		Application_Javascript::addFile( '/js/objects/jquery/tweet/min.js' );
		Application_Style::addFile( '/js/objects/jquery/tweet/css.css' );
		$this->setViewContent(  '' . self::__( '<div id="twitterbody"></div>' ) . '', true  );
	} 
	// END OF CLASS
}
