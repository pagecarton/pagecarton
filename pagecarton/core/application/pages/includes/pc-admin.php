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
* @version $Id: pc-admin.php	Friday 8th of September 2017 12:02:10 PM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_d89ddf2ba344d40a937a1d7a4510f2e6 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Breadcrumb',
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
								
$_d89ddf2ba344d40a937a1d7a4510f2e6 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_860cf6f944c2cc1ba706bdc253c8c42e = new Ayoola_Object_Embed( array (
  'editable' => '<p>Ayoola_Object_Play</p>',
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
								
$_860cf6f944c2cc1ba706bdc253c8c42e = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_00e45ea75c109ac55a184825bbb1f576 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div class="col-md-12 stats-info widget" style="width: 100%;"><div class="stats-title"><h4 class="title">pagecarton admin Dashboard</h4></div><div class="stats-body"><ul class="list-unstyled">	<li>Domain Name<span class="pull-right">{{{domain}}}</span></li>	<li>Last Back Up<span class="pull-right">{{{last_backup}}}</span></li>	<li>PageCarton Version Info<span class="pull-right">{{{pagecarton_version_info}}}</span></li>	<li>Domains<span class="pull-right">50%</span>	<div class="progress progress-striped active progress-right">	<div class="bar blue" style="width:50%;">&nbsp;</div>	</div>	</li>	<li>Back ups&nbsp;<span class="pull-right">80%</span>	<div class="progress progress-striped active progress-right">	<div class="bar light-blue" style="width:80%;">&nbsp;</div>	</div>	</li>	<li class="last">Upgrades<a href="{{{upgrade_link}}}"><span class="pull-right">Click to Upgrade</span></a></li></ul></div></div>',
  'url_prefix' => '/pagecarton/index.php',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
  'wrapper_name' => 'white-background',
  'markup_template_object_name' => 'Application_Info',
) );

							}
							else
							{
								
$_00e45ea75c109ac55a184825bbb1f576 = null;

							}
							
							if( Ayoola_Page::hasPriviledge( array (
  0 => '99',
), array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
								{
									
$_f6ca0d8c560b2c99a95473d7d33205e6 = new Ayoola_Object_Embed( array (
  'editable' => 'Application_Upgrade_Check',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'object_access_level' => 
  array (
    0 => '99',
  ),
  'wrapper_name' => 'white-background',
  '' => '',
) );

								}
								else
								{
									
$_f6ca0d8c560b2c99a95473d7d33205e6 = null;

								}
							}    
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_df0c168bb07449442fcafe5b945a3470 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section id="row-one" data-pc-section-created="1"><div class="row-one">					<div class="col-md-4 widget">						<div class="stats-left ">							<h5>total</h5>							<h4>Users</h4>						</div>						<div class="stats-right">							<label>{{{user_count}}}</label>						</div>						<div class="clearfix"> </div>						</div>					<div class="col-md-4 widget states-mdl">						<div class="stats-left">							<h5>total</h5>							<h4>Posts</h4>						</div>						<div class="stats-right">							<label>{{{post_count}}}</label>						</div>						<div class="clearfix"> </div>						</div>					<div class="col-md-4 widget states-last">						<div class="stats-left">							<h5>total</h5>							<h4>Pages</h4>						</div>						<div class="stats-right">							<label>{{{page_count}}}</label>						</div>						<div class="clearfix"> </div>						</div>					<div class="clearfix"> </div>					</div>				</section>',
  'url_prefix' => '/pagecarton/index.php',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Info',
  ),
  'wrapper_name' => 'white-background',
  'markup_template_object_name' => 'Application_Info',
) );

							}
							else
							{
								
$_df0c168bb07449442fcafe5b945a3470 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_d71f65ba79bac0155c2faf1c4ee1ec95 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<style>
.search-box
{
margin-left: 3px;
}
#page-wrapper {    padding: 3em 2em 2.5em;}

</style>',
  'url_prefix' => '/pagecarton/index.php',
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
								
$_d71f65ba79bac0155c2faf1c4ee1ec95 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_eb45f2ea98c8b1703d96c7f4a5981f2a = new Ayoola_Page_Editor_Text( array (
  'editable' => 'Contact Messages',
  'url_prefix' => '/pagecarton/index.php',
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
								
$_eb45f2ea98c8b1703d96c7f4a5981f2a = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_f4fb3c384da2bcf2f2471d74bb843c8e = new Ayoola_Object_Embed( array (
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
								
$_f4fb3c384da2bcf2f2471d74bb843c8e = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_83ccb2a5073fbe7f33ecbff23428e9c5 = new Ayoola_Page_Editor_Text( array (
  'editable' => 'Logo',
  'url_prefix' => '/pagecarton/index.php',
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
								
$_83ccb2a5073fbe7f33ecbff23428e9c5 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_4a0fa986885edbc3f15e675176feba7c = new Ayoola_Object_Embed( array (
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
								
$_4a0fa986885edbc3f15e675176feba7c = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_c7e6e423b814c5da0a3a4b87a76d823b = new Ayoola_Page_Editor_Text( array (
  'editable' => 'Favicon',
  'url_prefix' => '/pagecarton/index.php',
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
								
$_c7e6e423b814c5da0a3a4b87a76d823b = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$_f96df91e49680686e54e25ca89464c0d = new Ayoola_Object_Embed( array (
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
								
$_f96df91e49680686e54e25ca89464c0d = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_8482adf117a12e61c61531407905c96f = new Ayoola_Page_Editor_Text( array (
  'editable' => '<p style="text-align: center;">
<a class="btn btn-default" data-cke-saved-href="{{{pc_url_prefix}}}/object/name/Application_Personalization/" href="{{{pc_url_prefix}}}/object/name/Application_Personalization/" target="_blank">Personalize PageCarton Installation</a>

<a class="btn btn-default" data-cke-saved-href="{{{pc_url_prefix}}}/pc-admin/name/Application_Subscription_Checkout_Order_List/" href="{{{pc_url_prefix}}}/pc-admin/name/Application_Subscription_Checkout_Order_List/">Orders</a>
</p>',
  'url_prefix' => '/pagecarton/index.php',
  'advanced_parameter_value' => 
  array (
    0 => 'Application_Global',
  ),
  'wrapper_name' => 'well',
  'markup_template_object_name' => 'Application_Global',
) );

							}
							else
							{
								
$_8482adf117a12e61c61531407905c96f = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_4bca9db1f90962ceede8d9da4ef499c4 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<section data-pc-section-created="1" id="footer"><p>© 2017&nbsp;<a href="http://www.PageCarton.com">PageCarton</a> Admin Panel. All Rights Reserved | Design by <a href="https://w3layouts.com/" target="_blank">w3layouts</a></p></section>',
  'url_prefix' => '/pagecarton/index.php',
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
								
$_4bca9db1f90962ceede8d9da4ef499c4 = null;

							}
							