<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /pc-admin/old
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: old.php	Tuesday 3rd of October 2017 05:41:44 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_d3f23056ccee195c99a4ad1d55ecd4dc = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Info',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'grey-shadow-background',
  '' => '',
) );

							}
							else
							{
								
$_d3f23056ccee195c99a4ad1d55ecd4dc = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_2ee0402d50b486476381b1038d6d877f = new Ayoola_Object_Embed( array (
  'editable' => 'Application_ContactUs_ShowAll',
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
								
$_2ee0402d50b486476381b1038d6d877f = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_65fa896b81da25d62b865be1332e25c8 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<p><span style="font-size:12px;">User Posts</span></p>
',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'white-content-theme-border',
  '' => '',
) );

							}
							else
							{
								
$_65fa896b81da25d62b865be1332e25c8 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_a7bd509d3f11e0dac62916f88b2c97d7 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<p><span style="font-size:10px;">{{{article_type}}}</span><br>
<strong>{{{article_title}}}</strong><br>
<span style="font-size:10px;"><a href="{{{pc_url_prefix}}}{{{article_url}}}" rel="spotlight">View</a> - <a href="{{{pc_url_prefix}}}/object/name/Application_Article_Editor/?article_url={{{article_url}}}" rel="spotlight">Edit</a><a href="{{{pc_url_prefix}}}/article/post/editor/?article_url={{{article_url}}}" rel="spotlight"> </a>- <a href="{{{pc_url_prefix}}}/object/name/Application_Article_Delete/?article_url={{{article_url}}}" rel="spotlight">Delete</a></span> <span style="font-size:10px;"> - <a href="{{{pc_url_prefix}}}/object/name/Application_Article_Switch/?article_url={{{article_url}}}">Feature</a></span></p><hr>',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Article_ShowAll',
    1 => 'No posts created yet. <a href="/post/create">Create Post</a>',
  ),
  'wrapper_name' => 'white-background',
  'markup_template_object_name' => 'Application_Article_ShowAll',
  'markup_template_no_data' => 'No posts created yet. <a href="/post/create">Create Post</a>',
) );

							}
							else
							{
								
$_a7bd509d3f11e0dac62916f88b2c97d7 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_3048d35b02b2faea491a71775ef3d9b6 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<p><span style="font-size:12px;">User Pages</span></p>
',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'white-content-theme-border',
  '' => '',
) );

							}
							else
							{
								
$_3048d35b02b2faea491a71775ef3d9b6 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_8613c385d091a49dd9565e7054272913 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<p><span style="font-size:10px;">{{{auth_name}}}</span><br>
<strong>{{{display_name}}}</strong><br>
<span style="font-size:10px;"><a href="{{{pc_url_prefix}}}/{{{profile_url}}}" rel="spotlight">View</a> - <a href="{{{pc_url_prefix}}}/object/name/Application_Profile_Editor/?profile_url={{{profile_url}}}" rel="spotlight">Edit</a><a href="{{{pc_url_prefix}}}/article/post/editor/?article_url={{{article_url}}}" rel="spotlight"> </a>- <a href="{{{pc_url_prefix}}}/object/name/Application_Profile_Delete/?profile_url={{{profile_url}}}" rel="spotlight">Delete</a> - <a href="{{{pc_url_prefix}}}/object/name/Application_Profile_Switch/?profile_url={{{profile_url}}}">Feature</a></span></p>

<hr>',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Profile_All',
    1 => 'No user profiles created yet.',
  ),
  'wrapper_name' => 'white-background',
  'markup_template_object_name' => 'Application_Profile_All',
  'markup_template_no_data' => 'No user profiles created yet.',
) );

							}
							else
							{
								
$_8613c385d091a49dd9565e7054272913 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_7353e32c863adf892aa07d2258013c19 = new Ayoola_Menu( array (
  'option' => 'admin',
  'new_menu_name' => '',
  'template_name' => 'BlackAccordionSide-menu',
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
								
$_7353e32c863adf892aa07d2258013c19 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_d09f44eaa8d0b20123ff5a2ace5d430c = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>PageCarton Admin Panel</h1>
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
								
$_d09f44eaa8d0b20123ff5a2ace5d430c = null;

							}
							