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
* @version $Id: profile.php	Friday 5th of January 2018 09:54:55 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_b69000b28c902a54d8dbde45f0a62078 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div class="pc_theme_parallax_background" style="min-height:200px; background-image:url( \'{{{url_prefix}}}{{{display_picture}}}\' );">
<h1>{{{display_name}}}</h1>

<p>{{{auth_name}}}</p>

<p>@{{{profile_url}}}</p><p><br></p>

<blockquote>
<p>{{{profile_description}}}</p>
</blockquote>
</div>
',
  'url_prefix' => '/x',
  'markup_template_object_name' => 'Application_Profile_View',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => '',
  '' => '',
) );

							}
							else
							{
								
$_b69000b28c902a54d8dbde45f0a62078 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_d7866b074a6399dd5fc2b5b46d33102a = new Ayoola_Page_Editor_Text( array (
  'editable' => '<a href="{{{share_url}}}" style="margin-right:1em;" target="_blank">Share ({{{share_count}}})</a> <a href="/tools/classplayer/get/name/Application_Message_Creator/?to={{{profile_url}}}" target="_blank">Message</a>
',
  'url_prefix' => '/x',
  'markup_template_object_name' => 'Application_Share',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'white-background',
  '' => '',
) );

							}
							else
							{
								
$_d7866b074a6399dd5fc2b5b46d33102a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_1b79bf44524d32e0838706842f28e99f = new Application_Article_ShowAll( array (
  'option' => '1',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '1',
  ),
  'wrapper_name' => '',
  'show_profile_posts' => '1',
  'add_a_new_post' => '1',
  'pagination' => '1',
) );

							}
							else
							{
								
$_1b79bf44524d32e0838706842f28e99f = null;

							}
							