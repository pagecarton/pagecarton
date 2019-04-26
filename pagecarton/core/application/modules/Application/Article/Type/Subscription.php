<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Subscription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Subscription.php 5.11.2012 12.02am ayoola $  
 */

/**
 * @see Application_Article_Type_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_Subscription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Subscription extends Application_Article_Type_Abstract
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Add item to cart';      

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
		if( ! $subscriptionData = $this->getParameter( 'data' ) )
		{
			if( $this->getGlobalValue( 'article_url' ) )
			{
				$_GET['article_url'] = $this->getGlobalValue( 'article_url' );
			}
			$subscriptionData = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
		//	var_export( $subscriptionData );
		}
	//	var_export( $subscriptionData );
		$this->createForm( $this->getParameter( 'button_value' ) ? : ( @$subscriptionData['call_to_action'] ? : 'Add to cart' ), '' );
		$form = $this->getForm() ? $this->getForm()->view() : null;
		$class = new Application_Subscription();   
		$confirmation = $class::getConfirmation();
	
		//	$values = $this->getForm()->getValues();
		//	var_export( $_POST );
		if( @$_REQUEST['add_to_cart'] )
		{
			$_GET['article_url'] = $subscriptionData['article_url'];
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
			//	var_export( $data );
			$values = $data;
			unset( $values['document_url_base64'], $values['download_base64'] ); 
			
			//	data
			$values['quantity'] = intval( @$_REQUEST['quantity'] ) ? : 1;	
			if( ! empty( $values['subscription_minimum_order'] ) && intval( $values['subscription_minimum_order'] ) > $values['quantity'] )
			{
				$values['quantity'] = $values['subscription_minimum_order'];
			}
			$this->_objectData['quantity'] = $values['quantity'];	
			
			//	Domain Reg
			$values['subscription_name'] = $data['article_url'];
			$values['subscription_label'] = $data['article_title'];
			if( @$_REQUEST['supplementary_subscription_selections'] )
			{
				$selections = array_map( 'trim', explode( ',', $_REQUEST['supplementary_subscription_selections'] ) );
				foreach( $selections as $eachSelection )
				{
					$values['subscription_label'] = ( $_REQUEST['subscription_selections' . $eachSelection] ? ( $_REQUEST['subscription_selections' . $eachSelection] . ' | ' ) : null ) . $values['subscription_label'];
					$values['subscription_selections' . $eachSelection] = $_REQUEST['subscription_selections' . $eachSelection];
				}
			}
			elseif( ! empty( $_REQUEST['subscription_selections'] ) )
			{
				$values['subscription_label'] = ( $_REQUEST['subscription_selections'] ? ( $_REQUEST['subscription_selections'] . ' | ' ) : null ) . $values['subscription_label'];
			}
			$data['item_price'] = str_replace( array( ',', ' ' ), '', $data['item_price'] );

			$values['price'] = $data['item_price'] + floatval( array_sum( $values['product_option'] ? : array() ) );
			$values['product_option'] = $values['product_option'];
			$values['cycle_name'] = 'each'; 
			$values['cycle_label'] = '';
			$values['price_id'] = $data['article_url'];
			$values['subscription_description'] = $data['article_description'];
			$values['url'] = Ayoola_Application::getUrlPrefix() . ( @$data['cart_url'] ? : $data['article_url'] );     
			@$values['checkout_requirements'] = $data['article_requirements']; //"billing_address";
			//	''
			//	After we checkout this is where we want to come to
			$values['classplayer_link'] = "javascript:;";
			$values['object_id'] = $data['article_url'];
			$values['multiple'] = $values['quantity'];
			$class->subscribe( $values );
			header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/cart' );
			exit();
		//	$this->_objectData['confirmation'] = $confirmation;	
		}
		elseif( $this->getForm()  AND ! $values = $this->getForm()->getValues() )
		{ 
			//	var_export( 123 );
			//	show form
			$this->_objectData['badnews'] = $this->getForm()->getBadnews();	
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();  
			$this->setViewContent( '<span class="badnews" name="' . $this->getObjectName() . '' . $subscriptionData['article_url'] . '_badnews" style="display:none;"></span>' );
			$this->setViewContent( $form );
			$this->setViewContent( '<span name="' . $this->getObjectName() . '' . $subscriptionData['article_url'] . '_confirmation" style="display:none;">' . $confirmation . '</span>' );
			//	var_export( $data );
			//	return false; 
		}
		elseif( $this->getForm()  AND  $values = $this->getForm()->getValues() )
		{
		//	var_export( $values );
		//	exit();
			$_GET['article_url'] = $values['article_url'];
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
			$data['item_price'] = str_replace( array( ',', ' ' ), '', $data['item_price'] );
			do
			{
		//			exit();		
				$added = false;	
				if( @$data['price_option_title'] )   
				{

		//		var_export( $values );
					foreach( $data['price_option_title'] as $key => $each )
					{

						$pricing = $data['price_option_price'][$key];  
						$pricing = str_replace( array( ',', ' ' ), '', $pricing );

						//	$data['item_price']
						if( empty( $data['price_option_price'][$key] ) && ! empty( $data['item_price'] ) )
						{
							$pricing = $data['item_price'];
						}
						if( empty( $values['price_option' . $each] ) )
						{
							continue;
						}
						$values += $data;
						unset( $values['document_url_base64'], $values['download_base64'] ); 
						
						$values['subscription_name'] = $data['article_url'] . 'price_option' . $each;
						$values['subscription_label'] = $data['price_option_title'][$key] . ' - ' . $data['article_title'];
						if( $values['supplementary_subscription_selections'] )
						{
							$selections = array_map( 'trim', explode( ',', $values['supplementary_subscription_selections'] ) );
							foreach( $selections as $eachSelection )
							{
								$values['subscription_label'] = ( $values['subscription_selections' . $eachSelection] ? ( $values['subscription_selections' . $eachSelection] . ' | ' ) : null ) . $values['subscription_label'];
							}
						}
						elseif( ! empty( $values['subscription_selections'] ) )
						{
							$values['subscription_label'] = ( $values['subscription_selections'] ? ( $values['subscription_selections'] . ' | ' ) : null ) . $values['subscription_label'];
						}
						
						
			//			$values['subscription_label'] = ( $values['subscription_selections'] ? ( $values['subscription_selections'] . ' | ' ) : null ) . $data['price_option_title'][$key] . ' - ' . $data['article_title'];
						$values['price'] = str_replace( array( ',', ' ' ), '', $pricing );
						$values['cycle_name'] = 'each';   
						$values['cycle_label'] = '';
						$values['price_id'] = $values['subscription_name'];
						$values['subscription_description'] = $data['price_option_title'][$key] . ' - ' . $data['article_description'];
						$values['url'] = Ayoola_Application::getUrlPrefix() . $data['article_url'];
						@$values['checkout_requirements'] = $data['article_requirements']; //"billing_address";
						//
						//	After we checkout this is where we want to come to
						$values['classplayer_link'] = "javascript:;";
						$values['object_id'] = $data['article_url'];
						$values['multiple'] = $values['price_option' . $each];
						$class->subscribe( $values );
						$added = true;	

					}
				}

	//			var_export( $added );
	//			exit();

				if( $added ) 
				{
					break;
				}
				
				//	var_export( $data );
				
				//	data
				$this->_objectData['quantity'] = $values['quantity'];	
				//	var_export( $data );
				//	add main item to cart
				switch( $data['item_price'] )
				{
					case false:
					case null:
					case '':
			//		case true:
						//	Do nothing.
						//	 had to go through this route to process for 0.00
				//		var_export( __LINE__ );
					break;
					default:
					$values += $data;
					unset( $values['document_url_base64'], $values['download_base64'] ); 
					$values['subscription_name'] = $data['article_url'];
					$values['subscription_label'] = $data['article_title'];
					if( $values['supplementary_subscription_selections'] )
					{
						$selections = array_map( 'trim', explode( ',', $values['supplementary_subscription_selections'] ) );
						foreach( $selections as $eachSelection )
						{
							$values['subscription_label'] = ( $values['subscription_selections' . $eachSelection] ? ( $values['subscription_selections' . $eachSelection] . ' | ' ) : null ) . $values['subscription_label'];
						}
					}
					elseif( ! empty( $values['subscription_selections'] ) )
					{
						$values['subscription_label'] = ( $values['subscription_selections'] ? ( $values['subscription_selections'] . ' | ' ) : null ) . $values['subscription_label'];
					}
					$data['item_price'] = str_replace( array( ',', ' ' ), '', $data['item_price'] );
					$values['price'] = $data['item_price'] + floatval( array_sum( @$values['product_option'] ? : array() ) );
					$values['product_option'] = @$values['product_option'];
					$values['cycle_name'] = 'each';
					$values['cycle_label'] = '';
					$values['price_id'] = $data['article_url'];
					$values['subscription_description'] = $data['article_description'];
					$values['url'] = Ayoola_Application::getUrlPrefix() . $data['article_url'];
					@$values['checkout_requirements'] = $data['article_requirements']; //"billing_address";
					//	''
					//	After we checkout this is where we want to come to
					$values['classplayer_link'] = "javascript:;";
					$values['object_id'] = $data['article_url'];
					$values['multiple'] = $values['quantity'];
					$class->subscribe( $values );
					$added = true;	

		//			var_export( $values );
		//			exit();
					break;
				}
			}
			while( false );
			
			$this->_objectData['confirmation'] = $confirmation;	
			if( ! $added ) 
			{
				//	Don't redirect unless item added to cart
				return false;
			}


			//	not supposed to show this but lets put it just in case the browser didnt redirect.  
			$this->setViewContent( '<span name="' . $this->getObjectName() . '' . $subscriptionData['article_url'] . '_confirmation" style="">' . $confirmation . '</span>' );
			header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/cart' );
			exit();
			//	self::saveArticle( $data );
			
		}

		//	Display Graph
	//	var_export( $data );
	//	var_export( $SubscriptionData );
    } 

    /**
     * Form to display Subscription
     * 
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		if( ! $subscriptionData = $this->getParameter( 'data' ) )
		{
			$_GET['article_url'] = $this->getGlobalValue( 'article_url' );
			$subscriptionData = $this->getParameter( 'data' ) ? : $this->getIdentifierData(); 
		//	var_export( $subscriptionData );
		}
	//	var_export( $subscriptionData );

		//	Build a new Add To Cart button
		$onSubmit = '
					ayoola.events.add( document.getElementById( "' . $this->getObjectName() . '' . $subscriptionData['article_url'] . '" ), "submit", addToCart );';
					
		$addToCartFunction = '
					var addToCart = function( e )
					{
						var form = ayoola.events.getTarget( e );
						addToCartNow( form );
						
						//	dont send form
						if( e.preventDefault ){ e.preventDefault(); }
						return false;
					}
					var addToCartNow = function( form )
					{
						var formValues = ayoola.div.getFormValues( { form: form, buttonValue: "Adding to cart" } );
					
						//	UPLOAD TO SERVER
						var uniqueNameForAjax = form.id;
						
						//	Sets Ajax but dont send yet
						ayoola.xmlHttp.fetchLink( { url: "' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/' . $this->getObjectName() . '/", id: uniqueNameForAjax, data: formValues, skipSend: true } );
					//	alert( arguments.length );
						var ajax = ayoola.xmlHttp.objects[uniqueNameForAjax];
						ajax.setRequestHeader( "AYOOLA-PLAY-MODE", "JSON" ); 
						var ajaxCallback = function()
						{
							//	alert( ajax );
							if( ayoola.xmlHttp.isReady( ajax ) )
							{
								if( ! ajax.responseText )
								{
									return false;
								}
							//	alert( ajax.responseText );
								
								try
								{
									var response = JSON.parse( ajax.responseText );
								}
								catch( e )
								{
									alert( e ); //error in the above string(in this case,yes)! 						
									alert( ajax.responseText );
									
									// An error has occured, handle it, by e.g. logging it
									console.log( e );
									response = {};
									response.quantity = 1;
								}
							//	var response = JSON.parse( ajax.responseText );
								if( response.quantity )
								{
									//	response.quantity (NUM) added to cart
									ayoola.div.getFormValues( { form: form, buttonValue: response.quantity + " item(s) added!" } );
									
									//	Show confirmation
									var a = document.getElementsByName( form.id + "_confirmation" );
							//		alert( a.length );
									for( var b = 0; b < a.length; b++ )
									{
										a[b].style.display = "inline-block";
									}
								}
								if( response.badnews )
								{
									//	response.quantity (NUM) added to cart
									ayoola.div.getFormValues( { form: form, buttonValue: "Add to cart", dontDisable: true, enableAll: true } );
									
									//	Show confirmation
									var a = document.getElementsByName( form.id + "_badnews" );
								//	alert( response.badnews );
									var c = "";
									for( var key in response.badnews )
									{
										//	alert( c );
										if( response.badnews[key] )
										{ 
											c = response.badnews[key];
											break; 
										}
									}
									for( var b = 0; b < a.length; b++ )
									{
										a[b].style.display = "inline-block";
										a[b].innerHTML = c;
									}
								}
								
							} 
						}
						ayoola.events.add( ajax, "readystatechange", ajaxCallback );
						
						//	Send ajax request
						ajax.send( formValues );
					}
					';
		
 	//	Application_Javascript::addCode( $addToCartFunction );  
 	//	Application_Javascript::addCode( $onSubmit );
		
		//	Add to cart
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'id' => $this->getObjectName() . $subscriptionData['article_url'] . self::$_counter , 'data-not-playable' => true, 'action' => '' . Ayoola_Application::getUrlPrefix() . '' . $subscriptionData['article_url'] ) ); 
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = true;
	//	$form->submitValue = $submitValue ;
	//	$fieldset->placeholderInPlaceOfLabel = true;
	//	$subscriptionData = $this->getParameter( 'data' );
	//	self::v( $subscriptionData );
	//	var_export( $subscriptionData['no_of_items_in_stock'] );
		$showQuantity = 'Hidden';
		$optionsForSelect = array( 0 );
		if( @intval( $subscriptionData['no_of_items_in_stock'] ) > 1 )
		{
			$showQuantity = 'InputText';
		}
		elseif( is_numeric( $this->getParameter( 'min_quantity' ) ) ||  is_numeric( $this->getParameter( 'max_quantity' ) ) )
		{
		//	var_export( $subscriptionData['item_min_quantity'] );
			$min = 1;
			if( is_numeric( $this->getParameter( 'min_quantity' ) ) )
			{
				$min = $this->getParameter( 'min_quantity' );
			}
			if( is_numeric( @$subscriptionData['item_min_quantity'] ) )
			{
				$min = $subscriptionData['item_min_quantity'];
			}
			$max = $min;
			if( is_numeric( $this->getParameter( 'max_quantity' ) ) )
			{
				$max = $this->getParameter( 'max_quantity' );
			}
			if( is_numeric( @$subscriptionData['item_max_quantity'] ) )
			{
				$max = $subscriptionData['item_max_quantity'];
			}
			if( is_numeric( $this->getParameter( 'quantity_step' ) ) )
			{
				$step = $this->getParameter( 'quantity_step' );
			}
			if( is_numeric( @$subscriptionData['item_quantity_step'] ) )
			{
				$step = $subscriptionData['item_quantity_step'];
			}
			if( ! @$step )
			{
				$step = 1;
				$diff = $max - $min;
				if( $diff > 100 )
				{
					$step = round( $diff / 100 );
				}
				
			}
			$showQuantity = 'Select';
			$range = range( $min, $max, $step );
			$optionsForSelect += array_combine( $range, $range );
		//	if( ! in_array( 0, $optionsForSelect ) )
			{
	//			$optionsForSelect[0] = 0;
			}
		} 
		//	internal forms to use
		if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $subscriptionData['article_type'] ) )
		{

		}
		$features = is_array( @$postTypeInfo['post_type_options'] ) ? $postTypeInfo['post_type_options'] : array();
		$featuresPrefix = is_array( @$postTypeInfo['post_type_options_name'] ) ? $postTypeInfo['post_type_options_name'] : array();
		$features[] = $subscriptionData['true_post_type'];
		$featuresPrefix[] = '';
		$featureCount = array();
		$selections = array();
		foreach( $features as $key => $eachPostType )
		{	
			$featurePrefix = $featuresPrefix[$key];
			if( empty( $featureCount[$eachPostType] ) )
			{
				$featureCount[$eachPostType] = 1;
			}
			else
			{
				if( empty( $featurePrefix ) )
				{
					$featurePrefix = $featureCount[$eachPostType];
				}
				$featureCount[$eachPostType]++;
			}
			switch( $eachPostType )
			{
				case 'subscription-options':
					if( @$subscriptionData['subscription_selections' . $featurePrefix] )  
					{
						$optionsMenu = array();
						foreach( $subscriptionData['subscription_selections' . $featurePrefix] as $eachOption )
						{
							if( empty( $eachOption ) )
							{
								continue;
							}
							$optionsMenu[$eachOption] = $eachOption;
						}
				//		self::v( $optionsMenu );
						if( $optionsMenu )
						{
							$fieldset->addElement( array( 'name' => 'subscription_selections' . $featurePrefix, 'label' => 'Options ' . $featurePrefix, 'type' => 'Select', 'value' => @$subscriptionData['subscription_selections' . $featurePrefix] ), $optionsMenu );
							$selections[] = '' . $featurePrefix;
						}
					}
				break; 
			} 
		}
		$fieldset->addElement( array( 'name' => 'supplementary_subscription_selections', 'type' => 'Hidden', 'value' => implode( ',', $selections ) ) );

	//	var_export( $showQuantity );
	//	var_export( $options );
		$filter = 'Ayoola_Filter_Currency';
		$filter = new $filter();
	//	var_export( $this->getParameter( 'multi-price' ) );
		if( ! $this->getParameter( 'multi-price' ) )
		{	
			//	skip empty prices
			if( '' === trim( @$subscriptionData['item_price'] ) )
			{
				return false;
			}
			$fieldset->addElement( array( 'name' => 'quantity', 'id' => 'quantity_' . md5( @$subscriptionData['article_url'] ), 'label' => 'Quantity', 'style' => 'min-width:20px;max-width:60px;display:inline;margin-right:0;', 'type' => $showQuantity, 'value' => @$values['quantity'] ? : 1 ), $optionsForSelect );  
			$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
		//	$data['currency'] = $filter::$symbol;
			if( @$subscriptionData['option_name'] )  
			{
				$optionsMenu = array();
				foreach( $subscriptionData['option_name'] as $key => $eachOption )
				{
					if( empty( $eachOption ) )
					{
						continue;
					}
					if( empty( $subscriptionData['option_price'][$key] ) || empty( $subscriptionData['option_price'][$key] ) )
					{
					//	continue;
					}
					$price = $subscriptionData['option_price'][$key] ? $filter->filter( $subscriptionData['option_price'][$key] ) : null;
					$optionsMenu[$subscriptionData['option_price'][$key]] = $subscriptionData['option_name'][$key] . ' (' . $price . ') ';
				}
		//		self::v( $optionsMenu );
				$optionsMenu ? $fieldset->addElement( array( 'name' => 'product_option', 'label' => 'Options', 'type' => 'Checkbox', 'value' => @$subscriptionData['product_option'] ), $optionsMenu ) : null;
			}
		} 
		//	find out if everything is the same price 
		elseif( ! empty( $subscriptionData['price_option_title'] )  )
		{

			$samePricing = false;
			if( @$subscriptionData['price_option_price'] )   
			{
		//		$samePricing = array_flip( $subscriptionData['price_option_price'] );
			//	var_export( array_flip( $subscriptionData['price_option_price'] ) );
				if( $samePricing = count( array_flip( $subscriptionData['price_option_price'] ) ) === 1 )
				{
			//		;
				}
			}
	//		$optionsForSelect = array();
	//		self::v( $subscriptionData['price_option_title'] );
			foreach( $subscriptionData['price_option_title'] as $key => $each )
			{
				if( empty( $subscriptionData['price_option_price'][$key] ) && empty( $subscriptionData['price_option_title'][$key] ) )
				{
					continue; 
				}
				if( empty( $samePricing ) )
				{ 
					$pricing = ' - ' . $filter->filter( $subscriptionData['price_option_price'][$key] );
				}
				$optionsForSelect = empty( $optionsForSelect ) ? array_combine( range( 0, 100 ), range( 0, 100 ) ) : $optionsForSelect;
		//	var_export( $optionsForSelect );
				$fieldset->addElement( array( 'name' => 'price_option' . $each, 'label' => $each . $pricing , 'type' => 'Select', 'value' => 'price_option' . $each ), $optionsForSelect );
			}
			if( ! $optionsForSelect )
			{
				return false;  
			}
		} 
		else
		{
		//	var_export( $subscriptionData );
			return false;
		}
		$fieldset->addElement( array( 'name' => 'article_url', 'type' => 'Hidden', 'value' => @$subscriptionData['article_url'] ) );
	//	$fieldset->addElement( array( 'name' => 'submit',  'type' => 'Button',  'onClick' => 'addToCartNow( this.form );', 'value' => $submitValue ) );
		 @$subscriptionData['no_of_items_in_stock'] ? $fieldset->addRequirement( 'quantity', array( 'Int' => null, 'MinMax' => array( 1, @$subscriptionData['no_of_items_in_stock'] ? : 100 ) ) ) : null;
		$fieldset->addRequirement( 'article_url', array( 'NotEmpty' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset ); 
		$form->submitValue = $submitValue; 		
		$this->setForm( $form );

    } 
	
	// END OF CLASS
}
