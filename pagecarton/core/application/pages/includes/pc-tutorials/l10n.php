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
* @version $Id: l10n.php	Sunday 14th of February 2021 07:03:49 AM	ayoola.falola@yahoo.com $ 
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
	&nbsp;</li>
	<li>Plugin Name could be the name of the Language e.g. “Russian Language Pack”</li>
	<li>Plugin Widgets can be left blank</li>
	<li>Settings can be left blank</li>
	<li>Databases of the words should be selected in “Database Data to Include in Plugin” databases to select should be<br>
	&nbsp;
	<ul>
		<li>PageCarton_Locale</li>
		<li>PageCarton_Locale_Originalstring</li>
		<li>Page_Carton_Locale_Translation<br>
		&nbsp;</li>
	</ul>
	</li>
	<li>Documents should be left blank</li>
	<li>Pages should be left blank</li>
	<li>Click “Continue” to submit</li>
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
  'url_prefix' => '',
  'pagewidget_id' => '1575534212-0-16',
  'insert_id' => '1566255724-0-15',
  'content' => '<h1>&nbsp;</h1>

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
	&nbsp;</li>
	<li>Plugin Name could be the name of the Language e.g. “Russian Language Pack”</li>
	<li>Plugin Widgets can be left blank</li>
	<li>Settings can be left blank</li>
	<li>Databases of the words should be selected in “Database Data to Include in Plugin” databases to select should be<br>
	&nbsp;
	<ul>
		<li>PageCarton_Locale</li>
		<li>PageCarton_Locale_Originalstring</li>
		<li>Page_Carton_Locale_Translation<br>
		&nbsp;</li>
	</ul>
	</li>
	<li>Documents should be left blank</li>
	<li>Pages should be left blank</li>
	<li>Click “Continue” to submit</li>
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
) );

							}
							else
							{
								
$_7b8a8a969c5afc068e8279e7c4bded04 = null;

							}
							