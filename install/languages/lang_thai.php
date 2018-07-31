<?php
// header
	$lang['plikli_version'] = '4.1.0';
	$lang['installer'] = 'Installer';
	$lang['Welcome'] = 'Welcome';
	$lang['Install'] = 'ติดตั้ง';
	$lang['Upgrade'] = 'อัพเกรด';
	$lang['Upgrade-Kliqqi'] = 'Upgrade-Kliqqi';
	$lang['Upgrade-Pligg'] = 'Upgrade-Pligg';
	$lang['Troubleshooter'] = 'เครื่องมือแก้ปัญหา';
	$lang['Step'] = 'Step';
	$lang['Readme'] = 'Readme';
	$lang['Admin'] = 'หน้าแดชบอร์ด';
	$lang['Home'] = 'บ้าน';
	$lang['Install_instruct'] = 'กรุณาให้ข้อมูล MySQL ของคุณมีประโยชน์ ดูอัปเกรดเพื่ออัปเกรดเว็บไซต์ที่มีอยู่ ';
	$lang['Upgrade_instruct'] = 'การอัปเกรดจะทำการแก้ไขฐานข้อมูล MySQL ของคุณ โปรดสำรองก่อนดำเนินการต่อ ';
	$lang['Troubleshooter_instruct'] = 'โปรแกรมแก้ไขปัญหาจะตรวจพบปัญหาทั่วไปเช่นสิทธิ์โฟลเดอร์ที่ไม่ถูกต้อง';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'ยินดีต้อนรับสู่ Fileli CMS Installer!';
	$lang['Introduction'] = 'บทนำ';
	$lang['WelcomeToThe'] = 'ยินดีต้อนรับสู่ <a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer">Fileli</a> ซึ่งเป็น CMS ที่มีอำนาจหลายพัน เว็บไซต์ชุมชน ถ้านี่เป็นครั้งแรกที่คุณติดตั้ง Plikli CMS โปรดอ่านคำแนะนำทั้งหมดที่ให้มาอย่างระมัดระวังเพื่อไม่ให้คุณพลาดคำแนะนำที่สำคัญ ';
	$lang['Bugs'] = 'โปรดทำความคุ้นเคยกับเอกสารบางส่วนที่จัดเตรียมโดยชุมชน Plikli ที่ <a href="https://www.plikli.com/forum-2/"> ฟอรั่มแฟ้มli </a> เว็บไซต์ เราขอแนะนำให้คุณลงทะเบียนบัญชีเพื่อให้คุณสามารถเข้าถึงการสนับสนุนโมดูลเครื่องมือแม่แบบและแหล่งข้อมูลที่ยอดเยี่ยมอื่น ๆ ได้ ';
	$lang['Installation'] = 'การติดตั้ง (โปรดอ่านอย่างถี่ถ้วน)';
	$lang['OnceFamiliar'] = '<p> ถ้านี่เป็นครั้งแรกที่คุณติดตั้ง Plikli คุณควรดำเนินการต่อในหน้านี้หลังจากระมัดระวังตามคำแนะนำด้านล่าง หากคุณต้องการ <a href="./upgrade.php"> อัปเกรดไซต์ของคุณ </a> จากเวอร์ชันก่อนหน้าโปรดเรียกใช้สคริปต์การอัปเกรดโดยคลิกที่ลิงก์อัปเกรดด้านบน คำเตือน: การรันกระบวนการติดตั้งบนฐานข้อมูลไซต์ Plikli ที่มีอยู่จะเป็นการเขียนทับข้อมูลทั้งหมดดังนั้นโปรดตรวจสอบให้แน่ใจว่าคุณต้องการติดตั้งหากคุณเลือกดำเนินการต่อด้านล่างนี้ </p> <br />
	<ol>
		<li> เปลี่ยนชื่อ settings.php.default เป็น settings.php </li>
		<li> เปลี่ยนชื่อ / languages​​/lang_english.conf.default to lang_english.conf </li>
		<li> เปลี่ยนชื่อ /libs/dbconnect.php.default เป็น dbconnect.php </li>
		<li> เปลี่ยนชื่อไดเรกทอรี /logs.default เป็น / logs </li>
		<li> CHMOD 0777 ในโฟลเดอร์ต่อไปนี้:
			<ol>
				<li>/admin/backup/</li>
				<li>/avatars/groups_uploaded/</li>
				<li>/avatars/user_uploaded/</li>
				<li>/cache/</li>
				<li> /languages​​/ (CHMOD 0777 ไฟล์ทั้งหมดที่อยู่ในโฟลเดอร์นี้) </li>
			</ol>
		</li>
		<li> CHMOD 0666 ไฟล์ต่อไปนี้:
			<ol>
				<li> /libs/dbconnect.php </li>
				<li> settings.php </li>
			</ol>
		</li>
	</ol>
	<p> ตอนนี้คุณเป็นส่วนที่ยากที่สุด! ไปที่ขั้นตอนถัดไปเพื่อติดตั้ง Plikli ลงในฐานข้อมูล MySQL ของคุณ </p> ';

