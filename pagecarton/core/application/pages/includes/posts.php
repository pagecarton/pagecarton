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
* @version $Id: posts.php	Wednesday 4th of October 2017 12:13:52 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_aecd06df19d2ff04a6c59bf81f9ca8a5 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Category_View',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '0',
  ),
  'wrapper_name' => '',
  'allow_dynamic_category_selection' => '1',
  'build_meta_data' => '1',
  'pc_module_url_values_category_offset' => '0',
) );

							}
							else
							{
								
$_aecd06df19d2ff04a6c59bf81f9ca8a5 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_277b401b5ea8bda1e6dc371b39179905 = new Ayoola_Menu( array (
  'option' => 'menu_2',
  'template_name' => 'HorizontalWhite',
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
								
$_277b401b5ea8bda1e6dc371b39179905 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_429b6a49e16de16419b837e91076902a = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '0',
  ),
  'wrapper_name' => '',
  'add_a_new_post' => '1',
  'allow_dynamic_category_selection' => '1',
  'pc_module_url_values_category_offset' => '0',
) );

							}
							else
							{
								
$_429b6a49e16de16419b837e91076902a = null;

							}
							