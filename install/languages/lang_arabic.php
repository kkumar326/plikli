<?php
// header
	$lang['plikli_version'] = '4.1.0';
	$lang['installer'] = 'مثبت البرمجيات';
	$lang['Welcome'] = 'مرحبا';
	$lang['Install'] = 'تثبيت';
	$lang['Upgrade'] = 'تحديث';
	$lang['Upgrade-Kliqqi'] = 'Upgrade-Kliqqi';
	$lang['Upgrade-Pligg'] = 'Upgrade-Pligg';
	$lang['Troubleshooter'] = 'مستكشف الأخطاء ومصلحها';
	$lang['Step'] = 'مرحلة';
	$lang['Readme'] = 'الملف التمهيدي';
	$lang['Admin'] = 'لوحة القيادة';
	$lang['Home'] = 'الرئيسية';
	$lang['Install_instruct'] = 'الرجاء تحضير كافة معلومات قاعدة البيانات. إذا كنت ترغب في تحديث التطبيق، إنقر على "تحديث"';
	$lang['Upgrade_instruct'] = 'تحديث التطبيق يُدخل تعديلات على قاعدة البيانات. الرجاء التأكد من إستنساخ قاعدة البيانات قبل المضي في التحديث.';
	$lang['Troubleshooter_instruct'] = 'مستكشف الأخطاء يحدد المشاكل الموجودة في أذونات الملفّات والإعدادات.';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'مرحبًا بك في برنامج Plikli CMS Installer!';
	$lang['Introduction'] = 'مقدمة';
	$lang['WelcomeToThe'] = 'مرحبًا بك في <a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer"> Plikli </a> ، نظام إدارة المحتوى الذي يدير الآلاف من مواقع ويب المنتديات. إذا كانت هذه هي المرة الأولى التي تقوم فيها بتثبيت Plikli CMS ، يرجى قراءة جميع التعليمات المقدمة بعناية حتى لا تفوتك أي اتجاهات مهمة.';
	$lang['Bugs'] = 'يرجى الاطلاع على بعض الوثائق المتوفرة على الموقع الإلكتروني <a href="https://www.plikli.com/forum-2/"> Plikli Forum </a>. نقترح أيضًا أن تسجل حسابًا حتى تتمكن من الوصول إلى الدعم المجاني والوحدات والأدوات والقوالب والموارد الأخرى الرائعة.';
	$lang['Installation'] = 'التثبيت (يرجى القراءة بعناية)';
	$lang['OnceFamiliar'] = '<p>إذا كانت هذه هي المرة الأولى التي تثبت Plikli يجب عليك الاستمرار في هذه الصفحة بعد اتباع التوجيهات الواردة أدناه بعناية. إذا كنت بحاجة إلى <a href="./upgrade.php">تحديث موقعك</a> من إصدار سابق، الرجاء تشغيل برنامج التحديث عن طريق النقر على الرابط المرفق. تحذير: تشغيل عملية التثبيت على قاعدة بيانات موقع موجود سابقاً، يؤدي إلى إلغاء كل قاعدة البيانات الموجودة ، لذا يرجى التأكد من أنك تريد إجراء التثبيت إذا اخترت المتابعة.</p><br />
	<ol>
		<li>إعادة تسمية settings.php.default إلى settings.php</li>
		<li>إعادة تسمية /languages/lang_english.conf.default إلى lang_english.conf</li>
		<li>إعادة تسمية /libs/dbconnect.php.default إلى dbconnect.php</li>
<li> إعادة تسمية الدليل /logs.default إلى /logs </li>
		<li> CHMOD 0777 ، المجلدات التالية
		<ol>
			<li>/admin/backup/</li>
			<li>/avatars/groups_uploaded/</li>
			<li>/avatars/user_uploaded/</li>
			<li>/cache/</li>
<li> /languages/ (CHMOD 0777 جميع الملفات الموجودة داخل هذا المجلد) </li>
		</ol>
		</li>
		<li>CHMOD 0666 الملفات التالية
		<ol>
			<li>/libs/dbconnect.php</li>
			<li>settings.php</li>
		</ol>
		</li>
	</ol>
