<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /pc-tutorials/plugin
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: plugin.php	Friday 17th of June 2022 02:33:40 PM	joywealth@hotmail.com $ 
*/
//	Page Include Content

							
$_be52de7bd6cdef876aa721a6b16621a0 = null;

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_be52de7bd6cdef876aa721a6b16621a0 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<h1>Creating PageCarton Plugins</h1>

<p>&nbsp;</p>

<p>Creating plugins with PageCarton is pretty easy. Unlike many other frameworks, PageCarton Plugins doesn\'t really need to be created with codes. In fact, PageCarton plugins are built using a Graphic Interface. Now, the built plugins may now contain widgets, templates, etc which are created with codes but the way PageCarton plugins work make it simply to create and use.</p>

<p>&nbsp;</p>

<p>The concept of PageCarton plugins is to pack some components of a site in a way that it could be integrated into another website. So when a Plugin is built on Site A, it includes certain components - files, pages and other resources so that when such plugin is installed and activated on Site B, all the resources are available on site B just like they were originally on Site B. The moment the plugin is deactivated on Site B, the site go back to the way it was before the plugin was installed - without the components and functionalities introduced by the plugin built on Site A. In other words, a building a plugin is a way of creating a package which copies a functionality of a site into another.</p>

<p>&nbsp;</p>

<h2>Components of a plugin<br>
&nbsp;</h2>

<p>Plugins could have a number of files and other resources which provides the functionalities the plugin introduces into the site it is installed on. There are a number of components on which a plugin can be built. Each of this component types introduces a diverse kind of functionality into a site. The components to build into a plugin must first be created on the site which the plugin is being built on. There are three basic categories of the components, they are:</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<ol>
	<li><strong>Widgets</strong>&nbsp;<br>
	<br>
	Widgets are basically PHP classes of for some site-specific functions. Widgets can be built into plugins to export whatever functionalities such plugins are created for. Widgets&nbsp;bring specific functionalities to the site, depending on the kind of widget they are: Here are the kinds of widgets available:<br>
	&nbsp;
	<ol>
		<li>Standard&nbsp;<a href="https://docs.pagecarton.org/2017/10/13/widgets.html" target="_blank">Widgets</a><br>
		<br>
		Widgets are used to programmatically provides an output to the user.&nbsp; As a matter of principle, whenever PageCarton want to output any content, a widget is used. PageCarton core comes with a lot of widgets bringing different functionalities, Plugin developers can develop their own widgets and include it in the plugin components.<br>
		&nbsp;</li>
		<li><a href="https://docs.pagecarton.org/2017/10/13/settings-module.html" target="_blank">Settings</a><br>
		<br>
		Settings are special kinds of widgets in PageCarton which only provides forms and other interfaces to take settings data from the user. It directly links to the PageCarton Settings database to store and retrieves its data. When a settings is selected in building a plugin, the settings class will be used whenever the Plugin Settings is clicked on the site which has the plugin installed.&nbsp;<br>
		&nbsp;</li>
		<li><a href="https://docs.pagecarton.org/2017/10/13/database-tables.html" target="_blank">Databases</a><br>
		<br>
		Unlike most other frameworks and platforms of its kind. PageCarton has it\'s own database implementation using XML flat-file system. In PageCarton, databases are also a special kind of widgets - PHP classes which defines how a set of data is to be stored, retrieved and manipulated. PageCarton databases comes with ready APIs for CRUD systems, to insert, select, update and delete data from the tables. To include a database in the plugin, it must be selected under widgets. Including a database under widgets only include the database structure, including it under Database Tables allows the data to be included in the plugin.<br>
		&nbsp;</li>
	</ol>
	</li>
	<li><strong>Documents</strong><br>
	<br>
	Documents are files uploaded on&nbsp;the site, which can be retrieved directly using for example a URL:&nbsp;https://example.com/path/to/file.jpg. Documents can be built into plugins so that site that have them installed can also have those files on them.&nbsp;<br>
	&nbsp;</li>
	<li><strong>Pages</strong><br>
	<br>
	Plugins in PageCarton can also include pages. This allows for plugin developers to have pages defined in the plugin so that whenever the plugin is installed and activated on a new site, the defined pages becomes available immediately.<br>
	&nbsp;</li>
