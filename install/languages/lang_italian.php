<?php
// header
	$lang['plikli_version'] = '4.1.0';
	$lang['installer'] = 'Installer';
	$lang['Welcome'] = 'Benvenuto';
	$lang['Install'] = 'Installa';
	$lang['Upgrade'] = 'Aggiorna';
	$lang['Upgrade-Kliqqi'] = 'Aggiorna-Kliqqi';
	$lang['Upgrade-Pligg'] = 'Aggiorna-Pligg';
	$lang['Troubleshooter'] = 'Risoluzione dei problemi';
	$lang['Step'] = 'Step';
	$lang['Readme'] = 'Leggimi';
	$lang['Admin'] = 'Dashboard';
	$lang['Home'] = 'Casa';
	$lang['Install_instruct'] = 'Ti preghiamo di avere a portata di mano le tue informazioni MySQL. Vedi Aggiornamento per aggiornare un sito esistente. ';
	$lang['Upgrade_instruct'] = 'L\'aggiornamento apporterà modifiche al tuo database MySQL. Assicurati di eseguire il backup prima di procedere. ';
	$lang['Troubleshooter_instruct'] = 'Lo strumento di risoluzione dei problemi rileva problemi comuni come autorizzazioni di cartelle non corrette';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'Benvenuto in Plikli CMS Installer!';
	$lang['Introduction'] = 'Introduzione';
	$lang['WelcomeToThe'] = 'Benvenuto in <a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer"> Plikli </a>, il CMS che alimenta migliaia di siti web della comunità. Se è la prima volta che si installa Plikli CMS, leggere attentamente tutte le istruzioni fornite in modo da non perdere nessuna indicazione importante. ';
	$lang['Bugs'] = 'Per favore familiarizzare con la documentazione fornita dalla comunità di Plikli nel <a href="https://www.plikli.com/forum-2/"> Forum di Plikli </a > sito web. Ti suggeriamo inoltre di registrare un account in modo da avere accesso a supporto gratuito, moduli, widget, modelli e altre grandi risorse. ';
	$lang['Installation'] = 'Installation (Please Read Carefully)';
	$lang['OnceFamiliar'] = '<p> Se questa è la prima volta che si installa Plikli, è necessario continuare su questa pagina dopo aver seguito attentamente le istruzioni di seguito. Se hai bisogno di <a href="./upgrade.php"> aggiornare il tuo sito </a> da una versione precedente, esegui lo script di aggiornamento facendo clic sul link Aggiorna sopra. ATTENZIONE: l\'esecuzione del processo di installazione su un database del sito Plikli esistente sovrascriverà tutti i dati, quindi assicurati di voler eseguire un\'installazione se decidi di continuare qui sotto. </P> <br />
	<ol>
		<li> Rinomina settings.php.default in settings.php </li>
		<li> Rinomina /languages/lang_english.conf.default a lang_english.conf </li>
		<li> Rinomina /libs/dbconnect.php.default in dbconnect.php </li>
		<li> Rinomina la directory /logs.default in /logs </li>
		<li> CHMOD 0777 le seguenti cartelle: 
			<ol>
				<li> /admin/backup/ </li>
				<li> /avatars/groups_uploaded/ </li>
				<li> /avatars/user_uploaded/ </li>
				<li> /cache/ </li>
				<li> /languages​​/ (CHMOD 0777 tutti i file contenuti in questa cartella) </li>
			</ol>
		</li>
		<li> CHMOD 0666 i seguenti file:
			<ol>
				<li> /libs/dbconnect.php </li>
				<li> settings.php </li>
			</ol>
		</li>
	</ol>
	<p> Ora sei passato la parte più difficile! Passa al passaggio successivo per installare Plikli nel tuo database MySQL. </P> ';

// step 2
	$lang['EnterMySQL'] = 'Inserisci le impostazioni del tuo database MySQL qui sotto. Se non conosci le impostazioni del tuo database MySQL, controlla la documentazione del tuo webhost o contattale direttamente. ';
	$lang['DatabaseName'] = 'Nome database';
	$lang['DatabaseUsername'] = 'Database Username';
	$lang['DatabasePassword'] = 'Password del database';
	$lang['DatabaseServer'] = 'Database Server';
	$lang['TablePrefix'] = 'Prefisso tabella';
	$lang['PrefixExample'] = '(es: "plikli_" rende le tabelle per gli utenti diventano plikli_users)';
	$lang['CheckSettings'] = 'Controlla impostazioni';
	$lang['Errors'] = '<br /> <br /> Correggi gli errori di cui sopra, quindi <a class = "btn btn-default btn-xs" onClick = "document.location.reload (true ) "> Aggiorna la pagina </a> ';
	$lang['LangNotFound'] = 'non è stato trovato. Rimuovi l\'estensione ".default" da tutti i file di lingua e riprova.';

// step 3
	$lang['ConnectionEstab'] = 'Connessione al database stabilita ...';
	$lang['FoundDb'] = 'Trovato database ...';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\' è stato aggiornato con successo.';
	$lang['NoErrors'] = 'Non ci sono stati errori, continua con il prossimo passo ...';
	$lang['Next'] = 'Next Step';
	$lang['GoBack'] = 'Torna indietro e riprova';
	$lang['Error2-1'] = 'Non posso scrivere sul file "libs/dbconnect.php" file.';
	$lang['Error2-2'] = 'Impossibile aprire "libs/dbconnect.php" file per la scrittura.';
	$lang['Error2-3'] = 'Collegato al database, ma il nome del database non è corretto.';
	$lang['Error2-4'] = 'Impossibile connettersi al server del database utilizzando le informazioni fornite.';

