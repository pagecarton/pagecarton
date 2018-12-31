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
* @version $Id: signin.php	Monday 31st of December 2018 11:18:48 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Access_Login' ) )
							{
								
$_e2590564ad28e0d0e33673544c3cc1d8 = new Ayoola_Access_Login( array (
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
								
$_e2590564ad28e0d0e33673544c3cc1d8 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_857c0c44af7d4df602b5990ce7629e67 = new Ayoola_Page_Editor_Text( array (
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
								
$_857c0c44af7d4df602b5990ce7629e67 = null;

							}
							