</ol>

<div>When creating a plugin, a developer will need to know the components required to make the plugin. While simple plugins may only contain one component, some complex plugins may combine some of the components to introduce a fantastic feature.&nbsp;</div>

<div>&nbsp;</div>

<h2>Building a Plugin</h2>

<p>&nbsp;</p>

<div>
<ul>
	<li>Go to My Plugins<br>
	<br>
	<a class="pc-btn" href="http://localhost:8888/pc-admin/Ayoola_Extension_List" target="_blank">My Plugins</a><br>
	&nbsp;</li>
	<li>Click on “<a href="http://localhost:8888/pc-admin/Ayoola_Extension_Creator/" target="_blank">Build New Plugins</a>”<br>
	&nbsp;
	<ul>
		<li><strong>Plugin Name</strong> could be the name of the plugin e.g. “Hello World”</li>
		<li><strong>Widgets</strong> - Select the widgets to include in the plugin</li>
		<li><strong>Settings</strong> - Select the settings widget to use as Plugin Settings</li>
		<li><strong>Databases</strong> - Which database table data to include in the plugins</li>
		<li><strong>Documents</strong> - What files should be included in the plugin.</li>
		<li><strong>Pages</strong> - What pages should be included in the plugin</li>
		<li>Click “Continue” to submit</li>
	</ul>
	</li>
</ul>
</div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<div>
<h2>Installing the Plugin on Another Site</h2>

<p>&nbsp;</p>

<ul>
	<li>
	<p>Go to Installed Plugins<br>
	<br>
	<a class="pc-btn" href="http://localhost:8888/pc-admin/Ayoola_Extension_Import_List" target="_blank">Installed Plugins</a></p>
	</li>
	<li>Click on “<a href="http://localhost:8888/pc-admin/Ayoola_Extension_Import_Creator" target="_blank">Upload New</a>” to upload the plugin you have downloaded to your computer/device. The file should be a “tar.gz” archive.</li>
	<li>Continue to Install and “Turn On” the plugin to enable the plugin</li>
	<li>The components of the plugin should now be available on the site.</li>
</ul>
</div>

<div>&nbsp;</div>

<div>
<h2>Uploading Plugin to PageCarton Plugin Repository<br>
&nbsp;</h2>

<ul>
	<li>Go to&nbsp;<a href="https://plugins.pagecarton.org/">PageCarton Plugins&nbsp;- https://plugins.pagecarton.org</a><br>
	<br>
	<a class="pc-btn" href="https://plugins.pagecarton.com/" target="_blank">PageCarton Plugins Repository</a><br>
	&nbsp;</li>
	<li>Click on upload</li>
	<li>Sign in / Sign up for a new account</li>
	<li>Continue to add a new plugin post into the right&nbsp;category</li>
	<li>That\'s it</li>
</ul>
</div>

<div>&nbsp;</div>

<h2>Examples</h2>

<p>&nbsp;</p>

<h3>Content-based Plugin - Cookie notices plugin</h3>

<p>&nbsp;</p>

<p>A content based plugin is that which embeds/include a certain content into it\'s installed sites. We will consider a plugin to pop up cookie notices for&nbsp;<a href="https://ec.europa.eu/commission/priorities/justice-and-fundamental-rights/data-protection/2018-reform-eu-data-protection-rules_en" rel="noopener noreferrer" target="_blank">GDPR</a>&nbsp;and&nbsp;<a href="https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex%3A32009L0136" rel="noopener noreferrer" target="_blank">ePR</a>&nbsp;compliance. For this plugin, we need two components</p>

<p>&nbsp;</p>

<p><strong>Creating Files</strong></p>

<p>&nbsp;</p>

