<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /ayoola/page
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: page.php	Wednesday 20th of January 2016 11:09:09 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar0objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Ayoola_Page_List',
) );

							}
							else
							{
								
$objay__middlebar0objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar0menuView = new Ayoola_Menu( array (
  'view' => 'View a Menu',
  'option' => 'page',
) );

							}
							else
							{
								
$objay__leftbar0menuView = null;

							}
							