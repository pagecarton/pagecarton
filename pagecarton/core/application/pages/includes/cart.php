<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /cart
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: cart.php	Monday 31st of December 2018 12:09:07 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_59111ea7195911936bf0bdd8a73051d6 = new Ayoola_Page_Editor_Text( array (
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
								
$_59111ea7195911936bf0bdd8a73051d6 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Subscription_Cart' ) )
							{
								
$_8b263a1ada2a66c5cf1b0079528c6a9d = new Application_Subscription_Cart( array (
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
								
$_8b263a1ada2a66c5cf1b0079528c6a9d = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_0121ec4a8ce6f86d18dc4627e190445e = new Ayoola_Page_Editor_Text( array (
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
								
$_0121ec4a8ce6f86d18dc4627e190445e = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_82085fae9d5bae4a67aadf217eaf65fb = new Ayoola_Object_Embed( array (
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
								
$_82085fae9d5bae4a67aadf217eaf65fb = null;

							}
							