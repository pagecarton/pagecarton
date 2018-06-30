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
* @version $Id: account.php	Saturday 30th of June 2018 11:00:51 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_abfe4d78405111b0fb3f1897a066a533 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div>
<h1 class="pc-heading">My Account</h1>
</div>
',
  'preserved_content' => '<div>
<h1 class="pc-heading">My Account</h1>
</div>
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
								
$_1d6aff9ac2ae180800c5bcd948f940a0 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div><br></div><div><br></div>',
  'preserved_content' => '',
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
								
$_1d6aff9ac2ae180800c5bcd948f940a0 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_d3c6c1015e105533db3b93e0ccc8140b = new Ayoola_Menu( array (
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
								
$_d3c6c1015e105533db3b93e0ccc8140b = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_250d634cd4217dddff35b1c906fac53c = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Profile_ShowAll',
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
								
$_250d634cd4217dddff35b1c906fac53c = null;

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
  'wrapper_name' => 'white-well',
  '' => '',
) );

							}
							else
							{
								
$_ea35c1c2cdc5584d84cace9de47b1f1a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_147197d44fdcae73544ef6297ec5736b = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '',
  'article_types' => '',
  'template_name' => 'PlainListofTitles',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
  ),
  'wrapper_name' => 'well',
  'add_a_new_post' => '1',
  'show_post_by_me' => '1',
) );

							}
							else
							{
								
$_147197d44fdcae73544ef6297ec5736b = null;

							}
							