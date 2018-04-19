<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /accounts/signin
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: signin.php	Thursday 19th of April 2018 11:14:59 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Access_Login' ) )
							{
								
$_cbafede3be300be6957a7a5f6dafce63 = new Ayoola_Access_Login( array (
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'white-background',
  '' => '',
) );

							}
							else
							{
								
$_cbafede3be300be6957a7a5f6dafce63 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_ddc2c15c7f82aabf8c025940645ff7f2 = new Ayoola_Page_Editor_Text( array (
  'editable' => 'Forgot Password? <a rel="spotlight;" href="/x/index.php/object/name/Application_User_Help_ForgotUsernameOrPassword">Sign in Help</a>!',
  'url_prefix' => '/x/index.php',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'well',
  '' => '',
) );

							}
							else
							{
								
$_ddc2c15c7f82aabf8c025940645ff7f2 = null;

							}
							