<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /ayoola/developer
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: developer.php	Wednesday 20th of January 2016 11:10:30 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar0pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h4>Developers\' Corner</h4>
<p>Think you want to go deeper into the things that make your application work?</p>
<p>Good News! As simple as it is to get your application running, there is absolutely nothing you cannot touch. Here goes the things that make your application run as expected. You are adviced not to edit until you are sure you know what you are doing.</p>
<p>First timer? Let us help you get started... <a href="/site/help/?tag=developer">Get Started</a></p>',
) );

							}
							else
							{
								
$objay__middlebar0pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar0menuView = new Ayoola_Menu( array (
  'editable' => 'Insert a Menu',
  'option' => 'developers',
) );

							}
							else
							{
								
$objay__leftbar0menuView = null;

							}
							