// step 4
	$lang['CreatingTables'] = 'Creazione di tabelle del database';
	$lang['TablesGood'] = '<strong> Le tabelle sono state create con successo! </strong>';
	$lang['Error3-1'] = '<p> Si è verificato un problema nella creazione delle tabelle. </p>';
	$lang['Error3-2'] = '<p> Impossibile connettersi al database. </p>';
	$lang['EnterAdmin'] = '<p> <strong> Inserisci i dettagli dell\'account amministratore di seguito: </strong> <br /> Annota queste informazioni sull\'account perché saranno necessarie per accedere e configurare il tuo sito.</p> ';
	$lang['AdminLogin'] = 'Accesso amministratore';
	$lang['AdminPassword'] = 'Password amministratore';
	$lang['ConfirmPassword'] = 'Conferma password';
	$lang['AdminEmail'] = 'Admin E-mail';
	$lang['SiteTitleLabel'] = 'Nome sito web';
	$lang['CreateAdmin'] = 'Crea account amministratore';
	$lang['pwndPassword'] = 'Hai inserito una password comune e pericolosa!';

// Step 5
	$lang['Error5-1'] = 'Si prega di compilare tutti i campi per account admin.';
	$lang['Error5-2'] = 'I campi password non corrispondono. Torna indietro e inserisci di nuovo i campi della password. ';
	$lang['AddingAdmin'] = 'Aggiungi l\'account utente Admin ...';
	$lang['InstallSuccess'] = 'Installazione completata!';
	$lang['InstallSuccessMessage'] = 'Congratulazioni, hai creato un sito web Plikli CMS! Mentre il tuo sito è perfettamente funzionante a questo punto, ti consigliamo di fare un po \'di pulizia seguendo le indicazioni di seguito per proteggere il tuo sito.';
	$lang['WhatToDo'] = 'Cosa fare dopo:';
	$lang['WhatToDoList'] = '<li> chmod "/libs/dbconnect.php" torna a 644, non avremo bisogno di cambiare questo file di nuovo. </li>
	<li> <strong> DELETE </strong> la directory "/install" dal tuo server se hai installato Plikli con successo. </li>
	<li> Accedi alla <a href="../admin/admin_index.php"> dashboard </a> utilizzando le informazioni utente inserite nel passaggio precedente. Una volta effettuato il login dovresti ricevere maggiori informazioni su come utilizzare Plikli. </Li>
	<li> <a href="../admin/admin_config.php"> Configura il tuo sito </a> utilizzando la dashboard. </li>
	<li> Visita il <a href="https://www.plikli.com/forum-2/"> supporto Plikli </a> per eventuali domande. </li> ';
	$lang['ContinueToSite'] = 'Continua sul tuo nuovo sito web';

// Upgrade
	$lang['UpgradeHome'] = '<p style="text-decoration: underline"> L\'aggiornamento rende <STRONG> IRREVERSIBLE </STRONG> le modifiche al tuo database. BACKUP LE TABELLE DEL DATABASE PRIMA DI PROCEDERE INOLTRE E ASSICURI CHE IL TUO DATABASE VENGA ESPORTATO CON LA "TABELLA DEI DROP SE ESISTE" CONTROLLATO! </P> <p> NON PROCEDERE CON L\'AGGIORNAMENTO SE HAI FATTO QUALSIASI PERSONALIZZAZIONE AI TUOI FILE DEL CORE. IN PRIMO LUOGO, DEVI FARE QUANTO SEGUE: </p>
	<Ul>
	<li> UTILIZZANDO UNA COPIA DEI FILE DI BACKUP, UNISCI I TUOI FILE CON I NUOVI FILE DI KLIQQI UTILIZZANDO WINMERGE O UN SOFTWARE SIMILE! </li>
	<li> SE UTILIZZI DIVERSI DAL MODELLO BOOTSTRAP PREDEFINITO, ASSICURI DI ACCEDERE AI FILE CON IL MODELLO BOOTSTRAP KLIQQI PERCHÉ QUESTA ALCUNE CAMBIAMENTI SONO STATI FATTI AL CODICE. (VEDI NOTE DOPO CHE L\'UPGRADE HA FINITO) </li>
	</Ul>
	<br />
	Aggiornamento a Plikli delle versioni precedenti di Pligg o Kliqqi o Plikli 4.0.0. '. $lang['plikli_version'] . '  modificherà le tabelle del database fino all\'ultima versione. <br />';
	$lang['UpgradeAreYouSure'] = 'Sei sicuro di voler aggiornare il tuo database e il tuo file di lingua?';
	$lang['UpgradeYes'] = 'Procedi con l\'aggiornamento';
	$lang['UpgradeLanguage'] = 'Successo, Plikli ha aggiornato il tuo file di lingua. Ora include gli ultimi elementi della lingua. ';
	$lang['UpgradingTables'] = '<strong> Aggiornamento database ... </strong>';
	$lang['LanguageUpdate'] = '<strong> Aggiornamento del file di lingua ... </strong>';
	$lang['IfNoError'] = 'Se non ci sono stati errori visualizzati, l\'aggiornamento è completo!';
	$lang['PleaseFix'] = 'Correggi gli errori di cui sopra, aggiornamento terminato!';
	
// Errors
	$lang['NotFound'] = 'non trovato!';
	$lang['CacheNotFound'] = 'non è stato trovato! Crea una directory chiamata / cache nella tua directory root e impostala su CHMOD 777. ';
	$lang['DbconnectNotFound'] = 'non è stato trovato! Prova a rinominare dbconnect.php.default in dbconnect.php ';
	$lang['SettingsNotFound'] = 'non è stato trovato! Prova a rinominare settings.php.default in settings.php ';
	$lang['ZeroBytes'] = 'è 0 byte.';
	$lang['NotEditable'] = 'non è scrivibile. Per favore, CHMOD a 777 ';
	
?>