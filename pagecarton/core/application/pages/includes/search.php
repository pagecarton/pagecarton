<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /search
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: search.php	Monday 12th of August 2019 12:29:17 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_e90c57a46504076dd6f4c11db23dd9c0 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div class="container">
<p><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span>&nbsp;</p>

<p>You searched for</p>

<h1>{{{q}}}</h1>

<div>&nbsp;</div>

<div>&nbsp;Here are the top results:<span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span></div><div><br></div>
</div>',
  'preserved_content' => '<div class="container">
<p><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span>&nbsp;</p>

<p>You searched for</p>

<h1>{{{q}}}</h1>

<div>&nbsp;</div>

<div>&nbsp;Here are the top results:<span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span></div><div><br></div>
</div>',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'preserve_content',
    1 => 'embed_widgets',
    2 => 'wrappers',
    3 => 'parameters',
  ),
  'pagewidget_id' => '1565528051-0-4',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Global',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => 'q',
  ),
  'wrapper_name' => 'dark',
  'include_request[0]' => '1',
  'required_template_variables[0]' => 'q',
  'widget_name' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You searched for {{{q}}} &nbsp; &nbsp;Here are the top results:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
) );

							}
							else
							{
								
$_e90c57a46504076dd6f4c11db23dd9c0 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_5fcc604b55cca1d4c9405badf0bb4939 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_SearchBox',
  'pagewidget_id' => '1564094539-0-58',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
  'widget_name' => 'Application_SearchBox',
) );

							}
							else
							{
								
$_5fcc604b55cca1d4c9405badf0bb4939 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_cf38cfcaaadf4c77fd272763a75d99f0 = new Ayoola_Page_Editor_Text( array (
  'codes' => '<p><a href="{{{post_link}}}"><img alt="" src="{{{document_url_uri}}}?width=100&amp;height=100" style=" border-radius: 50%; float:right;"></a></p>

<h2><a href="{{{post_link}}}">{{{article_title}}}</a></h2>

<p>{{{article_description}}}</p>

<i>{{{post_type}}}</i>

<div style="clear:both;">&nbsp;</div>

<hr>
<p>&nbsp;</p>
',
  'preserved_content' => '',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'embed_widgets',
    1 => 'parameters',
  ),
  'pagewidget_id' => '',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Article_ShowAll',
  ),
  'advanced_parameter_value' => 
  array (
    0 => 'keyword',
    1 => 'pc_search_score',
    2 => '1',
    3 => '1',
    4 => '12',
  ),
  'search_mode[0]' => 'keyword',
  'sort_column[0]' => 'pc_search_score',
  'inverse_order[0]' => '1',
  'pagination' => '1',
  'no_of_post_to_show[0]' => '12',
  'widget_name' => '{{{article_title}}} {{{article_description}}} {{{post_type}}} &nbsp; &nbsp;',
  'insert_id' => '1565569757-0-11',
) );

							}
							else
							{
								
$_cf38cfcaaadf4c77fd272763a75d99f0 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_b32defe207938e4e6b3ceb85189d7562 = new Ayoola_Page_Editor_Text( array (
  'codes' => '<h3>Recent Searches</h3>
<ul class="pc_same_height_container">
<!--{{{0}}}
<li><a href="/search?q={{{query}}}">{{{query}}}</a></li>
{{{0}}}-->
</ul>	',
  'preserved_content' => '			
			<h3>Lorem Ipsum dolor</h3>
			<p>Vivamus sit amet dolor sit amet nunc maximus finibus. Donec vel ornare leo, eget gravida orci. Etiam vitae rutrum nisi. Mauris auctor velit et ultricies mollis. Donec in mattis lectus. In hac habitasse platea dictumst. Sed ultricies magna ut ligula fringilla facilisis. Ut sodales erat ut libero rhoncus hendrerit. Vivamus nunc magna, finibus vel velit in, tempus venenatis dolor. Aenean a leo non tellus semper ultricies eget quis enim.</p>
			',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'embed_widgets',
  ),
  'pagewidget_id' => '',
  'markup_template_object_name' => 
  array (
    0 => 'Application_SearchBox_Table',
  ),
  'widget_name' => 'Lorem Ipsum dolor Vivamus sit amet dolor sit amet nunc maximus finibus. Donec vel ornare leo eget gravida orci. Etiam vitae rutrum nisi. Mauris auctor velit et ultricies mollis. Donec in mattis lectus. In hac habitasse platea dictumst. Sed ultricies magna ut ligula fringilla facilisis. Ut sodales erat ut libero rhoncus hendrerit. Vivamus nunc magna finibus vel velit in tempus venenatis dolor. Aenean a leo non tellus semper ultricies eget quis enim.',
) );

							}
							else
							{
								
$_b32defe207938e4e6b3ceb85189d7562 = null;

							}
							