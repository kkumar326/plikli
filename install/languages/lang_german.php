<?php
// header
	$lang['plikli_version'] = '4.1.0';
	$lang['installer'] = 'Installer';
	$lang['Welcome'] = 'Willkommen';
	$lang['Install'] = 'Installieren';
	$lang['Upgrade'] = 'Upgrade';
	$lang['Upgrade-Kliqqi'] = 'Upgrade-Kliqqi';
	$lang['Upgrade-Pligg'] = 'Upgrade-Pligg';
	$lang['Troubleshooter'] = 'Troubleshooter';
	$lang['Step'] = 'Schritt';
	$lang['Readme'] = 'Readme';
	$lang['Admin'] = 'Dashboard';
	$lang['Home'] = 'Zuhause';
	$lang['Install_instruct'] = 'Bitte halten Sie Ihre MySQL Informationen bereit. Siehe Upgrade, um eine bestehende Site zu aktualisieren. ';
	$lang['Upgrade_instruct'] = 'Durch die Aktualisierung werden Änderungen an Ihrer MySQL-Datenbank vorgenommen. Stellen Sie sicher, dass Sie ein Backup durchführen, bevor Sie fortfahren. ';
	$lang['Troubleshooter_instruct'] = 'Der Troubleshooter erkennt häufig auftretende Probleme wie falsche Ordnerberechtigungen';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'Willkommen bei Plikli CMS Installer!';
	$lang['Introduction'] = 'Einführung';
	$lang['WelcomeToThe'] = 'Willkommen bei <a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer"> Plikli </a>, dem CMS, das tausende von Benutzern unterstützt Community-Websites. Wenn Sie das Plikli CMS zum ersten Mal installieren, lesen Sie bitte alle bereitgestellten Anweisungen sorgfältig durch, damit Sie keine wichtigen Anweisungen verpassen. ';
	$lang['Bugs'] = 'Bitte machen Sie sich mit der Dokumentation der Plikli-Community im <a href="https://www.plikli.com/forum-2/"> Plikli Forum </a vertraut > Webseite. Wir empfehlen Ihnen außerdem, ein Konto zu registrieren, damit Sie auf kostenlosen Support, Module, Widgets, Vorlagen und andere großartige Ressourcen zugreifen können. ';
	$lang['Installation'] = 'Installation (Bitte sorgfältig lesen)';
	$lang['OnceFamiliar'] = '<p> Wenn Sie Plikli zum ersten Mal installieren, sollten Sie auf dieser Seite fortfahren, nachdem Sie die Anweisungen unten genau befolgt haben. Wenn Sie <a href="./upgrade.php"> Ihre Website </a> von einer früheren Version aktualisieren müssen, führen Sie das Upgrade-Skript aus, indem Sie oben auf den Link "Upgrade" klicken. WARNUNG: Wenn Sie den Installationsprozess auf einer vorhandenen Plikli-Standortdatenbank ausführen, werden alle Daten überschrieben. Stellen Sie daher sicher, dass Sie eine Installation durchführen möchten, wenn Sie unten fortfahren. </p> <br />
	<ol>
		<li> Benenne settings.php.default in settings.php </li> um
		<li> Benennen Sie /languages/lang_english.conf.default in lang_english.conf </li> um
		<li> Benennen Sie /libs/dbconnect.php.default in dbconnect.php </li> um
		<li> Benennen Sie das Verzeichnis /logs.default in /logs </li> um
		<li> CHMOD 0777 die folgenden Ordner:
			<ol>
				<li> /admin/backup/ </li>
				<li> /avatars/groups_uploaded/ </li>
				<li> /avatars/user_uploaded/ </li>
				<li> /cache/ </li>
				<li> /languages​​/ (CHMOD 0777 alle Dateien in diesem Ordner) </li>
			</ol>
		</li>
		<li> CHMOD 0666 die folgenden Dateien:
			<ol>
				<li> /libs/dbconnect.php </li>
				<li> settings.php </li>
			</ol>
		</li>
	</ol>
	<p> Sie haben jetzt den schwierigsten Teil hinter sich! Fahren Sie mit dem nächsten Schritt fort, um Plikli in Ihrer MySQL-Datenbank zu installieren. </p> ';

