<?php
// header
	$lang['installer'] = '安装程序';
	$lang['Welcome'] = '欢迎';
	$lang['Install'] = '安装';
	$lang['Upgrade'] = '升级';
	$lang['Upgrade-Kliqqi'] = 'Upgrade-Kliqqi';
	$lang['Upgrade-Pligg'] = 'Upgrade-Pligg';
	$lang['Troubleshooter'] = '疑难解答';
	$lang['Step'] = '步';
	$lang['Readme'] = '自述';
	$lang['Admin'] = '管理员';
	$lang['Home'] = '首页';
	$lang['Install_instruct'] = '请让你的MySQL信息方便. 请参阅升级以升级现有网站.';
	$lang['Upgrade_instruct'] = '升级将会修改你的MySQL数据库. 继续操作之前一定要备份.';
	$lang['Troubleshooter_instruct'] = '疑难解答程序将检测常见问题，如不正确的文件夹权限';

// intro / step 1
	$lang['WelcomeToInstaller'] = '欢迎进入Plikli安装程序!';
	$lang['Introduction'] = '简介';
	$lang['WelcomeToThe'] = '欢迎来到<a href="https://www.plikli.com" target="_blank"> Plikli </a>，这是为数千个社区网站提供支持的CMS。 如果这是您第一次安装Plikli CMS，请仔细阅读所有提供的说明，以免错过任何重要的指示。';
	$lang['Bugs'] = '请熟悉一下Plikli社区在<a href="https://www.plikli.com/forum-2/"> Plikli论坛</a>网站上提供的一些文档。 我们还建议您注册一个帐户，以便您可以访问免费支持，模块，小工具，模板和其他优质资源。';
	$lang['Installation'] = '安装方法 (请仔细阅读)';
	$lang['OnceFamiliar'] = '如果这是您第一次安装Plikli，请仔细按照以下说明继续进行此操作。如果您需要从以前的版本<a href="./upgrade.php">升级您的网站</a>，请点击上面的升级链接运行升级脚本。警告：在现有Plikli站点数据库上运行安装过程将覆盖所有数据，因此请确保您希望在下面继续执行安装。</ p> <br />
<OL>
<li>将settings.php.default重命名为settings.php </ li>
<li>将/languages/lang_english.conf.default重命名为lang_english.conf </ li>
<li>将/libs/dbconnect.php.default重命名为dbconnect.php </ li>
<li>将目录/logs.default重命名为/ logs </ li>
<li> CHMOD 0777以下文件夹：</ li>
<OL>
<LI> /管理/备份/ </ LI>
<LI> /化身/ groups_uploaded / </ LI>
<LI> /化身/ user_uploaded / </ LI>
<LI> /高速缓存/ </ LI>
<li> / languages /（CHMOD 0777此文件夹中包含的所有文件）</ li>
</醇>
<li> CHMOD 0666以下文件</ li>
<OL>
<LI> /libs/dbconnect.php </ LI>
<LI>的settings.php </ LI>
</醇>
</醇>
你现在经历了最难的部分！继续下一步，将Plikli安装到您的MySQL数据库中.</ p>';

// step 2
	$lang['EnterMySQL'] = '在这里输入你的MYSQL数据库设置,如果你不知道你的MYSQL数据库是如何设置的,请阅读你的主机托管商提供的文档,或者直接与他们联系.';
	$lang['DatabaseName'] = '数据库名称';
	$lang['DatabaseUsername'] = '数据库用户名';
	$lang['DatabasePassword'] = '数据库访问密码';
	$lang['DatabaseServer'] = '数据库主机';
	$lang['TablePrefix'] = '数据库的表名前缀';
	$lang['PrefixExample'] = '(如: "plikli_" 那么用户表users将保存为plikli_users)';
	$lang['CheckSettings'] = '检查设置';
	$lang['Errors'] = '请修复上述错误,然后刷新本页,暂停安装!';
	$lang['LangNotFound'] = '没有被发现。请删除所有语言文件的默认扩展名，然后再试一次。';

// step 3
	$lang['ConnectionEstab'] = '初始化数据库连接...';
	$lang['FoundDb'] = '找到数据库...';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\' 保存完毕.';
	$lang['NoErrors'] = '没有发现错误, 可以继续下一步安装了...';
	$lang['Next'] = '下一步';
	$lang['GoBack'] = '返回';
	$lang['Error2-1'] = '无法写入文件 \'libs/dbconnect.php\'.';
	$lang['Error2-2'] = '无法打开文件 \'/libs/dbconnect.php\' 供写入.';
	$lang['Error2-3'] = '已连接到数据库服务器, 但是数据库名称有误.';
	$lang['Error2-4'] = '提供的信息无法连接数据库 <b>服务器</b> .';

