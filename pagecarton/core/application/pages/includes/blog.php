<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /blog
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: blog.php	Friday 17th of June 2022 12:50:21 PM	projects@comeriver.com $ 
*/
//	Page Include Content

							
$_56f2567a47ba97cf2adc43c06443ff27 = null;

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_56f2567a47ba97cf2adc43c06443ff27 = new Ayoola_Page_Editor_Text( array (
  'codes' => '<a href="{{{post_link}}}"><img class="pc_give_space_top_bottom" style="width:100%;" alt="" src="{{{document_url_cropped}}}"></a>

<div class="container pc_container">
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
  ),
  'pagewidget_id' => '1575534213-0-21',
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
  ),
  'insert_id' => '1566242672-0-17',
  'pagination[0]' => '1',
  'cover_photo_width' => '1800',
  'cover_photo_height' => '1500',
  'skip_ariticles_without_cover_photo[0]' => '1',
  'add_a_new_post[0]' => '1',
  'no_of_post_to_show[0]' => '6',
  'includes' => 
  array (
  ),
  'content' => '<a href="{{{post_link}}}"><img class="pc_give_space_top_bottom" style="width:100%;" alt="" src="{{{document_url_cropped}}}"></a>

<div class="container pc_container">
    <h3 class="pc_give_space_top_bottom"><a href="{{{post_link}}}">{{{article_title}}}</a></h3>
    <p class="pc_give_space_top_bottom"><a target="_blank" class="a2a_dd btn btn-default" addthis:title="{{{article_title}}}" addthis:description="{{{article_description}}}" addthis:url="{{{post_full_url}}}" href="https://www.addtoany.com/share?url={{{post_full_url}}}&amp;title={{{post_type}}} - {{{article_title}}}">
<span style="font-size:small;"> Share </span> <i class="fa fa-facebook"></i> <i class="fa fa-twitter"></i> <i class="fa fa-whatsapp"></i> <i class="fa fa-share-alt"></i> </a></p>
    <p style="font-size:small;" class="pc_give_space_top_bottom"><a href="/music/posts/{{{post_type_id}}}">{{{post_type}}}</a> by <a href="/music/{{{profile_url}}}">{{{display_name}}}</a> in {{{category_text}}}</p>
       
    <em class="pc_give_space_top_bottom">{{{article_description}}}</em>
    <div class="pc_give_space_top_bottom">{{{article_content}}}</div>
    <div class=""><a href="{{{post_link}}}">View <i class="fa fa-eye pc_give_space"></i> {{{views_count_total}}}   <i class="fa fa-comment pc_give_space"></i> {{{comments_count_total}}} </a></div>
    <div class="pc_give_space_top_bottom"><span style="font-size:x-small;"><i class="fa fa-clock-o "></i> {{{article_creation_date_filtered}}}</span></div>
</div>',
) );

							}
							