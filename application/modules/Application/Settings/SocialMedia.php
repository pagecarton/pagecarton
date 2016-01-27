<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_SocialMedia
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: SocialMedia.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_SocialMedia
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_SocialMedia extends Application_Settings_Abstract
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Settings';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'settingsname_name' );
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
    //    $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$settings = unserialize( htmlspecialchars_decode( @$values['settings'] ) );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );

		//	Facebook
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'facebook_consumer_key', 'label' => 'Facebook App ID/API Key', 'value' => @$settings['facebook_consumer_key'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'facebook_consumer_secret', 'label' => 'Facebook App Secret', 'value' => @$settings['facebook_consumer_secret'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'facebook_page_url', 'label' => 'Facebook Page Url', 'value' => @$settings['facebook_page_url'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Facebook' );
		$form->addFieldset( $fieldset );
	//	$form->addFieldset( $fieldset );
		
		//	twitter
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'twitter_username', 'description' => 'Get one account on https://twitter.com/', 'label' => 'Twitter Username', 'value' => @$settings['twitter_username'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'twitter_consumer_key', 'description' => 'Get one on https://dev.twitter.com/apps', 'label' => 'Consumer key', 'value' => $settings['twitter_consumer_key'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'twitter_consumer_secret', 'label' => 'Consumer secret', 'value' => $settings['twitter_consumer_secret'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'twitter_access_token', 'label' => 'Access token', 'value' => $settings['twitter_access_token'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'twitter_access_token_secret', 'label' => 'Access token secret', 'value' => $settings['twitter_access_token_secret'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Twitter' );
		$form->addFieldset( $fieldset );
		
		//	Google+
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'googleplus_id', 'label' => 'GooglePlus ID', 'value' => @$settings['googleplus_id'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'google_consumer_key', 'value' => $settings['google_consumer_key'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'google_consumer_secret', 'value' => $settings['google_consumer_secret'], 'type' => 'InputText' ) );
	//	@$fieldset->addElement( array( 'name' => 'google_map_link', 'value' => $settings['google_map_link'], 'type' => 'InputText' ) );
		@$fieldset->addElement( array( 'name' => 'google_analytics_tracking_id', 'value' => @$settings['google_analytics_tracking_id'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Google' );
		$form->addFieldset( $fieldset );
		
		//	Yahoo
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->addElement( array( 'name' => 'yahoo_consumer_key', 'value' => $settings['yahoo_consumer_key'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'yahoo_consumer_secret', 'value' => $settings['yahoo_consumer_secret'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Yahoo' );
		$form->addFieldset( $fieldset );
		
		//	Live
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->addElement( array( 'name' => 'live_consumer_key', 'value' => $settings['live_consumer_key'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'live_consumer_secret', 'value' => $settings['live_consumer_secret'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Live' );
		$form->addFieldset( $fieldset );
/* 		
		//	Myspace
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'myspace_consumer_key', 'value' => $settings['myspace_consumer_key'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'myspace_consumer_secret', 'value' => $settings['myspace_consumer_secret'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'MySpace' );
		$form->addFieldset( $fieldset );
 */		
		//	LinkedIn
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'linkedin_url', 'value' => @$settings['linkedin_url'], 'type' => 'InputText' ) );
//		$fieldset->addElement( array( 'name' => 'linkedin_consumer_key', 'value' => $settings['linkedin_consumer_key'], 'type' => 'InputText' ) );
//		$fieldset->addElement( array( 'name' => 'linkedin_consumer_secret', 'value' => $settings['linkedin_consumer_secret'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'LinkedIn' );
		$form->addFieldset( $fieldset );
		
		//	Foursquare
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'foursquare_link', 'value' => @$settings['foursquare_link'], 'type' => 'InputText' ) );
//		$fieldset->addElement( array( 'name' => 'foursquare_consumer_key', 'value' => $settings['foursquare_consumer_key'], 'type' => 'InputText' ) );
//		$fieldset->addElement( array( 'name' => 'foursquare_consumer_secret', 'value' => $settings['foursquare_consumer_secret'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Foursquare' );
		$form->addFieldset( $fieldset );
		
		//	Disqus+
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'disqus_shortname', 'label' => 'Disqus Shortname', 'value' => @$settings['disqus_shortname'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Disqus' );
		$form->addFieldset( $fieldset );
				
//		var_export( $fieldsets ); 
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
