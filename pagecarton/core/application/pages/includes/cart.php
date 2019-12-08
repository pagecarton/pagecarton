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
* @version $Id: cart.php	Thursday 5th of December 2019 08:23:34 AM	ayoola@ayoo.la $ 
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
  'insert_id' => '1566242672-0-18',
  'pagewidget_id' => '1575534209-0-4',
  '' => '',
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
  'insert_id' => '1566242672-0-19',
  'pagewidget_id' => '1575534209-0-5',
  'multiple_label' => '',
  '' => '',
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
  'insert_id' => '1566242672-0-20',
  'pagewidget_id' => '1575534209-0-6',
  '' => '',
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
  'insert_id' => '1566242672-0-21',
  'pagewidget_id' => '1575534209-0-7',
  '' => '',
) );

							}
							else
							{
								
$_7ed276139802b774216170fc44b3858f = null;

							}
							