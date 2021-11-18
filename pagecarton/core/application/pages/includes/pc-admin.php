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
* @version $Id: pc-admin.php	Thursday 18th of November 2021 07:25:57 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_183a467e23cc670297e6c5bd0c5c0829 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Breadcrumb',
  'widget_options' => 
  array (
    0 => 'privacy',
    1 => 'savings',
  ),
  'pagewidget_id_switch' => '',
  'widget_name' => 'Breadcrumb',
  'pagewidget_id' => '1575286110-0-153',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_183a467e23cc670297e6c5bd0c5c0829 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_dd6c65cbd2cd676c37a9abfde6efd317 = new Ayoola_Object_Embed( array (
  'editable' => 'Ayoola_Object_Play',
  'widget_options' => 
  array (
    0 => 'wrappers',
    1 => 'parameters',
    2 => 'privacy',
  ),
  'pagewidget_id' => '1575286110-0-154',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1575068648-0-1',
  ),
  'wrapper_name' => '',
  'silent_when_object_not_found' => '1',
  'widget_id' => '1575068648-0-1',
) );

							}
							else
							{
								
$_dd6c65cbd2cd676c37a9abfde6efd317 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_a0d1dbe8cd91f74655801013c1a9fded = new Ayoola_Page_Editor_Text( array (
  'codes' => '			
			<h3><br>&nbsp;</h3><div><br>&nbsp;</div>
			',
  'preserved_content' => '			
			<h3><br>&nbsp;</h3><div><br>&nbsp;</div>
			',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'preserve_content',
  ),
  'pagewidget_id' => '1575286110-0-155',
  'includes' => 
  array (
  ),
  'content' => '			
			<h3><br>&nbsp;</h3><div><br>&nbsp;</div>
			',
) );

							}
							else
							{
								
$_a0d1dbe8cd91f74655801013c1a9fded = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
								{
									
$_812e2aa44bc048b65f0488edd3206e29 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Upgrade_Check',
  'widget_options' => 
  array (
    0 => 'wrappers',
  ),
  'pagewidget_id' => '1575286111-0-156',
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
									
$_812e2aa44bc048b65f0488edd3206e29 = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_103e5ff7f83a41a3e7e18f307a3876ea = new Ayoola_Object_Embed( array (
  'editable' => 'PageCarton_NewSiteWizard',
  'widget_options' => 
  array (
    0 => 'parameters',
  ),
  'pagewidget_id' => '1575286111-0-157',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1',
  ),
  'wrapper_name' => 'white-well',
  'wrap_widget' => '1',
  'hide_if_stages_completed' => '1',
) );

							}
							else
							{
								
$_103e5ff7f83a41a3e7e18f307a3876ea = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_720295cbdf8d3b2b8ecc0c0c37aca03a = new Ayoola_Menu( array (
  'option' => 'admin',
  'template_name' => 'HorizontalWhite',
  'widget_options' => 
  array (
    0 => 'parameters',
  ),
  'pagewidget_id' => '1575286111-0-158',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_720295cbdf8d3b2b8ecc0c0c37aca03a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_4e8fd3c1ce6442243ee625dc127bf58a = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div class="col-md-12 stats-info widget" style="width: 100%;">
<div class="stats-title">
<h4 class="title">Dashboard</h4>
</div>

<div class="stats-body">
<ul class="list-unstyled">
	<li>Home Page<a href="http://{{{homepage}}}" target="_blank"><span class="pull-right">{{{homepage}}}</span>&nbsp;</a>&nbsp;</li>
	<li>Last Back Up<span class="pull-right">{{{last_backup}}}</span>&nbsp;</li>
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
	<li>Home Page<a href="http://{{{homepage}}}" target="_blank"><span class="pull-right">{{{homepage}}}</span>&nbsp;</a>&nbsp;</li>
	<li>Last Back Up<span class="pull-right">{{{last_backup}}}</span>&nbsp;</li>
</ul>
</div>
</div>
',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'preserve_content',
    1 => 'embed_widgets',
    2 => 'wrappers',
    3 => 'parameters',
  ),
  'pagewidget_id' => '1575286111-0-159',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Info',
  ),
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
  'wrapper_name' => 'white-well',
  'includes' => 
  array (
  ),
  'content' => '<div class="col-md-12 stats-info widget" style="width: 100%;">
<div class="stats-title">
<h4 class="title">Dashboard</h4>
</div>

<div class="stats-body">
<ul class="list-unstyled">
	<li>Home Page<a href="http://{{{homepage}}}" target="_blank"><span class="pull-right">{{{homepage}}}</span>&nbsp;</a>&nbsp;</li>
	<li>Last Back Up<span class="pull-right">{{{last_backup}}}</span>&nbsp;</li>
</ul>
</div>
</div>
',
) );

							}
							else
							{
								
$_4e8fd3c1ce6442243ee625dc127bf58a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_6e0c10910d5fbb6ad4e9ead328450193 = new Ayoola_Page_Editor_Text( array (
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
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'preserve_content',
  ),
  'pagewidget_id' => '1575286111-0-160',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Info',
  ),
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
  'includes' => 
  array (
  ),
  'content' => '<section id="row-one" data-pc-section-created="1">
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
) );

							}
							else
							{
								
$_6e0c10910d5fbb6ad4e9ead328450193 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_8f94b54f70cde9623d9cc279007489dd = new Ayoola_Page_Editor_Text( array (
  'codes' => '<section data-pc-section-created="1" id="footer"><p>© <script>document.write(new Date().getFullYear());</script> <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br>&nbsp;</p>&nbsp;</section>',
  'preserved_content' => '<section data-pc-section-created="1" id="footer"><p>© <script>document.write(new Date().getFullYear());</script> <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br>&nbsp;</p>&nbsp;</section>',
  'url_prefix' => '',
  'pagewidget_id' => '1575286111-0-161',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
  'includes' => 
  array (
  ),
  'content' => '<section data-pc-section-created="1" id="footer"><p>© <script>document.write(new Date().getFullYear());</script> <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br>&nbsp;</p>&nbsp;</section>',
) );

							}
							else
							{
								
$_8f94b54f70cde9623d9cc279007489dd = null;

							}
							