// step 2
	$lang['EnterMySQL'] = 'ป้อนการตั้งค่าฐานข้อมูล MySQL ด้านล่าง ถ้าคุณไม่ทราบการตั้งค่าฐานข้อมูล MySQL คุณควรตรวจสอบเอกสารของเว็บโฮสต์หรือติดต่อโดยตรง ';
	$lang['DatabaseName'] = 'ชื่อฐานข้อมูล';
	$lang['DatabaseUsername'] = 'ฐานข้อมูลชื่อผู้ใช้';
	$lang['DatabasePassword'] = 'ฐานข้อมูลรหัสผ่าน';
	$lang['DatabaseServer'] = 'เซิร์ฟเวอร์ฐานข้อมูล';
	$lang['TablePrefix'] = 'คำนำหน้าของตาราง';
	$lang['PrefixExample'] = '(เช่น: "plikli_" ทำให้ตารางผู้ใช้กลายเป็นไฟล์li_users)';
	$lang['CheckSettings'] = 'ตรวจสอบการตั้งค่า';
	$lang['Errors'] = '<br /> <br /> โปรดแก้ไขข้อผิดพลาดด้านบนจากนั้นคลิกที่  <a class="btn btn-default btn-xs"  onClick="document.location.reload(true)">Refresh the Page</a>';
	$lang['LangNotFound'] = 'ไม่พบ กรุณาลบส่วนขยาย  "default" ออกจากไฟล์ภาษาทั้งหมดและลองอีกครั้ง ';

// step 3
	$lang['ConnectionEstab'] = 'สร้างการเชื่อมต่อฐานข้อมูล ... ';
	$lang['FoundDb'] = 'พบฐานข้อมูล ... ';
	$lang['dbconnect'] = '"/libs/dbconnect.php" ได้รับการปรับปรุงเรียบร้อยแล้ว';
	$lang['NoErrors'] = 'ไม่มีข้อผิดพลาดเข้าสู่ขั้นตอนต่อไป ... ';
	$lang['Next'] = 'ขั้นตอนถัดไป';
	$lang['GoBack'] = 'กลับไปและลองอีกครั้ง';
	$lang['Error2-1'] = 'ไม่สามารถเขียนไฟล์  "/libs/dbconnect.php" ได้';
	$lang['Error2-2'] = 'ไม่สามารถเปิดไฟล์  "/ libs/dbconnect.php" สำหรับเขียนได้';
	$lang['Error2-3'] = 'เชื่อมต่อกับฐานข้อมูล แต่ชื่อฐานข้อมูลไม่ถูกต้อง';
	$lang['Error2-4'] = 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ฐานข้อมูลโดยใช้ข้อมูลที่มีให้';

// step 4
	$lang['CreatingTables'] = 'สร้างตารางฐานข้อมูล';
	$lang['TablesGood'] = '<strong> สร้างตารางเรียบร้อยแล้ว! </strong>';
	$lang['Error3-1'] = '<p> เกิดปัญหาในการสร้างตาราง </p>';
	$lang['Error3-2'] = '<p> ไม่สามารถเชื่อมต่อกับฐานข้อมูลได้ </p>';
	$lang['EnterAdmin'] = '<p> <strong> ใส่รายละเอียดบัญชีผู้ดูแลระบบของคุณด้านล่าง: </strong> <br /> โปรดจดข้อมูลลงในบัญชีนี้เนื่องจากจะต้องเข้าสู่ระบบและกำหนดค่าเว็บไซต์ของคุณ </p> ';
	$lang['AdminLogin'] = 'เข้าระบบ';
	$lang['AdminPassword'] = 'รหัสผ่านผู้ดูแลระบบ';
	$lang['ConfirmPassword'] = 'ยืนยันรหัสผ่าน';
	$lang['AdminEmail'] = 'Admin E-mail';
	$lang['SiteTitleLabel'] = 'ชื่อเว็บไซต์';
	$lang['CreateAdmin'] = 'สร้างบัญชีผู้ดูแลระบบ';
	$lang['pwndPassword'] = 'คุณป้อนรหัสผ่านที่พบโดยทั่วไปและไม่ปลอดภัย!';

