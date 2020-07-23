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
* @version $Id: pc-admin.php	Saturday 14th of December 2019 04:58:47 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_0c31198ab8134b66fc7a6a729689ad1e = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Breadcrumb',
  'widget_options' => 
  array (
    0 => 'privacy',
    1 => 'savings',
  ),
  'pagewidget_id_switch' => '',
  'pagewidget_id_version' => '',
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
								
$_0c31198ab8134b66fc7a6a729689ad1e = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_26a4384ebf0e9ca07bbc60f3df847c8b = new Ayoola_Object_Embed( array (
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
								
$_26a4384ebf0e9ca07bbc60f3df847c8b = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_a5aa57c39772cebde1fff97f73dc5103 = new Ayoola_Page_Editor_Text( array (
  'codes' => '			
			<h3><br></h3><div><br></div>
			',
  'preserved_content' => '			
			<h3><br></h3><div><br></div>
			',
  'url_prefix' => '/index.php',
  'widget_options' => 
  array (
    0 => 'preserve_content',
  ),
  'pagewidget_id' => '1575286110-0-155',
) );

							}
							else
							{
								
$_a5aa57c39772cebde1fff97f73dc5103 = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
								{
									
$_7e3ed0a5d2f967efa56477075a9f3bec = new Ayoola_Object_Embed( array (
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
									
$_7e3ed0a5d2f967efa56477075a9f3bec = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_5795f2af0334c6567383585ed4bda459 = new Ayoola_Object_Embed( array (
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
								
$_5795f2af0334c6567383585ed4bda459 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_5d8b8384509636edca9a1682938343eb = new Ayoola_Menu( array (
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
								
$_5d8b8384509636edca9a1682938343eb = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_0cdc55584796284b6ab2ffa1f70e3c89 = new Ayoola_Page_Editor_Text( array (
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
  'url_prefix' => '/index.php',
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
) );

							}
							else
							{
								
$_0cdc55584796284b6ab2ffa1f70e3c89 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_097c066c3ed3dbb233d013989a9586b7 = new Ayoola_Page_Editor_Text( array (
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
  'url_prefix' => '/index.php',
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
) );

							}
							else
							{
								
$_097c066c3ed3dbb233d013989a9586b7 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_eeb3b955e392533b3850e947803473e8 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section data-pc-section-created="1" id="footer"><p>© 2019 <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br></p></section>',
  'preserved_content' => '<section data-pc-section-created="1" id="footer"><p>© 2019 <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br></p></section>',
  'url_prefix' => '/index.php',
  'pagewidget_id' => '1575286111-0-161',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
) );

							}
							else
							{
								
$_eeb3b955e392533b3850e947803473e8 = null;

							}
							