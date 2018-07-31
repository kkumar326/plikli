<?php
// header
	$lang['plikli_version'] = '4.1.0';
	$lang['installer'] = 'Installer';
	$lang['Welcome'] = 'Välkommen';
	$lang['Install'] = 'Install';
	$lang['Upgrade'] = 'Uppgradera';
	$lang['Upgrade-Kliqqi'] = 'Uppgradera-Kliqqi';
	$lang['Upgrade-Pligg'] = 'Uppgradering-Pligg';
	$lang['Troubleshooter'] = 'Felsökare';
	$lang['Step'] = 'Steg';
	$lang['Readme'] = 'Readme';
	$lang['Admin'] = 'Dashboard';
	$lang['Home'] = 'Hem';
	$lang['Install_instruct'] = 'Vänligen ha din MySQL-information praktisk. Se Uppgradera för att uppgradera en befintlig webbplats. ';
	$lang['Upgrade_instruct'] = 'Uppgradering kommer att göra ändringar i din MySQL-databas. Var säker på att säkerhetskopiera innan du fortsätter. ';
	$lang['Troubleshooter_instruct'] = 'Felsökaren upptäcker vanliga problem som felaktiga mappbehörigheter';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'Välkommen till Plikli CMS Installer!';
	$lang['Introduction'] = 'Inledning';
	$lang['WelcomeToThe'] = 'Välkommen till <a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer"> Plikli </a>, CMS som driver tusentals gemenskapens webbplatser. Om det här är första gången du installerar Plikli CMS, läs noga igenom alla instruktioner så att du inte missar några viktiga riktningar. ';
	$lang['Bugs'] = 'Bekanta dig med en del av dokumentationen från Plikli-communityen på <a href="https://www.plikli.com/forum-2/"> Plikli Forum </a > webbplats. Vi föreslår också att du registrerar ett konto så att du får tillgång till gratis support, moduler, widgets, mallar och andra stora resurser. ';
	$lang['Installation'] = 'Installation (var god läs noga)';
	$lang['OnceFamiliar'] = '<p> Om det här är första gången du installerar Plikli bör du fortsätta på den här sidan efter noggrant följa anvisningarna nedan. Om du behöver <a href="./upgrade.php"> uppgradera din webbplats </a> från en tidigare version, kör uppgraderingsskriptet genom att klicka på länken Uppgradera ovan. VARNING! Om du kör installationsprocessen på en befintlig Plikli-databas, skriver du över alla data. Var därför säker på att du vill utföra en installation om du väljer att fortsätta nedan. </P> <br />
	<Ol>
		<li> Byt namn på settings.php.default till settings.php </li>
		<li> Byt namn på /languages/lang_english.conf.default to lang_english.conf </li>
		<li> Byt namn på /libs/dbconnect.php.default till dbconnect.php </li>
		<li> Byt namn på katalogen /logs.default till /logs </li>
		<li> CHMOD 0777 följande mappar:
			<Ol>
				<Li>/admin/backup/ </li>
				<Li>/avatars/groups_uploaded/ </li>
				<Li>/avatars/user_uploaded/ </li>
				<Li>/cache/ </li>
				<li>/languages/ (CHMOD 0777 alla filer som finns i den här mappen) </li>
			</Ol>
		</Li>
		<li> CHMOD 0666 följande filer:
			<Ol>
				<Li> /libs/dbconnect.php </li>
				<Li> settings.php </li>
			</Ol>
		</Li>
	</Ol>
	<p> Du är nu över den hårdaste delen! Fortsätt till nästa steg för att installera Plikli på din MySQL-databas. </P> ';

// step 2
	$lang['EnterMySQL'] = 'Ange dina MySQL-databasinställningar nedan. Om du inte känner till dina MySQL-databasinställningar bör du kontrollera din webbhotell dokumentation eller kontakta dem direkt. ';
	$lang['DatabaseName'] = 'Databasnamn';
	$lang['DatabaseUsername'] = 'Databas Användarnamn';
	$lang['DatabasePassword'] = 'Databas lösenord';
	$lang['DatabaseServer'] = 'Databas Server';
	$lang['TablePrefix'] = 'Tabellprefix';
	$lang['PrefixExample'] = '(dvs: "plikli_" gör tabellerna för användare blir plikli_users)';
	$lang['CheckSettings'] = 'Kontrollera inställningar';
	$lang['Fel'] = '<br /> <br /> Vänligen fixa ovanstående fel (er), sedan <a class = "btn btn-standard btn-xs" onClick = "document.location.reload (true ) "> Uppdatera sidan </a> ';
	$lang['LangNotFound'] = 'hittades inte. Ta bort \'.Default\' -tillägget från alla språkfiler och försök igen. ';

// step 3
	$lang['ConnectionEstab'] = 'Databasuppkoppling etablerad ...';
	$lang['FoundDb'] = 'Hittade databas ...';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\' uppdaterades framgångsrikt.';
	$lang['NoErrors'] = 'Det fanns inga fel, fortsätt till nästa steg ...';
	$lang['Next'] = 'Nästa steg';
	$lang['GoBack'] = 'Gå tillbaka och försök igen';
	$lang['Error2-1'] = 'Kunde inte skriva till \'/libs/dbconnect.php\' fil.';
	$lang['Error2-2'] = 'Kunde inte öppna \'/libs/ dbconnect.php\' fil för att skriva.';
	$lang['Error2-3'] = 'Ansluten till databasen, men databasnamnet är felaktigt.';
	$lang['Error2-4'] = 'Det går inte att ansluta till databasservern med hjälp av informationen som tillhandahålls.';


