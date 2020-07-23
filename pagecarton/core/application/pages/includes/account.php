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
* @version $Id: account.php	Saturday 14th of December 2019 09:18:43 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_5d0205259c2cf515c9cf6cca2425f3c4 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<br>

<h1>Account Dashboard</h1>

<br>',
  'preserved_content' => '<br>

<h1>Account Dashboard</h1>

<br>',
  'url_prefix' => '',
  'pagewidget_id' => '1575534201-0-173',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'insert_id' => '1566242675-0-12',
  '' => '',
) );

							}
							else
							{
								
$_5d0205259c2cf515c9cf6cca2425f3c4 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_1f640bacaa91af5b88cd27ebb94625c0 = new Ayoola_Page_Editor_Text( array (
  'codes' => '<a href="{{{post_link}}}"><img class="pc_give_space_top_bottom" style="width:100%;" alt="" src="{{{document_url_cropped}}}"></a>

<div class="xcontainer xpc_container">
    <h3 class="pc_give_space_top_bottom"><a href="{{{post_link}}}">{{{article_title}}}</a></h3>
    <p class="pc_give_space_top_bottom"><a target="_blank" class="a2a_dd btn btn-default" addthis:title="{{{article_title}}}" addthis:description="{{{article_description}}}" addthis:url="{{{post_full_url}}}" href="https://www.addtoany.com/share?url={{{post_full_url}}}&amp;title={{{post_type}}} - {{{article_title}}}">
<span style="font-size:small;"> Share </span> <i class="fa fa-facebook"></i> <i class="fa fa-twitter"></i> <i class="fa fa-whatsapp"></i> <i class="fa fa-share-alt"></i> </a></p>
    <p style="font-size:small;" class="pc_give_space_top_bottom"><a href="/music/posts/{{{post_type_id}}}">{{{post_type}}}</a> by <a href="/music/{{{profile_url}}}">{{{display_name}}}</a> in {{{category_text}}}</p>
       
    <em class="pc_give_space_top_bottom">{{{article_description}}}</em>
    <div class="pc_give_space_top_bottom">{{{article_content}}}</div>
    <div class=""><a href="{{{post_link}}}">View <i class="fa fa-eye pc_give_space"></i> {{{views_count_total}}}   <i class="fa fa-comment pc_give_space"></i> {{{comments_count_total}}} </a></div>
    <div class="pc_give_space_top_bottom"><span style="font-size:x-small;"><i class="fa fa-clock-o "></i> {{{article_creation_date_filtered}}}</span></div>
</div>',
  'preserved_content' => '',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'embed_widgets',
    1 => 'parameters',
    2 => 'savings',
  ),
  'savedwidget_id' => '',
  'pagewidget_id_switch' => '',
  'widget_name' => '',
  'pagewidget_id' => '1575534201-0-174',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Article_ShowAll',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1800',
    2 => '1500',
    3 => '1',
    4 => '1',
    5 => '6',
    6 => '1',
  ),
  'pagination[0]' => '1',
  'cover_photo_width' => '1800',
  'cover_photo_height' => '1500',
  'skip_ariticles_without_cover_photo[0]' => '1',
  'add_a_new_post[0]' => '1',
  'no_of_post_to_show[0]' => '6',
  'show_post_by_me[0]' => '1',
) );

							}
							else
							{
								
$_1f640bacaa91af5b88cd27ebb94625c0 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_d9c85b7ec581d502ad8d1e97ae5d62fb = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div style="">Email: {{{email}}}</div>

<div style="">Username: {{{username}}}</div>


',
  'preserved_content' => '<div style="">Email: {{{email}}}</div>

<div style="">Username: {{{username}}}</div>


',
  'url_prefix' => '',
  'pagewidget_id' => '1575534202-0-175',
  'markup_template_object_name' => 
  array (
    0 => 'Ayoola_Access_Dashboard',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'wrapper_name' => 'dark',
  'insert_id' => '1566242675-0-13',
  '' => '',
) );

							}
							else
							{
								
$_d9c85b7ec581d502ad8d1e97ae5d62fb = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_85e1debfaf199a689ecda4fc12a7f75d = new Ayoola_Menu( array (
  'option' => 'MyAccount',
  'template_name' => 'WhiteSidebarMenu',
  'pagewidget_id' => '1575534202-0-176',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'insert_id' => '1566242675-0-14',
  '' => '',
) );

							}
							else
							{
								
$_85e1debfaf199a689ecda4fc12a7f75d = null;

							}
							