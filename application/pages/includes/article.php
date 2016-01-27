<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /article
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: article.php	Wednesday 20th of January 2016 11:01:37 AM	 $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Application_Advert_View' ) )
							{
								
$objay__header0advertView = new Application_Advert_View( array (
  'view' => 'Display Adverts',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'fc2cf8d98b85c99f4a51bf936d5017fff3373f64' => 'advanced_parameters_7536303a4e3309826968e9dfa8241e47973',
  'e3ea6a70e3b42667724b2d982e43c1b2c1354e2a' => '107374182',
  'f9e840a3655f6d9ef6ddfabffab356ead6d2156e' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__header0advertView = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Application_Category_View' ) )
							{
								
$objay__middlebar0categoryView = new Application_Category_View( array (
  'category_name' => '0',
  'allow_dynamic_category_selection' => '1',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'f6f4ce145a6dab00b194f4ef187de510e616bee1' => 'advanced_parameters_482a987d70fa73c6e39e6a9f51295461124',
  'e3ea6a70e3b42667724b2d982e43c1b2c1354e2a' => '107374182',
  'f9e840a3655f6d9ef6ddfabffab356ead6d2156e' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__middlebar0categoryView = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__middlebar1objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Application_Article_Play',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'd4b719dd9ea841de129ecea428f13b2a4f201611' => 'advanced_parameters_1b7462e8a4eb7f1ca0c6f3ca337b6f12390',
  'e3ea6a70e3b42667724b2d982e43c1b2c1354e2a' => '107374182',
  'f9e840a3655f6d9ef6ddfabffab356ead6d2156e' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__middlebar1objectEmbed = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$objay__rightbar0menuView = new Ayoola_Menu( array (
  'option' => 'Articles',
  'template_name' => 'BlackAccordionSide-menu',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'b906dece09c45d61547252bd86267719ffff3' => 'advanced_parameters_9cbabf00532a0aafa02cc612dc280824756',
  'e3ea6a70e3b42667724b2d982e43c1b2c1354e2a' => '107374182',
  'f9e840a3655f6d9ef6ddfabffab356ead6d2156e' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__rightbar0menuView = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Object_Embed' ) )
							{
								
$objay__rightbar1objectEmbed = new Ayoola_Object_Embed( array (
  'view' => 'Embed PHP Class',
  'editable' => 'Application_Article_Category, Application_Article_HashTag',
  'advanced_parameter_value' => 
  array (
    0 => '',
  ),
  'f991d30037e1acba5459bda1bd8a3a2670786fed' => 'advanced_parameters_1b7462e8a4eb7f1ca0c6f3ca337b6f12484',
  'e3ea6a70e3b42667724b2d982e43c1b2c1354e2a' => '107374182',
  'f9e840a3655f6d9ef6ddfabffab356ead6d2156e' => '',
  '' => '',
) );

							}
							else
							{
								
$objay__rightbar1objectEmbed = null;

							}
							