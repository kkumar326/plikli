<?php
// header
	$lang['plikli_version'] ='4.1.0';
	$lang['installer'] ='安装程序';
	$lang['Welcome'] ='欢迎';
	$lang['Install'] ='安装';
	$lang['Upgrade'] ='升级';
	$lang['Upgrade-Kliqqi'] ='升级 - Kliqqi';
	$lang['Upgrade-Pligg'] ='Upgrade-Pligg';
	$lang['Troubleshooter'] ='疑难解答';
	$lang['Step'] ='Step';
	$lang['Readme'] ='自述';
	$lang['Admin'] ='仪表板';
	$lang['Home'] ='家';
	$lang['Install_instruct'] ='请将您的MySQL信息放在手边。 请参阅升级以升级现有站点。';
	$lang['Upgrade_instruct'] ='升级将修改你的MySQL数据库。 请务必在继续之前进行备份。';
	$lang['Troubleshooter_instruct'] ='故障排除程序将检测常见问题，例如文件夹权限不正确';

// intro / step 1
	$lang['WelcomeToInstaller'] = '欢迎进入Plikli安装程序!';
	$lang['Introduction'] = '简介';
	$lang['WelcomeToThe'] = '欢迎使用<a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer"> Plikli </a>，这是为数千个社区网站提供支持的CMS。 如果这是您第一次安装Plikli CMS，请仔细阅读所有提供的说明，以免错过任何重要指示。';
	$lang['Bugs'] = '请熟悉Plikli社区在<a href="https://www.plikli.com/forum-2/"> Plikli Forum </a>网站上提供的一些文档。 我们还建议您注册一个帐户，以便您可以访问免费支持，模块，小部件，模板和其他重要资源。';
	$lang['Installation'] = '安装（请仔细阅读）';
	$lang['OnceFamiliar'] = '<p>如果这是您第一次安装Plikli，请在仔细按照以下说明操作后继续阅读此页面。 如果您需要从以前的版本<a href="./upgrade.php">升级您的站点</a>，请单击上面的升级链接运行升级脚本。 警告：在现有Plikli站点数据库上运行安装过程将覆盖所有数据，因此如果您选择在下面继续，请确保您要执行安装。</p> <br />
<ol>
	<li>将settings.php.default重命名为settings.php </li>
	<li>将/languages/lang_english.conf.default重命名为lang_english.conf </li>
	<li>将/libs/dbconnect.php.default重命名为dbconnect.php </li>
	<li>将目录/logs.default重命名为/ logs </li>
	<li> CHMOD 0777以下文件夹：
		<ol>
			<li>/管理/备份/</li>
			<li>/化身/ groups_uploaded/</li>
			<li>/化身/ user_uploaded/</li>
			<li>/高速缓存/</li>
			<li> / languages /（CHMOD 0777此文件夹中包含的所有文件）</li>
		</ol>
	</li>
	<li> CHMOD 0666以下文件:
		<ol>
			<li>/libs/dbconnect.php</li>
			<li>的settings.php</li>
		</ol>
	</li>
</ol>
<p>你现在已经过了最困难的部分！ 继续下一步，将Plikli安装到MySQL数据库中。</ p>';

// step 2
	$lang['EnterMySQL'] ='在下面输入您的MySQL数据库设置。 如果您不了解MySQL数据库设置，则应检查您的webhost文档或直接与他们联系。';
	$lang['DatabaseName'] ='数据库名';
	$lang['DatabaseUsername'] ='数据库用户名';
	$lang['DatabasePassword'] ='数据库密码';
	$lang['DatabaseServer'] ='数据库服务器';
	$lang['TablePrefix'] ='表前缀';
	$lang['PrefixExample'] ='（即："plikli_"使用户的表成为plikli_users）';
	$lang['CheckSettings'] ='检查设置';
	$lang['Errors'] ='<br /> <br />请修正上述错误，然后<a class ="btn btn-default btn-xs" onClick ="document.location.reload（true ）">刷新页面</a>';
	$lang['LangNotFound'] ='没找到。 请从所有语言文件中删除".default"扩展名，然后重试。';

// step 3
	$lang['ConnectionEstab'] ='建立数据库连接......';
	$lang['FoundDb'] ='找到数据库......';
	$lang['dbconnect'] ='"/libs/dbconnect.php" 已成功更新。';
	$lang['NoErrors'] ='没有错误，继续下一步......';
	$lang['Next'] ='下一步';
	$lang['GoBack'] ='回去再试一次';
	$lang['Error2-1'] ='无法写入 "/libs/dbconnect.php" 文件。';
	$lang['Error2-2'] ='无法打开 "/libs/dbconnect.php" 文件进行写作。';
	$lang['Error2-3'] ='连接到数据库，但数据库名称不正确。';
	$lang['Error2-4'] ='无法使用提供的信息连接到数据库服务器。';

