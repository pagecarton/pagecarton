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
* @version $Id: account.php	Sunday 6th of January 2019 11:05:25 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_abfe4d78405111b0fb3f1897a066a533 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>

<h1>Account Dashboard</h1>

<br>',
  'preserved_content' => '<br>

<h1>Account Dashboard</h1>

<br>',
  'url_prefix' => '/x',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_abfe4d78405111b0fb3f1897a066a533 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_cb54a96507f5ce6fa4a027c5e36227c9 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div style="">Email: {{{email}}}</div>

<div style="">Username: {{{username}}}</div>


',
  'preserved_content' => '<div style="">Email: {{{email}}}</div>

<div style="">Username: {{{username}}}</div>


',
  'url_prefix' => '/x',
  'markup_template_object_name' => 
  array (
    0 => 'Ayoola_Access_Dashboard',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'dark',
  '' => '',
) );

							}
							else
							{
								
$_cb54a96507f5ce6fa4a027c5e36227c9 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_2187ef0587b10f3624dd768499920d24 = new Ayoola_Menu( array (
  'option' => 'MyAccount',
  'template_name' => 'WhiteSidebarMenu',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_2187ef0587b10f3624dd768499920d24 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_ea35c1c2cdc5584d84cace9de47b1f1a = new Ayoola_Page_Editor_Text( array (
  'editable' => '
			<div style="">
			<h3>My Posts<br></h3>
			
			</div>
			',
  'preserved_content' => '			<div style="">
			<h3>My Posts<br></h3>
			
			</div>
			',
  'url_prefix' => '/x',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'dark',
  '' => '',
) );

							}
							else
							{
								
$_ea35c1c2cdc5584d84cace9de47b1f1a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_3d635a79040269634bf9aece4519b644 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>
<div style="">
<a href="{{{post_link}}}" style="text-transform:uppercase;">{{{article_title}}}</a>
<br>
<a class="" href="/x/object/name/Application_Article_Editor?article_url={{{article_url}}}" target="_blank"> <span style="font-size:11px;">Edit Post</span> </a>  - 
<a class="" href="/x/object/name/Application_Article_Delete?article_url={{{article_url}}}" target="_blank"> <span style="font-size:11px;">Delete Post</span> </a>
</div>
<br>',
  'preserved_content' => '<br>
<div style="">
<a href="{{{post_link}}}" style="text-transform:uppercase;">{{{article_title}}}</a>
<br>
<a class="" href="/x/object/name/Application_Article_Editor?article_url={{{article_url}}}" target="_blank"> <span style="font-size:11px;">Edit Post</span> </a>  - 
<a class="" href="/x/object/name/Application_Article_Delete?article_url={{{article_url}}}" target="_blank"> <span style="font-size:11px;">Delete Post</span> </a>
</div>
<br>',
  'url_prefix' => '/x',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Article_ShowAll',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '2',
  ),
  'wrapper_name' => 'well',
  'add_a_new_post' => '1',
  'show_post_by_me' => '1',
  'no_of_post_to_show' => '2',
) );

							}
							else
							{
								
$_3d635a79040269634bf9aece4519b644 = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '98',
  1 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
								{
									
$_d7ccbb0d078f1382556241c0c2a4c9d9 = new Ayoola_Page_Editor_Text( array (
  'editable' => '
			
			<h3><br></h3><h3>Update Site</h3><div><br></div>
			',
  'preserved_content' => '			
			<h3><br></h3><h3>Update Site</h3><div><br></div>
			',
  'url_prefix' => '/x',
  'widget_options' => 
  array (
    0 => 'wrappers',
    1 => 'privacy',
  ),
  'object_access_level' => 
  array (
    0 => '98',
    1 => '99',
  ),
  'wrapper_name' => '',
) );

								}
								else
								{
									
$_d7ccbb0d078f1382556241c0c2a4c9d9 = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_1f813e5a90dc47a13accb08468c59439 = new Ayoola_Object_Embed( array (
  'editable' => 'PageCarton_NewSiteWizard',
  'widget_options' => 
  array (
    0 => 'parameters',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '1',
  ),
  'publisher_mode' => '1',
) );

							}
							else
							{
								
$_1f813e5a90dc47a13accb08468c59439 = null;

							}
							