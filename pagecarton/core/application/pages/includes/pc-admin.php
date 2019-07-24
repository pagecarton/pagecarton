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
* @version $Id: pc-admin.php	Wednesday 24th of July 2019 02:05:10 PM	ayoola@ayoo.la $ 
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
  'insert_id' => '1563976050-0-7',
  'pagewidget_id' => '1563976050-0-7',
  '' => '',
  'widget_name' => 'A p p l i c a t i o n _ B r e a d c r u m b',
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
  'insert_id' => '1563976050-0-8',
  'pagewidget_id' => '1563976050-0-8',
  'silent_when_object_not_found' => '1',
  'widget_name' => 'A y o o l a _ O b j e c t _ P l a y',
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
  'insert_id' => '1563976050-0-9',
  'pagewidget_id' => '1563976050-0-9',
  '' => '',
  'widget_name' => 'A p p l i c a t i o n _ U p g r a d e _ C h e c k',
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
  'insert_id' => '1563976050-0-10',
  'pagewidget_id' => '1563976050-0-10',
  'wrap_widget' => '1',
  'hide_if_stages_completed' => '1',
  'widget_name' => 'P a g e C a r t o n _ N e w S i t e W i z a r d',
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
  'insert_id' => '1563976050-0-11',
  'pagewidget_id' => '1563976050-0-11',
  '' => '',
  'widget_name' => 'a d m i n  -  H o r i z o n t a l W h i t e  -  A r r a y  -  1 5 6 3 9 7 6 0 5 0 - 0 - 1 1  -  1 5 6 3 9 7 6 0 5 0 - 0 - 1 1  -',
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
  'insert_id' => '1563976050-0-12',
  'pagewidget_id' => '1563976050-0-12',
  'widget_name' => 'D a s h b o a r d  H o m e  P a g e { { { h o m e p a g e } } }  L a s t  B a c k  U p { { { l a s t _ b a c k u p } } }',
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
  'insert_id' => '1563976050-0-13',
  'pagewidget_id' => '1563976050-0-13',
  'widget_name' => 'U S E R  A c c o u n t s  { { { u s e r _ c o u n t } } }  A L L  P o s t s  { { { p o s t _ c o u n t } } }  M Y  P a g e s  { { { p a g e _ c o u n t } } }',
) );

							}
							else
							{
								
$_4e8fd3c1ce6442243ee625dc127bf58a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_8f94b54f70cde9623d9cc279007489dd = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section data-pc-section-created="1" id="footer"><p>Â© 2019 <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br></p></section>',
  'preserved_content' => '<section data-pc-section-created="1" id="footer"><p>Â© 2019 <a href="http://www.PageCarton.com">PageCarton</a>. All Rights Reserved.<br></p></section>',
  'url_prefix' => '',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  '' => '',
  'widget_name' => 'Â ©  2 0 1 9  P a g e C a r t o n .  A l l  R i g h t s  R e s e r v e d .',
) );

							}
							else
							{
								
$_8f94b54f70cde9623d9cc279007489dd = null;

							}
							