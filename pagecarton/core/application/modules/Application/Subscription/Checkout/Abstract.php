<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Checkout_Abstract extends Application_Subscription_Checkout implements Application_Subscription_Checkout_Interface
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_CheckoutOption';
	
    /**
     * Namespace for withdrawal. Useful for storage
     *
     * @var string
     */
	public static $withdrawalNamespace = __CLASS__;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'checkoutoption_id' );
	
    /**
     * Plays the API that is selected
     * 
     */
	public static function getWithdrawalApi( $className )
    {
		$className = $className . '_Withdraw';
		require_once 'Ayoola/Loader.php';
		if( ! Ayoola_Loader::loadClass( $className ) )
		{ 
			throw new Application_Subscription_Checkout_Exception( 'WITHDRAWAL IS NOT YET ENABLED ON YOUR ACCOUNT WITH THE CHOSEN PAYMENT METHOD. PLEASE CHOOSE ANOTHER METHOD. PLEASE CHOOSE ANOTHER METHOD OR CONTACT CUSTOMER SERVICE.' ); 
		}
		return $className;
    } 

    /**
     * Plays the API that is selected
     * 
     */
	public static function changeStatus( $response )
    {
		//	var_export( $response );
		$table = Application_Subscription_Checkout_Order::getInstance();
		if( ! $orderInfo = $table->selectOne( null, array( 'order_id' => $response['order_id'] ) ) )
		{ 
		//	echo 'INVALID ORDER';
			return false; 
		}
	//	if( $orderInfo['order_api'] != $identifier['api'] )		
		{ 
	//		echo 'INVALID API';
	//		return false; 
		}

		$stages = Application_Subscription_Checkout::$checkoutStages;
//		var_export( $stages );
//		var_export( $orderInfo );
//		var_export( $response );
		if( $orderInfo['order_status'] == $response['order_status'] )
		{ 
		//	echo 'DUPLICATE NOTIFICATION';
			return false; 
		}
//	var_export( $response );
		//	Treat the callback methods
		if( ! is_array( $orderInfo['order'] ) )
		{
			//	compatibility
			$orderInfo['order'] = unserialize( $orderInfo['order'] );			
		}
		$values = $orderInfo['order'];
	//	var_export( $values );
//		$orderInfo['total'] 
		foreach( $values['cart'] as $each )
		{ 

/*			//	calc total price
			if( ! isset( $each['price'] ) )
			{
				$each = array_merge( self::getPriceInfo( $each['price_id'] ), $each );
			}  
			$orderInfo['total'] += $each['price'] * $each['multiple'];
*/

			//	call backs
			if( ! isset( $each['callback'] ) ){ continue; }
			$each['order_status'] = $response['order_status'];
			$each['transactionmethod'] =  $orderInfo['order_api'];
			$each['currency_abbreviation'] = $values['settings']['currency_abbreviation'];
			$callback = array_map( 'trim', explode( ',', $each['callback'] ) );
			foreach( $callback as $eachCallback )
			{
				//	Let's treat callbacks'
				if( ! $eachCallback ){ continue; }
				if( ! Ayoola_Loader::loadClass( $eachCallback ) )
				{ 
					continue;
				//	throw new Application_Subscription_Exception( 'INVALID CALLBACK - ' . $eachCallback );
				}
				$eachCallback::callback( $each ); 
/* 				$mailInfo['subject'] = 'Call back done';
				$mailInfo['body'] = '"' . var_export( $eachCallback, true ) . '"';
			//	var_export( $newCart );
				@Ayoola_Application_Notification::mail( $mailInfo );
 */			}
			
		}
		$update = array( 'order_random_code' => $response['order_random_code'], 'order_status' => $response['order_status'] );
		$update = array_merge( $orderInfo, $update);  
		$table->update( $update, array( 'order_id' => $response['order_id'] )  );
		//	Notify Admin
		$mailInfo = array();
		$mailInfo['subject'] = 'Order Status Change';
		$mailInfo['body'] = '<html><body>' . self::arrayToString( $orderInfo ) . '</body></html>';
		$mailInfo['html'] = true;
	//	var_export( $newCart );
		@Ayoola_Application_Notification::mail( $mailInfo );
		
		return;
    } 
	
    /**
     * creates the form for creating and editing cycles
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
	//	var_export( $values );	  
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
        $form->submitValue = 'Continue';   
		$fieldset = new Ayoola_Form_Element;
		  
		//	We don't allow editing UNIQUE Keys
		if( is_null( $values ) )
		{		
			$fieldset->addElement( array( 'name' => 'checkoutoption_name', 'description' => 'Give this chekout option a name', 'type' => 'InputText', 'value' => @$values['checkoutoption_name'] ) );
		}
		Application_Javascript::addFile( '/js/objects/ckeditor/ckeditor.js?x=1' );
		Application_Javascript::addCode 
		(  
			'ayoola.xmlHttp.setAfterStateChangeCallback
			( 
				function()
				{ 
					try
					{
						//	destroy all instances of ckeditor everytime state changes.
						for( name in CKEDITOR.instances )
						{
							CKEDITOR.instances[name].destroy();
						}
					}
					catch( e )
					{
					
					}
				}
			)' 
		);
	//	foreach( $htmlFields as $each )
		{
 			Application_Javascript::addCode
			( 
				'ayoola.events.add
				( 
					window, "load", 
					function()
					{ 
						ayoola.xmlHttp.callAfterStateChangeCallbacks();
					} 
				);' 
			
			);
 			Application_Javascript::addCode 
			(  
				'ayoola.xmlHttp.setAfterStateChangeCallback
				( 
					function()
					{ 
						//	Retrieve all the stylesheets in the doc and attach them to the editor
						var a = document.getElementsByTagName( "link" );
						var d = new Array();
						for( var b = 0; b < a.length; b++ )
						{
							if( ! a[b].href.search( /css/ ) || a[b].href.search( /css/ ) == -1 ) 
							{ 
								continue; 
							}
							
							d.push( a[b].href );
						}
				//		var a = document.getElementsByName( "" );
						var a = document.getElementsByTagName( "textarea" );
					//	alert( a.length );
						var initCKEditor = function( target )
						{
							CKEDITOR.plugins.addExternal( "uploadimage", "' . Ayoola_Application::getUrlPrefix() . '/js/objects/ckeditor/plugins/uploadimage/plugin.js", "" );
							CKEDITOR.plugins.addExternal( "confighelper", "' . Ayoola_Application::getUrlPrefix() . '/js/objects/ckeditor/plugins/confighelper/plugin.js", "" );
							CKEDITOR.config.extraPlugins = "confighelper,uploadimage,autogrow,tableresize";
							CKEDITOR.config.removePlugins = "maximize,resize,elementspath";
							CKEDITOR.config.allowedContent  = true;
							CKEDITOR.config.filebrowserUploadUrl = "' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Ajax/?";  
							CKEDITOR.replace
							( 
								target,
								{
									height: 50,
									toolbar : 
									[
										{ name: "insert", items: [ "Image", "Table", "SpecialChar" ] },
										{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
										{ name: "paragraph", groups: [ "list", "indent", "blocks", "align" ], items: [ "NumberedList", "BulletedList", "-", "Blockquote", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-" ] },
										{ name: "links", items: [ "Link", "Unlink" ] },
										{ name: "styles", items: [ "Format", "Font", "FontSize" ] },
										{ name: "colors", items: [ "TextColor", "BGColor" ] },
										{ name: "tools", items: [ "Maximize" ] }
									],
									autoGrow_minHeight : 50,
									autoGrow_maxHeight : 400,
								}
							);
						}
						var f = function( e )
						{
						//	alert( e ); 
							try
							{
								try
								{
									//	destroy all instances of ckeditor everytime state changes.
									for( name in CKEDITOR.instances )
									{
										CKEDITOR.instances[name].destroy();
									}
								}
								catch( e )
								{
								
								}
								var target = ayoola.events.getTarget( e );
					//			alert( target ); 
								initCKEditor( target );
							}
							catch( e )
							{
								//	throws exception if article content is not available
							}
						}
						for( var b = 0; b < a.length; b++ )
						{
						//	alert( a[b].name );
							switch( a[b].name  )
							{
								case "checkoutoption_logo":
								case "' . Ayoola_Form::hashElementName( 'checkoutoption_logo' ) . '":
									initCKEditor( a[b] );
								break;
								default:
								//	alert( a[b] ); 
									if( ! a[b].getAttribute( "data-html" ) )
									{
										break;
									}
								//	ayoola.events.add( a[b], "click", f );
									ayoola.events.add( a[b], "dblclick", f );
								break;
							}
						}
					}
				)' 
			);
		}		
		$fieldset->addElement( array( 'name' => 'checkoutoption_logo', 'label' => 'Acceptance Logo', 'description' => 'HTML for checkout option acceptance logo', 'type' => 'TextArea', 'value' => @$values['checkoutoption_logo'] ) );
		$type = @$_REQUEST['checkout_type'] ? : @$values['checkout_type'];
		switch( $type )
		{			
			case 'http_post':
			//	$fieldset->addElement( array( 'name' => 'object_name', 'description' => 'Which object will play this checkout option', 'type' => 'InputText', 'value' => 'Application_Subscription_Checkout_HttpPost' ) );
			break;               
			default:
				$fieldset->addElement( array( 'name' => 'object_name', 'description' => 'Which object will play this checkout option', 'type' => 'InputText', 'value' => @$values['object_name'] ) );
			break;
		}
//		$fieldset->addRequirement( 'object_name', array( 'InArray' => array_keys( $list )  ) );

		$options =  array( 'http_form' => '	HTTP POST', 'php' => 'PHP Class' );
		$fieldset->addElement( array( 'name' => 'checkout_type', 'label' => 'Checkout Option Type', 'type' => 'Hidden', 'value' => $type ), $options );
		
		
		
	//	$fieldset->addRequirements( array( 'NotEmpty' => null ,'WordCount' => array( 6,1000 ) ) );
		if( is_null( $values ) )
		{		
			$fieldset->addRequirement( 'checkoutoption_name', array( 'WordCount' => array( 3,100 )  ) );
		}
	//	$fieldset->addFilters( 'enabled', array( 'HtmlSpecialCharsDecode' => null  ) );
		$fieldset->addFilters( array( 'Trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		
		switch( $type )
		{			
			case 'http_post':
				$fieldset = new Ayoola_Form_Element;
				$fieldset->addElement( array( 'name' => 'ccc', 'type' => 'Html', 'value' => '' ), array( 'html' => ( '' ) ) );
				$fieldset->addLegend( 'Customize fields for default parameters' );
				$form->addFieldset( $fieldset );
				
				$i = 0;
				do 
				{
					$fieldset = new Ayoola_Form_Element;
					$parameters = array_keys( Application_Subscription_Checkout_Abstract_HtmlForm::getDefaultParameters() );
					$parameters = array( '' => 'Please select...' ) + array_combine( $parameters, $parameters );				
					$fieldset->addElement( array( 'name' => 'default_parameter_fields', 'label' => '  ', 'style' => 'max-width:350px', 'type' => 'Select', 'multiple' => 'multiple', 'value' => @$values['default_parameter_fields'][$i] ), $parameters );
					$fieldset->addElement( array( 'name' => 'custom_parameter_fields', 'label' => ' ', 'style' => 'max-width:300px', 'placeholder' => 'Custom Parameter Field', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['custom_parameter_fields'][$i] ) );
					$fieldset->allowDuplication = true;  
					$fieldset->placeholderInPlaceOfLabel = true;
					$fieldset->container = 'div';
		//			$fieldset->addLegend( 'Customize fields for default parameters' );
					$form->addFieldset( $fieldset );
					$i++;
				}
				while( @$values['custom_parameter_fields'][$i] );
				
				
				$fieldset = new Ayoola_Form_Element;
				$fieldset->addElement( array( 'name' => 'ccc', 'type' => 'Html', 'value' => '' ), array( 'html' => ( '' ) ) );
				$fieldset->addLegend( 'Custom form attribute name/value pairs' );
				$form->addFieldset( $fieldset );
				
				$i = 0;
				do 
				{
					$fieldset = new Ayoola_Form_Element;
					$fieldset->addElement( array( 'name' => 'form_attribute_name', 'label' => '  ', 'style' => 'max-width:300px', 'placeholder' => 'Form Attribute Name', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['form_attribute_name'][$i] ) );
					$fieldset->addElement( array( 'name' => 'form_attribute_value', 'label' => ' ', 'style' => 'max-width:300px', 'placeholder' => 'Form Attribute Value', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['form_attribute_value'][$i] ) );
					$fieldset->allowDuplication = true;  
					$fieldset->placeholderInPlaceOfLabel = true;
					$fieldset->container = 'div';
					$form->addFieldset( $fieldset );
					$i++;
				}
				while( @$values['form_attribute_name'][$i] );
				
				
				$fieldset = new Ayoola_Form_Element;
				$fieldset->addElement( array( 'name' => 'ccc', 'type' => 'Html', 'value' => '' ), array( 'html' => ( '' ) ) );
				$fieldset->addLegend( 'Custom form field name/value pairs' );
				$form->addFieldset( $fieldset );
				$i = 0;
				do 
				{
					$fieldset = new Ayoola_Form_Element;
					$fieldset->addElement( array( 'name' => 'default_form_field_name', 'label' => '  ', 'style' => 'max-width:300px', 'placeholder' => 'Default form field name', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['default_form_field_name'][$i] ) );
					$fieldset->addElement( array( 'name' => 'default_form_field_value', 'label' => ' ', 'style' => 'max-width:300px', 'placeholder' => 'Default form field value', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['default_form_field_value'][$i] ) );
					$fieldset->allowDuplication = true;  
					$fieldset->placeholderInPlaceOfLabel = true;
					$fieldset->container = 'div';
					$form->addFieldset( $fieldset );
					$i++;
				}
				while( @$values['default_form_field_name'][$i] );
			break;
			default:
			break;
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
