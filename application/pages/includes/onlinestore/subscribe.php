<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /onlinestore/subscribe
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: subscribe.php	Wednesday 20th of January 2016 11:12:49 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar0objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Application_Subscription_ShowLabel, Application_Subscription_ShowImage, Application_Subscription_ShowDescription, Application_Subscription_Detail',
) );

							}
							else
							{
								
$objay__middlebar0objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__leftbar0objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Application_Subscription',
) );

							}
							else
							{
								
$objay__leftbar0objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar1menuView = new Ayoola_Menu( array (
  'view' => 'Display a Menu',
  'option' => 'onlinestore',
) );

							}
							else
							{
								
$objay__leftbar1menuView = null;

							}
							