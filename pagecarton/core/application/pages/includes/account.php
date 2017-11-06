<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /account
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: account.php	Monday 6th of November 2017 12:14:38 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_6c650c847aa57236a2734ea528061c46 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div>
<h1 class="pc-heading">My Account</h1>
</div>
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
								
$_6c650c847aa57236a2734ea528061c46 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_cc1be32cfc3bc5973ae54fa01dfd960e = new Ayoola_Menu( array (
  'option' => 'MyAccount',
  'template_name' => 'WhiteSidebarMenu',
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
								
$_cc1be32cfc3bc5973ae54fa01dfd960e = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_38efc17b7b68b8977498c63e7364c826 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<p><span style="font-size:12px;"><strong>First name</strong>: {{{firstname}}}<strong> </strong></span></p>

<p><span style="font-size:12px;"><strong>Last name</strong>: {{{lastname}}}</span></p>

<p><span style="font-size:12px;"><strong>Email</strong>: {{{email}}}<strong> </strong></span></p>

<p><span style="font-size:12px;"><strong>Password</strong>: ******</span></p>

<p style="text-align: center;"><a rel="spotlight" class="btn btn-default" href="/x/index.php/tools/classplayer/get/name/Application_User_Editor/username/{{{username}}}/personal_info/1/">Update Personal Info</a></p>
',
  'url_prefix' => '/x/index.php',
  'advanced_parameter_value' => 
  array (
    0 => 'Ayoola_Access_Dashboard',
  ),
  'wrapper_name' => 'white-background',
  'markup_template_object_name' => 'Ayoola_Access_Dashboard',
) );

							}
							else
							{
								
$_38efc17b7b68b8977498c63e7364c826 = null;

							}
							