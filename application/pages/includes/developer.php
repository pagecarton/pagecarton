<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /developer
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: developer.php	Wednesday 20th of January 2016 11:04:01 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar0pageEditText = new Ayoola_Page_Editor_Text( array (
  'view' => 'Insert HTML Text',
  'editable' => '<h3>Application Developers Lounge</h3>
<p>You are welcome to the developers\' lounge. We have provided this page because we love developers - we are developers too.</p>
<p>Need help connecting to, or integrating our innovation? We know you mean business: We do too.</p>
<p>
We have different interfaces and application, easily navigate to the application you are trying to integrate.
</p>
<p>Still need help that is not covered in this section? You can <a href="/site/contact/">Contact Us</a>. Our <a href="/blog/">Blog</a> Page also might contain posts about what you are looking for.</p>',
) );

							}
							else
							{
								
$objay__middlebar0pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__leftbar0menuView = new Ayoola_Menu( array (
  'view' => 'Display a Menu',
  'option' => 'DevelopersLounge',
) );

							}
							else
							{
								
$objay__leftbar0menuView = null;

							}
							