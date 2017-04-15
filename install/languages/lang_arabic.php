<?php
// header
	$lang['installer'] = 'مثبت البرمجيات';
	$lang['Welcome'] = 'مرحبا';
	$lang['Install'] = 'تثبيت';
	$lang['Upgrade'] = 'تحديث';
	$lang['Troubleshooter'] = 'مستكشف الأخطاء';
	$lang['Step'] = 'خطوة';
	$lang['Readme'] = 'تفحّصني';
	$lang['Admin'] = 'لوحة القيادة';
	$lang['Home'] = 'الرئيسية';
	$lang['Install_instruct'] = 'الرجاء تحضير كافة معلومات قاعدة البيانات. إذا كنت ترغب في تحديث التطبيق، إنقر على "تحديث"';
	$lang['Upgrade_instruct'] = 'تحديث التطبيق يُدخل تعديلات على قاعدة البيانات. الرجاء التأكد من إستنساخ قاعدة البيانات قبل المضي في التحديث.';
	$lang['Troubleshooter_instruct'] = 'مستكشف الأخطاء يحدد المشاكل الموجودة في أذونات الملفّات والإعدادات.';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'مرحباً بكم في مثبّت نظام إدارة المحتوى، Kliqqi!';
	$lang['Introduction'] = 'مقدمة';
	$lang['WelcomeToThe'] = 'مرحباً بكم في نطام إدارة المحتوى الذي بُنِيت منه الآلاف من المواقع الإجتماعية <a href="http://www.kliqqi.com" target="_blank">Kliqqi Content Management System</a>. إذا كانت هذه المرة الأولى التي تُثَبت فيها Kliqqi، يرجى قراءة كافة المعلومات والإرشادات المرفقة بعناية لكي لا تفوتك أي معلومات مهمة!';
	$lang['Bugs'] = 'عند زيارتك لموقع Kliqqi.com, رجاء قم بقراءة الوثائق المقدمة من مجموعتنا. كما نقترح عليك التسجيل معنا للحصول على الكثير من المصادر من خلال هذا الرابط<a href="http://www.kliqqi.com/forum/" target="_blank">Kliqqi Forum</a>';
	$lang['Installation'] = 'التثبيت - اقرأها جيدا';
	$lang['OnceFamiliar'] = '<p>إذا كانت هذه هي المرة الأولى التي تثبت Kliqqi يجب عليك الاستمرار في هذه الصفحة بعد اتباع التوجيهات الواردة أدناه بعناية. إذا كنت بحاجة إلى <a href="./upgrade.php"> تحديث موقعك </A> من إصدار سابق، الرجاء تشغيل برنامج التحديث عن طريق النقر على الرابط تحديث أعلاه. تحذير: تشغيل عملية التثبيت على قاعدة بيانات موقع موجود سابقاً، يؤدي إلى إلغاء كل قاعدة البيانات الموجودة ، لذا يرجى التأكد من أنك تريد إجراء التثبيت إذا اخترت المتابعة.</p><br />
	<ol>
		<li>إعادة تسمية settings.php.default إلى settings.php</li>
		<li>إعادة تسمية /languages/lang_english.conf.default إلى lang_english.conf</li>
		<li>إعادة تسمية /libs/dbconnect.php.default إلى dbconnect.php</li>
		<li>إعادة تسمية /logs.default إلى /logs</li>
		<li>بدل صلاحيات المجلدات التالية إلى 755 ، في حال حدوث خطأ حاول تغيره الى 777.</li>
		<ol>
			<li>/admin/backup/</li>
			<li>/avatars/groups_uploaded/</li>
			<li>/avatars/user_uploaded/</li>
			<li>/cache/</li>
			<li>/languages/ (جميع الملفات في هذا المجلد يجب ان تملك صلاحيات 777)</li>
		</ol>
		<li>4. بدل صلاحيات الملفات التالية الى 666</h4>
		<ol>
			<li>/libs/dbconnect.php</li>
			<li>settings.php</li>
		</ol>
	</ol>
	لقد إجتزت الجزء الأصعب! انتقل إلى الخطوة التالية لتثبيت Kliqqi على قاعدة البيانات.';

// step 2
	$lang['EnterMySQL'] = 'قم بإدخال بيانات قاعدة البيانات الخاصة بك.';
	$lang['DatabaseName'] = 'إسم قاعدة البيانات';
	$lang['DatabaseUsername'] = 'إسم المستخدم';
	$lang['DatabasePassword'] = 'كلمة السر';
	$lang['DatabaseServer'] = 'رابط الخادم';
	$lang['TablePrefix'] = 'البادئة';
	$lang['PrefixExample'] = '(مثال: "kliqqi_" تجعل جدول المستخدم kliqqi_users)';
	$lang['CheckSettings'] = 'التحقق من الاعدادات';
	$lang['Errors'] = 'فشل التثبيت، الرجاء التحقق من المشكلات اعلاه!';
	$lang['LangNotFound'] = 'لم يتم العثور. يرجى إزالة التمديد. الافتراضية من جميع ملفات اللغة وحاول مرة أخرى.';

// step 3
	$lang['ConnectionEstab'] = 'تم تأسيس إتصال بقاعدة البيانات';
	$lang['FoundDb'] = 'قاعدة البيانات موجودة';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\' حدث بنجاح.';
	$lang['NoErrors'] = 'لا أخطاء، انتقل للخطوة التالية';
	$lang['Next'] = 'التالي';
	$lang['GoBack'] = 'ارجع';
	$lang['Error2-1'] = 'لا يمكن الكتابة على \'libs/dbconnect.php\' .';
	$lang['Error2-2'] = 'لا يمكن فتح \'/libs/dbconnect.php\' للكتابة عليه.';
	$lang['Error2-3'] = 'تم الاتصال بقاعدة البيانات ، لكن اسم قاعدة الي.';
	$lang['Error2-4'] = 'لا يمكن الاتصال بخادم قاعدة البيانات، الرجاء التاكد من البيانات المدخلة.';

