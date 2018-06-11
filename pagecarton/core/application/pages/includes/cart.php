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
* @version $Id: cart.php	Thursday 19th of April 2018 11:14:55 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_79c3488bb05d610f1aa339e89e0b4d48 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Shopping Cart</h1>
',
  'url_prefix' => '/x/index.php',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => '',
  '' => '',
) );

							}
							else
							{
								
$_79c3488bb05d610f1aa339e89e0b4d48 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Subscription_Cart' ) )
							{
								
$_67f23ec1035d39330527f78129cbd747 = new Application_Subscription_Cart( array (
  'advanced_parameter_value' => 
  array (
    0 => '',
    1 => '',
  ),
  'wrapper_name' => '',
  'multiple_label' => '',
  '' => '',
) );

							}
							else
							{
								
$_67f23ec1035d39330527f78129cbd747 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_992ecbde43a3e58a043698dbefea1d52 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h2>Checkout</h2>
',
  'url_prefix' => '/x/index.php',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => '',
  '' => '',
) );

							}
							else
							{
								
$_992ecbde43a3e58a043698dbefea1d52 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_11dfdc4f269612637101b65b72651c99 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Subscription_Checkout',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => '',
  '' => '',
) );

							}
							else
							{
								
$_11dfdc4f269612637101b65b72651c99 = null;

							}
							