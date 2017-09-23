<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /posts
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: posts.php	Saturday 16th of September 2017 10:59:07 PM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_7c4ed3ac313820f80863e34774775786 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section class="productname" style="padding:2em 1em 2em 1em;  background:     linear-gradient(      rgba(0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url({{{cover_photo}}});  background-size: cover;  color: #fff;"><h1>{{{category_label}}}</h1><p><br></p>{{{category_description}}} <span style="font-size:12px;">{{{auto_create_link}}}</span><span style="font-size:11px;"> {{{edit_link}}}</span></section>',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Category_View',
    1 => '1',
    2 => '1',
    3 => '0',
  ),
  'wrapper_name' => '',
  'markup_template_object_name' => 'Application_Category_View',
  'allow_dynamic_category_selection' => '1',
  'build_meta_data' => '1',
  'pc_module_url_values_category_offset' => '0',
) );

							}
							else
							{
								
$_7c4ed3ac313820f80863e34774775786 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_429b6a49e16de16419b837e91076902a = new Application_Article_ShowAll( array (
  'option' => '20',
  'category_name' => '',
  'article_types' => '',
  'template_name' => 'ItemsList',
  'advanced_parameter_value' => 
  array (
    0 => '0',
    1 => '1',
    2 => 'More...',
    3 => '600',
    4 => '600',
  ),
  'wrapper_name' => '',
  'pc_module_url_values_category_offset' => '0',
  'allow_dynamic_category_selection' => '1',
  'button_value' => 'More...',
  'cover_photo_width' => '600',
  'cover_photo_height' => '600',
) );

							}
							else
							{
								
$_429b6a49e16de16419b837e91076902a = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '98',
  1 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
								{
									
$_1b6eb7b49a162a4c42b33ec51a5b9d37 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section class="productname" style="padding:2em 1em 2em 1em; "><p style="text-align: center;"><span style="font-size:11px;">Add a new {{{post_type}}} to the "{{{category_label}}}" category.</span></p><p style="text-align: center;">&nbsp;</p><p style="text-align: center;"><a class="btn btn-primary" href="/tools/classplayer/get/name/Application_Article_Creator?article_type={{{post_type}}}&amp;category_name={{{category_name}}}&amp;pc_post_info_to_edit=article_title,article_description,document_url_base64,item_price,item_old_price,category_name" rel="spotlight"><span style="font-size:11px;">Add new</span></a></p></section>',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Category_View',
    1 => '1',
    2 => '0',
    3 => '1',
  ),
  'object_access_level' => 
  array (
    0 => '98',
    1 => '99',
  ),
  'wrapper_name' => 'well',
  'markup_template_object_name' => 'Application_Category_View',
  'allow_dynamic_category_selection' => '1',
  'pc_module_url_values_category_offset' => '0',
  'pc_module_url_values_request_fallback' => '1',
) );

								}
								else
								{
									
$_1b6eb7b49a162a4c42b33ec51a5b9d37 = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_7c4ed3ac313820f80863e34774775786 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section class="productname" style="padding:2em 1em 2em 1em;  background:     linear-gradient(      rgba(0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url({{{cover_photo}}});  background-size: cover;  color: #fff;"><h1>{{{category_label}}}</h1><p><br></p>{{{category_description}}} <span style="font-size:12px;">{{{auto_create_link}}}</span><span style="font-size:11px;"> {{{edit_link}}}</span></section>',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Category_View',
    1 => '1',
    2 => '1',
    3 => '0',
  ),
  'wrapper_name' => '',
  'markup_template_object_name' => 'Application_Category_View',
  'allow_dynamic_category_selection' => '1',
  'build_meta_data' => '1',
  'pc_module_url_values_category_offset' => '0',
) );

							}
							else
							{
								
$_7c4ed3ac313820f80863e34774775786 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_429b6a49e16de16419b837e91076902a = new Application_Article_ShowAll( array (
  'option' => '20',
  'category_name' => '',
  'article_types' => '',
  'template_name' => 'ItemsList',
  'advanced_parameter_value' => 
  array (
    0 => '0',
    1 => '1',
    2 => 'More...',
    3 => '600',
    4 => '600',
  ),
  'wrapper_name' => '',
  'pc_module_url_values_category_offset' => '0',
  'allow_dynamic_category_selection' => '1',
  'button_value' => 'More...',
  'cover_photo_width' => '600',
  'cover_photo_height' => '600',
) );

							}
							else
							{
								
$_429b6a49e16de16419b837e91076902a = null;

							}
							