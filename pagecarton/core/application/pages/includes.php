<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: includes.php	Saturday 16th of June 2018 10:59:57 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_eaad0d1b7e9b772c1571692e6753f2a6 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Personalization',
  'advanced_parameter_value' => 
  array (
    0 => '1',
  ),
  'wrapper_name' => '',
  'only_show_when_no_admin' => '1',
) );

							}
							else
							{
								
$_eaad0d1b7e9b772c1571692e6753f2a6 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_SiteInfo' ) )
							{
								
$_2be488f19117496aa2cb7dabe9d57342 = new Application_SiteInfo( array (
  'advanced_parameter_value' => 
  array (
    0 => 'margin-bottom:1.5em;',
  ),
  'wrapper_name' => '',
  'object_style' => 'margin-bottom:1.5em;',
) );

							}
							else
							{
								
$_2be488f19117496aa2cb7dabe9d57342 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_9cfa0159b65cbb1ced5e614f8d44d92d = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '1',
    3 => '1',
    4 => '1',
  ),
  'wrapper_name' => '',
  'add_a_new_post' => '1',
  'pagination' => '1',
  'get_audio_play_count' => '1',
  'get_download_count' => '1',
  'get_views_count' => '1',
) );

							}
							else
							{
								
$_9cfa0159b65cbb1ced5e614f8d44d92d = null;

							}
							