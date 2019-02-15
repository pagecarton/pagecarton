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
* @version $Id: pc-admin.php	Friday 15th of February 2019 09:14:29 AM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_183a467e23cc670297e6c5bd0c5c0829 = new Ayoola_Object_Embed( array (
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
								
$_183a467e23cc670297e6c5bd0c5c0829 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_dd6c65cbd2cd676c37a9abfde6efd317 = new Ayoola_Object_Embed( array (
  'editable' => 'Ayoola_Object_Play',
  'advanced_parameter_value' => 
  array (
    0 => '1',
  ),
  'silent_when_object_not_found' => '1',
) );

							}
							else
							{
								
$_dd6c65cbd2cd676c37a9abfde6efd317 = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
								{
									
$_f02b87ccc33a26259b03001b75547ac7 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Upgrade_Check',
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
									
$_f02b87ccc33a26259b03001b75547ac7 = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_812e2aa44bc048b65f0488edd3206e29 = new Ayoola_Object_Embed( array (
  'editable' => 'PageCarton_NewSiteWizard',
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
								
$_812e2aa44bc048b65f0488edd3206e29 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_bf2a002fc6578717a36bf0f78ea87160 = new Ayoola_Menu( array (
  'option' => 'admin',
  'template_name' => 'HorizontalWhite',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_bf2a002fc6578717a36bf0f78ea87160 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_ec42b8d03cb407c2a48fb9be1a94f821 = new Ayoola_Page_Editor_Text( array (
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
								
$_ec42b8d03cb407c2a48fb9be1a94f821 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_4e8fd3c1ce6442243ee625dc127bf58a = new Ayoola_Page_Editor_Text( array (
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
								
$_4e8fd3c1ce6442243ee625dc127bf58a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_8f94b54f70cde9623d9cc279007489dd = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section data-pc-section-created="1" id="footer"><p>© 2019 <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br></p></section>',
  'preserved_content' => '<section data-pc-section-created="1" id="footer"><p>© 2019 <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br></p></section>',
  'url_prefix' => '',
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
							