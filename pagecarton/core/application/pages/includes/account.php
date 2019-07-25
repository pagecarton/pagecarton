<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /account
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: account.php	Thursday 25th of July 2019 10:42:42 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_2f63bc0963e9afc89bc511de893c11fc = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>

<h1>Account Dashboard</h1>

<br>',
  'preserved_content' => '<br>

<h1>Account Dashboard</h1>

<br>',
  'url_prefix' => '',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'insert_id' => '1564094539-0-52',
  'pagewidget_id' => '1564094539-0-52',
  '' => '',
  'widget_name' => 'A c c o u n t  D a s h b o a r d',
) );

							}
							else
							{
								
$_2f63bc0963e9afc89bc511de893c11fc = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_692509e191fac1d67c625e321c4395b7 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div style="">Email: {{{email}}}</div>

<div style="">Username: {{{username}}}</div>


',
  'preserved_content' => '<div style="">Email: {{{email}}}</div>

<div style="">Username: {{{username}}}</div>


',
  'url_prefix' => '',
  'markup_template_object_name' => 
  array (
    0 => 'Ayoola_Access_Dashboard',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'dark',
  'insert_id' => '1564094539-0-53',
  'pagewidget_id' => '1564094539-0-53',
  '' => '',
  'widget_name' => 'E m a i l :  { { { e m a i l } } }  U s e r n a m e :  { { { u s e r n a m e } } }',
) );

							}
							else
							{
								
$_692509e191fac1d67c625e321c4395b7 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_356f72d00671943948b1fee15fdc71ec = new Ayoola_Menu( array (
  'option' => 'MyAccount',
  'template_name' => 'WhiteSidebarMenu',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'insert_id' => '1564094539-0-54',
  'pagewidget_id' => '1564094539-0-54',
  '' => '',
  'widget_name' => 'M y A c c o u n t  -  W h i t e S i d e b a r M e n u  -  A r r a y  -  1 5 6 4 0 9 4 5 3 9 - 0 - 5 4  -  1 5 6 4 0 9 4 5 3 9 - 0 - 5 4  -',
) );

							}
							else
							{
								
$_356f72d00671943948b1fee15fdc71ec = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_812a3c03c828e0fad72b06706dd89e2c = new Ayoola_Page_Editor_Text( array (
  'editable' => '
			<div style="">
			<h3>My Posts<br></h3>
			
			</div>
			',
  'preserved_content' => '			<div style="">
			<h3>My Posts<br></h3>
			
			</div>
			',
  'url_prefix' => '',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'dark',
  'insert_id' => '1564094539-0-55',
  'pagewidget_id' => '1564094539-0-55',
  '' => '',
  'widget_name' => 'M y  P o s t s',
) );

							}
							else
							{
								
$_812a3c03c828e0fad72b06706dd89e2c = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_edca16dd549bfe09f69705d257712133 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>
<div style="">
<a href="{{{post_link}}}" style="text-transform:uppercase;">{{{article_title}}}</a>
<br>
<a class="" href="/object/name/Application_Article_Editor?article_url={{{article_url}}}" target="_blank"> <span style="font-size:11px;">Edit Post</span> </a>  - 
<a class="" href="/object/name/Application_Article_Delete?article_url={{{article_url}}}" target="_blank"> <span style="font-size:11px;">Delete Post</span> </a>
</div>
<br>',
  'preserved_content' => '<br>
<div style="">
<a href="{{{post_link}}}" style="text-transform:uppercase;">{{{article_title}}}</a>
<br>
<a class="" href="/object/name/Application_Article_Editor?article_url={{{article_url}}}" target="_blank"> <span style="font-size:11px;">Edit Post</span> </a>  - 
<a class="" href="/object/name/Application_Article_Delete?article_url={{{article_url}}}" target="_blank"> <span style="font-size:11px;">Delete Post</span> </a>
</div>
<br>',
  'url_prefix' => '',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Article_ShowAll',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '2',
  ),
  'wrapper_name' => 'well',
  'insert_id' => '1564094539-0-56',
  'pagewidget_id' => '1564094539-0-56',
  'add_a_new_post' => '1',
  'show_post_by_me' => '1',
  'no_of_post_to_show' => '2',
  'widget_name' => '{ { { a r t i c l e _ t i t l e } } }  E d i t  P o s t  -  D e l e t e  P o s t',
) );

							}
							else
							{
								
$_edca16dd549bfe09f69705d257712133 = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '98',
  1 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
								{
									
$_d687a55ad8784085b5b9dd53060bad5c = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div class="container">
<p>&nbsp;</p>

<h3>Update Site</h3>

<div>&nbsp;</div>

<div>&nbsp;</div>
</div>
',
  'preserved_content' => '			
			<h3><br></h3><h3>Update Site</h3><div><br></div>
			',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'wrappers',
    1 => 'privacy',
  ),
  'object_access_level' => 
  array (
    0 => '98',
    1 => '99',
  ),
  'wrapper_name' => '',
  'insert_id' => '1564094539-0-57',
  'pagewidget_id' => '1564094539-0-57',
  'widget_name' => 'U p d a t e  S i t e',
) );

								}
								else
								{
									
$_d687a55ad8784085b5b9dd53060bad5c = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_a7e752f58e7a7c50ee706917a2a53a70 = new Ayoola_Object_Embed( array (
  'editable' => 'PageCarton_NewSiteWizard',
  'widget_options' => 
  array (
    0 => 'parameters',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '1',
  ),
  'publisher_mode' => '1',
  'widget_name' => 'P a g e C a r t o n _ N e w S i t e W i z a r d',
) );

							}
							else
							{
								
$_a7e752f58e7a7c50ee706917a2a53a70 = null;

							}
							