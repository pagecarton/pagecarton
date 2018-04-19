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
* @version $Id: account.php	Thursday 19th of April 2018 11:15:00 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_6c650c847aa57236a2734ea528061c46 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div>
<h1 class="pc-heading">My Account</h1>
</div>
',
  'url_prefix' => '/x',
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

<p style="text-align: center;"><a rel="spotlight" class="btn btn-default" href="/x/tools/classplayer/get/name/Application_User_Editor/username/{{{username}}}/personal_info/1/">Update Personal Info</a></p>
',
  'url_prefix' => '/x',
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
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_913c5af15de1d7d880c55109dd2188d9 = new Application_Article_ShowAll( array (
  'option' => '2',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
  ),
  'wrapper_name' => '',
  'add_a_new_post' => '1',
  'show_post_by_me' => '1',
) );

							}
							else
							{
								
$_913c5af15de1d7d880c55109dd2188d9 = null;

							}
							