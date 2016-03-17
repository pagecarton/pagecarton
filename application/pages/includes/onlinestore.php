<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /onlinestore
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: onlinestore.php	Wednesday 20th of January 2016 11:11:35 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar0objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
  'editable' => 'Application_Subscription_Price_ShowAll',
) );

							}
							else
							{
								
$objay__middlebar0objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$objay__middlebar1articleShowAll = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '0',
  'article_types' => 'subscription',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
) );

							}
							else
							{
								
$objay__middlebar1articleShowAll = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar0menuView = new Ayoola_Menu( array (
  'view' => 'Display a Menu',
  'option' => 'onlinestore',
  'call_to_action' => '',
  'markup_template_namespace' => '',
  'markup_template' => '',
) );

							}
							else
							{
								
$objay__leftbar0menuView = null;

							}
							