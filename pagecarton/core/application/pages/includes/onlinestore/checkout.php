<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /onlinestore/checkout
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: checkout.php	Tuesday 3rd of October 2017 05:41:29 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_155cae14dd1273b8d223c6024272ebe7 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Checkout</h1>
',
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
								
$_155cae14dd1273b8d223c6024272ebe7 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Subscription_Cart' ) )
							{
								
$_cb92ce6290f924aee8e8b9591a6b82bd = new Application_Subscription_Cart( array (
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
								
$_cb92ce6290f924aee8e8b9591a6b82bd = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_65f5254c3fefea31893d389849c45a14 = new Ayoola_Object_Embed( array (
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
								
$_65f5254c3fefea31893d389849c45a14 = null;

							}
							