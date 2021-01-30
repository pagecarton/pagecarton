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
* @version $Id: pc-tutorials.php	Saturday 30th of January 2021 08:31:39 PM	ayoola@ayoo.la $ 
*/
//	Page Include Content

							if( Ayoola_Loader::loadClass( 'Ayoola_Page_Editor_Text' ) )
							{
								
$_62811ae5aaaa125443b3142baae4a203 = new Ayoola_Page_Editor_Text( array (
  'editable' => '<div>
<p>&nbsp;</p>

<h2><a id="admin" name="admin">Admin access</a></h2>

<p>&nbsp;</p>
</div>

<p>Your website allows you to have unlimited number of administrators – people, who would have access to the backend to perform specific administrative functions. Adding new pages, editing existing pages, reading contact form feedbacks, changing logos and favicon, creating posts or adding new products and services are some of the functions users are able to perform when they have admin access to the website.</p>

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

<h2><a id="feedback" name="feedback">Feedbacks &amp; Responses&nbsp;</a></h2>

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

<p>Your website allows you to have unlimited number of administrators – people, who would have access to the backend to perform specific administrative functions. Adding new pages, editing existing pages, reading contact form feedbacks, changing logos and favicon, creating posts or adding new products and services are some of the functions users are able to perform when they have admin access to the website.</p>

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
    1 => 'wrappers',
  ),
  'pagewidget_id' => '1575534212-0-17',
  'markup_template_object_name' => 
  array (
    0 => 'Application_Global',
  ),
  'wrapper_name' => 'white-well',
  'content' => '<div>
<p>&nbsp;</p>

<h2><a id="admin" name="admin">Admin access</a></h2>

<p>&nbsp;</p>
</div>

<p>Your website allows you to have unlimited number of administrators – people, who would have access to the backend to perform specific administrative functions. Adding new pages, editing existing pages, reading contact form feedbacks, changing logos and favicon, creating posts or adding new products and services are some of the functions users are able to perform when they have admin access to the website.</p>

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

<h2><a id="feedback" name="feedback">Feedbacks &amp; Responses&nbsp;</a></h2>

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
) );

							}
							else
							{
								
$_62811ae5aaaa125443b3142baae4a203 = null;

							}
							
							if( Ayoola_Loader::loadClass( 'Ayoola_Menu' ) )
							{
								
$_e2bfc504239e8d32ae90e710ee38d29b = new Ayoola_Menu( array (
  'option' => 'menu_1567195621',
  'template_name' => 'BlackAccordionSide-menu',
  'pagewidget_id' => '1575534213-0-18',
  'insert_id' => '1567195628-0-2',
) );

							}
							else
							{
								
$_e2bfc504239e8d32ae90e710ee38d29b = null;

							}
							