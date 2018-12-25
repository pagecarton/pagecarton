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
* @version $Id: pc-admin.php	Tuesday 25th of December 2018 08:58:59 AM	ayoola@ayoo.la $ 
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
  '' => '',
) );

								}
								else
								{
									
$_fdb452d3ce6343c66d26aed2671ec2d9 = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_76e7e3da660c3d8115385434d8c36c76 = new Ayoola_Object_Embed( array (
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
								
$_76e7e3da660c3d8115385434d8c36c76 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_848972eb36aa04b62e7b6ec01bae0040 = new Ayoola_Menu( array (
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
								
$_848972eb36aa04b62e7b6ec01bae0040 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_d9e5cb31e5f2858952223a83bcfbe5b8 = new Ayoola_Page_Editor_Text( array (
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
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
  'wrapper_name' => 'white-well',
) );

							}
							else
							{
								
$_d9e5cb31e5f2858952223a83bcfbe5b8 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_81887aceed5b28012e715c3090815557 = new Ayoola_Page_Editor_Text( array (
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
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
) );

							}
							else
							{
								
$_81887aceed5b28012e715c3090815557 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_2b0ebdfee9b736d01f4e00b5169f0eb1 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section data-pc-section-created="1" id="footer"><p>© 2018 <a href="http://www.PageCarton.com">PageCarton</a> CMS. All Rights Reserved | Theme by <a href="https://w3layouts.com/" target="_blank">w3layouts</a></p></section>',
  'preserved_content' => '<section data-pc-section-created="1" id="footer"><p>© 2018 <a href="http://www.PageCarton.com">PageCarton</a> CMS. All Rights Reserved | Theme by <a href="https://w3layouts.com/" target="_blank">w3layouts</a></p></section>',
  'url_prefix' => '/x',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_2b0ebdfee9b736d01f4e00b5169f0eb1 = null;

							}
							