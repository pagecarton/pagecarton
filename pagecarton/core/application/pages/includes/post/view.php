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
* @version $Id: view.php	Wednesday 10th of April 2019 01:43:54 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_3fa21d2b3d9c77ddfd69616a823104e6 = new Ayoola_Page_Editor_Text( array (
  'codes' => '<p><img src="{{{document_url}}}" alt="Image" style="width:100%"></p>
<div class="container">
<br>
<h3>{{{article_title}}}</h3>
<br>
<p>{{{article_description}}}</p>
<br>
</div>
',
  'preserved_content' => '<p><img src="{{{document_url}}}" alt="Image" style="width:100%"></p>
<p>&nbsp;</p>
<div style="container">
<h3>{{{article_title}}}</h3>


<p>{{{article_description}}}</p>

<p>&nbsp;</p>
</div>
',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'preserve_content',
    1 => 'embed_widgets',
    2 => 'parameters',
  ),
  'markup_template_object_name' => 
  array (
    0 => 'Application_Article_View',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_3fa21d2b3d9c77ddfd69616a823104e6 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_ccfcd65b00854efe614caba97537085f = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Article_View',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
    2 => '1',
    3 => '1',
    4 => '1',
  ),
  'get_views_count' => '1',
  'get_audio_play_count' => '1',
  'get_download_count' => '1',
  'thumbnail' => '1',
  'hide_default_post_view' => '1',
) );

							}
							else
							{
								
$_ccfcd65b00854efe614caba97537085f = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Article_ShowAll' ) )
							{
								
$_43d921b8828bd2347640dfc69302d1e0 = new Application_Article_ShowAll( array (
  'option' => '6',
  'category_name' => '',
  'article_types' => '',
  'template_name' => 'ProductsforSale',
  'widget_options' => 
  array (
    0 => 'wrappers',
    1 => 'parameters',
  ),
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => 'container',
  ),
  'wrapper_name' => 'well',
  'post_with_same_true_post_type' => '1',
  'wrapper_inner_class' => 'container',
) );

							}
							else
							{
								
$_43d921b8828bd2347640dfc69302d1e0 = null;

							}
							