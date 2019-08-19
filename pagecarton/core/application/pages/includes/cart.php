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
* @version $Id: cart.php	Monday 19th of August 2019 07:24:32 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_72695e711f8fb6fd27ff426f3d8c5bbd = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Shopping Cart</h1>
',
  'url_prefix' => '/x/index.php',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => '',
  'insert_id' => '1564094536-0-23',
  'pagewidget_id' => '1564094536-0-23',
  '' => '',
  'widget_name' => 'Shopping Cart',
) );

							}
							else
							{
								
$_72695e711f8fb6fd27ff426f3d8c5bbd = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Subscription_Cart' ) )
							{
								
$_935dd3d6e0cc85fee8b541146ed7fa22 = new Application_Subscription_Cart( array (
  'advanced_parameter_value' => 
  array (
    0 => '',
    1 => '',
  ),
  'wrapper_name' => '',
  'insert_id' => '1564094536-0-24',
  'pagewidget_id' => '1564094536-0-24',
  'multiple_label' => '',
  '' => '',
  'widget_name' => '- - 1564094536-0-24 - 1564094536-0-24 - -',
) );

							}
							else
							{
								
$_935dd3d6e0cc85fee8b541146ed7fa22 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_eeb501f955d5c45e95ed4e97f4c7d442 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h2>Checkout</h2>
',
  'url_prefix' => '/x/index.php',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => '',
  'insert_id' => '1564094536-0-25',
  'pagewidget_id' => '1564094536-0-25',
  '' => '',
  'widget_name' => 'Checkout',
) );

							}
							else
							{
								
$_eeb501f955d5c45e95ed4e97f4c7d442 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_7ed276139802b774216170fc44b3858f = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Subscription_Checkout',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => '',
  'insert_id' => '1564094536-0-26',
  'pagewidget_id' => '1564094536-0-26',
  '' => '',
  'widget_name' => 'Application_Subscription_Checkout',
) );

							}
							else
							{
								
$_7ed276139802b774216170fc44b3858f = null;

							}
							