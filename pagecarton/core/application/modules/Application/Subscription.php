<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Subscription.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription extends Application_Subscription_Abstract
{
	
    /**
     * Confirmation
     *
     * @var string
     */
	protected static $_confirmation; 
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'subscription_name' );
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
			try
			{
				$this->getIdentifier();
			}
			catch( Ayoola_Exception $e )
			{ 
				if( $this->getGlobalValue( 'subscription_name' ) )
				{
					$this->setIdentifier( array( 'subscription_name' => $this->getGlobalValue( 'subscription_name' ) ) );
				}
				else
				{
					throw new Application_Subscription_Exception( 'COULD NOT LOCATE THE SELECTED PRODUCT OR SERVICE.' );
				}
			}
			if( ! $data = $this->getIdentifierData() ){ return $this->setViewContent(  '' . self::__( '<p>Subscription Package Not Found</p>' ) . '', true  ); }
			$this->setViewContent( $this->getForm()->view(), true );
			
			//	workarround for infinite loop at App_Domain_Reg "no_init"
		//	var_export( $this->getParameter( 'no_init' ) );
			if( $data['subscription_object_name'] && ! $this->getParameter( 'no_init' ) )
			{ 
				try
				{
					$objects = new Ayoola_Object_Embed();
			//		var_export( $data['subscription_object_name'] );
			//		exit();
					$objects->oneObjectAtATime = true;
					$objects->setParameter( array( 'editable' => $data['subscription_object_name'] ) );
					$this->setViewContent( $objects->view(), true ); 
				//	var_export( $objects->view() );
					return;
				}
				catch( Ayoola_Object_Exception $e ){ null; }
			}
			if( ! $this->subscribe() )
			{ 
			//	$this->setViewContent(  '' . self::__( '<p>Unable to add product or service to cart.</p>' ) . '', true  ); 
				return;
			}
			$this->setViewContent( self::getConfirmation(), true );
