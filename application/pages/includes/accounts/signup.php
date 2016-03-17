<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /accounts/signup
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: signup.php	Wednesday 20th of January 2016 11:06:39 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Application_User_Creator' ) )
							{
								
$objay__middlebar0userSignup = new Application_User_Creator( array (
  'view' => 'Sign Up Form',
) );

							}
							else
							{
								
$objay__middlebar0userSignup = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__leftbar0objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Ayoola_HybridAuth_SignIn',
) );

							}
							else
							{
								
$objay__leftbar0objectEmbed = null;

							}
							