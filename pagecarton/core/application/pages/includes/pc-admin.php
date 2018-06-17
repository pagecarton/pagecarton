<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /pc-admin
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: pc-admin.php	Sunday 17th of June 2018 12:20:37 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_0b9ac26e3cbcd36692cd5b2ccadf626e = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Breadcrumb',
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
								
$_0b9ac26e3cbcd36692cd5b2ccadf626e = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_28742e28f5492c7b1e886e903e64c3c3 = new Ayoola_Object_Embed( array (
  'editable' => 'Ayoola_Object_Play',
  'advanced_parameter_value' => 
  array (
    0 => '1',
  ),
  'wrapper_name' => '',
  'silent_when_object_not_found' => '1',
) );

							}
							else
							{
								
$_28742e28f5492c7b1e886e903e64c3c3 = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
								{
									
$_fdb452d3ce6343c66d26aed2671ec2d9 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Upgrade_Check',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'object_access_level' => 
  array (
    0 => '99',
  ),
  'wrapper_name' => '',
  '' => '',
) );

								}
								else
								{
									
$_fdb452d3ce6343c66d26aed2671ec2d9 = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_0f5b4fd608ed8ec14b958db3df760319 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div class="col-md-12 stats-info widget" style="width: 100%;">
<div class="stats-title">
<h4 class="title">Dashboard</h4>
</div>

<div class="stats-body">
<ul class="list-unstyled">
	<li>Home Page<a href="http://{{{homepage}}}" target="_blank"><span class="pull-right">{{{homepage}}}</span></a></li>
	<li>Last Back Up<span class="pull-right">{{{last_backup}}}</span></li>
</ul>
</div>
</div>
',
  'preserved_content' => '<div class="col-md-12 stats-info widget" style="width: 100%;">
<div class="stats-title">
<h4 class="title">Dashboard</h4>
</div>

<div class="stats-body">
<ul class="list-unstyled">
	<li>Home Page<a href="http://{{{homepage}}}" target="_blank"><span class="pull-right">{{{homepage}}}</span></a></li>
	<li>Last Back Up<span class="pull-right">{{{last_backup}}}</span></li>
</ul>
</div>
</div>
',
  'url_prefix' => '/x',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Info',
  ),
  'phrase_to_replace' => '',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
  'wrapper_name' => 'white-background',
) );

							}
							else
							{
								
$_0f5b4fd608ed8ec14b958db3df760319 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_e0b062b9ea3e343d6f92c3b1e96f4f7f = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section id="row-one" data-pc-section-created="1">
    <div class="row-one">
        <div class="col-md-4 widget">
            <div class="stats-left ">
                <h5>USER</h5>
                <h4>Accounts</h4>
            </div>
            <div class="stats-right"> <label>{{{user_count}}}</label> </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-4 widget states-mdl">
            <div class="stats-left">
                <h5>ALL</h5>
                <h4>Posts</h4>
            </div>
            <div class="stats-right"> <label>{{{post_count}}}</label> </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-4 widget states-last">
            <div class="stats-left">
                <h5>MY</h5>
                <h4>Pages</h4>
            </div>
            <div class="stats-right"> <label>{{{page_count}}}</label> </div>
            <div class="clearfix"> </div>
        </div>
        <div class="clearfix"> </div>
    </div>
</section>',
  'preserved_content' => '<section id="row-one" data-pc-section-created="1">
    <div class="row-one">
        <div class="col-md-4 widget">
            <div class="stats-left ">
                <h5>USER</h5>
                <h4>Accounts</h4>
            </div>
            <div class="stats-right"> <label>{{{user_count}}}</label> </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-4 widget states-mdl">
            <div class="stats-left">
                <h5>ALL</h5>
                <h4>Posts</h4>
            </div>
            <div class="stats-right"> <label>{{{post_count}}}</label> </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-4 widget states-last">
            <div class="stats-left">
                <h5>MY</h5>
                <h4>Pages</h4>
            </div>
            <div class="stats-right"> <label>{{{page_count}}}</label> </div>
            <div class="clearfix"> </div>
        </div>
        <div class="clearfix"> </div>
    </div>
</section>',
  'url_prefix' => '/x',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Info',
  ),
  'phrase_to_replace' => '',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
  'wrapper_name' => '',
) );

							}
							else
							{
								
$_e0b062b9ea3e343d6f92c3b1e96f4f7f = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_d9e5cb31e5f2858952223a83bcfbe5b8 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<style>
.logo a h1 {
    color: transparent;
    background-image: url("/x/img/logo.png");
    background-position: center;
    background-size: contain;
    background-repeat: no-repeat;
}
.logo {
    background: #4d4d4d;
}
.cbp-spmenu-vertical {
    background-color: #333;
}

</style>',
  'preserved_content' => '<style>
.logo a h1 {
    color: transparent;
    background-image: url("/x/img/logo.png");
    background-position: center;
    background-size: contain;
    background-repeat: no-repeat;
}
.logo {
    background: #4d4d4d;
}
.cbp-spmenu-vertical {
    background-color: #333;
}

</style>',
  'url_prefix' => '/x',
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
								
$_d9e5cb31e5f2858952223a83bcfbe5b8 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_720f96276816b09f68dd1cd2e539a0e9 = new Ayoola_Menu( array (
  'option' => 'admin',
  'template_name' => 'HorizontalGrayish',
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
								
$_720f96276816b09f68dd1cd2e539a0e9 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_eced507511dddb1b6d463ea065ac2612 = new Ayoola_Page_Editor_Text( array (
  'editable' => 'Contact Messages',
  'preserved_content' => 'Contact Messages',
  'url_prefix' => '/x',
  'phrase_to_replace' => '',
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
								
$_eced507511dddb1b6d463ea065ac2612 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_8b4bcbe8c750690add73ab389ca51d39 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_ContactUs_ShowAll',
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
								
$_8b4bcbe8c750690add73ab389ca51d39 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_3ea5ad3554fbc3238febe0eefe722618 = new Ayoola_Page_Editor_Text( array (
  'editable' => 'Logo',
  'preserved_content' => 'Logo',
  'url_prefix' => '/x',
  'phrase_to_replace' => '',
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
								
$_3ea5ad3554fbc3238febe0eefe722618 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_3fc2d203781b1098e2de279003b69c46 = new Ayoola_Object_Embed( array (
  'editable' => 'Ayoola_Doc_Upload_Link',
  'advanced_parameter_value' => 
  array (
    0 => '/img/logo.png',
    1 => '1',
  ),
  'wrapper_name' => 'white-background',
  'suggested_url' => '/img/logo.png',
  'ignore_width_and_height' => '1',
) );

							}
							else
							{
								
$_3fc2d203781b1098e2de279003b69c46 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_781f6bd596c645809bd82b98b1a6a126 = new Ayoola_Page_Editor_Text( array (
  'editable' => 'Favicon',
  'preserved_content' => 'Favicon',
  'url_prefix' => '/x',
  'phrase_to_replace' => '',
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
								
$_781f6bd596c645809bd82b98b1a6a126 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_81d87806f4fa99873ca6c47e319ca9e5 = new Ayoola_Object_Embed( array (
  'editable' => 'Ayoola_Doc_Upload_Link',
  'advanced_parameter_value' => 
  array (
    0 => '/favicon.ico',
    1 => '1',
  ),
  'wrapper_name' => 'white-background',
  'suggested_url' => '/favicon.ico',
  'ignore_width_and_height' => '1',
) );

							}
							else
							{
								
$_81d87806f4fa99873ca6c47e319ca9e5 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_2b0ebdfee9b736d01f4e00b5169f0eb1 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section data-pc-section-created="1" id="footer"><p>© 2018 <a href="http://www.PageCarton.com">PageCarton</a> CMS. All Rights Reserved | Theme by <a href="https://w3layouts.com/" target="_blank">w3layouts</a></p></section>',
  'preserved_content' => '<section data-pc-section-created="1" id="footer"><p>© 2018 <a href="http://www.PageCarton.com">PageCarton</a> CMS. All Rights Reserved | Theme by <a href="https://w3layouts.com/" target="_blank">w3layouts</a></p></section>',
  'url_prefix' => '/x',
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
								
$_2b0ebdfee9b736d01f4e00b5169f0eb1 = null;

							}
							