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
* @version $Id: profile.php	Friday 18th of May 2018 11:01:53 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_337f1f52a5981a69019d7adfdc6b6416 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Profile_View',
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
								
$_337f1f52a5981a69019d7adfdc6b6416 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_b69000b28c902a54d8dbde45f0a62078 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>',
  'preserved_content' => '<br>',
  'url_prefix' => '/x',
  'markup_template_object_name' => '',
  'phrase_to_replace' => '',
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
								
$_e9b8486357fa0ae2f80b0459237503b1 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'/x/tools/classplayer/get/name/Application_Message_ShowAll/?to={{{profile_url}}}\' );" href="javascript:"> <i class="fa fa-envelope" style="margin-right: 1em;"></i> Message</a>
',
  'preserved_content' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'/x/tools/classplayer/get/name/Application_Message_Creator/?to={{{profile_url}}}\' );" href="javascript:">Message</a>
',
  'url_prefix' => '/x',
  'markup_template_object_name' => 'Application_Global',
  'phrase_to_replace' => '',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'white-well',
  '' => '',
) );

							}
							else
							{
								
$_e9b8486357fa0ae2f80b0459237503b1 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_01eda85b98bda9e8333625dc77ad7484 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'{{{share_url}}}\' );" href="javascript:"> <i class="fa fa-share-alt" style="margin-right: 1em;"></i> Share ({{{share_count}}})</a>
',
  'preserved_content' => '<a style="margin-right:1em;" onclick="ayoola.spotLight.showLinkInIFrame( \'{{{share_url}}}\' );" href="javascript:">Share ({{{share_count}}})</a>
',
  'url_prefix' => '/x',
  'markup_template_object_name' => 'Application_Share',
  'phrase_to_replace' => '',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'well',
  '' => '',
) );

							}
							else
							{
								
$_01eda85b98bda9e8333625dc77ad7484 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_c018e6c5819c7575815757750463f0d3 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>',
  'preserved_content' => '<br>',
  'url_prefix' => '/x',
  'markup_template_object_name' => '',
  'phrase_to_replace' => '',
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
								
$_c018e6c5819c7575815757750463f0d3 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_47b477afa92568bdd6fb28a66d065985 = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '',
  'article_types' => '',
  'template_name' => '',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '1',
    3 => '<span class="pc_posts_option_items" style="text-decoration:line-through;" ></span>',
  ),
  'wrapper_name' => '',
  'show_profile_posts' => '1',
  'add_a_new_post' => '1',
  'pagination' => '1',
  'content_to_clear' => '<span class="pc_posts_option_items" style="text-decoration:line-through;" ></span>',
) );

							}
							else
							{
								
$_47b477afa92568bdd6fb28a66d065985 = null;

							}
							