// step 4
	$lang['CreatingTables'] = '<p><strong>انشاء جداول....</strong></p>';
	$lang['TablesGood'] = '<p><strong>تم انشاء الجداول بنجاح!</strong></p><hr />';
	$lang['Error3-1'] = '<p>كان هناك مشكلة عند محاولة انشاء الجداول.</p>';
	$lang['Error3-2'] = '<p>لا يمكن الاتصال بقاعدة البيانات.</p>';
	$lang['EnterAdmin'] = '<p><strong>قم بادخال بيانات المدير:</strong><br />الرجاء الاحتفاظ بهذه البيانات.</p>';
	$lang['AdminLogin'] = 'إسم المدير';
	$lang['AdminPassword'] = 'كلمة السر';
	$lang['ConfirmPassword'] = 'تأكيد كلمة السر';
	$lang['AdminEmail'] = 'البريد الالكتروني';
	$lang['SiteTitleLabel'] = 'اسم الموقع';
	$lang['CreateAdmin'] = 'انشئ حساب المدير';

// Step 5
	$lang['Error5-1'] = 'الرجاء قم بتعبئة كل البيانات.';
	$lang['Error5-2'] = 'كلمة المرور غير متطابقة. الرجاء العودة واعادة ادخال كلمة المرور.';
	$lang['AddingAdmin'] = 'إضافة حساب المدير ... ';
	$lang['InstallSuccess'] = '<a href="../">موقع kliqqi الخاص بك</a> تم تثبيته بنجاح!';
	$lang['InstallSuccessMessage'] = 'تهانينا، قمت بإعداد موقع على شبكة الانترنت Kliqqi CMS! في حين موقعك يعمل بشكل كامل عند هذه النقطة، سوف تريد أن تفعل قليلا تنظيف باتباع الإرشادات أدناه لتأمين موقع الويب الخاص بك.';
	$lang['WhatToDo'] = 'ما هي الخطوة التالية:';
	$lang['WhatToDoList'] = '		<li><p>قم بارجاع صلاحيات "/libs/dbconnect.php" الى 644, لن تحتاج لتغيير هذه الاعدادات لاحقا.</p></li>
		<li><p><strong>قم بحذف</strong> مجلد "/install" من موقعك.</p></li>
		<li><p>قم بالدخول <a href="../admin/admin_index.php">لوحة القيادة</a> باستخدام البيانات التي قمت بادخالها مسبقا.</p></li>
		<li><p><a href="../admin/admin_config.php">لوحة القيادة</a> من منطقة المدير.</p></li>
		<li><p>قم بزيارة <a href="http://kliqqi.com/">Kliqqi Forums</a> في حال كان لديك أية أسئلة أو أردت إبلاغنا عن موقعك الجديد.</p></li>';
	$lang['ContinueToSite'] = 'يواصل موقع الويب الخاص بك جديد';

// Upgrade
	$lang['UpgradeHome'] = '<p><b>إنسخ جداول قاعدة البيانات التي تريد تحديثها، قبل أن تباشر بأي شيء ههنا، وتأكد من وضع علامة إختيار في المربّع</b> "DROP TABLE IF EXISTS"</p><p><b>لا يجب ان تتابع التحديث إذا قمت بتخصيص وتغيير في الملفات الأصلية.</b> أولاً، عليك عمل الآتي:</p>
<ul><li>إستنسخ الملفات الأصلية إلى مجلّد جديد ومن ثم قمّ بدمج هذه الملفات، بما فيها ملفات "Template" إذا كانت غير "Bootstrap" مع ملفات Kliqqi بواسطة البرنامج "Winmerge" او أي برنامج مشابه.</li></ul>';
	$lang['UpgradeAreYouSure'] = 'هل انت متأكد من كونك تريد ترقية قاعدة البيانات و ملف اللغة الخاصة بموقعك؟';
	$lang['UpgradeYes'] = 'نعم';
	$lang['UpgradeLanguage'] = 'تم ترقية ملف اللغة بنجاح.';
	$lang['UpgradingTables'] = '<strong>ترقية قاعدة البيانات ....</strong>';
	$lang['LanguageUpdate'] = '<strong>ترقية ملف اللغة ....</strong>';
	$lang['IfNoError'] = 'في حال عدم ظهور أية أخطاء فذلك يدل على انتهاء عملية الترقية';
	$lang['PleaseFix'] = 'الرجاء اصلاح الاخطاء. تم ايقاف عملية الترقية';
	
// Errors
	$lang['NotFound'] = 'غير موجود';
	$lang['CacheNotFound'] = 'لم يتم ايجاده. الرجاء القيام بانشاء مجلد باسم /cache في المجلد الاساسي للموقع.';
	$lang['DbconnectNotFound'] = 'لم يتم ايجاده. الرجاء القيام بتغيير اسم الملف dbconnect.php.default الى dbconnect.php';
	$lang['SettingsNotFound'] = 'لم يتم ايجاده. الرجاء القيام بتغيير اسم الملف settings.php.default الى settings.php';
	$lang['ZeroBytes'] = 'is 0 bytes.';
	$lang['NotEditable'] = 'غير قابل للكتابة. غير الصلاحيات الى 777';
	
?>