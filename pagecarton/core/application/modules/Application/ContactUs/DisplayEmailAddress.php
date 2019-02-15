<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_ContactUs_DisplayEmailAddress
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DisplayEmailAddress.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_ContactUs_Abstract
 */
 
require_once 'Application/ContactUs/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_ContactUs_DisplayEmailAddress
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_ContactUs_DisplayEmailAddress extends Application_ContactUs_Abstract
{
    /**
     * Access level for player
     *
     * @var int
     */
	protected static $_accessLevel = 0;
	
    /**
     * 
     *
     * @var int
     */
	protected static $_counter = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$email = Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'email' );
			$email = array_shift( array_map( 'trim', explode( ',', $email ) ) );
			$username = array_shift( array_map( 'trim', explode( '@', $email ) ) );
			$domain = array_pop( array_map( 'trim', explode( '@', $email ) ) );
			$identifier = $this->getObjectName() . '_email_container_' . self::$_counter++;
			$this->_objectTemplateValues['email'] = '<span name="' . $identifier . '"></span>';
			$this->setViewContent( $this->_objectTemplateValues['email'], true );
			if( self::hasPriviledge() )
			{
				$this->setViewContent( '<span onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/CompanyInformation/\' ); return false;" class="badnews" title="Change e-mail address and other organizational information"> x </span>' );
		//		$this->_objectTemplateValues['email_edit_link'] = '<span name="' . $identifier . '"></span>';
			}
			Application_Javascript::addCode
			(
				'
					var g = function()
					{
						var a = "' . $username . '";
						var b = "' . rand( 100, 200 ) . '";
						var c = "' . $domain . '";
						var d = "" + a + "@" + c + "";
						
						//	Fill all containers with the email address
						var e = document.getElementsByName( "' . $identifier . '" );
						for( var f = 0; f < e.length; f++ )
						{
							e[f].innerHTML = d;
							if( e[f].parentNode && e[f].parentNode.tagName.toLowerCase() == "a" )
							{
								//	Make links go directly to e-mail if it doesnt have a link already
			//					alert( e[f].parentNode.href );
								switch( e[f].parentNode.href )
								{
									case "":
										e[f].parentNode.href = "mailto:" + d;
									break;
									case "#":
										e[f].parentNode.href = "mailto:" + d;
									break;
									case "javascript:":
										e[f].parentNode.href = "mailto:" + d;
									break;
									case undefined:
										e[f].parentNode.href = "mailto:" + d;
									break;
									
								}
								e[f].parentNode.innerHTML += e[f].innerHTML;
								e[f].parentNode.removeChild( e[f] );
								
							}
						}
					}
					//	First call the function
					g();
					
					//	Call again when document is loaded.
					ayoola.events.add
					(
						window,
						"load",
						g
					);				
				'
			);
			
		}
		catch( Application_ContactUs_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
