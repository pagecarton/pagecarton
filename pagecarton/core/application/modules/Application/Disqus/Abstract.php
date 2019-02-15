<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Disqus_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Disqus_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Disqus/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Disqus_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Disqus_Abstract extends Application_SocialMedia_Abstract
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
	protected static $_accessLevel = 0;
	
    /**
     * loads the Disqus sdk
     * 
     */
	public static function load()
    {
	//	if( self::$_loaded ){ return; }
		$username = self::getSettings();
	//	var_export( $username );
		if( empty( $username['disqus_shortname'] ) ){ return; }
		$username = $username['disqus_shortname'];
		$code = "var disqus_shortname = '{$username}'; var disqus_identifier = '" . Ayoola_Page::getCanonicalUri() . "'; var disqus_title = '" . trim( Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' ) . "'; var disqus_url = '" . Ayoola_Page::getCanonicalUrl() . "';(function() { var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true; dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);})();";
		Application_Javascript::addCode( $code );
		self::$_loaded = true;
    } 
	// END OF CLASS
}