// Step 5
	$lang['Error5-1'] = 'กรุณากรอกข้อมูลให้ครบถ้วนสำหรับบัญชี admin';
	$lang['Error5-2'] = 'ช่องรหัสผ่านไม่ตรงกัน โปรดย้อนกลับไปและป้อนฟิลด์รหัสผ่านใหม่ ';
	$lang['AddingAdmin'] = 'กำลังเพิ่มบัญชีผู้ใช้งานธุรการ ... ';
	$lang['InstallSuccess'] = 'การติดตั้งเสร็จสมบูรณ์!';
	$lang['InstallSuccessMessage'] = 'ขอแสดงความยินดีคุณได้ตั้งค่าเว็บไซต์ Plikli CMS แล้ว! ขณะที่ไซต์ของคุณใช้งานได้สมบูรณ์แล้ว ณ จุดนี้คุณจะต้องทำความสะอาดโดยทำตามคำแนะนำด้านล่างเพื่อรักษาความปลอดภัยให้กับไซต์ของคุณ ';
	$lang['WhatToDo'] = 'จะทำอย่างไรต่อไป:';
	$lang['WhatToDoList'] = '<li> chmod "/libs/dbconnect.php" กลับไปที่ 644 เราจะไม่ต้องเปลี่ยนไฟล์อีกครั้ง </li>
	<li> <strong> ลบ </strong> ไดเร็กทอรี "/ install" จากเซิร์ฟเวอร์ของคุณหากคุณได้ติดตั้ง plili เรียบร้อยแล้ว </li>
	<li> ลงชื่อเข้าใช้ <a href="../admin/admin_index.php"> แดชบอร์ด </a> โดยใช้ข้อมูลผู้ใช้ที่คุณป้อนจากขั้นตอนก่อนหน้า เมื่อคุณเข้าสู่ระบบคุณควรจะนำเสนอข้อมูลเพิ่มเติมเกี่ยวกับวิธีการใช้ Plikli </li>
	<li> <a href="../admin/admin_config.php"> กำหนดค่าไซต์ของคุณ </a> โดยใช้แดชบอร์ด </li>
	<li> ไปที่เว็บไซต์ <a href="https://www.plikli.com/forum-2/"> Plikli Support </a> หากคุณมีคำถามใด ๆ </li> ';
	$lang['ContinueToSite'] = 'ไปยังเว็บไซต์ใหม่ของคุณ';
	
// Upgrade
	$lang['UpgradeHome'] = '<p style = "text-decoration: underline"> การอัพเกรดทำให้ <STRONG> IRREVERSIBLE </STRONG> เปลี่ยนไปใช้กับฐานข้อมูลของคุณ สำรองฐานข้อมูลของคุณก่อนที่คุณจะดำเนินการต่อและตรวจดูให้แน่ใจว่าฐานข้อมูลของคุณถูกส่งออกไปพร้อมกับ "DROP TABLE IF EXISTS" ที่ได้รับการตรวจสอบ! </p> <p> อย่าดำเนินการกับการอัปเกรดหากคุณได้ทำการปรับแต่งไฟล์ให้เป็นของคุณเอง ขั้นแรกคุณต้องปฏิบัติตาม: </p>
	<ul>
	<li> การใช้สำเนาของไฟล์ที่สำรองไว้ผสานไฟล์ของคุณกับไฟล์ KLIQQI ใหม่ที่ใช้ WINMERGE หรือซอฟต์แวร์ที่คล้ายกัน </li>
	<li> หากคุณใช้ที่อื่นนอกเหนือจากเทมเพลต BOOTSTRAP เริ่มต้นให้แน่ใจว่าคุณได้รวมไฟล์ไว้ด้วย TEMPLATE ของ BOOTSTRAP KLIQQI เนื่องจากมีการเปลี่ยนแปลงบางอย่างที่เกิดขึ้นกับซอฟต์แวร์นี้ (ดูหมายเหตุหลังการอัปเกรดเสร็จ) </li>
	</ul>
	<br />
	อัปเกรดไฟล์ Pligg เก่าหรือ Kliqqi หรือ Plikli 4.0.0 เป็นไฟล์ '. $lang['plikli_version'].' จะแก้ไขตารางฐานข้อมูลของคุณเป็นเวอร์ชันล่าสุด <br />';
	$lang['UpgradeAreYouSure'] = 'คุณแน่ใจหรือว่าต้องการอัปเกรดไฟล์ฐานข้อมูลและไฟล์ภาษาของคุณ?';
	$lang['UpgradeYes'] = 'ดำเนินการอัพเกรด';
	$lang['UpgradeLanguage'] = 'Success, Plikli อัพเดตไฟล์ภาษาของคุณ ขณะนี้มีรายการภาษาล่าสุด ';
	$lang['UpgradingTables'] = '<strong> การอัพเกรดฐานข้อมูล ... </strong>';
	$lang['LanguageUpdate'] = '<strong> การอัพเกรดไฟล์ภาษา ... </strong>';
	$lang['IfNoError'] = 'ถ้าไม่พบข้อผิดพลาดการอัพเกรดเสร็จสิ้น!';
	$lang['PleaseFix'] = 'กรุณาแก้ไขข้อผิดพลาดข้างต้นอัพเกรดหยุด';
	
// Errors
	$lang['NotFound'] = 'ไม่พบ!';
	$lang['CacheNotFound'] = 'ไม่พบ! สร้างไดเรกทอรีที่เรียกว่า / แคชในไดเรกทอรีรากของคุณและตั้งค่าให้ CHMOD 777';
	$lang['DbconnectNotFound'] = 'ไม่พบ! ลอง dbconnect.php.default การเปลี่ยนชื่อเพื่อ dbconnect.php';
	$lang['SettingsNotFound'] = 'ไม่พบ! ลอง settings.php.default การเปลี่ยนชื่อเพื่อ settings.php';
	$lang['ZeroBytes'] = 'เป็น 0 ไบต์.';
	$lang['NotEditable'] = 'ไม่สามารถเขียน กรุณา CHMOD เป็น 777';
	
?>