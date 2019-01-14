<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Domain_Registration_CheckAvailability
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CheckAvailability.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Exception 
 */
 
require_once 'Application/Domain/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Registration_CheckAvailability
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration_CheckAvailability extends Application_Domain_Registration_Abstract
{
	
    /**
     * 
     */
	const DEFAULT_TLD = 'com';
	
    /**
     * 
     */
	protected static $_universalTlds = array( 'com', 'net', 'org', 'com.ng', 'info' );
	
    /**
     * 
     */
	protected static $_defaultPrefixes = array( 'i', 'our', 'my', 'the', 'e', 'first', 'free', 'cheap', 'premium' );
	
    /**
     * 
     */
	protected static $_defaultsuffixes = array( 'online', 'net', '247', 'forever', 'international', 'ing', 's' );
	
    /**
     * 
     */
	protected static $_defaultFieldset = 'Search for a new domain';
	
    /**
     * 
     * 
     */
	public function init()
    {
		try
		{
		//	$this->setViewContent( '<h2>Choose a Domain Name</h2>' );
			$this->createForm( 'Search Domain', self::$_defaultFieldset );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $values );
			$this->createForm( 'Search Domain', self::$_defaultFieldset );
			$this->setViewContent( $this->getForm()->view(), true );
		//	$this->setViewContent( '<h2>Choose another Domain Name</h2>', true );
		//	$domains = explode( "\n", str_replace( array( "\r\n", "\r" ), "\n", $values['domain_name'] ) );
		}
		catch( Exception $e ){ return false; }		
		
    } 
	
    /**
     * 
     * 
     */
	public static function check( $domain )
    {
		//	var_export( $domain );
	//	return false;
		$pieces = explode( ".", $domain );
		$extension = (count($pieces) == 2) ? $pieces[1] : $pieces[1] . "." . $pieces[2];

		$server = $extension . ".whois-servers.net";
		if( @$response = self::getResponse( $server, $domain ) )
		{
			if( stripos( $response, 'no match for' ) !== FALSE ){ return false; }
			if( stripos( $response, 'NOT FOUND' ) === 0 ){ return false; }
			
			switch( trim( strtolower( $response ) ) )
			{
				case 'notfound':
				case 'not found':
				return false;
				break;
			}
		//	var_export( $domain . '<br>' );
		//	var_export( $response . '<br>' );
			return true;
		}
		else
		{
			//	Try individual search
			$table = Application_Domain_Registration_Whois::getInstance();
			$extension = explode( ".", $extension );
			$extension = array_pop( $extension );
			if( ! $whoisInfo = $table->selectOne( null, array( 'extension' => $extension ) ) )
			{
				return true;
			}
			
	//		var_export( $extension );
	//		var_export( $whoisInfo );
			$server = $whoisInfo['server'];
			if( ! @$response = self::getResponse( $server, $domain ) )
			{
				return true;
			}
	//		var_export( $server . '<br>' );
		//	var_export( strlen( $response ) . '<br>' );
			$response = str_ireplace( $domain, '', $response );
			$response = strlen( $response );
//			var_export( $response . '<br>' );
			if( $response == $whoisInfo['badnews_length'] ){ return false; }
			return true;
		}
    } 
	
    /**
     * 
     * 
     */
	public static function getResponse( $server, $domain )
    {
		$fp = fsockopen($server, 43, $errno, $errstr, 10);
		$result = "";
		if($fp === FALSE){ return FALSE; }
		fputs($fp, $domain . "\r\n");    
		while(!feof($fp)){ $result .= fgets($fp, 128); }
		fclose($fp);
	//	var_export( $result );
		return $result;
    } 
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;
	//	$form->formNamespace = get_class( $this ) . rand( 10, 1000 );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addLegend( self::$_defaultFieldset );
		$fieldset->addElement( array( 'name' => 'domain_name', 'label' => '', 'placeholder' => 'Search for a new domain name e.g. yourcompany.com', 'type' => 'InputSearch', 'value' => @$values['domain_name'] ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirement( 'domain_name', array( 'WordCount' => array( 3, 100 ), 'Username' => null ) );
		$form->addFieldset( $fieldset );
		$domainName = $this->getGlobalValue( 'domain_name' );
		$limit = $this->getParameter( 'suggestion_limit' );
		do
		{

			if( ! $domainName )
			{
				break;
			}
			$domains = array();
			$sub = array_map( 'trim', explode( '.', $domainName ) );
	//		$tld = $sub;
			
			//	default suffix is .com
			if( count( $sub ) < 2 )
			{ 
				$domainName .= '.' . self::DEFAULT_TLD;
			//	array_push( $sub, self::DEFAULT_TLD ); 
				
			}
			
			$domainName = str_ireplace( 'www.', '', $domainName );
			$sub = array_map( 'trim', explode( '.', $domainName ) );
			
			//	the first is the domain
			$subPart = array_shift( $sub );
		//	$tld = array_pop( $sub );
		
			//	the remaining is subdomain
			$tld = implode( '.', $sub );
			$domainName = $subPart . '.' . $tld;
		//	var_export( $values['domain_name'] );
			$domains[$domainName] = $domainName;
			
			$suggestions = array();
			$unavailable = array();
			
			$unavailableList = null;
			foreach( $domains as $domain )
			{
			//	if( self::check( $domain ) )
				if( self::check( $domain ) )
				{
					$unavailable[$domain] = $domain . $price;
			//		$this->getObjectStorage( 'unavailable' )->store( $unavailable );
					$unavailableList .= '<li style="list-style:none;display:inline-block;padding-right:1em;min-width:100px;">' . $domain . '</li>';
				}
				else
				{
				//	$this->getObjectStorage( 'unavailable' )->clear();
					$suggestions[$domain] = $domain . $price;
					if( $limit && count( $suggestions ) >= $limit )
					{
						break;
					}
				}
			}

			//	Suggest universal tld
			//	Filter the price to display unit in domain price
			$filter = 'Ayoola_Filter_Currency';
			$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
			$filter = new $filter();
		//	$value['price'] = $filter->filter( $value['price'] );
			foreach( static::$_universalTlds as $each )
			{
				if( self::getTldPrice( $each ) )
				{
			//		self::v( $each );
					$price = ' (' . $filter->filter( self::getTldPrice( $each ) ) . '/yr) ';
				}
				else
				{
					$price = null;
				}
				$each = $subPart . '.' . $each;
				if( ! self::check( $each ) )
				{
					$suggestions[$each] = $each . $price;
					if( $limit && count( $suggestions ) >= $limit )
					{
						break;
					}
				}
			}
			if( self::getTldPrice( $tld ) )
			{
		//		self::v( $each );
				$price = ' (' . $filter->filter( self::getTldPrice( $tld ) ) . '/yr) ';
			}
			else
			{
				$price = null;
			}
			
			//	Suggest prefix
			foreach( static::$_defaultPrefixes as $each )
			{
				$each = $each . $domainName;
				if( ! self::check( $each ) )
				{
					$suggestions[$each] = $each . $price;
					if( $limit && count( $suggestions ) >= $limit )
					{
						break;
					}
				}
			}
			
			//	Suggest suffixes
			foreach( static::$_defaultsuffixes as $each )
			{
				$each = $subPart . $each . '.' . $tld;
				if( ! self::check( $each ) )
				{
					if( $limit && count( $suggestions ) >= $limit )
					{
						break;
					}
					$suggestions[$each] = $each . $price;
				}
			}
		}
		while( false );
		$suggestions = @$suggestions ? : $this->getObjectStorage( 'suggestions' )->retrieve();
		$unavailable = @$unavailable ? : $this->getObjectStorage( 'unavailable' )->retrieve();
	//	var_export( $domains );
	//	var_export( $suggestions );
		if( @$suggestions || @$unavailable )
		{
			$fieldset = new Ayoola_Form_Element;		
			switch( $this->getGlobalValue( 'unavailable_selection_option', 'dont_allow_me_to_search_session' ) )
			{
				case 'new_search':
					//	One step backwards
					$form->actions[] = $form::BACKBUTTON_INDICATOR;
			//		$fieldset->addRequirement( 'unavailable_selection_option', array( 'WordCount' => array( 9999, 999999 ), ) );
				break;
			}
			if( @$unavailable && array_key_exists( $domainName, $unavailable ) )
			{ 
			//	$fieldset = new Ayoola_Form_Element;		
			//	var_export( $domainName );
				$fieldset->addElement( array( 'type' => 'html', 'name' => 'ee' ), array( 'html' => '<div class="badnews">Sorry! <strong> ' . $domainName . ' </strong> is not available! It is likely that someone else has taken it.</div>' ) );
				$option = array( 
									'new_search' => 'I want to search for a new domain name', 
									'no_ownership' => 'I will choose from the suggestions', 
									'domain_transfer' => 'I own "' . $domainName . '". I want to transfer the domain.', 
									'domain_hosting_only' => 'I own "' . $domainName . '". I want to retain my registrar. I will change the nameservers.',
								);
		//		$fieldset->addElement( array( 'name' => 'unavailable_selection_option', 'label' => 'Tell us what you would like to do now... ', 'required' => 'required', 'type' => 'Select', 'value' => @$values['unavailable_selection_option'] ), $option );		
		//		$fieldset->addRequirement( 'unavailable_selection_option', array( 'NotEmpty' => null ) );
				
				$fieldset->addFilters( array( 'trim' => null ) );
				if( 'domain_transfer' === $this->getGlobalValue( 'unavailable_selection_option' ) )
				{
					$fieldset->addElement( array( 'name' => 'unavailable', 'label' => 'Please select this domain if you own it and if you are willing to transfer the domain (or point the nameservers) to us:', 'type' => 'Checkbox', 'value' => @$values['unavailable'] ), $unavailable );		
					$fieldset->addRequirement( 'unavailable', array( 'NotEmpty' => null ) );
				}
			//	$unavailableDomain = implode( ', ', $unavailable );
				$this->getObjectStorage( 'unavailable' )->store( $unavailable ); 
				
			}	
			if( $suggestions )
			{
			//	$limit = 1;
				if( $limit && count( $suggestions ) > $limit )
				{
					while( count( $suggestions ) > $limit )
					{
						array_pop( $suggestions );
					}
				}
				$fieldset->addElement( array( 'type' => 'html', 'name' => 'exx' ), array( 'html' => '<div class="goodnews">Congratulations! The following domain names are available! </div>' ) );
				$fieldset->addElement( array( 'name' => 'suggestions', 'label' => ' ', 'type' => 'Checkbox', 'value' => @$values['suggestions'] ? : array( $domainName )  ), $suggestions );		
				$fieldset->addFilters( array( 'trim' => null ) );
				if( ! $this->getGlobalValue( 'unavailable_selection_option' ) || 'no_ownership' === $this->getGlobalValue( 'unavailable_selection_option' ) )
				{
					//	if we didnt selected unavailable, we must select one of these
					$fieldset->addRequirement( 'suggestions', array( 'NotEmpty' => null ) );
				}
				$this->getObjectStorage( 'suggestions' )->store( $suggestions );
				
			}
			elseif( 'no_ownership' === $this->getGlobalValue( 'unavailable_selection_option' ) )
			{
				//	One step backwards
				$form->actions[] = $form::BACKBUTTON_INDICATOR;
			}
		}
//	if( @$unavailable && array_key_exists( $domainName, $unavailable ) )
		{
		//	$fieldset->addLegend( self::$_defaultFieldset );
			$form->addFieldset( $fieldset );
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
