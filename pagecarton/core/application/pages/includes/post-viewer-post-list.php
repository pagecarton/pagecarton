<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /post-viewer-post-list
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: post-viewer-post-list.php	Saturday 30th of January 2021 08:39:05 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_7e8536ad0889aa87ff44bb6c8e34cf6b = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div><br></div><div><br></div>',
  'preserved_content' => '<div><br></div><div><br></div>',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611800230-7',
  'content' => '<div><br></div><div><br></div>',
) );

							}
							else
							{
								
$_7e8536ad0889aa87ff44bb6c8e34cf6b = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_9ed35f44f7b66165630d635239200a26 = new Ayoola_Page_Editor_Text( array (
  'codes' => '<widget parameters=\'{ "class": "Application_Article_View" }\'>
<h2 class="pc_give_space_top_bottom">{{{article_title}}}</h2>
<p>{{{article_description}}}</p>
</widget>',
  'preserved_content' => '',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611800063-3',
  'content' => '<widget parameters=\'{ "class": "Application_Article_View" }\'>
<h2 class="pc_give_space_top_bottom">{{{article_title}}}</h2>
<p>{{{article_description}}}</p>
</widget>',
) );

							}
							else
							{
								
$_9ed35f44f7b66165630d635239200a26 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_a241e31eedca3550957404a8509cd91d = new Ayoola_Page_Editor_Text( array (
  'codes' => '<widget parameters=\'{ "class": "Application_Article_PostList", "no_of_post_to_show": 100 }\'>
    <ol>
    <repeat>
        <li><a href="{{{article_url}}}"><strong>{{{article_title}}}</strong></a></li>
    </repeat>
    </ol>
</widget>

<widget parameters=\'{ "class": "Application_Article_View" }\'>

<a href="javascript:"  onClick="ayoola.spotLight.showLinkInIFrame(\'/tools/classplayer/get/name/Application_Article_Creator?article_type={{{list_post_type}}}&post_list={{{article_url}}}\', \'page_refresh\');"  class="btn btn-success pc_give_space_top_bottom">Add new item</a>

<a href="/widgets/Application_Article_Creator?article_type={{{article_type}}}"  class="btn btn-default pc_give_space_top_bottom">Create a new List</a>

</widget>
<widget parameters=\'{ "class": "Application_Article_View", "show_to_editors_only": true }\'>
<a href="/widgets/Application_Article_PostList_Sort?article_url={{{article_url}}}" class="btn btn-default pc_give_space_top_bottom">Sort List</a>
<a href="/widgets/Application_Article_Editor?article_url={{{article_url}}}" class="btn btn-default pc_give_space_top_bottom">Update list</a>
</widget>',
  'preserved_content' => '',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'parameters',
  ),
  'pagewidget_id' => '0-1611846602-12',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
  'content' => '<widget parameters=\'{ "class": "Application_Article_PostList", "no_of_post_to_show": 100 }\'>
    <ol>
    <repeat>
        <li><a href="{{{article_url}}}"><strong>{{{article_title}}}</strong></a></li>
    </repeat>
    </ol>
</widget>

<widget parameters=\'{ "class": "Application_Article_View" }\'>

<a href="javascript:"  onClick="ayoola.spotLight.showLinkInIFrame(\'/tools/classplayer/get/name/Application_Article_Creator?article_type={{{list_post_type}}}&post_list={{{article_url}}}\', \'page_refresh\');"  class="btn btn-success pc_give_space_top_bottom">Add new item</a>

<a href="/widgets/Application_Article_Creator?article_type={{{article_type}}}"  class="btn btn-default pc_give_space_top_bottom">Create a new List</a>

</widget>
<widget parameters=\'{ "class": "Application_Article_View", "show_to_editors_only": true }\'>
<a href="/widgets/Application_Article_PostList_Sort?article_url={{{article_url}}}" class="btn btn-default pc_give_space_top_bottom">Sort List</a>
<a href="/widgets/Application_Article_Editor?article_url={{{article_url}}}" class="btn btn-default pc_give_space_top_bottom">Update list</a>
</widget>',
) );

							}
							else
							{
								
$_a241e31eedca3550957404a8509cd91d = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_82d3cc274801f7b6e361e93e8a37257a = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div><br></div><div><br></div>',
  'preserved_content' => '<div><br></div><div><br></div>',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611800230-7',
  'content' => '<div><br></div><div><br></div>',
) );

							}
							else
							{
								
$_82d3cc274801f7b6e361e93e8a37257a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_febabc8c2d69bc7e0b7e363a7c4cdeb2 = new Ayoola_Page_Editor_Text( array (
  'codes' => '<widget parameters=\'{ "class": "Application_Article_View", "hide_default_post_view": true }\'></widget>',
  'preserved_content' => '',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611865178-13',
  'content' => '<widget parameters=\'{ "class": "Application_Article_View", "hide_default_post_view": true }\'></widget>',
) );

							}
							else
							{
								
$_febabc8c2d69bc7e0b7e363a7c4cdeb2 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_a34eedd518cb43f2e2d58a4405212700 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div><br></div><div><br></div>',
  'preserved_content' => '<div><br></div><div><br></div>',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611800230-7',
  'content' => '<div><br></div><div><br></div>',
) );

							}
							else
							{
								
$_a34eedd518cb43f2e2d58a4405212700 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_9b41e83eb7b024f6a2582f59a33f3962 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_CommentBox_ShowComments',
  'pagewidget_id' => '0-1611800059-2',
) );

							}
							else
							{
								
$_9b41e83eb7b024f6a2582f59a33f3962 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_7b4898fcd80ad44c53f6e1b4031da6b9 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_CommentBox',
  'pagewidget_id' => '0-1611800024-1',
) );

							}
							else
							{
								
$_7b4898fcd80ad44c53f6e1b4031da6b9 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_0cf304b273ecb75409095cc554bc9f4e = new Ayoola_Page_Editor_Text( array (
  'codes' => '<widget parameters=\'{ "class": "Application_Article_View" }\'>
<br>
<p>Posts similar to {{{post_type}}}</p>
<br>
</widget>',
  'preserved_content' => '',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'wrappers',
  ),
  'pagewidget_id' => '0-1611800769-9',
  'wrapper_name' => 'well',
  'content' => '<widget parameters=\'{ "class": "Application_Article_View" }\'>
<br>
<p>Posts similar to {{{post_type}}}</p>
<br>
</widget>',
) );

							}
							else
							{
								
$_0cf304b273ecb75409095cc554bc9f4e = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_219e73c5d3d5910ae8aa4ae07f999e81 = new Ayoola_Page_Editor_Text( array (
  'codes' => '<widget parameters=\'{ "class": "Application_Article_View" }\'>
<img src="{{{document_url_cropped}}}" alt="{{{article_title}}} cover photo">
</widget>',
  'preserved_content' => '',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611801048-10',
  'content' => '<widget parameters=\'{ "class": "Application_Article_View" }\'>
<img src="{{{document_url_cropped}}}" alt="{{{article_title}}} cover photo">
</widget>',
) );

							}
							else
							{
								
$_219e73c5d3d5910ae8aa4ae07f999e81 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_7c8f52184be0a804cde8bd952a16a788 = new Application_Article_ShowAll( array (
  'option' => '5',
  'category_name' => '',
  'article_types' => '',
  'template_name' => 'SearchStyle',
  'widget_options' => 
  array (
    0 => 'wrappers',
    1 => 'parameters',
  ),
  'pagewidget_id' => '0-1611800089-6',
  'advanced_parameter_value' => 
  array (
    0 => '1',
  ),
  'wrapper_name' => 'white-well',
  'post_with_same_article_type' => '1',
) );

							}
							else
							{
								
$_7c8f52184be0a804cde8bd952a16a788 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_dcb2368f994d871d30b09185f61350e2 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div><br></div><div><br></div>',
  'preserved_content' => '<div><br></div><div><br></div>',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611800063-5',
  'content' => '<div><br></div><div><br></div>',
) );

							}
							else
							{
								
$_dcb2368f994d871d30b09185f61350e2 = null;

							}
							