<?php
/**
* PageCarton Page Generator
*
* LICENSE
*
* @category PageCarton
* @package /pc-tutorials
* @generated Ayoola_Page_Editor_Layout
* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)
* @license    http://www.PageCarton.com/license.txt
* @version $Id: pc-tutorials.php	Thursday 25th of July 2019 10:42:35 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_7e559ca9f527527b35db37ac74db2c6d = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div>
<p>&nbsp;</p>

<h2><a id="admin" name="admin">Admin access</a></h2>

<p>&nbsp;</p>
</div>

<p>Your website allows you to have unlimited number of administrators â€“ people, who would have access to the backend to perform specific administrative functions. Adding new pages, editing existing pages, reading contact form feedbacks, changing logos and favicon, creating posts or adding new products and services are some of the functions users are able to perform when they have admin access to the website.</p>

<p>The direct link to your administrative backend of your website is on <a href="/pc-admin">Control Panel</a>. By default, only the website developer&nbsp;would have access to the website admin panel to update the website&nbsp;but he could grant as many individuals as needed access to do the same tasks.</p>

<p>&nbsp;</p>

<ul>
	<li>
	<p><strong>Request admin access</strong><br>
	<br>
	To be able to gain admin access to the website so you could update the website, you should request admin access. Only current admin users are able to grant admin access to others. If you are in doubt who would give you access to the website, you should talk to your website or service providers as they should have the capacity to do this for you. Meanwhile, any other person with admin access could create other admin users. Request Admin Access by following the link below.<br>
	<br>
	<a class="pc-btn" href="/widgets/Application_User_Creator/notify_us/create" target="_blank">Request Admin Access</a><br>
	<br>
	All current admin will now be notified of your request and they would grant you access easily with a few clicks<br>
	&nbsp;</p>
	</li>
	<li>
	<p><strong>Visit Control Panel</strong><br>
	<br>
	You are able to administer your website from any internet enabled device where ever you are. Updates you make on the website would take effect immediately in real-time.<br>
	<br>
	<a class="pc-btn" href="/pc-admin" target="_blank">Go to Control Panel</a></p>
	</li>
	<li>
	<p><strong>Granting Others Admin Access</strong><br>
	<br>
	Any admin can grant admin access to other registered users of the website. Follow the following instructions</p>

	<ul>
		<li>
		<p>Ask the individuals to create an account and request for admin access by filling this form:&nbsp;<a href="/widgets/Application_User_Creator/notify_us/create" target="_blank">Request Admin Access - {{{pc_domain}}}/widgets/Application_User_Creator/notify_us/create</a></p>
		</li>
		<li>
		<p>Go to "<a href="/pc-admin/Application_User_List">Users</a>" under Control Panel and locate the email address of the user you want to give admin access to</p>
		</li>
		<li>
		<p>Click "options" then under the options that appear click "Update Account"&nbsp;</p>
		</li>
		<li>
		<p>On the Pop Up, set the "Access Level" to "Owner" and save the settings<br>
		<br>
		<a class="pc-btn" href="/pc-admin/Application_User_List" style="word-spacing: 0px;" target="_blank">Go to Users</a></p>
		</li>
	</ul>
	</li>
</ul>

<div>&nbsp;</div>

<h2><a id="emails" name="emails">Corporate Emails</a></h2>

<p>&nbsp;</p>

<p>Fully hosted websites come with corporate emails and it is very likely that there is capacity for you to create emails like example@{{{pc_domain}}}. Only requirement in managing email accounts is Admin Access. If you don\'t have admin access and you would like to create a corporate email @{{{pc_domain}}}, you would need to either request admin access or ask someone who has the access to help create the email accounts you need. The email service for PageCarton websites are provided by ComeRiver Mail.</p>

<p>&nbsp;</p>

<ul>
	<li><strong>Creating Email Account</strong><br>
	<br>
	Creating a new email is as easy as just few clicks. Follow the link below to create new emails:<br>
	<br>
	<a class="pc-btn" href="/pc-admin/Application_User_Email_Creator" target="_blank">Create a new email account&nbsp;@{{{pc_domain}}}</a><br>
	&nbsp;</li>
	<li><strong>Managing Existing Emails</strong><br>
	<br>
	Change passwords, delete emails and manage generally all the email accounts linked to this website using a web interface. Follow the link below to manage email accounts.&nbsp;<br>
	<br>
	<a class="pc-btn" href="/pc-admin/Application_User_Email_List" target="_blank">Manage existing email accounts</a><br>
	&nbsp;</li>
	<li><strong>Sending &amp; Receiving Emails</strong><br>
	<br>
	You have several options of checking your emails. The easiest to set you is to use our webmail interface to check your email. But you can as well integrate the email account to any digital device like your mobile phones and/or computer.&nbsp;<br>
	<br>
	&nbsp;
	<ul>
		<li>Access Mail Through the Web<br>
		<br>
		This is especially useful because you could have your email anywhere you are around the world. So far you have access to the internet. Using any web browser, visit <a href="https://mail.comeriver.com">https://mail.comeriver.com</a>, then enter your email and password you created above.<br>
		<br>
		<a class="pc-btn" href="https://mail.comeriver.com" target="_blank">Check through Web&nbsp;Mail</a><br>
		&nbsp;</li>
		<li>Integrating Corporate Email to a Digital Device<br>
		<br>
		When you integrate corporate email to your digital device, emails automatically synchronizes to your phone. So that you don\'t have to keep checking for new mails every time.<br>
		&nbsp;
		<ul>
			<li>Integrating to iPhone
			<ol>
				<li>Go to iPhone "Settings"</li>
				<li>Select "Passwords &amp; Accounts"</li>
				<li>Select "Add Account"</li>
				<li>Select "Other"</li>
				<li>Select "Add Mail Account
				<ul>
					<li>Name: your full name e.g. John Bello</li>
					<li>Email: the email e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li>Password; the email password</li>
					<li>Description: Anything to describe the email</li>
				</ul>
				</li>
				<li>Select "IMAP" on the next sceen</li>
				<li>Incoming Mail Server
				<ul>
					<li>Hostname: mail.comeriver.com</li>
					<li>Username:&nbsp;the email e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li>Password: the email password</li>
				</ul>
				</li>
				<li>Outgoing Mail Server
				<ul>
					<li>Hostname: mail.comeriver.com</li>
					<li>Username:&nbsp;the email e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li>Password: the email password</li>
				</ul>
				</li>
				<li>Select "Next"</li>
				<li>After verification, the email would have being added successfully. The email will come in automatically and you can use the "Mail" app on the phone to send and receive emails from the added account.<br>
				&nbsp;</li>
			</ol>
			</li>
			<li>Integrating to Android<br>
			&nbsp;
			<ol>
				<li>Open the Gmail app and navigate to the&nbsp;<strong>Settings</strong>&nbsp;section.</li>
				<li>Tap&nbsp;<strong>Add account</strong>.</li>
				<li>Tap&nbsp;<strong>Personal (IMAP/POP)</strong>&nbsp;and then&nbsp;<strong>Next</strong>.</li>
				<li>Enter your full email address&nbsp;e.g. &nbsp;example@{{{pc_domain}}} and tap&nbsp;<strong>Next</strong>.</li>
				<li>Choose "IMAP"</li>
				<li>Enter the password for your email address and tap&nbsp;<strong>Next</strong>.</li>
				<li>
				<p id="incoming">Incoming Server Settings</p>

				<ul>
					<li>Username:&nbsp;Enter your full email address&nbsp;e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li>Password:&nbsp;Enter the password for your email account</li>
					<li>Server:&nbsp;mail.comeriver.com</li>
					<li>Port&nbsp;and&nbsp;Security Type:
					<ul>
						<li>Secure - Port: 993 and Security Type: SSL/TLS (Accept all certificates)</li>
					</ul>
					</li>
					<li>Once the settings are entered, tap&nbsp;Next.<br>
					&nbsp;</li>
				</ul>
				</li>
				<li>Outgoing Server Settings
				<ul>
					<li>Select&nbsp;<strong>Require Sign-In</strong>.</li>
					<li><strong>Username:</strong>&nbsp;Enter your full email address&nbsp;&nbsp;e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li><strong>Password:</strong>&nbsp;Enter the password for your email account</li>
					<li><strong>Server:</strong>&nbsp;mail.comeriver.com</li>
					<li>For SMTP&nbsp;<strong>Port</strong>&nbsp;and&nbsp;<strong>Security Type</strong>, choose:
					<ul>
						<li>Secure -&nbsp;<strong>Port:</strong>&nbsp;465 and&nbsp;<strong>Security Type:</strong>&nbsp;SSL/TLS (Accept all certificates)</li>
					</ul>
					</li>
					<li>Once the settings are entered, tap&nbsp;<strong>Next</strong>.<br>
					&nbsp;</li>
				</ul>
				</li>
			</ol>
			</li>
		</ul>
		</li>
	</ul>
	</li>
</ul>

<h2><a id="content" name="content">Adding Content</a></h2>

<p>&nbsp;</p>

<p>Adding content to your website is incredibly easy. There are a number of ways to add content but the easiest ways are going to be highlighted here. More advanced methods are covered in other documentations articles and forums. Content on this website is categorized into three different types:<br>
&nbsp;</p>

<ol>
	<li>Branding Content<br>
	<br>
	This includes logo, icons, banners, site title and descriptions. This is also where the site theme type is set.&nbsp;The content here are not expected to change frequently. On most cases, the things set here are never changed.&nbsp;<br>
	&nbsp;
	<ul>
		<li><a class="pc-btn" href="/pc-admin/Application_Personalization" target="_blank">Update Branding Content</a><br>
		&nbsp;</li>
	</ul>
	</li>
	<li>Static Content<br>
	<br>
	This contains some information some other content that may not change frequently but are&nbsp;likely to change more often than Branding Content. Examples are content of the About Us section, contact information etc. Basically, the items in this category are not related to one another. Static images are the photos on the website that can be easily replaced with new ones.<br>
	&nbsp;
	<ul>
		<li><a class="pc-btn" href="/pc-admin/Ayoola_Page_Layout_ReplaceText" target="_blank">Update Static Text Content</a>&nbsp;</li>
		<li><a class="pc-btn" href="/pc-admin/Ayoola_Page_Layout_Images" target="_blank">Update Static Images</a>&nbsp;<br>
		&nbsp;</li>
	</ul>
	</li>
	<li>Dynamic Content<br>
	<br>
	Dynamic contents are called "posts" because they do have related characteristics like titles, descriptions and image. Dynamic site content on PageCarton sites are content that are expected to change frequently. In fact, it allows one to add new dynamic content more frequently so that they could replace the old content on the website. When new dynamic content are added, they don\'t delete the old ones, the old ones may just be taken off the home page but they are also archived and can be accessed by search engines. This is a great way to build up site content to have a lot of information about the organization. Example dynamic contents are blog posts, event &amp; news, testimonials, products &amp; services etc. Dynamic content also have dynamic categories which can be easily updated<br>
	&nbsp;
	<ul>
		<li><a class="pc-btn" href="/pc-admin/Application_Article_Publisher" target="_blank">Add New Dynamic Content</a>&nbsp;<br>
		<br>
		Clicking the link above will display an array of the kind of dynamic posts available for this website. Just select "Add a new item" under the particular kind of post you want to add.<br>
		<br>
		On the first time a post is being added, one would have to create a profile:<br>
		<br>
		Display Name: Your full name<br>
		Profile description: A profile description for yourself<br>
		Profile URL: Url would be the page to create for your content like {{{pc_domain}}}/example&nbsp;<br>
		<br>
		On the post creation:<br>
		<br>
		"Title" is like the name of the content.<br>
		"Article Content" would be text information of the content.&nbsp;<br>
		"Cover Photo" would be the image that would accompany the content as the main photo<br>
		<br>
		Other information may be required based on the type of content being added. Most of the fields would be self explanatory.<br>
		&nbsp;</li>
		<li style="text-align: left;">To edit, delete or just change the settings for dynamic content:&nbsp;<br>
		<br>
		<a class="pc-btn" href="/pc-admin/Application_Article_List" target="_blank">Manage All Dynamic Content</a></li>
	</ul>
	</li>
</ol>

<p>&nbsp;</p>

<h2>Feedbacks &amp; Responses&nbsp;</h2>

<p>&nbsp;</p>

<p>There are features on the websites where users of the website will leave feedback on the website through pre-designed forms and other systems. When a user takes an action on the site, we have built a system to record the information in a database and such is accessible by the admin users of the website. Feedbacks could be in one of the following forms:<br>
&nbsp;</p>

<ul>
	<li><a class="pc-btn" href="/pc-admin/Ayoola_Form_List" target="_blank">Custom Forms Data</a></li>
	<li><a class="pc-btn" href="/pc-admin/Application_ContactUs_List" target="_blank">Contact Us Form Data</a></li>
	<li><a class="pc-btn" href="/pc-admin/Application_Subscription_Checkout_Order_List" target="_blank">Product &amp; Service Orders</a></li>
	<li><a class="pc-btn" href="/pc-admin/Application_User_UserEmail_Table" target="_blank">Email Opt-In</a></li>
</ul>

<p>&nbsp;</p>
',
  'preserved_content' => '<div>
<p>&nbsp;</p>

<h2><a id="admin" name="admin">Admin access</a></h2>

<p>&nbsp;</p>
</div>

<p>Your website allows you to have unlimited number of administrators â€“ people, who would have access to the backend to perform specific administrative functions. Adding new pages, editing existing pages, reading contact form feedbacks, changing logos and favicon, creating posts or adding new products and services are some of the functions users are able to perform when they have admin access to the website.</p>

<p>The direct link to your administrative backend of your website is on <a href="/pc-admin">Control Panel</a>. By default, only the website developer&nbsp;would have access to the website admin panel to update the website&nbsp;but he could grant as many individuals as needed access to do the same tasks.</p>

<p>&nbsp;</p>

<ul>
	<li>
	<p><strong>Request admin access</strong><br>
	<br>
	To be able to gain admin access to the website so you could update the website, you should request admin access. Only current admin users are able to grant admin access to others. If you are in doubt who would give you access to the website, you should talk to your website or service providers as they should have the capacity to do this for you. Meanwhile, any other person with admin access could create other admin users. Request Admin Access by following the link below.<br>
	<br>
	<a class="pc-btn" href="/widgets/Application_User_Creator/notify_us/create" target="_blank">Request Admin Access</a><br>
	<br>
	All current admin will now be notified of your request and they would grant you access easily with a few clicks<br>
	&nbsp;</p>
	</li>
	<li>
	<p><strong>Visit Control Panel</strong><br>
	<br>
	You are able to administer your website from any internet enabled device where ever you are. Updates you make on the website would take effect immediately in real-time.<br>
	<br>
	<a class="pc-btn" href="/pc-admin" target="_blank">Go to Control Panel</a></p>
	</li>
	<li>
	<p><strong>Granting Others Admin Access</strong><br>
	<br>
	Any admin can grant admin access to other registered users of the website. Follow the following instructions</p>

	<ul>
		<li>
		<p>Ask the individuals to create an account and request for admin access by filling this form:&nbsp;<a href="/widgets/Application_User_Creator/notify_us/create" target="_blank">Request Admin Access - {{{pc_domain}}}/widgets/Application_User_Creator/notify_us/create</a></p>
		</li>
		<li>
		<p>Go to "<a href="/pc-admin/Application_User_List">Users</a>" under Control Panel and locate the email address of the user you want to give admin access to</p>
		</li>
		<li>
		<p>Click "options" then under the options that appear click "Update Account"&nbsp;</p>
		</li>
		<li>
		<p>On the Pop Up, set the "Access Level" to "Owner" and save the settings<br>
		<br>
		<a class="pc-btn" href="/pc-admin/Application_User_List" style="word-spacing: 0px;" target="_blank">Go to Users</a></p>
		</li>
	</ul>
	</li>
</ul>

<div>&nbsp;</div>

<h2><a id="emails" name="emails">Corporate Emails</a></h2>

<p>&nbsp;</p>

<p>Fully hosted websites come with corporate emails and it is very likely that there is capacity for you to create emails like example@{{{pc_domain}}}. Only requirement in managing email accounts is Admin Access. If you don\'t have admin access and you would like to create a corporate email @{{{pc_domain}}}, you would need to either request admin access or ask someone who has the access to help create the email accounts you need. The email service for PageCarton websites are provided by ComeRiver Mail.</p>

<p>&nbsp;</p>

<ul>
	<li><strong>Creating Email Account</strong><br>
	<br>
	Creating a new email is as easy as just few clicks. Follow the link below to create new emails:<br>
	<br>
	<a class="pc-btn" href="/pc-admin/Application_User_Email_Creator" target="_blank">Create a new email account&nbsp;@{{{pc_domain}}}</a><br>
	&nbsp;</li>
	<li><strong>Managing Existing Emails</strong><br>
	<br>
	Change passwords, delete emails and manage generally all the email accounts linked to this website using a web interface. Follow the link below to manage email accounts.&nbsp;<br>
	<br>
	<a class="pc-btn" href="/pc-admin/Application_User_Email_List" target="_blank">Manage existing email accounts</a><br>
	&nbsp;</li>
	<li><strong>Sending &amp; Receiving Emails</strong><br>
	<br>
	You have several options of checking your emails. The easiest to set you is to use our webmail interface to check your email. But you can as well integrate the email account to any digital device like your mobile phones and/or computer.&nbsp;<br>
	<br>
	&nbsp;
	<ul>
		<li>Access Mail Through the Web<br>
		<br>
		This is especially useful because you could have your email anywhere you are around the world. So far you have access to the internet. Using any web browser, visit <a href="https://mail.comeriver.com">https://mail.comeriver.com</a>, then enter your email and password you created above.<br>
		<br>
		<a class="pc-btn" href="https://mail.comeriver.com" target="_blank">Check through Web&nbsp;Mail</a><br>
		&nbsp;</li>
		<li>Integrating Corporate Email to a Digital Device<br>
		<br>
		When you integrate corporate email to your digital device, emails automatically synchronizes to your phone. So that you don\'t have to keep checking for new mails every time.<br>
		&nbsp;
		<ul>
			<li>Integrating to iPhone
			<ol>
				<li>Go to iPhone "Settings"</li>
				<li>Select "Passwords &amp; Accounts"</li>
				<li>Select "Add Account"</li>
				<li>Select "Other"</li>
				<li>Select "Add Mail Account
				<ul>
					<li>Name: your full name e.g. John Bello</li>
					<li>Email: the email e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li>Password; the email password</li>
					<li>Description: Anything to describe the email</li>
				</ul>
				</li>
				<li>Select "IMAP" on the next sceen</li>
				<li>Incoming Mail Server
				<ul>
					<li>Hostname: mail.comeriver.com</li>
					<li>Username:&nbsp;the email e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li>Password: the email password</li>
				</ul>
				</li>
				<li>Outgoing Mail Server
				<ul>
					<li>Hostname: mail.comeriver.com</li>
					<li>Username:&nbsp;the email e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li>Password: the email password</li>
				</ul>
				</li>
				<li>Select "Next"</li>
				<li>After verification, the email would have being added successfully. The email will come in automatically and you can use the "Mail" app on the phone to send and receive emails from the added account.<br>
				&nbsp;</li>
			</ol>
			</li>
			<li>Integrating to Android<br>
			&nbsp;
			<ol>
				<li>Open the Gmail app and navigate to the&nbsp;<strong>Settings</strong>&nbsp;section.</li>
				<li>Tap&nbsp;<strong>Add account</strong>.</li>
				<li>Tap&nbsp;<strong>Personal (IMAP/POP)</strong>&nbsp;and then&nbsp;<strong>Next</strong>.</li>
				<li>Enter your full email address&nbsp;e.g. &nbsp;example@{{{pc_domain}}} and tap&nbsp;<strong>Next</strong>.</li>
				<li>Choose "IMAP"</li>
				<li>Enter the password for your email address and tap&nbsp;<strong>Next</strong>.</li>
				<li>
				<p id="incoming">Incoming Server Settings</p>

				<ul>
					<li>Username:&nbsp;Enter your full email address&nbsp;e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li>Password:&nbsp;Enter the password for your email account</li>
					<li>Server:&nbsp;mail.comeriver.com</li>
					<li>Port&nbsp;and&nbsp;Security Type:
					<ul>
						<li>Secure - Port: 993 and Security Type: SSL/TLS (Accept all certificates)</li>
					</ul>
					</li>
					<li>Once the settings are entered, tap&nbsp;Next.<br>
					&nbsp;</li>
				</ul>
				</li>
				<li>Outgoing Server Settings
				<ul>
					<li>Select&nbsp;<strong>Require Sign-In</strong>.</li>
					<li><strong>Username:</strong>&nbsp;Enter your full email address&nbsp;&nbsp;e.g. &nbsp;example@{{{pc_domain}}}</li>
					<li><strong>Password:</strong>&nbsp;Enter the password for your email account</li>
					<li><strong>Server:</strong>&nbsp;mail.comeriver.com</li>
					<li>For SMTP&nbsp;<strong>Port</strong>&nbsp;and&nbsp;<strong>Security Type</strong>, choose:
					<ul>
						<li>Secure -&nbsp;<strong>Port:</strong>&nbsp;465 and&nbsp;<strong>Security Type:</strong>&nbsp;SSL/TLS (Accept all certificates)</li>
					</ul>
					</li>
					<li>Once the settings are entered, tap&nbsp;<strong>Next</strong>.<br>
					&nbsp;</li>
				</ul>
				</li>
			</ol>
			</li>
		</ul>
		</li>
	</ul>
	</li>
</ul>

<h2><a id="content" name="content">Adding Content</a></h2>

<p>&nbsp;</p>

<p>Adding content to your website is incredibly easy. There are a number of ways to add content but the easiest ways are going to be highlighted here. More advanced methods are covered in other documentations articles and forums. Content on this website is categorized into three different types:<br>
&nbsp;</p>

<ol>
	<li>Branding Content<br>
	<br>
	This includes logo, icons, banners, site title and descriptions. This is also where the site theme type is set.&nbsp;The content here are not expected to change frequently. On most cases, the things set here are never changed.&nbsp;<br>
	&nbsp;
	<ul>
		<li><a class="pc-btn" href="/pc-admin/Application_Personalization" target="_blank">Update Branding Content</a><br>
		&nbsp;</li>
	</ul>
	</li>
	<li>Static Content<br>
	<br>
	This contains some information some other content that may not change frequently but are&nbsp;likely to change more often than Branding Content. Examples are content of the About Us section, contact information etc. Basically, the items in this category are not related to one another. Static images are the photos on the website that can be easily replaced with new ones.<br>
	&nbsp;
	<ul>
		<li><a class="pc-btn" href="/pc-admin/Ayoola_Page_Layout_ReplaceText" target="_blank">Update Static Text Content</a>&nbsp;</li>
		<li><a class="pc-btn" href="/pc-admin/Ayoola_Page_Layout_Images" target="_blank">Update Static Images</a>&nbsp;<br>
		&nbsp;</li>
	</ul>
	</li>
	<li>Dynamic Content<br>
	<br>
	Dynamic contents are called "posts" because they do have related characteristics like titles, descriptions and image. Dynamic site content on PageCarton sites are content that are expected to change frequently. In fact, it allows one to add new dynamic content more frequently so that they could replace the old content on the website. When new dynamic content are added, they don\'t delete the old ones, the old ones may just be taken off the home page but they are also archived and can be accessed by search engines. This is a great way to build up site content to have a lot of information about the organization. Example dynamic contents are blog posts, event &amp; news, testimonials, products &amp; services etc. Dynamic content also have dynamic categories which can be easily updated<br>
	&nbsp;
	<ul>
		<li><a class="pc-btn" href="/pc-admin/Application_Article_Publisher" target="_blank">Add New Dynamic Content</a>&nbsp;<br>
		<br>
		Clicking the link above will display an array of the kind of dynamic posts available for this website. Just select "Add a new item" under the particular kind of post you want to add.<br>
		<br>
		On the first time a post is being added, one would have to create a profile:<br>
		<br>
		Display Name: Your full name<br>
		Profile description: A profile description for yourself<br>
		Profile URL: Url would be the page to create for your content like {{{pc_domain}}}/example&nbsp;<br>
		<br>
		On the post creation:<br>
		<br>
		"Title" is like the name of the content.<br>
		"Article Content" would be text information of the content.&nbsp;<br>
		"Cover Photo" would be the image that would accompany the content as the main photo<br>
		<br>
		Other information may be required based on the type of content being added. Most of the fields would be self explanatory.<br>
		&nbsp;</li>
		<li style="text-align: left;">To edit, delete or just change the settings for dynamic content:&nbsp;<br>
		<br>
		<a class="pc-btn" href="/pc-admin/Application_Article_List" target="_blank">Manage All Dynamic Content</a></li>
	</ul>
	</li>
</ol>

<p>&nbsp;</p>

<h2>Feedbacks &amp; Responses&nbsp;</h2>

<p>&nbsp;</p>

<p>There are features on the websites where users of the website will leave feedback on the website through pre-designed forms and other systems. When a user takes an action on the site, we have built a system to record the information in a database and such is accessible by the admin users of the website. Feedbacks could be in one of the following forms:<br>
&nbsp;</p>

<ul>
	<li><a class="pc-btn" href="/pc-admin/Ayoola_Form_List" target="_blank">Custom Forms Data</a></li>
	<li><a class="pc-btn" href="/pc-admin/Application_ContactUs_List" target="_blank">Contact Us Form Data</a></li>
	<li><a class="pc-btn" href="/pc-admin/Application_Subscription_Checkout_Order_List" target="_blank">Product &amp; Service Orders</a></li>
	<li><a class="pc-btn" href="/pc-admin/Application_User_UserEmail_Table" target="_blank">Email Opt-In</a></li>
</ul>

<p>&nbsp;</p>
',
  'url_prefix' => '',
  'widget_options' => 
  array (
    0 => 'embed_widgets',
  ),
  'pagewidget_id' => '',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Global',
  ),
  'widget_name' => '& n b s p ;  A d m i n  a c c e s s  & n b s p ;  Y o u r  w e b s i t e  a l l o w s  y o u  t o  h a v e  u n l i m i t e d  n u m b e r  o f  a d m i n i s t r a t o r s  â € “  p e o p l e ,  w h o  w o u l d  h a v e  a c c e s s  t o  t h e  b a c k e n d  t o  p e r f o r m  s p e c i f i c  a d m i n i s t r a t i v e  f u n c t i o n s .  A d d i n g  n e w  p a g e s ,  e d i t i n g  e x i s t i n g  p a g e s ,  r e a d i n g  c o n t a c t  f o r m  f e e d b a c k s ,  c h a n g i n g  l o g o s  a n d  f a v i c o n ,  c r e a t i n g  p o s t s  o r  a d d i n g  n e w  p r o d u c t s  a n d  s e r v i c e s  a r e  s o m e  o f  t h e  f u n c t i o n s  u s e r s  a r e  a b l e  t o  p e r f o r m  w h e n  t h e y  h a v e  a d m i n  a c c e s s  t o  t h e  w e b s i t e .  T h e  d i r e c t  l i n k  t o  y o u r  a d m i n i s t r a t i v e  b a c k e n d  o f  y o u r  w e b s i t e  i s  o n  C o n t r o l  P a n e l .  B y  d e f a u l t ,  o n l y  t h e  w e b s i t e  d e v e l o p e r & n b s p ; w o u l d  h a v e  a c c e s s  t o  t h e  w e b s i t e  a d m i n  p a n e l  t o  u p d a t e  t h e  w e b s i t e & n b s p ; b u t  h e  c o u l d  g r a n t  a s  m a n y  i n d i v i d u a l s  a s  n e e d e d  a c c e s s  t o  d o  t h e  s a m e  t a s k s .  & n b s p ;  R e q u e s t  a d m i n  a c c e s s  T o  b e  a b l e  t o  g a i n  a d m i n  a c c e s s  t o  t h e  w e b s i t e  s o  y o u  c o u l d  u p d a t e  t h e  w e b s i t e ,  y o u  s h o u l d  r e q u e s t  a d m i n  a c c e s s .  O n l y  c u r r e n t  a d m i n  u s e r s  a r e  a b l e  t o  g r a n t  a d m i n  a c c e s s  t o  o t h e r s .  I f  y o u  a r e  i n  d o u b t  w h o  w o u l d  g i v e  y o u  a c c e s s  t o  t h e  w e b s i t e ,  y o u  s h o u l d  t a l k  t o  y o u r  w e b s i t e  o r  s e r v i c e  p r o v i d e r s  a s  t h e y  s h o u l d  h a v e  t h e  c a p a c i t y  t o  d o  t h i s  f o r  y o u .  M e a n w h i l e ,  a n y  o t h e r  p e r s o n  w i t h  a d m i n  a c c e s s  c o u l d  c r e a t e  o t h e r  a d m i n  u s e r s .  R e q u e s t  A d m i n  A c c e s s  b y  f o l l o w i n g  t h e  l i n k  b e l o w .  R e q u e s t  A d m i n  A c c e s s  A l l  c u r r e n t  a d m i n  w i l l  n o w  b e  n o t i f i e d  o f  y o u r  r e q u e s t  a n d  t h e y  w o u l d  g r a n t  y o u  a c c e s s  e a s i l y  w i t h  a  f e w  c l i c k s  & n b s p ;  V i s i t  C o n t r o l  P a n e l  Y o u  a r e  a b l e  t o  a d m i n i s t e r  y o u r  w e b s i t e  f r o m  a n y  i n t e r n e t  e n a b l e d  d e v i c e  w h e r e  e v e r  y o u  a r e .  U p d a t e s  y o u  m a k e  o n  t h e  w e b s i t e  w o u l d  t a k e  e f f e c t  i m m e d i a t e l y  i n  r e a l - t i m e .  G o  t o  C o n t r o l  P a n e l  G r a n t i n g  O t h e r s  A d m i n  A c c e s s  A n y  a d m i n  c a n  g r a n t  a d m i n  a c c e s s  t o  o t h e r  r e g i s t e r e d  u s e r s  o f  t h e  w e b s i t e .  F o l l o w  t h e  f o l l o w i n g  i n s t r u c t i o n s  A s k  t h e  i n d i v i d u a l s  t o  c r e a t e  a n  a c c o u n t  a n d  r e q u e s t  f o r  a d m i n  a c c e s s  b y  f i l l i n g  t h i s  f o r m : & n b s p ; R e q u e s t  A d m i n  A c c e s s  -  { { { p c _ d o m a i n } } } / w i d g e t s / A p p l i c a t i o n _ U s e r _ C r e a t o r / n o t i f y _ u s / c r e a t e  G o  t o  " U s e r s "  u n d e r  C o n t r o l  P a n e l  a n d  l o c a t e  t h e  e m a i l  a d d r e s s  o f  t h e  u s e r  y o u  w a n t  t o  g i v e  a d m i n  a c c e s s  t o  C l i c k  " o p t i o n s "  t h e n  u n d e r  t h e  o p t i o n s  t h a t  a p p e a r  c l i c k  " U p d a t e  A c c o u n t " & n b s p ;  O n  t h e  P o p  U p ,  s e t  t h e  " A c c e s s  L e v e l "  t o  " O w n e r "  a n d  s a v e  t h e  s e t t i n g s  G o  t o  U s e r s  & n b s p ;  C o r p o r a t e  E m a i l s  & n b s p ;  F u l l y  h o s t e d  w e b s i t e s  c o m e  w i t h  c o r p o r a t e  e m a i l s  a n d  i t  i s  v e r y  l i k e l y  t h a t  t h e r e  i s  c a p a c i t y  f o r  y o u  t o  c r e a t e  e m a i l s  l i k e  e x a m p l e @ { { { p c _ d o m a i n } } } .  O n l y  r e q u i r e m e n t  i n  m a n a g i n g  e m a i l  a c c o u n t s  i s  A d m i n  A c c e s s .  I f  y o u  d o n \' t  h a v e  a d m i n  a c c e s s  a n d  y o u  w o u l d  l i k e  t o  c r e a t e  a  c o r p o r a t e  e m a i l  @ { { { p c _ d o m a i n } } } ,  y o u  w o u l d  n e e d  t o  e i t h e r  r e q u e s t  a d m i n  a c c e s s  o r  a s k  s o m e o n e  w h o  h a s  t h e  a c c e s s  t o  h e l p  c r e a t e  t h e  e m a i l  a c c o u n t s  y o u  n e e d .  T h e  e m a i l  s e r v i c e  f o r  P a g e C a r t o n  w e b s i t e s  a r e  p r o v i d e d  b y  C o m e R i v e r  M a i l .  & n b s p ;  C r e a t i n g  E m a i l  A c c o u n t  C r e a t i n g  a  n e w  e m a i l  i s  a s  e a s y  a s  j u s t  f e w  c l i c k s .  F o l l o w  t h e  l i n k  b e l o w  t o  c r e a t e  n e w  e m a i l s :  C r e a t e  a  n e w  e m a i l  a c c o u n t & n b s p ; @ { { { p c _ d o m a i n } } }  & n b s p ;  M a n a g i n g  E x i s t i n g  E m a i l s  C h a n g e  p a s s w o r d s ,  d e l e t e  e m a i l s  a n d  m a n a g e  g e n e r a l l y  a l l  t h e  e m a i l  a c c o u n t s  l i n k e d  t o  t h i s  w e b s i t e  u s i n g  a  w e b  i n t e r f a c e .  F o l l o w  t h e  l i n k  b e l o w  t o  m a n a g e  e m a i l  a c c o u n t s . & n b s p ;  M a n a g e  e x i s t i n g  e m a i l  a c c o u n t s  & n b s p ;  S e n d i n g  & a m p ;  R e c e i v i n g  E m a i l s  Y o u  h a v e  s e v e r a l  o p t i o n s  o f  c h e c k i n g  y o u r  e m a i l s .  T h e  e a s i e s t  t o  s e t  y o u  i s  t o  u s e  o u r  w e b m a i l  i n t e r f a c e  t o  c h e c k  y o u r  e m a i l .  B u t  y o u  c a n  a s  w e l l  i n t e g r a t e  t h e  e m a i l  a c c o u n t  t o  a n y  d i g i t a l  d e v i c e  l i k e  y o u r  m o b i l e  p h o n e s  a n d / o r  c o m p u t e r . & n b s p ;  & n b s p ;  A c c e s s  M a i l  T h r o u g h  t h e  W e b  T h i s  i s  e s p e c i a l l y  u s e f u l  b e c a u s e  y o u  c o u l d  h a v e  y o u r  e m a i l  a n y w h e r e  y o u  a r e  a r o u n d  t h e  w o r l d .  S o  f a r  y o u  h a v e  a c c e s s  t o  t h e  i n t e r n e t .  U s i n g  a n y  w e b  b r o w s e r ,  v i s i t  h t t p s : / / m a i l . c o m e r i v e r . c o m ,  t h e n  e n t e r  y o u r  e m a i l  a n d  p a s s w o r d  y o u  c r e a t e d  a b o v e .  C h e c k  t h r o u g h  W e b & n b s p ; M a i l  & n b s p ;  I n t e g r a t i n g  C o r p o r a t e  E m a i l  t o  a  D i g i t a l  D e v i c e  W h e n  y o u  i n t e g r a t e  c o r p o r a t e  e m a i l  t o  y o u r  d i g i t a l  d e v i c e ,  e m a i l s  a u t o m a t i c a l l y  s y n c h r o n i z e s  t o  y o u r  p h o n e .  S o  t h a t  y o u  d o n \' t  h a v e  t o  k e e p  c h e c k i n g  f o r  n e w  m a i l s  e v e r y  t i m e .  & n b s p ;  I n t e g r a t i n g  t o  i P h o n e  G o  t o  i P h o n e  " S e t t i n g s "  S e l e c t  " P a s s w o r d s  & a m p ;  A c c o u n t s "  S e l e c t  " A d d  A c c o u n t "  S e l e c t  " O t h e r "  S e l e c t  " A d d  M a i l  A c c o u n t  N a m e :  y o u r  f u l l  n a m e  e . g .  J o h n  B e l l o  E m a i l :  t h e  e m a i l  e . g .  & n b s p ; e x a m p l e @ { { { p c _ d o m a i n } } }  P a s s w o r d ;  t h e  e m a i l  p a s s w o r d  D e s c r i p t i o n :  A n y t h i n g  t o  d e s c r i b e  t h e  e m a i l  S e l e c t  " I M A P "  o n  t h e  n e x t  s c e e n  I n c o m i n g  M a i l  S e r v e r  H o s t n a m e :  m a i l . c o m e r i v e r . c o m  U s e r n a m e : & n b s p ; t h e  e m a i l  e . g .  & n b s p ; e x a m p l e @ { { { p c _ d o m a i n } } }  P a s s w o r d :  t h e  e m a i l  p a s s w o r d  O u t g o i n g  M a i l  S e r v e r  H o s t n a m e :  m a i l . c o m e r i v e r . c o m  U s e r n a m e : & n b s p ; t h e  e m a i l  e . g .  & n b s p ; e x a m p l e @ { { { p c _ d o m a i n } } }  P a s s w o r d :  t h e  e m a i l  p a s s w o r d  S e l e c t  " N e x t "  A f t e r  v e r i f i c a t i o n ,  t h e  e m a i l  w o u l d  h a v e  b e i n g  a d d e d  s u c c e s s f u l l y .  T h e  e m a i l  w i l l  c o m e  i n  a u t o m a t i c a l l y  a n d  y o u  c a n  u s e  t h e  " M a i l "  a p p  o n  t h e  p h o n e  t o  s e n d  a n d  r e c e i v e  e m a i l s  f r o m  t h e  a d d e d  a c c o u n t .  & n b s p ;  I n t e g r a t i n g  t o  A n d r o i d  & n b s p ;  O p e n  t h e  G m a i l  a p p  a n d  n a v i g a t e  t o  t h e & n b s p ; S e t t i n g s & n b s p ; s e c t i o n .  T a p & n b s p ; A d d  a c c o u n t .  T a p & n b s p ; P e r s o n a l  ( I M A P / P O P ) & n b s p ; a n d  t h e n & n b s p ; N e x t .  E n t e r  y o u r  f u l l  e m a i l  a d d r e s s & n b s p ; e . g .  & n b s p ; e x a m p l e @ { { { p c _ d o m a i n } } }  a n d  t a p & n b s p ; N e x t .  C h o o s e  " I M A P "  E n t e r  t h e  p a s s w o r d  f o r  y o u r  e m a i l  a d d r e s s  a n d  t a p & n b s p ; N e x t .  I n c o m i n g  S e r v e r  S e t t i n g s  U s e r n a m e : & n b s p ; E n t e r  y o u r  f u l l  e m a i l  a d d r e s s & n b s p ; e . g .  & n b s p ; e x a m p l e @ { { { p c _ d o m a i n } } }  P a s s w o r d : & n b s p ; E n t e r  t h e  p a s s w o r d  f o r  y o u r  e m a i l  a c c o u n t  S e r v e r : & n b s p ; m a i l . c o m e r i v e r . c o m  P o r t & n b s p ; a n d & n b s p ; S e c u r i t y  T y p e :  S e c u r e  -  P o r t :  9 9 3  a n d  S e c u r i t y  T y p e :  S S L / T L S  ( A c c e p t  a l l  c e r t i f i c a t e s )  O n c e  t h e  s e t t i n g s  a r e  e n t e r e d ,  t a p & n b s p ; N e x t .  & n b s p ;  O u t g o i n g  S e r v e r  S e t t i n g s  S e l e c t & n b s p ; R e q u i r e  S i g n - I n .  U s e r n a m e : & n b s p ; E n t e r  y o u r  f u l l  e m a i l  a d d r e s s & n b s p ; & n b s p ; e . g .  & n b s p ; e x a m p l e @ { { { p c _ d o m a i n } } }  P a s s w o r d : & n b s p ; E n t e r  t h e  p a s s w o r d  f o r  y o u r  e m a i l  a c c o u n t  S e r v e r : & n b s p ; m a i l . c o m e r i v e r . c o m  F o r  S M T P & n b s p ; P o r t & n b s p ; a n d & n b s p ; S e c u r i t y  T y p e ,  c h o o s e :  S e c u r e  - & n b s p ; P o r t : & n b s p ; 4 6 5  a n d & n b s p ; S e c u r i t y  T y p e : & n b s p ; S S L / T L S  ( A c c e p t  a l l  c e r t i f i c a t e s )  O n c e  t h e  s e t t i n g s  a r e  e n t e r e d ,  t a p & n b s p ; N e x t .  & n b s p ;  A d d i n g  C o n t e n t  & n b s p ;  A d d i n g  c o n t e n t  t o  y o u r  w e b s i t e  i s  i n c r e d i b l y  e a s y .  T h e r e  a r e  a  n u m b e r  o f  w a y s  t o  a d d  c o n t e n t  b u t  t h e  e a s i e s t  w a y s  a r e  g o i n g  t o  b e  h i g h l i g h t e d  h e r e .  M o r e  a d v a n c e d  m e t h o d s  a r e  c o v e r e d  i n  o t h e r  d o c u m e n t a t i o n s  a r t i c l e s  a n d  f o r u m s .  C o n t e n t  o n  t h i s  w e b s i t e  i s  c a t e g o r i z e d  i n t o  t h r e e  d i f f e r e n t  t y p e s :  & n b s p ;  B r a n d i n g  C o n t e n t  T h i s  i n c l u d e s  l o g o ,  i c o n s ,  b a n n e r s ,  s i t e  t i t l e  a n d  d e s c r i p t i o n s .  T h i s  i s  a l s o  w h e r e  t h e  s i t e  t h e m e  t y p e  i s  s e t . & n b s p ; T h e  c o n t e n t  h e r e  a r e  n o t  e x p e c t e d  t o  c h a n g e  f r e q u e n t l y .  O n  m o s t  c a s e s ,  t h e  t h i n g s  s e t  h e r e  a r e  n e v e r  c h a n g e d . & n b s p ;  & n b s p ;  U p d a t e  B r a n d i n g  C o n t e n t  & n b s p ;  S t a t i c  C o n t e n t  T h i s  c o n t a i n s  s o m e  i n f o r m a t i o n  s o m e  o t h e r  c o n t e n t  t h a t  m a y  n o t  c h a n g e  f r e q u e n t l y  b u t  a r e & n b s p ; l i k e l y  t o  c h a n g e  m o r e  o f t e n  t h a n  B r a n d i n g  C o n t e n t .  E x a m p l e s  a r e  c o n t e n t  o f  t h e  A b o u t  U s  s e c t i o n ,  c o n t a c t  i n f o r m a t i o n  e t c .  B a s i c a l l y ,  t h e  i t e m s  i n  t h i s  c a t e g o r y  a r e  n o t  r e l a t e d  t o  o n e  a n o t h e r .  S t a t i c  i m a g e s  a r e  t h e  p h o t o s  o n  t h e  w e b s i t e  t h a t  c a n  b e  e a s i l y  r e p l a c e d  w i t h  n e w  o n e s .  & n b s p ;  U p d a t e  S t a t i c  T e x t  C o n t e n t & n b s p ;  U p d a t e  S t a t i c  I m a g e s & n b s p ;  & n b s p ;  D y n a m i c  C o n t e n t  D y n a m i c  c o n t e n t s  a r e  c a l l e d  " p o s t s "  b e c a u s e  t h e y  d o  h a v e  r e l a t e d  c h a r a c t e r i s t i c s  l i k e  t i t l e s ,  d e s c r i p t i o n s  a n d  i m a g e .  D y n a m i c  s i t e  c o n t e n t  o n  P a g e C a r t o n  s i t e s  a r e  c o n t e n t  t h a t  a r e  e x p e c t e d  t o  c h a n g e  f r e q u e n t l y .  I n  f a c t ,  i t  a l l o w s  o n e  t o  a d d  n e w  d y n a m i c  c o n t e n t  m o r e  f r e q u e n t l y  s o  t h a t  t h e y  c o u l d  r e p l a c e  t h e  o l d  c o n t e n t  o n  t h e  w e b s i t e .  W h e n  n e w  d y n a m i c  c o n t e n t  a r e  a d d e d ,  t h e y  d o n \' t  d e l e t e  t h e  o l d  o n e s ,  t h e  o l d  o n e s  m a y  j u s t  b e  t a k e n  o f f  t h e  h o m e  p a g e  b u t  t h e y  a r e  a l s o  a r c h i v e d  a n d  c a n  b e  a c c e s s e d  b y  s e a r c h  e n g i n e s .  T h i s  i s  a  g r e a t  w a y  t o  b u i l d  u p  s i t e  c o n t e n t  t o  h a v e  a  l o t  o f  i n f o r m a t i o n  a b o u t  t h e  o r g a n i z a t i o n .  E x a m p l e  d y n a m i c  c o n t e n t s  a r e  b l o g  p o s t s ,  e v e n t  & a m p ;  n e w s ,  t e s t i m o n i a l s ,  p r o d u c t s  & a m p ;  s e r v i c e s  e t c .  D y n a m i c  c o n t e n t  a l s o  h a v e  d y n a m i c  c a t e g o r i e s  w h i c h  c a n  b e  e a s i l y  u p d a t e d  & n b s p ;  A d d  N e w  D y n a m i c  C o n t e n t & n b s p ;  C l i c k i n g  t h e  l i n k  a b o v e  w i l l  d i s p l a y  a n  a r r a y  o f  t h e  k i n d  o f  d y n a m i c  p o s t s  a v a i l a b l e  f o r  t h i s  w e b s i t e .  J u s t  s e l e c t  " A d d  a  n e w  i t e m "  u n d e r  t h e  p a r t i c u l a r  k i n d  o f  p o s t  y o u  w a n t  t o  a d d .  O n  t h e  f i r s t  t i m e  a  p o s t  i s  b e i n g  a d d e d ,  o n e  w o u l d  h a v e  t o  c r e a t e  a  p r o f i l e :  D i s p l a y  N a m e :  Y o u r  f u l l  n a m e  P r o f i l e  d e s c r i p t i o n :  A  p r o f i l e  d e s c r i p t i o n  f o r  y o u r s e l f  P r o f i l e  U R L :  U r l  w o u l d  b e  t h e  p a g e  t o  c r e a t e  f o r  y o u r  c o n t e n t  l i k e  { { { p c _ d o m a i n } } } / e x a m p l e & n b s p ;  O n  t h e  p o s t  c r e a t i o n :  " T i t l e "  i s  l i k e  t h e  n a m e  o f  t h e  c o n t e n t .  " A r t i c l e  C o n t e n t "  w o u l d  b e  t e x t  i n f o r m a t i o n  o f  t h e  c o n t e n t . & n b s p ;  " C o v e r  P h o t o "  w o u l d  b e  t h e  i m a g e  t h a t  w o u l d  a c c o m p a n y  t h e  c o n t e n t  a s  t h e  m a i n  p h o t o  O t h e r  i n f o r m a t i o n  m a y  b e  r e q u i r e d  b a s e d  o n  t h e  t y p e  o f  c o n t e n t  b e i n g  a d d e d .  M o s t  o f  t h e  f i e l d s  w o u l d  b e  s e l f  e x p l a n a t o r y .  & n b s p ;  T o  e d i t ,  d e l e t e  o r  j u s t  c h a n g e  t h e  s e t t i n g s  f o r  d y n a m i c  c o n t e n t : & n b s p ;  M a n a g e  A l l  D y n a m i c  C o n t e n t  & n b s p ;  F e e d b a c k s  & a m p ;  R e s p o n s e s & n b s p ;  & n b s p ;  T h e r e  a r e  f e a t u r e s  o n  t h e  w e b s i t e s  w h e r e  u s e r s  o f  t h e  w e b s i t e  w i l l  l e a v e  f e e d b a c k  o n  t h e  w e b s i t e  t h r o u g h  p r e - d e s i g n e d  f o r m s  a n d  o t h e r  s y s t e m s .  W h e n  a  u s e r  t a k e s  a n  a c t i o n  o n  t h e  s i t e ,  w e  h a v e  b u i l t  a  s y s t e m  t o  r e c o r d  t h e  i n f o r m a t i o n  i n  a  d a t a b a s e  a n d  s u c h  i s  a c c e s s i b l e  b y  t h e  a d m i n  u s e r s  o f  t h e  w e b s i t e .  F e e d b a c k s  c o u l d  b e  i n  o n e  o f  t h e  f o l l o w i n g  f o r m s :  & n b s p ;  C u s t o m  F o r m s  D a t a  C o n t a c t  U s  F o r m  D a t a  P r o d u c t  & a m p ;  S e r v i c e  O r d e r s  E m a i l  O p t - I n  & n b s p ;',
) );

							}
							else
							{
								
$_7e559ca9f527527b35db37ac74db2c6d = null;

							}
							