<ol>
	<li>Populate Site-wide widgets database<br>
	<br>
	PageCarton has a database which stores information&nbsp;of widgets saved in pages. The class of this database is "Ayoola_Object_PageWidget". It also has a special page "/sitewide-page-widgets" which stores widgets to&nbsp;display site-wide.&nbsp;<br>
	<br>
	To populate this database we need to&nbsp;<br>
	&nbsp;
	<ol>
		<li>External Javascript or CSS files - External files can be uploaded directly using the file manager. The URL may now be used in the content as a valid image, JS/CSS script<br>
		<br>
		<a class="pc-btn" href="/widgets/Ayoola_Doc_Browser" target="_blank">File Manager</a><br>
		&nbsp;</li>
		<li>Update&nbsp;"/sitewide-page-widgets" page to include all content to include in all the pages of the site.<br>
		<br>
		<a class="pc-btn" href="/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=/sitewide-page-widgets" target="_blank">Edit&nbsp;/sitewide-page-widgets</a>
		<pre><code>&lt;!--sample HTML Text Content--&gt; 
&lt;script src="/public/2019/01/01/cookieBubble.js"&gt;&lt;/script&gt; &lt;!--Include uploaded external javascript and other files --&gt; 
&lt;script&gt;
&nbsp; &nbsp; (function ($) {
&nbsp; &nbsp; &nbsp; $.cookieBubble();
&nbsp; &nbsp; &nbsp;})(jQuery);
&lt;/script&gt;</code>
</pre>

		<ol>
			<li>Click on "Widget Options" if you can\'t see widget containers (where you have "Insert Widget Here")&nbsp;on&nbsp;the page</li>
			<li>Click on "Insert Widget Here"</li>
			<li>Select "HTML Text"</li>
			<li>On the HTML Text box, switch the box to "Code View"&nbsp;</li>
			<li>Paste the codes of the content to the code text area.</li>
			<li>Click Save on the lower left corner of the screen<br>
			<br>
			Click on the "Insert Widget Here" wherever you want the content to be, then select "HTML Text".&nbsp;When adding javascript files/code in "&lt;script&gt;" tags, it is best to include such in the last widget container (where you have "Insert Widget Here")&nbsp;on&nbsp;the page, it is best to switch the HTML Text widget to "Code View" mode when writing codes. The uploaded external files can be used here to display images, include CSS and JS in the page, etc.<br>
			&nbsp;</li>
		</ol>
		</li>
	</ol>
	</li>
</ol>

<div><strong>Build Plugin</strong></div>

<div>&nbsp;</div>

<ul>
	<li>Go to My Plugins<br>
	<br>
	<a class="pc-btn" href="http://localhost:8888/pc-admin/Ayoola_Extension_List" target="_blank">My Plugins</a><br>
	&nbsp;</li>
	<li>Click on “<a href="http://localhost:8888/pc-admin/Ayoola_Extension_Creator/" target="_blank">Build New Plugins</a>”<br>
	&nbsp;
	<ul>
		<li>Plugin Name could be the name of the plugin e.g. “Cookie Compliance Plugin”</li>
		<li>Plugin Widgets - Should be left blank since our plugin does not require a widget</li>
		<li>Settings - Should be left blank since our plugin does not require settings</li>
		<li>Databases - just one databases to include:
		<ul>
			<li>Ayoola_Object_PageWidget - The database for the page widgets</li>
		</ul>
		</li>
		<li>Documents - Select all the uploaded external files</li>
		<li>Pages -&nbsp;Should be left blank since our plugin does not require a page</li>
		<li>Click “Continue” to submit</li>
		<li>Download plugin</li>
	</ul>
	</li>
</ul>

<h3>&nbsp;</h3>
',
  'preserved_content' => '<h1>Creating PageCarton Plugins</h1>

<p>&nbsp;</p>

<p>Creating plugins with PageCarton is pretty easy. Unlike many other frameworks, PageCarton Plugins doesn\'t really need to be created with codes. In fact, PageCarton plugins are built using a Graphic Interface. Now, the built plugins may now contain widgets, templates, etc which are created with codes but the way PageCarton plugins work make it simply to create and use.</p>

<p>&nbsp;</p>

