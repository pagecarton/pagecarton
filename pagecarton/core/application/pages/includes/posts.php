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
* @version $Id: posts.php	Wednesday 18th of October 2017 08:58:25 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_aecd06df19d2ff04a6c59bf81f9ca8a5 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Category_View',
  'category_name' => '',
  'pc_module_url_values_category_offset' => '?',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '0',
    3 => 'margin-bottom:1.5em;',
  ),
  'wrapper_name' => '',
  'allow_dynamic_category_selection' => '1',
  'build_meta_data' => '1',
  'object_style' => 'margin-bottom:1.5em;',
) );

							}
							else
							{
								
$_aecd06df19d2ff04a6c59bf81f9ca8a5 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_028c45200f20dd30665527b611466ede = new Ayoola_Menu( array (
  'option' => 'menu_2',
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
								
$_028c45200f20dd30665527b611466ede = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_8dc06455a087fe8cf383f9c0eaf09ef5 = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '0',
    3 => '1',
  ),
  'wrapper_name' => '',
  'add_a_new_post' => '1',
  'allow_dynamic_category_selection' => '1',
  'pc_module_url_values_category_offset' => '0',
  'pagination' => '1',
) );

							}
							else
							{
								
$_8dc06455a087fe8cf383f9c0eaf09ef5 = null;

							}
							