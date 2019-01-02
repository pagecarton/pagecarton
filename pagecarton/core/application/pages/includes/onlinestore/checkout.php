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
* @version $Id: checkout.php	Monday 31st of December 2018 12:10:10 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_1474849a695e3f1946b95561c3106e2c = new Ayoola_Page_Editor_Text( array (
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
								
$_1474849a695e3f1946b95561c3106e2c = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Subscription_Cart' ) )
							{
								
$_c164d261d14590444b92b47667b1a615 = new Application_Subscription_Cart( array (
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
								
$_c164d261d14590444b92b47667b1a615 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_fb7ffbb31bcfd7cacb1cec474f5d7aa0 = new Ayoola_Object_Embed( array (
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
								
$_fb7ffbb31bcfd7cacb1cec474f5d7aa0 = null;

							}
							