<p>The concept of PageCarton plugins is to pack some components of a site in a way that it could be integrated into another website. So when a Plugin is built on Site A, it includes certain components - files, pages and other resources so that when such plugin is installed and activated on Site B, all the resources are available on site B just like they were originally on Site B. The moment the plugin is deactivated on Site B, the site go back to the way it was before the plugin was installed - without the components and functionalities introduced by the plugin built on Site A. In other words, a building a plugin is a way of creating a package which copies a functionality of a site into another.</p>

<p>&nbsp;</p>

<h2>Components of a plugin<br>
&nbsp;</h2>

<p>Plugins could have a number of files and other resources which provides the functionalities the plugin introduces into the site it is installed on. There are a number of components on which a plugin can be built. Each of this component types introduces a diverse kind of functionality into a site. The components to build into a plugin must first be created on the site which the plugin is being built on. There are three basic categories of the components, they are:</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<ol>
	<li>Widgets&nbsp;<br>
	<br>
	Widgets are basically PHP classes of for some site-specific functions. Widgets can be built into plugins to export whatever functionalities such plugins are created for. Widgets&nbsp;bring specific functionalities to the site, depending on the kind of widget they are: Here are the kinds of widgets available:<br>
	&nbsp;
	<ol>
		<li>Standard&nbsp;<a href="https://docs.pagecarton.org/2017/10/13/widgets.html" target="_blank">Widgets</a><br>
		<br>
		Widgets are used to programmatically provides an output to the user.&nbsp; As a matter of principle, whenever PageCarton want to output any content, a widget is used. PageCarton core comes with a lot of widgets bringing different functionalities, Plugin developers can develop their own widgets and include it in the plugin components.<br>
		&nbsp;</li>
		<li><a href="https://docs.pagecarton.org/2017/10/13/settings-module.html" target="_blank">Settings</a><br>
		<br>
		Settings are special kinds of widgets in PageCarton which only provides forms and other interfaces to take settings data from the user. It directly links to the PageCarton Settings database to store and retrieves its data. When a settings is selected in building a plugin, the settings class will be used whenever the Plugin Settings is clicked on the site which has the plugin installed.&nbsp;<br>
		&nbsp;</li>
		<li><a href="https://docs.pagecarton.org/2017/10/13/database-tables.html" target="_blank">Databases</a><br>
		<br>
		Unlike most other frameworks and platforms of its kind. PageCarton has it\'s own database implementation using XML flat-file system. In PageCarton, databases are also a special kind of widgets - PHP classes which defines how a set of data is to be stored, retrieved and manipulated. PageCarton databases comes with ready APIs for CRUD systems, to insert, select, update and delete data from the tables. To include a database in the plugin, it must be selected under widgets. Including a database under widgets only include the database structure, including it under Database Tables allows the data to be included in the plugin.<br>
		&nbsp;</li>
	</ol>
	</li>
	<li>Documents<br>
	<br>
	Documents are files uploaded on&nbsp;the site, which can be retrieved directly using for example a URL:&nbsp;https://example.com/path/to/file.jpg. Documents can be built into plugins so that site that have them installed can also have those files on them.&nbsp;<br>
	&nbsp;</li>
	<li>Pages<br>
	<br>
	Plugins in PageCarton can also include pages. This allows for plugin developers to have pages defined in the plugin so that whenever the plugin is installed and activated on a new site, the defined pages becomes available immediately.<br>
	&nbsp;</li>
</ol>

<div>When creating a plugin, a developer will need to know the components required to make the plugin. While simple plugins may only contain one component, some complex plugins may combine some of the components to introduce a fantastic feature.&nbsp;</div>

<div>&nbsp;</div>

<h2>Building a Plugin</h2>

<p>&nbsp;</p>

