<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /ayoola/page/menu/edit
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: edit.php	Sunday 11th of June 2017 06:46:55 PM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_41ffd1b1d303ca90d4592fb6fe62d54d = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Ayoola_Page_Menu_Edit_List',
) );

							}
							else
							{
								
$_41ffd1b1d303ca90d4592fb6fe62d54d = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_8fc66feea53bfc3b530bf01fec382d76 = new Ayoola_Menu( array (
  'view' => 'Display a Menu',
  'option' => 'page',
) );

							}
							else
							{
								
$_8fc66feea53bfc3b530bf01fec382d76 = null;

							}
							