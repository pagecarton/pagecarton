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
* @version $Id: signin.php	Wednesday 3rd of January 2018 08:10:40 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Access_Login' ) )
							{
								
$_fc40490cbf01d653fbced4e69f73c901 = new Ayoola_Access_Login( array (
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
								
$_fc40490cbf01d653fbced4e69f73c901 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_f046f4eadd53dc1db619bc502ee2c5e6 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<ul>
	<li><a href="/x/tools/classplayer/get/name/Application_User_Help_ForgotUsernameOrPassword" rel="spotlight;">Forgot password</a>&nbsp;</li>
	<li>
<a onclick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/name/Application_User_Creator\', \'page_refresh\' );" href="javascript:">Create a new account</a>
</li>
</ul>

',
  'url_prefix' => '/x',
  'markup_template_object_name' => '',
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
								
$_f046f4eadd53dc1db619bc502ee2c5e6 = null;

							}
							