<p>لقد تجاوزت الآن أصعب جزء! انتقل إلى الخطوة التالية لتثبيت Plikli على قاعدة بيانات MySQL.</p>';

// step 2
	$lang['EnterMySQL'] = 'أدخل إعدادات قاعدة بيانات MySQL أدناه. إذا كنت لا تعرف إعدادات قاعدة بيانات MySQL فيجب عليك التحقق من وثائق مضيفك أو الاتصال بهم مباشرة.';
	$lang['DatabaseName'] = 'إسم قاعدة البيانات';
	$lang['DatabaseUsername'] = 'إسم المستخدم';
	$lang['DatabasePassword'] = 'كلمة السر';
	$lang['DatabaseServer'] = 'خادم قاعدة البيانات';
	$lang['TablePrefix'] = 'بادئة الجدول';
	$lang['PrefixExample'] = '(مثال: "plikli_" تجعل جدول المستخدم plikli_users)';
	$lang['CheckSettings'] = 'التحقق من الاعدادات';
	$lang['Errors'] = 'يرجى تصحيح الخطأ (الأخطاء) أعلاه ، ثم <a class="btn btn-default btn-xs" onClick="document.location.reload(true)"> تحديث الصفحة </a>';
	$lang['LangNotFound'] = 'لم يتم العثور على ملف اللغة. الرجاء إزالة الإضافة <span style="direction:ltr">"default."</span> من إسم ملف اللغة التي تريد إستعمالها وإعادة المحاولة.';

// step 3
	$lang['ConnectionEstab'] = 'تم تأسيس إتصال بقاعدة البيانات';
	$lang['FoundDb'] = 'قاعدة البيانات موجودة';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\' تم تحديثه بنجاح.';
	$lang['NoErrors'] = 'لا أخطاء، إنتقل إلى المرحلة التالية...';
	$lang['Next'] = 'المرحلة التالية';
	$lang['GoBack'] = 'إرجع';
	$lang['Error2-1'] = 'لم نتمكن من الكتابة في هذا الملف \'libs/dbconnect.php\'';
	$lang['Error2-2'] = 'لم نتمكن من فتح هذا الملف \'libs/dbconnect.php\' للكتابة';
	$lang['Error2-3'] = 'متصل بقاعدة البيانات ، ولكن اسم قاعدة البيانات غير صحيح.';
	$lang['Error2-4'] = 'للا يمكن الاتصال بخادم قاعدة البيانات باستخدام المعلومات المقدمة. الرجاء التأكد من المعلومات التي تمّ إدخالها';

// step 4
	$lang['CreatingTables'] = '<p><strong>إنشاء جداول قاعدة البيانات...</strong></p>';
	$lang['TablesGood'] = '<p><strong>تم إنشاء جداول قاعدة البيانات بنجاح!</strong></p><hr />';
	$lang['Error3-1'] = '<p>هناك مشكلة في إنشاء جداول قاعدة البيانات.</p>';
	$lang['Error3-2'] = '<p>لا يمكن الاتصال بقاعدة البيانات.</p>';
	$lang['EnterAdmin'] = '<p><strong>أدخل تفاصيل حساب المشرف أدناه: </ strong> <br /> الرجاء كتابة معلومات الحساب هذه لإستعمالها في تسجيل الدخول وتهيئة موقعك.</p>';
	$lang['AdminLogin'] = 'إسم المشرف';
	$lang['AdminPassword'] = 'كلمة السر';
	$lang['ConfirmPassword'] = 'تأكيد كلمة السر';
	$lang['AdminEmail'] = 'البريد الالكتروني';
	$lang['SiteTitleLabel'] = 'اسم الموقع';
	$lang['CreateAdmin'] = 'إنشئ حساب المشرف';
	$lang['pwndPassword'] = 'لقد أدخلت كلمة مرور مشتركة وغير آمنة!';

