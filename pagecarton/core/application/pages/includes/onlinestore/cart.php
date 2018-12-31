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
* @version $Id: cart.php	Monday 31st of December 2018 11:18:51 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_6a7f8980fc8d00a1ef6aec28c75efe04 = new Ayoola_Page_Editor_Text( array (
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
								
$_6a7f8980fc8d00a1ef6aec28c75efe04 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Subscription_Cart' ) )
							{
								
$_7a988dae6b7ce7e9d4537a3cbcc83e32 = new Application_Subscription_Cart( array (
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
								
$_7a988dae6b7ce7e9d4537a3cbcc83e32 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_87044070d6e8170c2de22660a33f80d5 = new Ayoola_Page_Editor_Text( array (
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
								
$_87044070d6e8170c2de22660a33f80d5 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_7f8486c96b4eb6fb3753bd68526780cc = new Ayoola_Object_Embed( array (
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
								
$_7f8486c96b4eb6fb3753bd68526780cc = null;

							}
							