<div>
<ul>
	<li>Go to My Plugins<br>
	<br>
	<a class="pc-btn" href="http://localhost:8888/pc-admin/Ayoola_Extension_List" target="_blank">My Plugins</a><br>
	&nbsp;</li>
	<li>Click on “<a href="http://localhost:8888/pc-admin/Ayoola_Extension_Creator/" target="_blank">Build New Plugins</a>”<br>
	&nbsp;</li>
	<li>Plugin Name could be the name of the plugin e.g. “Hello World”</li>
	<li>Plugin Widgets - Select the widgets to include in the plugin</li>
	<li>Settings - Select the settings widget to use as Pluging Settings</li>
	<li>Databases - Which database table data to include in the plugins</li>
	<li>Documents - What files should be included in the plugin.</li>
	<li>Pages - What pages should be included in the plugin</li>
	<li>Click “Continue” to submit</li>
</ul>
</div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<p>&nbsp;</p>
',
  'url_prefix' => '',
  'pagewidget_id' => '1575534212-0-15',
  'includes' => 
  array (
  ),
  'content' => '<h1>Creating PageCarton Plugins</h1>

<p>&nbsp;</p>

<p>Creating plugins with PageCarton is pretty easy. Unlike many other frameworks, PageCarton Plugins doesn\'t really need to be created with codes. In fact, PageCarton plugins are built using a Graphic Interface. Now, the built plugins may now contain widgets, templates, etc which are created with codes but the way PageCarton plugins work make it simply to create and use.</p>

<p>&nbsp;</p>

<p>The concept of PageCarton plugins is to pack some components of a site in a way that it could be integrated into another website. So when a Plugin is built on Site A, it includes certain components - files, pages and other resources so that when such plugin is installed and activated on Site B, all the resources are available on site B just like they were originally on Site B. The moment the plugin is deactivated on Site B, the site go back to the way it was before the plugin was installed - without the components and functionalities introduced by the plugin built on Site A. In other words, a building a plugin is a way of creating a package which copies a functionality of a site into another.</p>

<p>&nbsp;</p>

<h2>Components of a plugin<br>
&nbsp;</h2>

<p>Plugins could have a number of files and other resources which provides the functionalities the plugin introduces into the site it is installed on. There are a number of components on which a plugin can be built. Each of this component types introduces a diverse kind of functionality into a site. The components to build into a plugin must first be created on the site which the plugin is being built on. There are three basic categories of the components, they are:</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<ol>
	<li><strong>Widgets</strong>&nbsp;<br>
	<br>
	Widgets are basically PHP classes of for some site-specific functions. Widgets can be built into plugins to export whatever functionalities such plugins are created for. Widgets&nbsp;bring specific functionalities to the site, depending on the kind of widget they are: Here are the kinds of widgets available:<br>
	&nbsp;
	<ol>
		<li>Standard&nbsp;<a href="https://docs.pagecarton.org/2017/10/13/widgets.html" target="_blank">Widgets</a><br>
		<br>
		Widgets are used to programmatically provides an output to the user.&nbsp; As a matter of principle, whenever PageCarton want to output any content, a widget is used. PageCarton core comes with a lot of widgets bringing different functionalities, Plugin developers can develop their own widgets and include it in the plugin components.<br>
		&nbsp;</li>
		<li><a href="https://docs.pagecarton.org/2017/10/13/settings-module.html" target="_blank">Settings</a><br>
		<br>
		Settings are special kinds of widgets in PageCarton which only provides forms and other interfaces to take settings data from the user. It directly links to the PageCarton Settings database to store and retrieves its data. When a settings is selected in building a plugin, the settings class will be used whenever the Plugin Settings is clicked on the site which has the plugin installed.&nbsp;<br>
		&nbsp;</li>
		<li><a href="https://docs.pagecarton.org/2017/10/13/database-tables.html" target="_blank">Databases</a><br>
		<br>
		Unlike most other frameworks and platforms of its kind. PageCarton has it\'s own database implementation using XML flat-file system. In PageCarton, databases are also a special kind of widgets - PHP classes which defines how a set of data is to be stored, retrieved and manipulated. PageCarton databases comes with ready APIs for CRUD systems, to insert, select, update and delete data from the tables. To include a database in the plugin, it must be selected under widgets. Including a database under widgets only include the database structure, including it under Database Tables allows the data to be included in the plugin.<br>
		&nbsp;</li>
	</ol>
	</li>
	<li><strong>Documents</strong><br>
	<br>
	Documents are files uploaded on&nbsp;the site, which can be retrieved directly using for example a URL:&nbsp;https://example.com/path/to/file.jpg. Documents can be built into plugins so that site that have them installed can also have those files on them.&nbsp;<br>
	&nbsp;</li>
	<li><strong>Pages</strong><br>
	<br>
	Plugins in PageCarton can also include pages. This allows for plugin developers to have pages defined in the plugin so that whenever the plugin is installed and activated on a new site, the defined pages becomes available immediately.<br>
	&nbsp;</li>
