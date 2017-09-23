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
* @version $Id: contact.php	Sunday 11th of June 2017 05:50:04 PM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Application_ContactUs_Creator' ) )
							{
								
$_b0953b2663e1ea6d5f80cbaeb15e1e12 = new Application_ContactUs_Creator( array (
  'editable' => 'Contact Us',
) );

							}
							else
							{
								
$_b0953b2663e1ea6d5f80cbaeb15e1e12 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_50f0268c52a5146d91f96c35bdf8bfef = new Ayoola_Menu( array (
  'editable' => 'View a Menu',
  'option' => 'terms_and_policies',
) );

							}
							else
							{
								
$_50f0268c52a5146d91f96c35bdf8bfef = null;

							}
							