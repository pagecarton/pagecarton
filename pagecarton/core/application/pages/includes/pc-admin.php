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
* @version $Id: pc-admin.php	Saturday 30th of November 2019 12:06:41 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_183a467e23cc670297e6c5bd0c5c0829 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Breadcrumb',
  'pagewidget_id' => '1569854139-0-5',
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
								
$_183a467e23cc670297e6c5bd0c5c0829 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_dd6c65cbd2cd676c37a9abfde6efd317 = new Ayoola_Object_Embed( array (
  'editable' => 'Ayoola_Object_Play',
  'pagewidget_id' => '1569854139-0-6',
  'advanced_parameter_value' => 
  array (
    0 => '1',
    1 => '1575068648-0-1',
  ),
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
			
			<h3><br></h3><div><br></div>
			',
  'preserved_content' => '			
			<h3><br></h3><div><br></div>
			',
  'url_prefix' => '',
  'pagewidget_id' => '1569854139-0-7',
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
  'pagewidget_id' => '1569854139-0-8',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'object_access_level' => 
  array (
    0 => '99',
  ),
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
  'pagewidget_id' => '1569854139-0-9',
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
  'pagewidget_id' => '1569854139-0-10',
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
  'url_prefix' => '',
  'pagewidget_id' => '1569854140-0-11',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Info',
  ),
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
  'wrapper_name' => 'white-well',
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
  'pagewidget_id' => '1569854140-0-12',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Info',
  ),
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
) );

							}
							else
							{
								
$_6e0c10910d5fbb6ad4e9ead328450193 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_8f94b54f70cde9623d9cc279007489dd = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section data-pc-section-created="1" id="footer"><p>© 2019 <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br></p></section>',
  'preserved_content' => '<section data-pc-section-created="1" id="footer"><p>© 2019 <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br></p></section>',
  'url_prefix' => '',
  'pagewidget_id' => '1569854140-0-13',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_8f94b54f70cde9623d9cc279007489dd = null;

							}
							