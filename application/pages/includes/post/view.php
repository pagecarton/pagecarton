<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /post/view
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: view.php	Wednesday 20th of January 2016 11:00:32 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar0objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Application_Article_View',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'f8bd8fee16b165fcdf48aabe48deb61b6318fa71' => 'advanced_parameters_1b7462e8a4eb7f1ca0c6f3ca337b6f12678',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__middlebar0objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__middlebar1pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h3>Please leave a comment</h3>
',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'de82ac39dc4303e4447ae68c7d2b9d8a20f0' => 'advanced_parameters_d037e4103e83a1b0065888e24b8faaa9273',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__middlebar1pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar2objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'PHP Object',
  'editable' => ' Application_Facebook_Comment, Application_Disqus_Comment',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'd32fff179bac07f5f143239fa9959a254730601a' => 'advanced_parameters_1b7462e8a4eb7f1ca0c6f3ca337b6f12271',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__middlebar2objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__rightbar0menuView = new Ayoola_Menu( array (
  'option' => 'Articles',
  'template_name' => 'BlackAccordionSide-menu',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'c47ffe80287434c62dd45dbc76d086971f735' => 'advanced_parameters_9cbabf00532a0aafa02cc612dc280824310',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__rightbar0menuView = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Advert_View' ) )
							{
								
$objay__leftbar0advertView = new Application_Advert_View( array (
  'view' => 'Adverts',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'dcf62d48816d899fd2a3e555f540db333114f' => 'advanced_parameters_7536303a4e3309826968e9dfa8241e47179',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__leftbar0advertView = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__leftbar1pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h3>Recent Posts</h3>
',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'ba7eff1230d8a004c5b4671de9920cc86177e658' => 'advanced_parameters_d037e4103e83a1b0065888e24b8faaa9816',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__leftbar1pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$objay__leftbar2articleShowAll = new Application_Article_ShowAll( array (
  'option' => '3',
  'category_name' => '0',
  'article_types' => '',
  'template_name' => '',
  'advanced_parameter_value' => 
  array (
    0 => '<p><strong><a href="{{{article_url}}}" title="{{{article_description}}}">{{{article_title}}}</a></strong> {{{cover_photo_with_link}}} </p>',
    1 => 'Recent Posts',
    2 => '1',
  ),
  'b593ec9d301d0d875ce57162d32d1730f91ac0' => 'advanced_parameters_52a294c1060c2a3a2b06e9baeef1457c120',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  'markup_template' => '<p><strong><a href="{{{article_url}}}" title="{{{article_description}}}">{{{article_title}}}</a></strong> {{{cover_photo_with_link}}} </p>',
  'markup_template_namespace' => 'Recent Posts',
  'max_group_no' => '1',
) );

							}
							else
							{
								
$objay__leftbar2articleShowAll = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$objay__footer0pageEditText = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h3 style="text-align: center;">Other Posts in this Category</h3>
',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'e3d809d6e7379e41772cb7954fd3bc7712aa' => 'advanced_parameters_d037e4103e83a1b0065888e24b8faaa9353',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__footer0pageEditText = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$objay__footer1articleShowAll = new Application_Article_ShowAll( array (
  'option' => '3',
  'category_name' => '0',
  'article_types' => '',
  'template_name' => 'ProductsandServicesList2',
  'advanced_parameter_value' => 
  array (
    0 => '1',
  ),
  'f52c6305fd07de9b093498bbd721c41da72b9b79' => 'advanced_parameters_52a294c1060c2a3a2b06e9baeef1457c275',
  'f43c8945ced5074c89bce9ebb18aab3e6784e1' => '107374182',
  'd2d4aa66c32b6e98f0de9347a11407a262496314' => '',
  'post_with_same_category' => '1',
) );

							}
							else
							{
								
$objay__footer1articleShowAll = null;

							}
							