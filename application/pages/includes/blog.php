<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /blog
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: blog.php	Wednesday 20th of January 2016 11:13:48 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Application_Category_View' ) )
							{
								
$objay__header0categoryView = new Application_Category_View( array (
  'category_name' => '0',
  'allow_dynamic_category_selection' => '1',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
) );

							}
							else
							{
								
$objay__header0categoryView = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar0pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Recent Posts...</h1>
',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
) );

							}
							else
							{
								
$objay__middlebar0pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$objay__middlebar1articleShowAll = new Application_Article_ShowAll( array (
  'option' => '10',
  'category_name' => '0',
  'article_types' => '',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
) );

							}
							else
							{
								
$objay__middlebar1articleShowAll = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar2pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Older Blog Posts...</h1>
',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
) );

							}
							else
							{
								
$objay__middlebar2pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar3objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
  'editable' => 'Application_Blog_ShowAll',
) );

							}
							else
							{
								
$objay__middlebar3objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar0menuView = new Ayoola_Menu( array (
  'view' => 'Display a Menu',
  'option' => 'blog',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
) );

							}
							else
							{
								
$objay__leftbar0menuView = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar1menuView = new Ayoola_Menu( array (
  'view' => 'Insert a Menu',
  'option' => 'Articles',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
) );

							}
							else
							{
								
$objay__leftbar1menuView = null;

							}
							