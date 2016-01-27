<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Subscription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Subscription extends Application_Article_Type_Abstract
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
		if( ! $subscriptionData = $this->getParameter( 'data' ) )
		{
			if( $this->getGlobalValue( 'article_url' ) )
			{
				$_GET['article_url'] = $this->getGlobalValue( 'article_url' );
			}
			$subscriptionData = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
		//	var_export( $subscriptionData );
		}
		$this->createForm( $this->getParameter( 'button_value' ) ? : ( @$subscriptionData['call_to_action'] ? : 'Add to cart' ), '' );
		$form = $this->getForm()->view();
		$class = new Application_Subscription();
		$confirmation = $class::getConfirmation();
	
		//	$values = $this->getForm()->getValues();
		//	var_export( $_POST );
		if( @$_REQUEST['add_to_cart'] )
		{
			$values = array();
			$_GET['article_url'] = $subscriptionData['article_url'];
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
			//	var_export( $data );
			
			//	data
			$this->_objectData['quantity'] = $values['quantity'] = 1;	
			
			//	Domain Reg
			$values['subscription_name'] = $data['article_url'];
			$values['subscription_label'] = $data['article_title'];
			$values['price'] = $data['item_price'] + floatval( array_sum( $values['product_option'] ? : array() ) );
			$values['product_option'] = $values['product_option'];
			$values['cycle_name'] = 'each'; 
			$values['cycle_label'] = '';
			$values['price_id'] = $data['article_url'];
			$values['subscription_description'] = $data['article_description'];
			$values['url'] = $data['article_url'];
			@$values['checkout_requirements'] = $data['article_requirements']; //"billing_address";
			//	''
			//	After we checkout this is where we want to come to
			$values['classplayer_link'] = "javascript:;";
			$values['object_id'] = $data['article_url'];
			$values['multiple'] = $values['quantity'];
			$class->subscribe( $values );
			header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/onlinestore/cart' );
			exit();
		//	$this->_objectData['confirmation'] = $confirmation;	
		}
		elseif( ! $values = $this->getForm()->getValues() )
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
		else
		{
			$_GET['article_url'] = $values['article_url'];
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
			//	var_export( $data );
			
			//	data
			$this->_objectData['quantity'] = $values['quantity'];	
			
			//	Domain Reg
			$values['subscription_name'] = $data['article_url'];
			$values['subscription_label'] = $data['article_title'];
			$values['price'] = $data['item_price'] + floatval( array_sum( $values['product_option'] ? : array() ) );
			$values['product_option'] = $values['product_option'];
			$values['cycle_name'] = 'each';
			$values['cycle_label'] = '';
			$values['price_id'] = $data['article_url'];
			$values['subscription_description'] = $data['article_description'];
			$values['url'] = $data['article_url'];
			@$values['checkout_requirements'] = $data['article_requirements']; //"billing_address";
			//	''
			//	After we checkout this is where we want to come to
			$values['classplayer_link'] = "javascript:;";
			$values['object_id'] = $data['article_url'];
			$values['multiple'] = $values['quantity'];
			$class->subscribe( $values );
			$this->_objectData['confirmation'] = $confirmation;	
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
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		if( ! $subscriptionData = $this->getParameter( 'data' ) )
		{
			$_GET['article_url'] = $this->getGlobalValue( 'article_url' );
			$subscriptionData = $this->getParameter( 'data' ) ? : $this->getIdentifierData(); 
		//	var_export( $subscriptionData );
		}
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
						ayoola.xmlHttp.fetchLink( { url: "/tools/classplayer/get/object_name/' . $this->getObjectName() . '/", id: uniqueNameForAjax, data: formValues, skipSend: true } );
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
								//	alert( ajax.responseText );
									
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
		
 		Application_Javascript::addCode( $addToCartFunction );
 		Application_Javascript::addCode( $onSubmit );
		
		//	Add to cart
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'id' => $this->getObjectName() . $subscriptionData['article_url'], 'data-not-playable' => true, 'action' => $subscriptionData['article_url'] ) ); 
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = true;
	//	$form->submitValue = $submitValue ;
	//	$fieldset->placeholderInPlaceOfLabel = true;
	//	$subscriptionData = $this->getParameter( 'data' );
	//	self::v( $subscriptionData );
	//	var_export( $subscriptionData['no_of_items_in_stock'] );
		
		$fieldset->addElement( array( 'name' => 'quantity', 'id' => 'quantity_' . md5( @$subscriptionData['article_url'] ), 'label' => 'Quantity', 'style' => 'min-width:20px;max-width:50px;display:inline;margin-right:0;', 'type' => @$subscriptionData['no_of_items_in_stock'] < 2 ? 'Hidden'  : 'InputText', 'value' => @$values['quantity'] ? : 1 ) );
		if( @$subscriptionData['option_name'] )  
		{
			$optionsMenu = array();
			$filter = 'Ayoola_Filter_Currency';
			$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
		//	$data['currency'] = $filter::$symbol;
			$filter = new $filter();
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
	//	if( @$subscriptionData['subscription_selections'] )   
		{
		//	var_export( $subscriptionData );
//			$fieldset->addElement( array( 'name' => 'subscription_selections', 'label' => 'Options', 'type' => 'Select', 'value' => @$subscriptionData['subscription_selections'] ), @array_combine( $subscriptionData['subscription_selections'], $subscriptionData['subscription_selections'] ) );
		} 
		$fieldset->addElement( array( 'name' => 'article_url', 'type' => 'Hidden', 'value' => @$subscriptionData['article_url'] ) );
		$fieldset->addElement( array( 'name' => 'submit',  'type' => 'Button',  'onClick' => 'addToCartNow( this.form );', 'value' => $submitValue ) );
		$fieldset->addRequirement( 'quantity', array( 'Int' => null, 'MinMax' => array( 1, @$subscriptionData['no_of_items_in_stock'] ? : 100 ) ) );
	//	$fieldset->addRequirement( 'quantity', array( 'Int' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset ); 
		$this->setForm( $form );

    } 
	
	// END OF CLASS
}