// step 4
	$lang['CreatingTables'] = '<p><strong>创建数据表...</strong></p>';
	$lang['TablesGood'] = '<p><strong>数据表创建成功!</strong></p><hr />';
	$lang['Error3-1'] = '<p>创建数据表出错.</p>';
	$lang['Error3-2'] = '<p>无法连接数据库.</p>';
	$lang['EnterAdmin'] = '<p><strong>请输入你的管理员帐号信息:</strong><br />请牢记你的管理员帐号信息,你需要使用它来登入到后台设置你的网站.</p>';
	$lang['AdminLogin'] = '管理员用户名';
	$lang['AdminPassword'] = '管理员密码';
	$lang['ConfirmPassword'] = '重复输入管理员密码';
	$lang['AdminEmail'] = '管理员的E-mail';
	$lang['SiteTitleLabel'] = '网站名称';
	$lang['CreateAdmin'] = '创建管理员帐号';

// Step 5
	$lang['Error5-1'] = '请填写所有的内容,不要留空.';
	$lang['Error5-2'] = '密码核对出错,请返回重新输入两遍密码.';
	$lang['AddingAdmin'] = '添加管理员帐号...';
	$lang['InstallSuccess'] = '<a href="../">你的Kliqqi站点</a>已经成功创建!';
	$lang['InstallSuccessMessage'] = '恭喜你，你已设立的Kliqqi CMS网站！当你的网站是功能齐全的在这一点上，你会想要做的一点点清理，按照下列指示，以确保您的网站。';
	$lang['WhatToDo'] = '接下来还要做什么:';
	$lang['WhatToDoList'] = '		<li><p>将 "/libs/dbconnect.php" 的访问权限改为 644, 我们不会再去修改这个文件了.</p></li>
		<li><p>如果你已成功安装了Kliqqi,请从服务器上<strong>删除</strong>  "/install" 这个目录.</p></li>
		<li><p>用你刚才设置的管理员帐号 <a href="../admin/admin_index.php">仪表盘</a>管理界面. 系统会提示你更多的操作信息.</p></li>
		<li><p>用管理界面<a href="../admin/admin_config.php">仪表盘</a> .</p></li>
		<li><p>如果有问题，你可以访问 <a href="https://www.plikli.com/">Plikli 论坛</a> , 或者就来告诉我们一下你做了一个新站.</p></li>';

// Upgrade
	$lang['UpgradeHome'] = '<p style =“text-decoration：underline”>升级将<STRONG> IRREVERSIBLE </ STRONG>更改为您的数据库。 备份您的数据库表格，然后再继续进行并确保您的数据库与“下降表格已存在”一起导出！</ p> <p>如果您已对自己的核心文件进行任何定制，请不要进行升级。 首先，你必须做以下事情：</ p>
<UL>
<li>使用备份文件的副本，使用WINMERGE或类似软件将您的文件与新的KLIQQI文件合并！</ li>
<li>如果您使用的不是默认靴靴模板，请确保您使用KLIQQI靴靴模板合并其文件，因为对代码进行了一些更改。 （请参阅升级完成后的注意事项）</ li>
</ UL>
<br />
将旧的Pligg或Kliqqi版本升级到Plikli'. $lang ['plikli_version']. '会将您的数据库表修改为最新版本。<br />';
	$lang['UpgradeAreYouSure'] = '你确定你想升级你的数据库和语言文件吗?';
	$lang['UpgradeYes'] = '是的';
	$lang['UpgradeLanguage'] = 'Plikli已成功更新了你的语言文件,现在已包括了最新的内容.';
	$lang['UpgradingTables'] = '<strong>更新数据库...</strong>';
	$lang['LanguageUpdate'] = '<strong>更新语言文件...</strong>';
	$lang['IfNoError'] = '如果没有任何出错信息那么升级已经完成!';
	$lang['PleaseFix'] = '暂停更新，请修复上述错误!';
	
// Errors
	$lang['NotFound'] = '没有找到!';
	$lang['CacheNotFound'] = '没有找到! 请在根目录下手工创建 /cache .';
	$lang['DbconnectNotFound'] = '没有找到! 试试把 dbconnect.php.default 改名为 dbconnect.php';
	$lang['SettingsNotFound'] = '没有找到! 试试把 settings.php.default 改名为 settings.php';
	$lang['ZeroBytes'] = '是 0 字节.';
	$lang['NotEditable'] = '不可写. 请修改它的访问权限为 777( CHMOD 777 )';
	
?>