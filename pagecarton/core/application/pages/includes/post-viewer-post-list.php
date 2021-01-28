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
* @version $Id: post-viewer-post-list.php	Thursday 28th of January 2021 02:07:49 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_df31865e69a975a3db920b03c85eeabb = new Ayoola_Page_Editor_Text( array (
  'codes' => '<widget parameters=\'{ "class": "Application_Article_View" }\'>
<h2 class="pc_give_space_top_bottom">{{{article_title}}}</h2>
<p>{{{article_description}}}</p>
</widget>',
  'preserved_content' => '',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611783911-6',
  'content' => '<widget parameters=\'{ "class": "Application_Article_View" }\'>
<h2 class="pc_give_space_top_bottom">{{{article_title}}}</h2>
<p>{{{article_description}}}</p>
</widget>',
) );

							}
							else
							{
								
$_df31865e69a975a3db920b03c85eeabb = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_0c09c67ba3a7fb201e3e77716069f24a = new Ayoola_Page_Editor_Text( array (
  'codes' => '<widget parameters=\'{ "class": "Application_Article_PostList", "no_of_post_to_show": 100 }\'>
    <ol>
    <repeat>
        <li><a href="{{{article_url}}}"><strong>{{{article_title}}}</strong></a>
<br> {{{article_description}}}<br>
</li>
    </repeat>
    </ol>
</widget>
<widget parameters=\'{ "class": "Application_Article_View" }\'>
<a href="/widgets/Application_Article_Editor?article_url={{{article_url}}}" class="btn btn-primary pc_give_space_top_bottom">Update from list</a>
<a href="/widgets/Application_Article_PostList_Sort?article_url={{{article_url}}}" class="btn btn-default pc_give_space_top_bottom">Sort List</a>
</widget>',
  'preserved_content' => '',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'parameters',
  ),
  'pagewidget_id' => '0-1611794526-16',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
  'content' => '<widget parameters=\'{ "class": "Application_Article_PostList", "no_of_post_to_show": 100 }\'>
    <ol>
    <repeat>
        <li><a href="{{{article_url}}}"><strong>{{{article_title}}}</strong></a>
<br> {{{article_description}}}<br>
</li>
    </repeat>
    </ol>
</widget>
<widget parameters=\'{ "class": "Application_Article_View" }\'>
<a href="/widgets/Application_Article_Editor?article_url={{{article_url}}}" class="btn btn-primary pc_give_space_top_bottom">Update from list</a>
<a href="/widgets/Application_Article_PostList_Sort?article_url={{{article_url}}}" class="btn btn-default pc_give_space_top_bottom">Sort List</a>
</widget>',
) );

							}
							else
							{
								
$_0c09c67ba3a7fb201e3e77716069f24a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_dcb2368f994d871d30b09185f61350e2 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div><br></div><div><br></div>',
  'preserved_content' => '<div><br></div><div><br></div>',
  'url_prefix' => '',
  'pagewidget_id' => '0-1611791946-9',
  'content' => '<div><br></div><div><br></div>',
) );

							}
							else
							{
								
$_dcb2368f994d871d30b09185f61350e2 = null;

							}
							