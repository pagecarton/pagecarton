<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_ShowLabel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowLabel.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_ShowLabel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_ShowLabel extends Application_Subscription_Detail
{

    /**
     * Attribute to show
     * 
     * @var string
     */
	protected static $_attribute = 'subscription_label';

    /**
     * The wrapper
     * 
     * @var string
     */
	protected static $_parentTag = 'h1';
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = $this->getIdentifierData() ){ return null; }
			$message = '<' . static::$_parentTag . '>' . $data[static::$_attribute] . ' ';
		//	$this->setViewContent( , true );
			if( self::hasPriviledge() )
			{
				$message .= '<span class="goodnews" >
								<a class="" title="Add a category to ' . $data['subscription_label'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Level_Creator/subscription_id/' . $data['subscription_id'] . '/"> + </a> 
								<a class="badnews" title="Edit ' . $data['subscription_label'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Editor/subscription_id/' . $data['subscription_id'] . '/"> - </a>
								<a class="badnews" title="Delete ' . $data['subscription_label'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Delete/subscription_id/' . $data['subscription_id'] . '/"> x </a>
							</span>';
			}
			$message .= '</' . static::$_parentTag . '>';
			$this->setViewContent( $message, true );
		}
		catch( Exception $e ){ return; }
	//	var_export( $this->_xml );
    } 
	// END OF CLASS
}
