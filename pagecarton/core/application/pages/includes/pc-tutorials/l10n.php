<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /pc-tutorials/l10n
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: l10n.php	Tuesday 30th of July 2019 01:14:28 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_7b8a8a969c5afc068e8279e7c4bded04 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>&nbsp;</h1>

<h1>Localization</h1>

<p>&nbsp;</p>

<p>Localization&nbsp;of PageCarton refers to the&nbsp;adaptation&nbsp;of web content to meet the language, cultural and other requirements of a specific target market. PageCarton has been made to support internationalization and so, it is ready to be localized into any such language which its users may want. This feature is supported from PageCarton 1.9 and above.</p>

<p>&nbsp;</p>

<h2>Locale Settings</h2>

<p>You are able to set your preferred localization settings and how you want your site to behave in case where you have a visitor from some particular locale.&nbsp;</p>

<ul>
	<li>Default Locale - Sets the default locale which determines&nbsp;date and time formatting,&nbsp;decimal separator and system responses.</li>
	<li>Locale Options - Choose whether to&nbsp;<br>
	&nbsp;
	<ul>
		<li>Auto Translate Output Text - Choose whether to translate output text</li>
		<li>Save new words - choose to save new words which are not currently translated into any local language</li>
		<li>Auto-Detect Locale - Auto-detect locale through user browser.</li>
	</ul>
	</li>
</ul>

<p><a class="pc-btn" href="/pc-admin/PageCarton_Locale_Settings" target="_blank">View or Set Locale Settings</a></p>

<p>&nbsp;</p>

<h2>Translation</h2>

<p>&nbsp;</p>

<p>You are able to translate your site&nbsp;into any other language of choice. Here are the few steps to achieve this</p>

<ol>
</ol>

<p>&nbsp;</p>

<ol>
	<li>Add new language locale to the <a href="/pc-admin/PageCarton_Locale_List" target="_blank">Locale List</a>&nbsp;on your site.&nbsp;<br>
	&nbsp;
	<ul>
		<li>Locale Name - would be the "English" name of the language to be added. e.g. Russian</li>
		<li>Native Name - would be the local way of spelling the name of the language e.g. native name of "Russian" is "Русский", and the native name of "Yoruba" is "Yorùbá"</li>
		<li>Locale Code - The internationalized locale code for the language being added. e.g. Russian is "ru_RU" and Yoruba is "yor"<br>
		<br>
		<a class="pc-btn" href="/pc-admin/PageCarton_Locale_Creator" target="_blank">Add new Locale</a>&nbsp;<a class="pc-btn" href="/pc-admin/PageCarton_Locale_List" style="word-spacing: 0px;" target="_blank">Manage Locale List</a><br>
		&nbsp;</li>
	</ul>
	</li>
	<li>Put the right Settings up<br>
	<br>
	In the <a href="/pc-admin/PageCarton_Locale_Settings" target="_blank">PageCarton Locale Settings</a>, set Locale Options to "Save new words", and "Auto Translate Output Text".&nbsp; This is to allow PageCarton to pick up words and get them ready to translation into the languages set in the <a href="/pc-admin/PageCarton_Locale_List" target="_blank">Locale List</a>. We wouldn\'t want to be inputing the words one by one, especially for sites loaded with a lot of words already.&nbsp;<br>
	<br>
	<a class="pc-btn" href="/pc-admin/PageCarton_Locale_Settings" style="word-spacing: 0px;" target="_blank">View or Set Locale Settings</a></li>
	<br>
	<li>Auto populate words&nbsp;<br>
	<br>
	Normally, all the site pages need to be previewed so that new words may be detected and added to the translation library. But then, to simply the process, PageCarton now has a widget that call all the pages and widgets automatically in other to invoke the right words into the words translation database. This is now as simple has just clicking a button<br>
	<br>
	<a class="pc-btn" href="/pc-admin/PageCarton_Locale_Translation_AutoPopulateWords" style="word-spacing: 0px;" target="_blank">Populate Words</a><br>
	&nbsp;</li>
	<li>Go to the <a href="/pc-admin/PageCarton_Locale_List" target="_blank">Locale List</a> to see the list of locale you have added&nbsp;on your site.<br>
	&nbsp;</li>
	<li>Now under any of the locale choose the one you wish to translate, click on "translations". This will bring up a list of all the populated words.&nbsp;Those would be the words set up for translation.<br>
	&nbsp;</li>
	<li>Pick the words one after the other and click on "translate"&nbsp;<br>
	&nbsp;</li>
	<li>Enter the translation of the word into the space provided on the screen that opens up</li>
