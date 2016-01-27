<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /onlinestore/cart
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: cart.php	Wednesday 20th of January 2016 11:12:19 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar0pageEditText = new Ayoola_Page_Editor_Text( array (
  'view' => 'Insert HTML Text',
  'editable' => '<h1>Shopping Cart</h1>
',
  'call_to_action' => '',
) );

							}
							else
							{
								
$objay__middlebar0pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Subscription_Cart' ) )
							{
								
$objay__middlebar1subscriptionCart = new Application_Subscription_Cart( array (
  'view' => 'Display "Shopping" Cart',
  'call_to_action' => '',
) );

							}
							else
							{
								
$objay__middlebar1subscriptionCart = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar2pageEditText = new Ayoola_Page_Editor_Text( array (
  'view' => 'Insert HTML Text',
  'editable' => '<h2>Checkout</h2>
',
  'call_to_action' => '',
) );

							}
							else
							{
								
$objay__middlebar2pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar3objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'call_to_action' => '',
  'editable' => 'Application_Subscription_Checkout',
) );

							}
							else
							{
								
$objay__middlebar3objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar0menuView = new Ayoola_Menu( array (
  'view' => 'Display a Menu',
  'option' => 'onlinestore',
  'call_to_action' => '',
) );

							}
							else
							{
								
$objay__leftbar0menuView = null;

							}
							