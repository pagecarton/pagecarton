<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /about/service
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: service.php	Wednesday 20th of January 2016 11:03:01 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar0pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Our Services</h1>
',
  'call_to_action' => '',
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
  'category_name' => 'services',
  'article_types' => '',
  'call_to_action' => '',
) );

							}
							else
							{
								
$objay__middlebar1articleShowAll = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__rightbar0pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h2>Products</h2>
',
  'call_to_action' => '',
) );

							}
							else
							{
								
$objay__rightbar0pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$objay__rightbar1articleShowAll = new Application_Article_ShowAll( array (
  'option' => '1',
  'category_name' => 'products',
  'article_types' => '',
  'call_to_action' => '',
) );

							}
							else
							{
								
$objay__rightbar1articleShowAll = null;

							}
							