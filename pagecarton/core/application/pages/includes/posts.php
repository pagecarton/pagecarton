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
* @version $Id: posts.php	Monday 31st of December 2018 11:18:40 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_4fb63061f94927642d8ec2793bbbcc5d = new Ayoola_Object_Embed( array (
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
								
$_4fb63061f94927642d8ec2793bbbcc5d = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_a7e9c4f356d6710eb63b45f368ae0d6f = new Ayoola_Menu( array (
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
								
$_a7e9c4f356d6710eb63b45f368ae0d6f = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_150b03d6e41401066b62cf41cc624026 = new Application_Article_ShowAll( array (
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
    4 => '1',
    5 => '1',
    6 => '1',
  ),
  'wrapper_name' => '',
  'add_a_new_post' => '1',
  'allow_dynamic_category_selection' => '1',
  'pc_module_url_values_category_offset' => '0',
  'pagination' => '1',
  'get_views_count' => '1',
  'get_audio_play_count' => '1',
  'get_download_count' => '1',
) );

							}
							else
							{
								
$_150b03d6e41401066b62cf41cc624026 = null;

							}
							