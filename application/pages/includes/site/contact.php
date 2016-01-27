<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /site/contact
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: contact.php	Wednesday 20th of January 2016 11:06:29 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Application_ContactUs_Creator' ) )
							{
								
$objay__middlebar0contactUsCreator = new Application_ContactUs_Creator( array (
  'editable' => 'Contact Us',
) );

							}
							else
							{
								
$objay__middlebar0contactUsCreator = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__rightbar0menuView = new Ayoola_Menu( array (
  'editable' => 'View a Menu',
  'option' => 'terms_and_policies',
) );

							}
							else
							{
								
$objay__rightbar0menuView = null;

							}
							