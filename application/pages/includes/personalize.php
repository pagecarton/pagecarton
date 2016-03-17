<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /personalize
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: personalize.php	Wednesday 20th of January 2016 11:01:27 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar0pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Personalize your website</h1>
',
  'call_to_action' => '',
) );

							}
							else
							{
								
$objay__middlebar0pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar1objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'call_to_action' => '',
  'editable' => 'Application_Personalization',
) );

							}
							else
							{
								
$objay__middlebar1objectEmbed = null;

							}
							