// Step 5
	$lang['Error5-1'] = 'يرجى ملء جميع الحقول لحساب المشرف.';
	$lang['Error5-2'] = 'كلمة المرور غير متطابقة. يرجى العودة وإعادة إدخال كلمة المرور.';
	$lang['AddingAdmin'] = 'إضافة حساب المشرف... ';
	$lang['InstallSuccess'] = 'تمّ إكتمال التثبيت';
	$lang['InstallSuccessMessage'] = 'تهانينا ، لقد أعددت موقع Plikli CMS! يعمل موقعك بشكل كامل في هذه المرحلة، لكن ستحتاج إلى اتباع الإرشادات أدناه لتأمين موقعك.';
	$lang['WhatToDo'] = 'ما هي الخطوة التالية:';
	$lang['WhatToDoList'] = '		<li>CHMOD مرة أخرى "/libs/dbconnect.php" إلى 644 ، لن نحتاج إلى تغيير هذا الملف مرة أخرى. </li>
<li> <strong> DELETE</strong> مجلد "install/" من الخادم الخاص بك إذا قمت بتثبيت Plikli بنجاح. </li>
<li> سجّل الدخول إلى <a href="../admin/admin_index.php">لوحة التحكم</a> باستخدام معلومات المستخدم التي أدخلتها من الخطوة السابقة. بمجرد تسجيل الدخول ، يجب تقديم المزيد من المعلومات حول كيفية استخدام Plikli. </li>
<li> <a href="../admin/admin_config.php">تهيئة موقعك</a> باستخدام لوحة التحكم. </li>
<li> قم بزيارة موقع الويب <a href="https://www.plikli.com/forum-2/">Plikli Support</a> إذا كانت لديك أية أسئلة.</li>';
	$lang['ContinueToSite'] = 'أكمل إلى موقع الويب الجديد الخاص بك';

// Upgrade
	$lang['UpgradeHome'] = '<p><b>إنسخ جداول قاعدة البيانات التي تريد تحديثها، قبل أن تباشر بأي شيء ههنا، وتأكد من وضع علامة إختيار في المربّع</b> "DROP TABLE IF EXISTS"</p><p><b>لا يجب ان تتابع التحديث إذا قمت بتخصيص وتغيير في الملفات الأصلية.</b> أولاً، عليك عمل الآتي:</p>
<ul><li>إستنسخ الملفات الأصلية إلى مجلّد جديد ومن ثم قمّ بدمج هذه الملفات، بما فيها ملفات "Template" إذا كانت غير "Bootstrap" مع ملفات Plikli بواسطة البرنامج "Winmerge" او أي برنامج مشابه.</li></ul>';
	$lang['UpgradeAreYouSure'] = 'هل أنت متأكد أنك تريد ترقية قاعدة البيانات وملف اللغة؟';
	$lang['UpgradeYes'] = 'نعم';
	$lang['UpgradeLanguage'] = 'تم ترقية ملف اللغة بنجاح.';
	$lang['UpgradingTables'] = '<strong>ترقية قاعدة البيانات ....</strong>';
	$lang['LanguageUpdate'] = '<strong>ترقية ملف اللغة ....</strong>';
	$lang['IfNoError'] = 'في حال عدم ظهور أية أخطاء فذلك يدل على انتهاء عملية الترقية';
	$lang['PleaseFix'] = 'الرجاء اصلاح الاخطاء. تم ايقاف عملية الترقية';
	
// Errors
	$lang['NotFound'] = 'غير موجود';
	$lang['CacheNotFound'] = 'لم يتم العثور على مجلد cache! قم بإنشاء مجلد يسمى cache في المجلد الجذر (ROOT FOLDER) مع صلاحيات CHMOD 777.';
	$lang['DbconnectNotFound'] = 'لم يتم ايجاده. الرجاء القيام بتغيير اسم الملف dbconnect.php.default الى dbconnect.php';
	$lang['SettingsNotFound'] = 'لم يتم ايجاده. الرجاء القيام بتغيير اسم الملف settings.php.default الى settings.php';
	$lang['ZeroBytes'] = 'is 0 bytes.';
	$lang['NotEditable'] = 'غير قابل للكتابة. عدّل الصلاحيات الى 777 CHMOD';
	
?>