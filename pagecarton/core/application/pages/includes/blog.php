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
* @version $Id: blog.php	Thursday 12th of October 2017 09:53:00 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_9dfde2505e34fcdc3f8ce5adbe93607b = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Posts</h1><div><br></div>
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
								
$_9dfde2505e34fcdc3f8ce5adbe93607b = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_9ff82ac70f3bd1edc063566b7ac872c8 = new Application_Article_ShowAll( array (
  'option' => '20',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
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
								
$_9ff82ac70f3bd1edc063566b7ac872c8 = null;

							}
							