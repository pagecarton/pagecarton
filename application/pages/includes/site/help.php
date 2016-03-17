<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /site/help
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: help.php	Wednesday 20th of January 2016 11:08:58 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar0pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h4>Browse Our Help Documents</h4>
<p>
Whether you are looking for more information, or you would like to let us know how we did, you will find easy ways to contact us right here.

In a rear occassion that you cannot find answer to the question you have. Feel free to contact us <a href="/site/contact/">here</a>.
</p>',
) );

							}
							else
							{
								
$objay__middlebar0pageEditText = null;

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
							