</ol>

<div>When creating a plugin, a developer will need to know the components required to make the plugin. While simple plugins may only contain one component, some complex plugins may combine some of the components to introduce a fantastic feature.&nbsp;</div>

<div>&nbsp;</div>

<h2>Building a Plugin</h2>

<p>&nbsp;</p>

<div>
<ul>
	<li>Go to My Plugins<br>
	<br>
	<a class="pc-btn" href="http://localhost:8888/pc-admin/Ayoola_Extension_List" target="_blank">My Plugins</a><br>
	&nbsp;</li>
	<li>Click on “<a href="http://localhost:8888/pc-admin/Ayoola_Extension_Creator/" target="_blank">Build New Plugins</a>”<br>
	&nbsp;
	<ul>
		<li><strong>Plugin Name</strong> could be the name of the plugin e.g. “Hello World”</li>
		<li><strong>Widgets</strong> - Select the widgets to include in the plugin</li>
		<li><strong>Settings</strong> - Select the settings widget to use as Plugin Settings</li>
		<li><strong>Databases</strong> - Which database table data to include in the plugins</li>
		<li><strong>Documents</strong> - What files should be included in the plugin.</li>
		<li><strong>Pages</strong> - What pages should be included in the plugin</li>
		<li>Click “Continue” to submit</li>
	</ul>
	</li>
</ul>
</div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<div>
<h2>Installing the Plugin on Another Site</h2>

<p>&nbsp;</p>

<ul>
	<li>
	<p>Go to Installed Plugins<br>
	<br>
	<a class="pc-btn" href="http://localhost:8888/pc-admin/Ayoola_Extension_Import_List" target="_blank">Installed Plugins</a></p>
	</li>
	<li>Click on “<a href="http://localhost:8888/pc-admin/Ayoola_Extension_Import_Creator" target="_blank">Upload New</a>” to upload the plugin you have downloaded to your computer/device. The file should be a “tar.gz” archive.</li>
	<li>Continue to Install and “Turn On” the plugin to enable the plugin</li>
	<li>The components of the plugin should now be available on the site.</li>
</ul>
</div>

<div>&nbsp;</div>

<div>
<h2>Uploading Plugin to PageCarton Plugin Repository<br>
&nbsp;</h2>

<ul>
	<li>Go to&nbsp;<a href="https://plugins.pagecarton.org/">PageCarton Plugins&nbsp;- https://plugins.pagecarton.org</a><br>
	<br>
	<a class="pc-btn" href="https://plugins.pagecarton.com/" target="_blank">PageCarton Plugins Repository</a><br>
	&nbsp;</li>
	<li>Click on upload</li>
	<li>Sign in / Sign up for a new account</li>
	<li>Continue to add a new plugin post into the right&nbsp;category</li>
	<li>That\'s it</li>
</ul>
</div>

<div>&nbsp;</div>

<h2>Examples</h2>

<p>&nbsp;</p>

<h3>Content-based Plugin - Cookie notices plugin</h3>

<p>&nbsp;</p>

<p>A content based plugin is that which embeds/include a certain content into it\'s installed sites. We will consider a plugin to pop up cookie notices for&nbsp;<a href="https://ec.europa.eu/commission/priorities/justice-and-fundamental-rights/data-protection/2018-reform-eu-data-protection-rules_en" rel="noopener noreferrer" target="_blank">GDPR</a>&nbsp;and&nbsp;<a href="https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex%3A32009L0136" rel="noopener noreferrer" target="_blank">ePR</a>&nbsp;compliance. For this plugin, we need two components</p>