</ol>

<p>&nbsp;</p>

<h2>Exporting Locale Translation</h2>

<p>&nbsp;</p>

<p>Once translation is done for words and phrases in a particular locale, it is possible to export the translation as a plugin and make it available for other users with PageCarton sites.</p>

<p>&nbsp;</p>

<h3>Building Language Plugin</h3>

<p>The process of creating a plugin is easy and only a few steps:</p>

<p>&nbsp;</p>

<ul>
	<li>Go to My Plugins<br>
	<br>
	<a class="pc-btn" href="/pc-admin/Ayoola_Extension_List" target="_blank">My Plugins</a><br>
	&nbsp;</li>
	<li>Click on “<a href="/pc-admin/Ayoola_Extension_Creator/" target="_blank">Build New Plugins</a>”<br>
	&nbsp;
	<ul style="list-style-type:circle;">
		<li>Plugin Name could be the name of the Language e.g. “Russian Language Pack”</li>
		<li>Plugin Widgets can be left blank</li>
		<li>Settings can be left blank</li>
		<li>Databases of the words should be selected in “Database Data to Include in Plugin” databases to select should be<br>
		&nbsp;
		<ul>
			<li>PageCarton_Locale</li>
			<li>PageCarton_Locale_Originalstring</li>
			<li>Page_Carton_Locale_Translation</li>
		</ul>
		</li>
	</ul>
	</li>
</ul>

<p style="margin-left:1.5in;">&nbsp;</p>

<ul style="list-style-type:circle;">
	<li>Documents should be left blank</li>
	<li>Pages should be left blank</li>
	<li>Click “Continue” to submit</li>
</ul>

<ul>
	<li>Close the pop up screen and the new added plugin should show in the <a href="/pc-admin/Ayoola_Extension_List" target="_blank">My Plugins</a> list.</li>
	<li>Click on “Download” to download this plugin to your device. The downloaded file should be a “tar.gz” archive</li>
</ul>

<p>&nbsp;</p>

<h3>Installing the language on another site</h3>

<p>&nbsp;</p>

<ul style="list-style-type:circle;">
	<li>Go to Installed Plugins<br>
	<br>
	<a class="pc-btn" href="/pc-admin/Ayoola_Extension_Import_List" target="_blank">Installed Plugins</a><br>
	&nbsp;</li>
	<li>Click on “<a href="/pc-admin/Ayoola_Extension_Import_Creator" target="_blank">Upload New</a>” to upload the plugin you have downloaded to your computer/device. The file should be a “tar.gz” archive.</li>
	<li>Continue to Install and “Turn On” the plugin to enable the plugin</li>
	<li>Go to site Locale List and the new language should be listed</li>
	<li>Set the preferred language as default and change other “Locale Settings” to “Auto Translate Output Text” and “Auto-Detect Locale”</li>
	<li>PageCarton should now be displayed in the new language</li>
</ul>

<div>&nbsp;</div>

<h3>Uploading Language Plugin to PageCarton Plugin Repository<br>
&nbsp;</h3>

<ul>
	<li>Go to <a href="https://plugins.pagecarton.org">PageCarton Plugins&nbsp;- https://plugins.pagecarton.org</a><br>
	<br>
	<a class="pc-btn" href="https://plugins.pagecarton.com" target="_blank">PageCarton Plugins Repository</a><br>
	&nbsp;</li>
	<li>Click on upload</li>
	<li>Sign in / Sign up for a new account</li>
	<li>Continue to add a new plugin post into the "Languages" category</li>
	<li>That\'s it</li>
</ul>

<p>&nbsp;</p>