/* 			if( ! $data['subscription_object_name'] )
			{ 
				$this->setViewContent( self::getConfirmation(), true );
				return;
			}
 */
		}
		catch( Exception $e )
		{ 
	//		echo $e->getMessage();
		//	return $this->setViewContent(  '' . self::__( '<p class="badnews boxednews centerednews">Error with subscription package.</p>' ) . '', true  ); 
			$this->setViewContent(  '' . self::__( '<p class="badnews boxednews centerednews">Error with subscription package.</p>' ) . '', true  ); 
			$this->setViewContent( self::__( '<p class="badnews boxednews centerednews">' . $e->getMessage() . '</p>' ) ); 
		}
    }
	
    /**
     * sort price list 
     * 
     * @param array The price List to sort from DB
     * @return array The price List according to the sort credentials
     */
	public static function sortPriceList( Array $priceList )
    {
		$sort = array();
		$i = 0;
		foreach( $priceList as $value )
		{
			$sort[$value['subscription_name']] = isset( $sort[$value['subscription_name']] ) ? $sort[$value['subscription_name']] : array();
			if( isset( $sort[$value['subscription_name']][$value['price']] ) )
			{
				$sort[$value['subscription_name']][$value['price'] . $i++] = $value;
			}
			else
			{
				$sort[$value['subscription_name']][$value['price']] = $value;
			}
			ksort( $sort );
		}
		return $sort;
    }
	
    /**
     * Return $_confirmation 
     * 
     * @param void
     * @return string
     */
	public static function getConfirmation()
    {
		if( self::$_confirmation ){ return self::$_confirmation; }
/* 			<span>
				<a onClick="window.parent.location.href=this.href;" href="' . Ayoola_Application::getUrlPrefix() . '/onlinestore/">
					<input name="' . __CLASS__ . '_next_steps" onClick="ayoola.div.selectElement( this )" class="boxednews" value="See products and services" type="button" /></a>
			</span> 
 */ 
		self::$_confirmation = '<p class="goodnews">Product or service has been added to your shopping cart. <a href="' . Ayoola_Application::getUrlPrefix() . '/cart/">View Shopping Cart</a></p>
		<div>
			<span>
				
			</span>
		</div>';
 	//	self::$_confirmation .= Ayoola_Menu::viewInLine( null, 'onlinestore' );
//		self::$_confirmation .= Application_Subscription_Cart::viewInLine();
		return self::$_confirmation;
    }
	
    /**
     * Creates the form for subscription
     * 
     */
	public function subscribe( array $values = null )
    {
		if( ! $values )
		{
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
		}
	
		//	Clear plain text password for security reasons
		unset( $values['password'], $values['password2'] );
		
		@$settings['currency_abbreviation'] = $values['currency_abbreviation'] ? : Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' );
		@$settings['return_url'] = $values['return_url'] ? : 'http://' . Ayoola_Page::getDefaultDomain() . '/';
		@$settings['read_only'] = $values['read_only'];
		@$settings['edit_cart_url'] = $values['edit_cart_url'] ? : 'javascript:';
		
		//	Introducing passwords for cart, so we can deal with ambigiuty
		@$settings['password'] = $values['cart_password'] ? : 'default_password';
		$values['item_time'] = time();	//	Also useful for randomization of cart item
		
		if( ! @$values['subscription_name'] )
		{
			$data = $this->getIdentifierData();
			$values['subscription_name'] = $values[$data['subscription_name'] . 'subscription_name'];	//	
		}
		@$values['price_id'] = $values['price_id'] ? : $values[$values['subscription_name'] . 'price_id'];	//	
		@$values['multiple'] = $values['multiple'] ? : $values[$values['subscription_name'] . 'multiple'];	//	
		@$values['url'] = $values['url'] ? : ( $values['subscription_name'] ? '/' .  strtolower( $values['subscription_name'] ) . '/' : null );	//	
		@$values['classplayer_url'] = $data['subscription_object_name'] ? '/tools/classplayer/get/object_name/' .  $data['subscription_object_name'] . '/' : null;	//	
		
		//	Store in a session
		$previousData = $this->getStorage()->retrieve() ? : array();


		// Inconsistent currency
		if( @$previousData['settings']['currency_abbreviation'] && $previousData['settings']['currency_abbreviation'] != $settings['currency_abbreviation'] )
		{ 
			$this->getStorage()->store( array() );
			$previousData = array();
		}
		
		//	Inconsistent Password or we want to refresh cart
		if( ( @$previousData['settings']['password'] && $previousData['settings']['password'] != $settings['password'] ) || ! empty( $values['refresh_cart'] ) )
		{ 
			$this->getStorage()->store( array() );
			$previousData = array();
		}
		if( ! isset( $values['multiple'] ) )
		{ 
			$values['multiple'] = '1';
		}
		if( $values['multiple'] != strval( intval( $values['multiple'] ) ) )
		{ 
			return false;
		}

        $newCart = array( $values['subscription_name'] => $values );
		
		@$newCart = is_array( $previousData['cart'] ) ? array_merge( $previousData['cart'], $newCart ) : $newCart;
		
		//	When multiple, is 0, we are deleting the item from the subscription list
		if( $newCart[$values['subscription_name']]['multiple'] == 0 )
        { 
            unset( $newCart[$values['subscription_name']] ); 
        }

		//	calculate the total price
		$settings['total'] = 0.00; 
		$settings['article_url'] = array();
		$settings['terms_and_conditions'] = '';
        $refreshList = array();
		foreach( $newCart as $name => $eachItem )
		{
			if( ! empty( $eachItem['exclusive'] ) )
			{
                //  don't add default payment
				continue;
			}  

			if( ! isset( $eachItem['price'] ) && $eachItem['price_id'] )
			{
				$eachItem = array_merge( self::getPriceInfo( $eachItem['price_id'] ), $eachItem );
			}  
			if( ! empty( $eachItem['article_url'] ) )
			{
				$settings['article_url'][] = $eachItem['article_url'];
			}  
			if( ! empty( $eachItem['item_terms_and_conditions'] ) && strpos( $settings['terms_and_conditions'], $eachItem['item_terms_and_conditions'] ) === false )
			{
				$settings['terms_and_conditions'] .= strtoupper( $eachItem['article_title'] ) . "\r\n" . "\r\n";
				$settings['terms_and_conditions'] .= $eachItem['item_terms_and_conditions'] . "\r\n" . "\r\n";
			}  

            //  refresh promo codes
            if( 
                $eachItem['refreshable'] 
                && $values['refreshable'] != $eachItem['refreshable'] 
                && empty( $values['refreshing_cart_item'] )
            )
            {
                $eachItem['refreshing_cart_item'] = time();
                $refreshList[$eachItem['refreshable']] = $eachItem;
            }

			@$newCart[$name]['item_total'] = $eachItem['price'] * $eachItem['multiple'];
            //var_export( $newCart[$name]['item_total'] );
            //var_export( '<br>' );
			@$settings['total'] += $newCart[$name]['item_total'];
		}

    //  if( empty( $previousData ) )
        {
            //	surcharges
            $paymentSettings = Application_Settings_Abstract::getSettings( 'Payments' );
            $totalSurcharge = 0.00;
    
            if( ! empty( $paymentSettings['surcharge_title'] ) )
            {
                foreach( $paymentSettings['surcharge_title'] as $key => $eachSurcharge )
                {
                    if( empty( $paymentSettings['surcharge_title'][$key] ) )
                    {
                        continue;
                    }
                    if( ! empty( $paymentSettings['cart_item_type'][$key] ) && $paymentSettings['cart_item_type'][$key] !== $data['settings']['password'] )
                    {
                        continue;
                    }
                    $surchargeText = '';
                    $surchargePrice = 0;
                    $paymentSettings['surcharge_value'][$key] = doubleval( $paymentSettings['surcharge_value'][$key] );
                    $surchargePricTextX = '0.00';
                    switch( @$paymentSettings['surcharge_type'][$key] )
                    {
                        case 'percentage':   
                            if( ! empty( $paymentSettings['surcharge_value'][$key] ) && intval( $paymentSettings['surcharge_value'][$key] ) <= 100 )
                            {  
                                $surchargeText .= '+ ' . $paymentSettings['surcharge_value'][$key] . '% of total order';
                                $surchargePrice += ( $paymentSettings['surcharge_value'][$key]/100 ) * $settings['total'];
                            }
                        break;
                        case 'constant':
                            if( ! empty( $paymentSettings['surcharge_value'][$key] ) )
                            {  
                                $filter = 'Ayoola_Filter_Currency';
                                $filter::$symbol = $data['settings']['currency_abbreviation'] ? : ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$' );
                                $filter::$symbol .=  '';
                                $filter = new $filter;
                        
                                $surchargeText .= '+ ' . $filter->filter( $paymentSettings['surcharge_value'][$key] ) . ' fixed charge';
                                $surchargePrice += $paymentSettings['surcharge_value'][$key];
                            }
                        break;
                        case 'not-calculated':
                            $surchargeText .= 'Not Calculated.  ' . $paymentSettings['surcharge_value'][$key] . '';
                        break;
                    }
                    $surchargeText = $surchargeText ? $paymentSettings['surcharge_title'][$key] . ' (' . $surchargeText . ')' : $paymentSettings['surcharge_title'][$key];

                    $surchargeValue = array();
                    @$surchargeValue['price_id'] = $surchargeText;	//	
                    @$surchargeValue['multiple'] = 1;	//	
                    @$surchargeValue['price'] = $surchargePrice;	//	
                    @$surchargeValue['readonly'] = $surchargePrice;	//	
                    @$surchargeValue['exclusive'] = true;	//	
                    $surchargeValue['subscription_name'] = $surchargeText;
                    $surchargeValue['subscription_label'] = $surchargeText;
                    //$settings['total'] += $surchargePrice;
                    unset( $newCart[$surchargeText] );
                    $newCart[$surchargeText] = $surchargeValue;
                }
            }
        }

		$settings['article_url'] = array_unique( $settings['article_url'] );
		$this->getStorage()->store( array( 'cart' => $newCart, 'settings' => $settings ) );
        //var_export( $refreshList );

        foreach( $refreshList as $method => $item )
        {
            //var_export( $name );
            if( is_callable( $method ) )
            {
                $response = $method( $item );
            }
            else
            {
                // remove item that cannot be refreshed
                $item['multiple'] = 0;
                $this->subscribe( $item );
            }
            //var_export( $response );

    
        }
        //exit();

		return true;
    }
	
    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( & $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		@$object['option'] = $object['option'] ? : $object['view_option'];
	//	$html .= "<span data-parameter_name='view' >{$object['view']}</span>";
		
		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object
	//	var_export( $object );
		$html .= '<span style=""> Show subscription option for </span>';
		
		$options = new Application_Subscription_Subscription;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'subscription_name', 'subscription_label');
		$options = $filter->filter( $options );
		
		$html .= '<select data-parameter_name="subscription_name">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['subscription_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		return $html;
	}

    /**
     * Creates the form for subscription
     * 
     */
	public function createForm( $submitValue = null, $legend = NULL, array $values = NULL )
    {
		$priceList = new Application_Subscription_Price;
//		var_export( $priceList->select() );
		$priceList = $priceList->select( null, $this->getIdentifier() );
		$values = array();
		$newPriceList = array();
	//	var_export( $priceList );
		$form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		if( ! $priceList )
		{ 
			$this->setForm( $form );
			return false; 
		}
		foreach( $priceList as $value )
		{
			$newPriceList[$value['price_id']] = $value;

			//	Filter the price to display unit
			$filter = 'Ayoola_Filter_Currency';
			$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
			$filter = new $filter();
			$value['price'] = $filter->filter( $value['price'] );
			$xml = new Ayoola_Xml();
			$link = $xml->createElement( 'a', $value['subscriptionlevel_name'] );
			$link->setAttribute( 'href', 'javascript:' );
			$link->setAttribute( 'title', $value['subscription_description'] );
			$xml->appendChild( $link );
			$value['subscriptionlevel_name'] = $xml->saveHTML();
			$values[$value['price_id']] = $value['subscriptionlevel_name'] . "\n" . $value['price'] . ' ' . $value['cycle_name'];
		}
		$form->oneFieldSetAtATime = true;
		$form->formNamespace = get_class( $this ) . $value['subscription_name'];
		$form->submitValue = 'Continue checkout';
	//	var_export( $newPriceList );
		$previousData = $this->getStorage()->retrieve();
  //  	var_export( $previousData );
		$previousData = @$previousData['cart'][$value['subscription_name']];
  //  	var_export( $previousData );
		
		//	First fieldset
		$fieldset = new Ayoola_Form_Element();		
		$priceId = $value['subscription_name'] . 'price_id';
	//	var_export( $_GET );
	//	var_export( $previousData['price_id'] );
		$outputType = count( $values ) > 5 ? 'Select' : 'Radio';
		$fieldset->addElement( array( 'name' => $priceId, 'label' => 'Choose "' . $value['subscription_label'] . '" option :', 'type' => $outputType, 'value' => @$previousData['price_id'] ), $values );
		
		//	Post the subscription name so it could retain state
		$fieldset->addElement( array( 'name' => 'subscription_name', 'type' => 'hidden', 'value' => $value['subscription_name'] ) );
		$fieldset->addRequirement( $priceId, array( 'InArray' => array_keys( $values ) ) );
	//	$fieldset->addFilter( $priceId, array( 'Int' => null ) );
		$fieldset->addLegend( 'Choose option for "' . $value['subscription_label'] . '"' );
	//	$form->addFieldset( $fieldset );
		
		//	second fieldset
		$priceIdValue = $this->getGlobalValue( $priceId ) ? : array();
	//	@$_POST[$priceId] = $_POST[$priceId] ? : $_POST[Ayoola_Form::hashElementName( $priceId )];
	//	@$value = $newPriceList[$_POST[$priceId]];
		@$minQuantity = (int) $newPriceList[$priceIdValue]['min_quantity'] ? : 0;
		@$maxQuantity = (int) $newPriceList[$priceIdValue]['max_quantity'] ? : 5;
		@$allowedMultiples = (int) $newPriceList[$priceIdValue]['allowed_multiples'] ? : 1;
		$quantity = $minQuantity ? : 1;
		$amount = array();
		$amount[$quantity] = $quantity;
		while( $quantity <= $maxQuantity )
		{
		//	var_export( $allowedMultiples * $minQuantity );
			$amount[$quantity] = $quantity;
			$quantity += $allowedMultiples;
		}
	//	$amount = array_combine( range( 1, 5 ), range( 1, 5 ) );
		
		$uniqueNameForStorage = $value['subscription_name'] . $value['price_id'];
		if( ! $storage = $this->getObjectStorage( $uniqueNameForStorage )->retrieve() )
		{
			$storage = array();
		}
		if( @$newPriceList[$priceIdValue] )
		{
			$storage['price_info'] =  $value = $newPriceList[$priceIdValue];
			$storage['amount'] = $amount;
			$this->getObjectStorage( $uniqueNameForStorage )->store( $storage );
		}
		$multiple = $value['subscription_name'] . 'multiple';
		$subscriptionName = $value['subscription_name'] . 'subscription_name';
		$fieldset->addElement( array( 'name' => $subscriptionName, 'type' => 'Hidden' ) );
		$fieldset->addFilter( $subscriptionName, array( 'DefiniteValue' => $value['subscription_name'] ) );
	//		var_export( $value );
		
		if( $storage && $minQuantity )
//		if( $storage )
		{
		//	$fieldset = new Ayoola_Form_Element();		
			@$multipleLabel = $storage['price_info']['cycle_label'] ? : 'Multiples:';
		//	var_export( $value );
		//	if 
			$fieldset->addElement( array( 'name' => $multiple, 'label' => 'How many ' . $multipleLabel, 'type' => 'Radio', 'value' => @$previousData['multiple'] ), $storage['amount'] );
			@$fieldset->addRequirement( $multiple, array( 'Int' => null, 'ArrayKeys' => $storage['amount'] ) );
			$fieldset->addElement( array( 'name' => 'unit_price', 'type' => 'Hidden', 'value' => '' ) );
			$fieldset->addFilter( 'unit_price', array( 'DefiniteValue' => $storage['price_info']['price'] ) );
			$fieldset->addElement( array( 'name' => 'subscriptionlevel_name', 'type' => 'Hidden', 'value' => '' ) );
			$fieldset->addFilter( 'subscriptionlevel_name', array( 'DefiniteValue' => $storage['price_info']['subscriptionlevel_name'] ) );
			$fieldset->addElement( array( 'name' => 'subscription_requirements', 'type' => 'Hidden', 'value' => '' ) );
			$fieldset->addFilter( 'subscription_requirements', array( 'DefiniteValue' => @implode( ',', $storage['price_info']['subscription_requirements'] ) ) );
			$fieldset->addElement( array( 'name' => 'checkout_requirements', 'type' => 'Hidden', 'value' => '' ) );
			$fieldset->addFilter( 'checkout_requirements', array( 'DefiniteValue' => @implode( ',', $storage['price_info']['checkout_requirements'] ) ) );
			$fieldset->addElement( array( 'name' => 'subscription_label', 'type' => 'hidden', 'value' => null ) );
			$fieldset->addFilter( 'subscription_label', array( 'DefiniteValue' => @$storage['price_info']['subscription_label'] ) );
			$fieldset->addLegend( 'Add "' . $value['subscription_label'] . '" to shopping cart' );
		//	$form->addFieldset( $fieldset );
			
		}
		$form->addFieldset( $fieldset );
	//	var_export( $storage['price_info'] );
	//	var_export( $newPriceList[$priceIdValue] );

		if( ! empty( $storage['price_info']['subscription_requirements'] ) )
		{
			$form->submitValue = $form->submitValue ? 'Continue checkout' : $form->submitValue;
			
			self::setFormRequirements( $form, $storage['price_info']['subscription_requirements'] );
/* 			
			//	Regular form elements
			$form->setFormRequirements( $storage['price_info']['subscription_requirements'] );
			
			//	settle internal requirements
			$requirements = array();
			foreach( $storage['price_info']['subscription_requirements'] as $each )
			{
				if( self::$_requirementOptions[$each]['class'] )
				{
					$requirements[] = array(
												array( 'requirement_class' => self::$_requirementOptions[$each]['class'], 'requirement_legend' => self::$_requirementOptions[$each]['legend'], 'parameters' => self::$_requirementOptions[$each]['parameters'], 'requirement_goodnews' => str_ireplace( '@@@SUBSCRIPTION_LABEL@@@', $value['subscription_label'] , self::$_requirementOptions[$each]['goodnews'] ) ), 
											);
				}
			}
			$form->setFormRequirements( $requirements );
 *//* 			$requirements = $storage['price_info']['subscription_requirements'];
			foreach( $requirements as $each )
			{
		//		continue;
				$class = self::$_requirementOptions[$each]['class'];	
				
				if( ! Ayoola_Loader::loadClass( $class ) )
				{
					continue;
				//	throw new Ayoola_Object_Exception( 'INVALID CLASS: ' . $class );
				}
				$class = new $class( @self::$_requirementOptions[$each]['parameters'] );
				if( ! method_exists( $class, 'createForm' ) ){ continue; }
				$fieldsets = $class->getForm()->getFieldsets();
				foreach( $fieldsets as $fieldset )
				{
					$fieldset->appendElement = false;
					$fieldset->addLegend( self::$_requirementOptions[$each]['legend'] );
					$fieldset->addElement( array( 'type' => 'html', 'name' => 'e' ), array( 'html' => '<blockquote class=""><em class="">' . str_ireplace( '@@@SUBSCRIPTION_LABEL@@@', $value['subscription_label'] , self::$_requirementOptions[$each]['goodnews'] ) . '</em></blockquote>' ) );
					$form->addFieldset( $fieldset );
				}
			}
 */		
		}
		
		//	Begin to go through the subscription requirements
		
		
		$this->setForm( $form );
    }
	// END OF CLASS
}