<p>&nbsp;</p>

<p><strong>Creating Files</strong></p>

<p>&nbsp;</p>

<ol>
	<li>Populate Site-wide widgets database<br>
	<br>
	PageCarton has a database which stores information&nbsp;of widgets saved in pages. The class of this database is "Ayoola_Object_PageWidget". It also has a special page "/sitewide-page-widgets" which stores widgets to&nbsp;display site-wide.&nbsp;<br>
	<br>
	To populate this database we need to&nbsp;<br>
	&nbsp;
	<ol>
		<li>External Javascript or CSS files - External files can be uploaded directly using the file manager. The URL may now be used in the content as a valid image, JS/CSS script<br>
		<br>
		<a class="pc-btn" href="/widgets/Ayoola_Doc_Browser" target="_blank">File Manager</a><br>
		&nbsp;</li>
		<li>Update&nbsp;"/sitewide-page-widgets" page to include all content to include in all the pages of the site.<br>
		<br>
		<a class="pc-btn" href="/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=/sitewide-page-widgets" target="_blank">Edit&nbsp;/sitewide-page-widgets</a>
		<pre><code>&lt;!--sample HTML Text Content--&gt; 
&lt;script src="/public/2019/01/01/cookieBubble.js"&gt;&lt;/script&gt; &lt;!--Include uploaded external javascript and other files --&gt; 
&lt;script&gt;
&nbsp; &nbsp; (function ($) {
&nbsp; &nbsp; &nbsp; $.cookieBubble();
&nbsp; &nbsp; &nbsp;})(jQuery);
&lt;/script&gt;</code>
</pre>

		<ol>
			<li>Click on "Widget Options" if you can\'t see widget containers (where you have "Insert Widget Here")&nbsp;on&nbsp;the page</li>
			<li>Click on "Insert Widget Here"</li>
			<li>Select "HTML Text"</li>
			<li>On the HTML Text box, switch the box to "Code View"&nbsp;</li>
			<li>Paste the codes of the content to the code text area.</li>
			<li>Click Save on the lower left corner of the screen<br>
			<br>
			Click on the "Insert Widget Here" wherever you want the content to be, then select "HTML Text".&nbsp;When adding javascript files/code in "&lt;script&gt;" tags, it is best to include such in the last widget container (where you have "Insert Widget Here")&nbsp;on&nbsp;the page, it is best to switch the HTML Text widget to "Code View" mode when writing codes. The uploaded external files can be used here to display images, include CSS and JS in the page, etc.<br>
			&nbsp;</li>
		</ol>
		</li>
	</ol>
	</li>
</ol>

<div><strong>Build Plugin</strong></div>

<div>&nbsp;</div>

<ul>
	<li>Go to My Plugins<br>
	<br>
	<a class="pc-btn" href="http://localhost:8888/pc-admin/Ayoola_Extension_List" target="_blank">My Plugins</a><br>
	&nbsp;</li>
	<li>Click on “<a href="http://localhost:8888/pc-admin/Ayoola_Extension_Creator/" target="_blank">Build New Plugins</a>”<br>
	&nbsp;
	<ul>
		<li>Plugin Name could be the name of the plugin e.g. “Cookie Compliance Plugin”</li>
		<li>Plugin Widgets - Should be left blank since our plugin does not require a widget</li>
		<li>Settings - Should be left blank since our plugin does not require settings</li>
		<li>Databases - just one databases to include:
		<ul>
			<li>Ayoola_Object_PageWidget - The database for the page widgets</li>
		</ul>
		</li>
		<li>Documents - Select all the uploaded external files</li>
		<li>Pages -&nbsp;Should be left blank since our plugin does not require a page</li>
		<li>Click “Continue” to submit</li>
		<li>Download plugin</li>
	</ul>
	</li>
</ul>

<h3>&nbsp;</h3>
',
) );

							}
							