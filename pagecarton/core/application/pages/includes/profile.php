<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /profile
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: profile.php	Saturday 14th of December 2019 09:18:30 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_797e4e03e5bd96892fdb15da21fb6f7f = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Profile_View',
  'pagewidget_id' => '1575534210-0-8',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'insert_id' => '1566242672-0-23',
  '' => '',
) );

							}
							else
							{
								
$_797e4e03e5bd96892fdb15da21fb6f7f = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '98',
  1 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
								{
									
$_8837fde2685e638f954b537fb9d0f8f8 = new Ayoola_Page_Editor_Text( array (
  'editable' => '

<p style="text-align: center;">
<a class="btn btn-default" href="/widgets/Application_Profile_Editor/?profile_url={{{profile_url}}}">Edit Profile</a>
<a class="btn btn-danger" href="/widgets/Application_Profile_Delete/?profile_url={{{profile_url}}}">Delete Profile</a>

</p>',
  'preserved_content' => '
<p style="text-align: center;">
<a class="btn btn-default" href="/widgets/Application_Profile_Editor/?profile_url={{{profile_url}}}">Edit Profile</a>
<a class="btn btn-danger" href="/widgets/Application_Profile_Delete/?profile_url={{{profile_url}}}">Delete Profile</a>

</p>',
  'url_prefix' => '',
  'pagewidget_id' => '1575534210-0-9',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Profile_View',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'object_access_level' => 
  array (
    0 => '98',
    1 => '99',
  ),
  'wrapper_name' => 'well',
  'insert_id' => '1566242672-0-24',
  '' => '',
) );

								}
								else
								{
									
$_8837fde2685e638f954b537fb9d0f8f8 = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_f11de9aa6da678561a3d7c5bada0c58e = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>',
  'preserved_content' => '<br>',
  'url_prefix' => '',
  'pagewidget_id' => '1575534210-0-10',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'insert_id' => '1566242673-0-25',
  '' => '',
) );

							}
							else
							{
								
$_f11de9aa6da678561a3d7c5bada0c58e = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_6a669deaa4532932ef5b08be7cb802d5 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/name/Application_Message_ShowAll/?to={{{profile_url}}}\' );" href="javascript:"> <i class="fa fa-envelope" style="margin-right: 1em;"></i> Message</a>
',
  'preserved_content' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/name/Application_Message_ShowAll/?to={{{profile_url}}}\' );" href="javascript:"> <i class="fa fa-envelope" style="margin-right: 1em;"></i> Message</a>
',
  'url_prefix' => '',
  'pagewidget_id' => '1575534210-0-11',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Global',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'white-well',
  'insert_id' => '1566242673-0-26',
  '' => '',
) );

							}
							else
							{
								
$_6a669deaa4532932ef5b08be7cb802d5 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_c2ca346e85169d0349e45445ad0cbcbc = new Ayoola_Page_Editor_Text( array (
  'editable' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'{{{share_url}}}\' );" href="javascript:"> <i class="fa fa-share-alt" style="margin-right: 1em;"></i> Share ({{{share_count}}})</a>
',
  'preserved_content' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'{{{share_url}}}\' );" href="javascript:"> <i class="fa fa-share-alt" style="margin-right: 1em;"></i> Share ({{{share_count}}})</a>
',
  'url_prefix' => '',
  'pagewidget_id' => '1575534210-0-12',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Share',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'well',
  'insert_id' => '1566242673-0-27',
  '' => '',
) );

							}
							else
							{
								
$_c2ca346e85169d0349e45445ad0cbcbc = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_e427e2d459cec8a2c7b0fd826c8d072c = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>',
  'preserved_content' => '<br>',
  'url_prefix' => '',
  'pagewidget_id' => '1575534211-0-13',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'insert_id' => '1566242673-0-28',
  '' => '',
) );

							}
							else
							{
								
$_e427e2d459cec8a2c7b0fd826c8d072c = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_23701c0688ae1965e17ea53f25ed362b = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
  'pagewidget_id' => '1575534211-0-14',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '1',
    3 => '<span class="pc_posts_option_items" style="text-decoration:line-through;" ></span>',
    4 => 'container',
  ),
  'insert_id' => '1566242673-0-29',
  'show_profile_posts' => '1',
  'add_a_new_post' => '1',
  'pagination' => '1',
  'content_to_clear' => '<span class="pc_posts_option_items" style="text-decoration:line-through;" ></span>',
  'object_class' => 'container',
) );

							}
							else
							{
								
$_23701c0688ae1965e17ea53f25ed362b = null;

							}
							