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
* @version $Id: account.php	Monday 31st of December 2018 12:10:08 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_abfe4d78405111b0fb3f1897a066a533 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>

<h1>Account Dashboard</h1>

<br>',
  'preserved_content' => '<h1>&nbsp;Account Dashboard</h1>

<p>&nbsp;</p>
',
  'url_prefix' => '/x',
  'phrase_to_replace' => '',
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

<div style="">Password: ******</div>
',
  'url_prefix' => '/x',
  'markup_template_object_name' => 
  array (
    0 => 'Ayoola_Access_Dashboard',
  ),
  'phrase_to_replace' => '',
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
  'wrapper_name' => '',
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
  'phrase_to_replace' => '',
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
{{{article_description}}}
</div>
<br>',
  'preserved_content' => '',
  'url_prefix' => '/x',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Article_ShowAll',
  ),
  'phrase_to_replace' => '',
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
							