<p>&nbsp;</p>
',
  'preserved_content' => '<h1>&nbsp;</h1>

<h1>Localization</h1>

<p>&nbsp;</p>

<p>Localization&nbsp;of PageCarton refers to the&nbsp;adaptation&nbsp;of web content to meet the language, cultural and other requirements of a specific target market. PageCarton has been made to support internationalization and so, it is ready to be localized into any such language which its users may want. This feature is supported from PageCarton 1.9 and above.</p>

<p>&nbsp;</p>

<h2>Locale Settings</h2>

<p>You are able to set your preferred localization settings and how you want your site to behave in case where you have a visitor from some particular locale.&nbsp;</p>

<ul>
	<li>Default Locale - Sets the default locale which determines&nbsp;date and time formatting,&nbsp;decimal separator and system responses.</li>
	<li>Locale Options - Choose whether to&nbsp;<br>
	&nbsp;
	<ul>
		<li>Auto Translate Output Text - Choose whether to translate output text</li>
		<li>Save new words - choose to save new words which are not currently translated into any local language</li>
		<li>Auto-Detect Locale - Auto-detect locale through user browser.</li>
	</ul>
	</li>
</ul>

<p><a class="pc-btn" href="/pc-admin/PageCarton_Locale_Settings" target="_blank">View or Set Locale Settings</a></p>

<p>&nbsp;</p>

<h2>Translation</h2>

<p>&nbsp;</p>

<p>You are able to translate your site&nbsp;into any other language of choice. Here are the few steps to achieve this</p>

<ol>
</ol>

<p>&nbsp;</p>

<ol>
	<li>Add new language locale to the <a href="/pc-admin/PageCarton_Locale_List" target="_blank">Locale List</a>&nbsp;on your site.&nbsp;<br>
	&nbsp;
	<ul>
		<li>Locale Name - would be the "English" name of the language to be added. e.g. Russian</li>
		<li>Native Name - would be the local way of spelling the name of the language e.g. native name of "Russian" is "Русский", and the native name of "Yoruba" is "Yorùbá"</li>
		<li>Locale Code - The internationalized locale code for the language being added. e.g. Russian is "ru_RU" and Yoruba is "yor"<br>
		<br>
		<a class="pc-btn" href="/pc-admin/PageCarton_Locale_Creator" target="_blank">Add new Locale</a>&nbsp;<a class="pc-btn" href="/pc-admin/PageCarton_Locale_List" style="word-spacing: 0px;" target="_blank">Manage Locale List</a><br>
		&nbsp;</li>
	</ul>
	</li>
	<li>Put the right Settings up<br>
	<br>
	In the <a href="/pc-admin/PageCarton_Locale_Settings" target="_blank">PageCarton Locale Settings</a>, set Locale Options to "Save new words", and "Auto Translate Output Text".&nbsp; This is to allow PageCarton to pick up words and get them ready to translation into the languages set in the <a href="/pc-admin/PageCarton_Locale_List" target="_blank">Locale List</a>. We wouldn\'t want to be inputing the words one by one, especially for sites loaded with a lot of words already.&nbsp;<br>
	<br>
	<a class="pc-btn" href="/pc-admin/PageCarton_Locale_Settings" style="word-spacing: 0px;" target="_blank">View or Set Locale Settings</a></li>
	<br>
	<li>Auto populate words&nbsp;<br>
	<br>
	Normally, all the site pages need to be previewed so that new words may be detected and added to the translation library. But then, to simply the process, PageCarton now has a widget that call all the pages and widgets automatically in other to invoke the right words into the words translation database. This is now as simple has just clicking a button<br>
	<br>
	<a class="pc-btn" href="/pc-admin/PageCarton_Locale_Translation_AutoPopulateWords" style="word-spacing: 0px;" target="_blank">Populate Words</a><br>
	&nbsp;</li>
	<li>Go to the <a href="/pc-admin/PageCarton_Locale_List" target="_blank">Locale List</a> to see the list of locale you have added&nbsp;on your site.<br>
	&nbsp;</li>
	<li>Now under any of the locale choose the one you wish to translate, click on "translations". This will bring up a list of all the populated words.&nbsp;Those would be the words set up for translation.<br>
	&nbsp;</li>
	<li>Pick the words one after the other and click on "translate"&nbsp;<br>
	&nbsp;</li>
	<li>Enter the translation of the word into the space provided on the screen that opens up</li>
