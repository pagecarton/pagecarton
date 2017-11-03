<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /account/signin
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: signin.php	Thursday 2nd of November 2017 11:06:23 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Access_Login' ) )
							{
								
$_4d09cda9ff8254e1ecf6aacb1825ec3f = new Ayoola_Access_Login( array (
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
								
$_4d09cda9ff8254e1ecf6aacb1825ec3f = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_e5272b9b837757b5a9e3cf808ba2dccb = new Ayoola_Page_Editor_Text( array (
  'editable' => 'Forgot Password? <a rel="spotlight;" href="/x/index.php/object/name/Application_User_Help_ForgotUsernameOrPassword">Sign in Help</a>!',
  'url_prefix' => '/x/index.php',
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
								
$_e5272b9b837757b5a9e3cf808ba2dccb = null;

							}
							