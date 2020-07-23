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
* @version $Id: cart.php	Saturday 14th of December 2019 09:18:34 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_f80a10b51087fc6feb948b6ff548fdd9 = new Ayoola_Page_Editor_Text( array (
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
								
$_f80a10b51087fc6feb948b6ff548fdd9 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Subscription_Cart' ) )
							{
								
$_09b24ebc8e2e2fcd26f9cdae5eba313c = new Application_Subscription_Cart( array (
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
								
$_09b24ebc8e2e2fcd26f9cdae5eba313c = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_1aac9a93ab20446fbf67f4b97f105eeb = new Ayoola_Page_Editor_Text( array (
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
								
$_1aac9a93ab20446fbf67f4b97f105eeb = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_b44a4f9bffd776c901a78926b23fa26f = new Ayoola_Object_Embed( array (
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
								
$_b44a4f9bffd776c901a78926b23fa26f = null;

							}
							