// step 4
	$lang['CreatingTables'] = 'Skapa databastabeller';
	$lang['TablesGood'] = '<strong> Tabeller skapades med framgång! </strong>';
	$lang['Error3-1'] = '<p> Det uppstod ett problem med att skapa tabellerna. </p>';
	$lang['Error3-2'] = '<p> Kunde inte ansluta till databasen. </p>';
	$lang['EnterAdmin'] = '<p> <strong> Ange dina administratörskontouppgifter nedan: </strong> <br /> Skriv ner denna kontoinformation eftersom det kommer att behövas för att logga in och konfigurera din webbplats. </p> ';
	$lang['AdminLogin'] = 'Admin Login';
	$lang['AdminPassword'] = 'Admin-lösenord';
	$lang['ConfirmPassword'] = 'Bekräfta lösenord';
	$lang['AdminEmail'] = 'Admin E-post';
	$lang['SiteTitleLabel'] = 'Webbplatsnamn';
	$lang['CreateAdmin'] = 'Skapa administratörskonto';
	$lang['pwndPassword'] = 'Du angav ett vanligt och osäkert lösenord!';

// Step 5
	$lang['Error5-1'] = 'Var god fyll i alla fält för administratörskonto.';
	$lang['Error5-2'] = 'Lösenordsfälten matchar inte. Vänligen gå tillbaka och skriv in lösenordsfälten igen. ';
	$lang['AddingAdmin'] = 'Lägga till administratörs användarkonto ...';
	$lang['InstallSuccess'] = 'Installation Complete!';
	$lang['InstallSuccessMessage'] = 'Grattis, du har skapat en Plikli CMS-webbplats! Medan din webbplats är fullt fungerande vid denna tidpunkt, vill du göra en liten städning genom att följa anvisningarna nedan för att säkra din webbplats. ';
	$lang['WhatToDo'] = 'Vad gör du nu:';
	$lang['WhatToDoList'] = '<li> chmod "/libs/dbconnect.php" tillbaka till 644, behöver vi inte ändra den här filen igen. </li>
	<li> <strong> DELETE </strong> katalogen "/ installera" från servern om du har installerat Plikli framgångsrikt. </li>
	<li> Logga in på <a href="../admin/admin_index.php"> instrumentbrädan </a> med användarinformationen du angav från föregående steg. När du loggar in bör du presenteras med mer information om hur du använder Plikli. </Li>
	<li> <a href="../admin/admin_config.php"> Konfigurera din webbplats </a> med instrumentpanelen. </li>
	<li> Besök <a href="https://www.plikli.com/forum-2/"> Plikli Support </a> webbplatsen om du har några frågor. </li> ';
	$lang['ContinueToSite'] = 'Fortsätt till din nya hemsida';
	
// Upgrade
	$lang['UpgradeHome'] = '<p style = "text-decoration: underline"> Uppgraderingen gör <STRONG> IRREVERSIBEL </STRONG> ändringar i din databas. TILLBAKA DATABASE TABELLERNA DÄR FÖRE DU TILLVERKAR NÅGON YTTERLIGARE OCH SÄKER ATT DIN DATABASE EXPORTERAS MED "DROP TABLE IF EXISTS" KONTROLLERAD! </P> <p> FÖRSÄKR INTE MED UPPGRADERINGEN OM DU HAR GJUTT NÅGOT KUNDINSTÄLLNING TILL DIN KORA FILER. Först måste du göra följande: </p>
	<Ul>
	<li> ANVÄNDA EN KOPP AV DE BACKADE UPPFILERNA, MERGE DITT FILER MED NYA KLIQQI FILER ANVÄNDA WINMERGE ELLER LIKNANDE PROGRAMVARA! </li>
	<li> OM DU ANVÄNDER ANNAN ÄN SÅ ÄR DET SÄKERHETSBESTÄMMADE BOOTSTRAPEMPLATET, SÄKER ATT DU MERAR SIN FILER MED KLIQQI BOOTSTRAP-MÄRKEN, FÖRETAGET VISSA VISSA ÄNDRINGAR HAR TILLGÄNDS KODEN. (Se noteringar efter uppgraderingen har slutförts) </li>
	</Ul>
	<br />
	Uppgradering av dina gamla Pligg eller Kliqqi eller Plikli 4.0.0 versioner till Plikli '. $lang['plikli_version']. 'kommer att ändra databas tabeller till den senaste versionen. <br />';
	$lang['UpgradeAreYouSure'] = 'Är du säker på att du vill uppgradera din databas och språkfil?';
	$lang['UpgradeYes'] = 'Fortsätt med uppgradering';
	$lang['UpgradeLanguage'] = 'Framgång, Plikli uppdaterade din språkfil. Det innehåller nu de senaste språket. ';
	$lang['UpgradingTables'] = '<strong> Uppgradering av databas ... </strong>';
	$lang['LanguageUpdate'] = '<strong> Uppgradering av språkfil ... </strong>';
	$lang['IfNoError'] = 'Om det inte fanns några fel visas uppgraderingen!';
	$lang['PleaseFix'] = 'Vänligen åtgärda ovanstående fel (er), uppgradera stoppad!';
	
// Errors
	$lang['NotFound'] = 'hittades inte!';
	$lang['CacheNotFound'] = 'hittades inte! Skapa en katalog kallad /cache i din rotkatalog och ställ den till CHMOD 777. ';
	$lang['DbconnectNotFound'] = 'hittades inte! Försök att byta namn dbconnect.php.default till dbconnect.php ';
	$lang['SettingsNotFound'] = 'hittades inte! Försök att byta namn på settings.php.default till settings.php ';
	$lang['ZeroBytes'] = 'är 0 bytes.';
	$lang['NotEditable'] = 'kan inte skrivas. Vänligen CHMOD den till 777 ';
	
?>