<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /ayoola/log
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: log.php	Wednesday 20th of January 2016 11:10:03 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar0objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Application_Log',
) );

							}
							else
							{
								
$objay__middlebar0objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar0menuView = new Ayoola_Menu( array (
  'view' => 'Display a Menu',
  'option' => 'admin',
) );

							}
							else
							{
								
$objay__leftbar0menuView = null;

							}
							