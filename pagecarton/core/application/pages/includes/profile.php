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
* @version $Id: profile.php	Friday 26th of July 2019 09:04:21 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_5624cf8c5adba1f8d7bdb295e1423e72 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Profile_View',
  'pagewidget_id' => '1564094536-0-28',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
  'widget_name' => 'A p p l i c a t i o n _ P r o f i l e _ V i e w',
) );

							}
							else
							{
								
$_5624cf8c5adba1f8d7bdb295e1423e72 = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '98',
  1 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
								{
									
$_4271e658c6bccc97ba5b3cf6db86f81a = new Ayoola_Page_Editor_Text( array (
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
  'pagewidget_id' => '1564094536-0-29',
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
  '' => '',
  'widget_name' => 'E d i t  P r o f i l e  D e l e t e  P r o f i l e',
) );

								}
								else
								{
									
$_4271e658c6bccc97ba5b3cf6db86f81a = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_51ad269b2804228e073d6ded3588f388 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>',
  'preserved_content' => '<br>',
  'url_prefix' => '',
  'pagewidget_id' => '1564094536-0-30',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
  'widget_name' => 'A y o o l a _ P a g e _ E d i t o r _ T e x t  -  a y _ _ m i d d l e b a r 2',
  'insert_id' => '1564131861-0-14',
) );

							}
							else
							{
								
$_51ad269b2804228e073d6ded3588f388 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_166a149c8ae9cfb5621fc376dd6613a1 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/name/Application_Message_ShowAll/?to={{{profile_url}}}\' );" href="javascript:"> <i class="fa fa-envelope" style="margin-right: 1em;"></i> Message</a>
',
  'preserved_content' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/name/Application_Message_ShowAll/?to={{{profile_url}}}\' );" href="javascript:"> <i class="fa fa-envelope" style="margin-right: 1em;"></i> Message</a>
',
  'url_prefix' => '',
  'pagewidget_id' => '1564094536-0-31',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Global',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'white-well',
  '' => '',
  'widget_name' => 'M e s s a g e',
) );

							}
							else
							{
								
$_166a149c8ae9cfb5621fc376dd6613a1 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_d41c138d6ef884c18d72b5e988f6e6c2 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'{{{share_url}}}\' );" href="javascript:"> <i class="fa fa-share-alt" style="margin-right: 1em;"></i> Share ({{{share_count}}})</a>
',
  'preserved_content' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'{{{share_url}}}\' );" href="javascript:"> <i class="fa fa-share-alt" style="margin-right: 1em;"></i> Share ({{{share_count}}})</a>
',
  'url_prefix' => '',
  'pagewidget_id' => '1564094537-0-32',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Share',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'well',
  '' => '',
  'widget_name' => 'S h a r e  ( { { { s h a r e _ c o u n t } } } )',
) );

							}
							else
							{
								
$_d41c138d6ef884c18d72b5e988f6e6c2 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_5b583620a69a1074ce34c80deaf8c812 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>',
  'preserved_content' => '<br>',
  'url_prefix' => '',
  'pagewidget_id' => '1564094537-0-33',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
  'widget_name' => 'A y o o l a _ P a g e _ E d i t o r _ T e x t  -  a y _ _ l a s t o n e n e s s 0',
) );

							}
							else
							{
								
$_5b583620a69a1074ce34c80deaf8c812 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_007a086925f4447b298f90a895538b81 = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
  'pagewidget_id' => '1564094537-0-34',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '1',
    3 => '<span class="pc_posts_option_items" style="text-decoration:line-through;" ></span>',
    4 => 'container',
  ),
  'show_profile_posts' => '1',
  'add_a_new_post' => '1',
  'pagination' => '1',
  'content_to_clear' => '<span class="pc_posts_option_items" style="text-decoration:line-through;" ></span>',
  'object_class' => 'container',
  'widget_name' => '5  -  -  -  -  1 5 6 4 0 9 4 5 3 7 - 0 - 3 4  -  A r r a y  -  1  -  1  -  1  -  -  c o n t a i n e r',
) );

							}
							else
							{
								
$_007a086925f4447b298f90a895538b81 = null;

							}
							