// step 2
	$lang['EnterMySQL'] = 'Geben Sie unten Ihre MySQL-Datenbankeinstellungen ein. Wenn Sie Ihre MySQL-Datenbankeinstellungen nicht kennen, sollten Sie Ihre Webhost-Dokumentation überprüfen oder sie direkt kontaktieren. ';
	$lang['DatabaseName'] = 'Datenbankname';
	$lang['DatabaseUsername'] = 'Datenbank Benutzername';
	$lang['DatabasePassword'] = 'Datenbank Passwort';
	$lang['DatabaseServer'] = 'Datenbankserver';
	$lang['TablePrefix'] = 'Tabellenpräfix';
	$lang['PrefixExample'] = '(zB: "plikli_" macht die Tabellen für Benutzer zu plikli_users)';
	$lang['CheckSettings'] = 'Einstellungen überprüfen';
	$lang['Errors'] = '<br /> <br /> Bitte beheben Sie die obigen Fehler, dann <a class = "btn btn-default btn-xs" onClick = "document.location.reload (true ) "> Aktualisieren Sie die Seite </a>';
	$lang['LangNotFound'] = 'wurde nicht gefunden. Bitte entfernen Sie die Erweiterung \'.Default\' aus allen Sprachdateien und versuchen Sie es erneut. ';

// step 3
	$lang['ConnectionEstab'] = 'Datenbankverbindung hergestellt ...';
	$lang['FoundDb'] = 'Datenbank gefunden ...';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\' wurde erfolgreich aktualisiert.';
	$lang['NoErrors'] = 'Es gab keine Fehler, gehe weiter zum nächsten Schritt ...';
	$lang['Next'] = 'Nächster Schritt';
	$lang['GoBack'] = 'Geh zurück und versuche es noch einmal';
	$lang['Error2-1'] = 'Konnte nicht in die \'/libs/dbconnect.php\' Datei schreiben.';
	$lang['Error2-2'] = 'Die Datei \'/libs/dbconnect.php\' konnte nicht zum Schreiben geöffnet werden.';
	$lang['Error2-3'] = 'Verbunden mit der Datenbank, aber der Datenbankname ist falsch.';
	$lang['Error2-4'] = 'Kann mit den bereitgestellten Informationen keine Verbindung zum Datenbankserver herstellen.';

// step 4
	$lang['CreatingTables'] = 'Erstellen von Datenbanktabellen';
	$lang['TablesGood'] = '<strong> Tabellen wurden erfolgreich erstellt! </strong>';
	$lang['Error3-1'] = '<p> Beim Erstellen der Tabellen ist ein Problem aufgetreten. </p>';
	$lang['Error3-2'] = '<p> Verbindung zur Datenbank konnte nicht hergestellt werden. </p>';
	$lang['EnterAdmin'] = '<p> <strong> Geben Sie unten Ihre Administratorkontodaten ein: </strong> <br /> Bitte notieren Sie sich diese Kontoinformationen, da sie für die Anmeldung und Konfiguration Ihrer Website benötigt werden. < / p> ';
	$lang['AdminLogin'] = 'Admin Login';
	$lang['AdminPassword'] = 'Admin Passwort';
	$lang['ConfirmPassword'] = 'Passwort bestätigen';
	$lang['AdminEmail'] = 'Admin E-Mail';
	$lang['SiteTitleLabel'] = 'Name der Webseite';
	$lang['CreateAdmin'] = 'Admin Account erstellen';
	$lang['pwndPassword'] = 'Sie haben ein allgemeines und unsicheres Passwort eingegeben!';

// Step 5
	$lang['Error5-1'] = 'Bitte füllen Sie alle Felder für das Administratorkonto aus.';
	$lang['Error5-2'] = 'Passwortfelder stimmen nicht überein. Bitte gehen Sie zurück und geben Sie die Passwortfelder erneut ein. ';
	$lang['AddingAdmin'] = 'Hinzufügen des Admin-Benutzerkontos ...';
	$lang['InstallSuccess'] = 'Installation abgeschlossen!';
	$lang['InstallSuccessMessage'] = 'Herzlichen Glückwunsch, Sie haben eine Plikli CMS Website eingerichtet! Während Ihre Website zu diesem Zeitpunkt voll funktionsfähig ist, sollten Sie ein wenig aufräumen, indem Sie die unten stehenden Anweisungen befolgen, um Ihre Website zu sichern. ';
	$lang['WhatToDo'] = 'Was nun zu tun ist:';
	$lang['WhatToDoList'] = '<li> chmod "/libs/dbconnect.php" Zurück zu 644, wir müssen diese Datei nicht mehr ändern. </li>
	<li> <strong> LÖSCHEN Sie </strong> das Verzeichnis "/install" von Ihrem Server, wenn Sie Plikli erfolgreich installiert haben. </li>
	<li> Melden Sie sich am <a href="../admin/admin_index.php"> Dashboard </a> mit den Benutzerinformationen an, die Sie im vorherigen Schritt eingegeben haben. Sobald Sie sich eingeloggt haben, sollten Sie weitere Informationen zur Verwendung von Plikli erhalten. </Li>
	<li> <a href="../admin/admin_config.php"> Konfigurieren Sie Ihre Website </a> mithilfe des Dashboards. </li>
	<li> Besuchen Sie die <a href="https://www.plikli.com/forum-2/"> Plikli-Support </a> -Website, falls Sie Fragen haben. </li> ';
	$lang['ContinueToSite'] = 'Weiter zu Ihrer neuen Website';
	