</ol>

<p>&nbsp;</p>

<h2>Exporting Translation</h2>

<p>&nbsp;</p>
',
  'url_prefix' => '',
  'pagewidget_id' => '1563706806-0-4',
  'widget_name' => '& n b s p ;  L o c a l i z a t i o n  & n b s p ;  L o c a l i z a t i o n & n b s p ; o f  P a g e C a r t o n  r e f e r s  t o  t h e & n b s p ; a d a p t a t i o n & n b s p ; o f  w e b  c o n t e n t  t o  m e e t  t h e  l a n g u a g e ,  c u l t u r a l  a n d  o t h e r  r e q u i r e m e n t s  o f  a  s p e c i f i c  t a r g e t  m a r k e t .  P a g e C a r t o n  h a s  b e e n  m a d e  t o  s u p p o r t  i n t e r n a t i o n a l i z a t i o n  a n d  s o ,  i t  i s  r e a d y  t o  b e  l o c a l i z e d  i n t o  a n y  s u c h  l a n g u a g e  w h i c h  i t s  u s e r s  m a y  w a n t .  T h i s  f e a t u r e  i s  s u p p o r t e d  f r o m  P a g e C a r t o n  1 . 9  a n d  a b o v e .  & n b s p ;  L o c a l e  S e t t i n g s  Y o u  a r e  a b l e  t o  s e t  y o u r  p r e f e r r e d  l o c a l i z a t i o n  s e t t i n g s  a n d  h o w  y o u  w a n t  y o u r  s i t e  t o  b e h a v e  i n  c a s e  w h e r e  y o u  h a v e  a  v i s i t o r  f r o m  s o m e  p a r t i c u l a r  l o c a l e . & n b s p ;  D e f a u l t  L o c a l e  -  S e t s  t h e  d e f a u l t  l o c a l e  w h i c h  d e t e r m i n e s & n b s p ; d a t e  a n d  t i m e  f o r m a t t i n g , & n b s p ; d e c i m a l  s e p a r a t o r  a n d  s y s t e m  r e s p o n s e s .  L o c a l e  O p t i o n s  -  C h o o s e  w h e t h e r  t o & n b s p ;  & n b s p ;  A u t o  T r a n s l a t e  O u t p u t  T e x t  -  C h o o s e  w h e t h e r  t o  t r a n s l a t e  o u t p u t  t e x t  S a v e  n e w  w o r d s  -  c h o o s e  t o  s a v e  n e w  w o r d s  w h i c h  a r e  n o t  c u r r e n t l y  t r a n s l a t e d  i n t o  a n y  l o c a l  l a n g u a g e  A u t o - D e t e c t  L o c a l e  -  A u t o - d e t e c t  l o c a l e  t h r o u g h  u s e r  b r o w s e r .  V i e w  o r  S e t  L o c a l e  S e t t i n g s  & n b s p ;  T r a n s l a t i o n  & n b s p ;  Y o u  a r e  a b l e  t o  t r a n s l a t e  y o u r  s i t e & n b s p ; i n t o  a n y  o t h e r  l a n g u a g e  o f  c h o i c e .  H e r e  a r e  t h e  f e w  s t e p s  t o  a c h i e v e  t h i s  & n b s p ;  A d d  n e w  l a n g u a g e  l o c a l e  t o  t h e  L o c a l e  L i s t & n b s p ; o n  y o u r  s i t e . & n b s p ;  & n b s p ;  L o c a l e  N a m e  -  w o u l d  b e  t h e  " E n g l i s h "  n a m e  o f  t h e  l a n g u a g e  t o  b e  a d d e d .  e . g .  R u s s i a n  N a t i v e  N a m e  -  w o u l d  b e  t h e  l o c a l  w a y  o f  s p e l l i n g  t h e  n a m e  o f  t h e  l a n g u a g e  e . g .  n a t i v e  n a m e  o f  " R u s s i a n "  i s  " �  � � � � � � � � � � � � " ,  a n d  t h e  n a t i v e  n a m e  o f  " Y o r u b a "  i s  " Y o r � � b � � "  L o c a l e  C o d e  -  T h e  i n t e r n a t i o n a l i z e d  l o c a l e  c o d e  f o r  t h e  l a n g u a g e  b e i n g  a d d e d .  e . g .  R u s s i a n  i s  " r u _ R U "  a n d  Y o r u b a  i s  " y o r "  A d d  n e w  L o c a l e & n b s p ; M a n a g e  L o c a l e  L i s t  & n b s p ;  P u t  t h e  r i g h t  S e t t i n g s  u p  I n  t h e  P a g e C a r t o n  L o c a l e  S e t t i n g s ,  s e t  L o c a l e  O p t i o n s  t o  " S a v e  n e w  w o r d s " ,  a n d  " A u t o  T r a n s l a t e  O u t p u t  T e x t " . & n b s p ;  T h i s  i s  t o  a l l o w  P a g e C a r t o n  t o  p i c k  u p  w o r d s  a n d  g e t  t h e m  r e a d y  t o  t r a n s l a t i o n  i n t o  t h e  l a n g u a g e s  s e t  i n  t h e  L o c a l e  L i s t .  W e  w o u l d n \' t  w a n t  t o  b e  i n p u t i n g  t h e  w o r d s  o n e  b y  o n e ,  e s p e c i a l l y  f o r  s i t e s  l o a d e d  w i t h  a  l o t  o f  w o r d s  a l r e a d y . & n b s p ;  V i e w  o r  S e t  L o c a l e  S e t t i n g s  A u t o  p o p u l a t e  w o r d s & n b s p ;  N o r m a l l y ,  a l l  t h e  s i t e  p a g e s  n e e d  t o  b e  p r e v i e w e d  s o  t h a t  n e w  w o r d s  m a y  b e  d e t e c t e d  a n d  a d d e d  t o  t h e  t r a n s l a t i o n  l i b r a r y .  B u t  t h e n ,  t o  s i m p l y  t h e  p r o c e s s ,  P a g e C a r t o n  n o w  h a s  a  w i d g e t  t h a t  c a l l  a l l  t h e  p a g e s  a n d  w i d g e t s  a u t o m a t i c a l l y  i n  o t h e r  t o  i n v o k e  t h e  r i g h t  w o r d s  i n t o  t h e  w o r d s  t r a n s l a t i o n  d a t a b a s e .  T h i s  i s  n o w  a s  s i m p l e  h a s  j u s t  c l i c k i n g  a  b u t t o n  P o p u l a t e  W o r d s  & n b s p ;  G o  t o  t h e  L o c a l e  L i s t  t o  s e e  t h e  l i s t  o f  l o c a l e  y o u  h a v e  a d d e d & n b s p ; o n  y o u r  s i t e .  & n b s p ;  N o w  u n d e r  a n y  o f  t h e  l o c a l e  c h o o s e  t h e  o n e  y o u  w i s h  t o  t r a n s l a t e ,  c l i c k  o n  " t r a n s l a t i o n s " .  T h i s  w i l l  b r i n g  u p  a  l i s t  o f  a l l  t h e  p o p u l a t e d  w o r d s . & n b s p ; T h o s e  w o u l d  b e  t h e  w o r d s  s e t  u p  f o r  t r a n s l a t i o n .  & n b s p ;  P i c k  t h e  w o r d s  o n e  a f t e r  t h e  o t h e r  a n d  c l i c k  o n  " t r a n s l a t e " & n b s p ;  & n b s p ;  E n t e r  t h e  t r a n s l a t i o n  o f  t h e  w o r d  i n t o  t h e  s p a c e  p r o v i d e d  o n  t h e  s c r e e n  t h a t  o p e n s  u p  & n b s p ;  E x p o r t i n g  T r a n s l a t i o n  & n b s p ;',
) );

							}
							else
							{
								
$_7b8a8a969c5afc068e8279e7c4bded04 = null;

							}
							