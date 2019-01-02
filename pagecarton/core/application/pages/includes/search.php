<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /search
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: search.php	Monday 31st of December 2018 12:10:09 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_967ab248ab9cbfe6eb51a6474613745f = new Ayoola_Object_Embed( array (
  'editable' => 'Application_SearchBox',
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
								
$_967ab248ab9cbfe6eb51a6474613745f = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_38b40a91427a650d9d939bb5a458afa1 = new Application_Article_ShowAll( array (
  'option' => '12',
  'category_name' => '',
  'article_types' => '',
  'template_name' => 'ProductsforSale',
  'advanced_parameter_value' => 
  array (
    0 => 'keyword',
    1 => 'View',
  ),
  'wrapper_name' => '',
  'search_mode' => 'keyword',
  'button_value' => 'View',
) );

							}
							else
							{
								
$_38b40a91427a650d9d939bb5a458afa1 = null;

							}
							