// step 4
	$lang['CreatingTables'] ='创建数据库表';
	$lang['TablesGood'] ='<strong>表格已成功创建！</strong>';
	$lang['Error3-1'] ='<p>创建表时出现问题。</p>';
	$lang['Error3-2'] ='<p>无法连接数据库。</p>';
	$lang['EnterAdmin'] ='<p> <strong>在下面输入您的管理员帐户详细信息：</strong> <br />请写下此帐户信息，因为需要登录并配置您的网站。</p>';
	$lang['AdminLogin'] ='管理员登录';
	$lang['AdminPassword'] ='管理员密码';
	$lang['ConfirmPassword'] ='确认密码';
	$lang['AdminEmail'] ='管理员电子邮件';
	$lang['SiteTitleLabel'] ='网站名称';
	$lang['CreateAdmin'] ='创建管理员帐户';
	$lang['pwndPassword'] ='您输入了一个常见且不安全的密码！';

// Step 5
	$lang['Error5-1'] ='请填写管理员帐户的所有字段。';
	$lang['Error5-2'] ='密码字段不匹配。请返回并重新输入密码字段。';
	$lang['AddingAdmin'] ='添加管理员用户帐户...';
	$lang['InstallSuccess'] ='安装完成！';
	$lang['InstallSuccessMessage'] ='恭喜，您已经建立了Plikli CMS网站！虽然您的网站目前功能齐全，但您仍需要按照以下说明进行清理，以确保您的网站安全。';
	$lang['WhatToDo'] ='下一步做什么：';
	$lang['WhatToDoList'] ='<li> chmod“/libs/dbconnect.php”返回644，我们不需要再次更改此文件。</li>
	如果您已成功安装Plikli，则<li> <strong>删除</strong>服务器中的“/ install”目录。</li>
	<li>使用您在上一步中输入的用户信息登录<a href="../admin/admin_index.php">信息中心</a>。登录后，您应该会看到有关如何使用Plikli的更多信息。</li>
	<li>使用信息中心<a href="../admin/admin_config.php">配置您的网站</a>。</li>
	<li>如果您有任何疑问，请访问<a href="https://www.plikli.com/forum-2/"> Plikli支持</a>网站。</li>';
	$lang['ContinueToSite'] ='继续浏览新网站';

// Upgrade
	$lang['UpgradeHome'] ='<p style =“text-decoration：underline”>升级使<STRONG> IRIRVERSIBLE </STRONG>更改为您的数据库。 在您进行任何进一步的操作之前备份您的数据库表格并确保您的数据库出现在“已确认的DROP TABLE”中！</p> <p>如果您已经对您的核心文件进行了任何定制，请不要继续进行升级。 首先，你必须做以下事项：</p>
	<UL>
	<li>使用备份文件的副本，使用WINMERGE或类似软件将新文件与新的KLIQQI文件合并！</li>
	<li>如果您使用的不是默认的BOOTSTRAP模板，请确保您使用KLIQQI BOOTSTRAP模板合并其文件，因为已经对该代码进行了一些更改。 （升级完成后的注释）</li>
	</UL>
	<br />
	将旧的Pligg或Kliqqi或Plikli 4.0.0版本升级到Plikli。'; 
	$lang['plikli_version'] = '将数据库表修改为最新版本。<br />';
	$lang['UpgradeAreYouSure'] = 'Are you sure you want to upgrade your database and language file?';
	$lang['UpgradeYes'] = 'Proceed with Upgrade';
	$lang['UpgradeLanguage'] = 'Success, Plikli updated your language file. It now includes the latest language items.';
	$lang['UpgradingTables'] = '<strong>Upgrading Database...</strong>';
	$lang['LanguageUpdate'] = '<strong>Upgrading Language File...</strong>';
	$lang['IfNoError'] = 'If there were no errors displayed, upgrade is complete!';
	$lang['PleaseFix'] = 'Please fix the above error(s), upgrade halted!';
	
// Errors
	$lang['NotFound'] ='找不到！';
	$lang['CacheNotFound'] ='找不到！ 在根目录中创建一个名为/ cache的目录，并将其设置为CHMOD 777。';
	$lang['DbconnectNotFound'] ='找不到！ 尝试将dbconnect.php.default重命名为dbconnect.php';
	$lang['SettingsNotFound'] ='未找到！ 尝试将settings.php.default重命名为settings.php';
	$lang['ZeroBytes'] ='是0字节。';
	$lang['NotEditable'] ='不可写。 请把它改为777';
	
?>