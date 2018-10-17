<p align="center">
  <img height="100" src="https://www.plikli.com/wp-content/uploads/2018/02/plikli.png">
</p>

___
<b>Plikli is an open source content management system (CMS) that lets you easily create your own user-powered website for blogging, link sharing and creating a social community like Reddit.</b>
___

[![Generic badge](https://img.shields.io/badge/version-4.1.0-green.svg)](https://github.com/Plikli-CMS/Plikli-CMS/releases)
[![Github all releases](https://img.shields.io/github/downloads/Naereen/StrapDown.js/total.svg)](https://github.com/Plikli-CMS/Plikli-CMS/releases)
[![MIT license](https://img.shields.io/badge/License-MIT-blue.svg)](https://lbesson.mit-license.org/)
[![Open Source Love png1](https://badges.frapsoft.com/os/v1/open-source.png?v=103)](https://github.com/ellerbrock/open-source-badges/)

___

<h2>Table of Contents</h2>

<ol>
<li><a href="#features">Features</a></li>
<li><a href="#installation">Installation</a></li>
<ul>
<li><a href="#requirements">Requirements</a></li>
<li><a href="#troubleshooter">Troubleshooter</a></li>
<li><a href="#install-steps">Install Steps</a></li>
</ul>
<li><a href="#upgrade">Upgrade</a></li>
<ul>
<li><a href="#backup">Backup</a></li>
<li><a href="#version-verify">Version Verify</a></li>
<li><a href="#upgrade-steps">Upgrade Steps</a></li>
</ul>
<li><a href="#documentation">Documentation</a></li>
<ul>
<li><a href="#text">Text</a></li>
<li><a href="#video">Videos</a></li>
</ul>
<li><a href="#support">User Support</a></li>
<li><a href="#contribution">Contribution</a></li>
<li><a href="#credits">Credits</a></li>
<li><a href="#license">License</a></li>
</ol>

___

<h3 id="features">Features</h3>
<ul>
<li>Users can submit Links</li>
<li>Users can write Blogs</li>
<li>Users can Upvote/ Downvote stories</li>
<li>Users can save their favourite contents</li>
<li>Users can create and share in Groups</li>
<li>Users can comment on stories</li>
<li>Users can follow others</li>
<li>Integrated Spam Control</li>
<li>Karma integration</li>
<li>SEO optimized</li>
<li>Live News ticker based on user's preference</li>
<li>Dozens of language support</li>
<li>External modules and theme integration</li>
<li>Hundreds of Dashboard options to optimize the platform as per user needs</li>
<li>Dozen of amazing free modules already integrated</li>
<li>Marketplace for users and module and theme developers</li>
<li>and many more amazing features...</li>
</ul>

___
<h3 id="installation">Installation</h3>

<h4 id="requirements">Requirements:</h4>

<b>PHP</b><br>
<small>** NOTE that Plikli is NOT yet compatible with PHP version 7; It is in progress!</small>
<p>Plikli CMS has been tested on PHP version 5.4+. We have designed the Content Management System based on PHP 5.4+ technologies, so certain problems may occur when using older versions of PHP. We recommend that your server runs a minimum of PHP 5.4+.</p>
<p>If your server runs on PHP version higher than 5:
Check the cPanel under SOFTWARE -> MultiPHP Manager. if it is available and you can select the PHP version you want, then set it. Otherwise, ask your host to install EasyApache so you can have access to MultiPHP Manager.</p>

<b>PHP Extensions</b>
<ul>
<li>cURL PHP</li>
<li>GD Graphics Library</li>
</ul>


<b>PHP Functions</b><br>
<small>Can be checked and set in cPanel under MultiPHP INI Editor.</small>

<ul>
<li>fopen (PHP Directive: allow_url_fopen)</li>
<li>file_get_contents (PHP Directive: allow_url_fopen)</li>
<li>fwrite (Function is used in conjunction with the fopen Function)</li>
</ul>

<b>MySQL</b>
<p>Plikli has been tested on both MySQL versions 4 and 5, during that process we have discovered that bugs will occasionally pop up if you are running MySQL 4. For this reason it is recommend that you use a server with MySQL 5.0.3+ or later to run a Plikli CMS website.</p>
			

<h4 id="troubleshooter">Troubleshooter:</h4>

<p>To test if your server is capable of running Plikli please view the "Troubleshooter". If any errors appear on that page you may have a problem with either installing or running Plikli.</p>
				<p style="color:#ff0000;">But, fear not, other than the requirement number 1 (Create a mysql database), Plikli Installation system will fix all the other requirements for you! The requirements listed below are just for your info and to get familiar with them and make sure that this is what you need to install Plikli CMS.</p>
                <p style="color:#ff0000;">Once you read the readme page, proceed to "Troubleshooter" in the navigation menu and follow the process that shows you the readiness to proceed with the installation. Once you have finished with the Troubleshooter, Click on the "Install" link in the navigation menu!</p>

<h4 id="install-steps">Install Steps:</h4>

<ol>
					<li>Create a mysql database. If you are unfamiliar with how to create a mysql database, please contact your web host or search their support site. Please pay careful attention when creating a database and write down your database name, username, password, and host somewhere.</li>
					<li>Rename the /favicon.ico.default to /favicon.ico</li>
					<li>Rename the /settings.php.default to /settings.php</li>
					<li>Rename the /languages/lang_english.conf.default file to lang_english.conf. Same instructions apply to any other language file that you might use that are located in the /languages directory.</li>
					<li>Rename the /libs/dbconnect.php.default file to dbconnect.php</li>
					<li>Rename the directory /logs.default to /logs</li>
					<li>Upload the files to your server.</li>
					<li>CHMOD 755 the following directories and files. If you experience any errors try 777.
						<ul>
							<li>/admin/backup/</li>
							<li>/avatars/groups_uploaded/</li>
							<li>/avatars/user_uploaded/</li>
							<li>/cache/</li>
							<li>/languages/ (all files contained in this folder should be CHMOD 777)</li>
							<li>/logs/ (all files contained should be CHMOD 777)</li>
						</ul>
					</li>
					<li>CHMOD 666 the following files
						<ul>
							<li>/libs/dbconnect.php</li>
							<li>/settings.php</li>
						</ul>
					</li>
					<li>Open /install/index.php in your web browser. If you are reading this document after you uploaded it to your server, click on the install link at the top of the page.
						<ul>
							<li>Select a language from the list. </li>
							<li>Fill out your database name, username, password, host, and your desired table prefix.</li>
							<li>Create an admin account. Please write down the login credentials for future reference.</li>
							<li>Make sure there are no error messages! If you see an error message, or if installation fails, <a href="https://www.plikli.com/forum-2/"  target="_blank" rel="noopener noreferrer">report it here</a>.</li>
						</ul>
					</li>
					<li>Delete your /install folder.</li>
					<li>CHMOD 644 libs/dbconnect.php</li>
					<li>Open /index.php</li>
					<li>Log in to the admin account using the credentials generated during the install process.</li>
					<li>Log in to the admin panel ( /admin ).</li>
					<li>Configure your Plikli site to your liking.</li> 
				</ol>

___

<h3 id="upgrade">Upgrade</h3>

<h4 id="backup">Backup:</h4>
<ol>
					<li>Log into your site as admin</li>
					<li>Click on admin panel link</li>
					<li>Click on File and MySQL backup link</li>
					<li>Backup your files, avatars, and MySQL database</li>
					<li>Download the backup .zip files to your computer</li>
					<li>Delete the files from the backup manager</li>
				</ol>

<h4 id="version-verify">Version Verify:</h4>
				<p>For easy access, the Plikli Admin Panel's "Statistics" widget displays your Plikli CMS Version. Plikli stores your version number in a MySQL database under the plikli_misc_data table. If you have access to a tool to view your MySQL database, like PhpMyAdmin, you will find that the data is stored under the name "plikli_version". </p>
				<p>To upgrade to the latest version of Plikli CMS, make sure that you run the file /install/upgrade.php in your browser after uploading the latest version Plikli files to your server. If you forget to take this step your database may not be up to date with the latest version, causing the version number displayed in the Admin Panel to be incorrect.</p>

<h4 id="upgrade-steps">Upgrade Steps:</h4>
<div class="well">
				Please be sure to <strong>make a backup of your MySQL databases	and files before upgrading</strong> to the latest version of Plikli. Some upgrades might require that you upgrade your MySQL database, so please make backups whenever upgrading.<br />
				These instructions do not support upgrading your template. Template upgrades require manual changes and should be carefully handled by the template author.<br />
				The directions below are recommendations that we have come up with to mininimize the number of problems that might come from upgrading your site.
				</div>
				<ol>
					<li>Backup your MySQL database</li>
					<li>Backup your old Plikli CMS files.</li>
					<li>Rename your template folder so that the data is not overwritten by upgrade files. If you use a template name that is not included with Plikli, skip this step.</li>
					<li> Disable all of the modules from your admin panel. <br />
					You will need to re-enable them after upgrade.</li>
					<li>Delete all of the files from your server EXCEPT:
					   <ol>
						   <li>/avatars/ (entire directory)</li>
						   <li>/libs/dbconnect.php</li>
						   <li>/favicon.ico</li>
						   <li>/settings.php</li>
					   </ol>
					</li>
					<li>Upload all of the new files to your server. <br />
					If you are able to, I suggest uploading a zip file to the server and extracting it server-side. This will minimize the chances of files being corrupted during FTP transfer, which is a common issue.<br />
					<li>Confirm that the CHMOD permissions for the following files or directories are all set to 777.</li>
						<ul>
							<li>/settings.php</li>
							<li>/admin/backup/</li>
							<li>/avatars/groups_uploaded/</li>
							<li>/avatars/user_uploaded/</li>
							<li>/cache/</li>
							<li>/languages/ (and all of the content within this directory)</li>
						</ul>
					<li>Run the upgrade from /install/upgrade.php<br />
						If there are no error messages, delete the /install directory from your web server.</li>
					<li>Navigate to your homepage and log in as an "admin" level user.<br />
						In some cases you may already be logged in from your previous version of Plikli.</li>
					<li>Either update your template to work with the latest version or navigate to /admin/admin_config.php?page=Template and change the template name to "bootstrap"</li>
					<li>Re-activate the disabled modules from step 4</li>
				</ol>
                
___

<h3 id="documentation">Documentation</h3>

<h4 id="text">Text:</h4>
<p>Coming Soon...</p>

<h4 id="video">Videos:</h4>
<p>Coming Soon...</p>


___


<h3 id="support">User Support</h3>
<p>Plikli is an Open source project, but that doesn't stop us from having a rock-solid support team. Users from all across the world are watching the forum 24 hours a day helping each other out. If you have any questions, ideas, modifications or bugs to discuss please let us know through the Plikli forum.</p>

<legend>Questions / Comments?</legend>
				<p>General questions and comments can be posted to the <a href="https://www.plikli.com/forum-2/" target="_blank" rel="noopener noreferrer">Plikli Support</a> website.</p>
				<p>Please report security flaws through our <a href="https://www.plikli.com/contact/" target="_blank" rel="noopener noreferrer">Contact Form</a> on plikli.com. You may also use the contact form to offer your help in developing the project. Do not contact us directly for any other purposes, as we will ignore any messages outside of those two categories.</p>
				<p>Bugs can be reported using our <a href="https://www.plikli.com/forum-2/" target="_blank" rel="noopener noreferrer">forum</a>.</p>


___


<h3 id="contribution">Contribution</h3>

<p>Many thanks to those who donated time and money to the Plikli project. Without their support we wouldn't be able to keep this project going. If you are interested in becoming a part of the development team please <a href="https://www.plikli.com/contact/" target="_blank" rel="noopener noreferrer">contact us through plikli.com</a>.</p>

___

<h3 id="credits">Credits</h3>
<p>This code was originally written by Ricardo Galli for the open source project known as <a href="http://www.meneame.net">Meneame</a>.</p>
				
<p>Graphic design elements by the following authors or projects:
					<ul>
						<li><a href="http://glyphicons.com/" rel="nofollow" target="_blank" rel="noopener noreferrer">Bootstrap icons by Glyphicons.com</a></li>
						<li><a href="http://vervex.deviantart.com/art/Somacro-29-300DPI-Social-Media-Icons-267955425?" rel="nofollow" target="_blank" rel="noopener noreferrer">Social Media Icons by Vervex</a></li>
					</ul>
				
<p><strong>A special thank you to all of the Plikli developers and translators.</strong></p>

___

<h3 id="license">License</h3>

<b>MIT License</b>

Copyright (c) [year] [fullname]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