// Upgrade
	$lang['UpgradeHome'] = '<p style="text-decoration:underline"> Durch das Upgrade werden <STRONG> IRREVERSIBLE </STRONG> Änderungen an Ihrer Datenbank vorgenommen. BACKUP YOUR DATABASE TABLES, BEVOR SIE WEITERE WEITERE VERFAHREN UND SICHERSTELLEN, DASS IHRE DATENBANK MIT DEM ÜBERPRÜFTEN "DROP TABLE IF EXISTS" EXPORTIERT IST! </P> <p> VERWENDEN SIE DAS UPGRADE NICHT, WENN SIE IHRE KERNDATEIEN ANGEPASST HABEN. Zunächst müssen Sie Folgendes tun: </p>
	<ul>
	<li> VERWENDEN SIE EINE KOPIE DER GESPEICHERTEN DATEIEN, FÜGEN SIE IHRE DATEIEN MIT DEN NEUEN KLIQQI-DATEIEN MIT WINMERGE ODER EINER ÄHNLICHEN SOFTWARE ZUSAMMEN! </li>
	<li> WENN SIE EINE ANDERE ALS DIE STANDARD-BOOTSTRAP-SCHABLONE VERWENDEN, VERGEWISSERN SIE SICH, DASS SIE IHRE DATEIEN MIT DER KLIQQI-BOOTSTRAP-SCHABLONE VERBINDEN, DA VIELE ÄNDERUNGEN AM CODE GEMACHT WORDEN SIND. (SIEHE HINWEISE NACH DEM UPGRADE) </li>
	</ul>
	<br />
	Aktualisieren Sie Ihre alten Pligg oder Kliqqi oder Plikli 4.0.0 Versionen auf Plikli '. $lang['plikli_version']. 'wird Ihre Datenbanktabellen auf die neueste Version ändern. <br />';
	$lang['UpgradeAreYouSure'] = 'Sind Sie sicher, dass Sie Ihre Datenbank und Sprachdatei aktualisieren möchten?';
	$lang['UpgradeYes'] = 'Mit Upgrade fortfahren';
	$lang['UpgradeLanguage'] = 'Erfolg, Plikli hat Ihre Sprachdatei aktualisiert. Es enthält jetzt die neuesten Sprachelemente. ';
	$lang['UpgradeTables'] = '<strong> Datenbank wird aktualisiert ... </strong>';
	$lang['LanguageUpdate'] = '<strong> Sprachdatei wird aktualisiert ... </strong>';
	$lang['IfNoError'] = 'Wenn keine Fehler angezeigt wurden, ist das Upgrade abgeschlossen!';
	$lang['PleaseFix'] = 'Bitte beheben Sie die oben genannten Fehler, Upgrade gestoppt!';
	
// Errors
	$lang['NotFound'] = 'wurde nicht gefunden!';
	$lang['CacheNotFound'] = 'wurde nicht gefunden! Erstellen Sie in Ihrem Stammverzeichnis ein Verzeichnis namens / cache und setzen Sie es auf CHMOD 777. ';
	$lang['DbconnectNotFound'] = 'wurde nicht gefunden! Versuchen Sie, dbconnect.php.default in dbconnect.php umzubenennen ';
	$lang['SettingsNotFound'] = 'wurde nicht gefunden! Versuchen Sie, settings.php.default in settings.php umzubenennen. ';
	$lang['ZeroBytes'] = 'ist 0 Bytes.';
	$lang['NotEditable'] = 'ist nicht beschreibbar. Bitte CHMOD es auf 777 ';
	
?>