<?php
// header
	$lang['plikli_version'] = '4.1.0';
	$lang['installer'] = 'Instal·lador';
	$lang['Benvinguda'] = 'Benvinguda';
	$lang['Install'] = 'Instal · lar';
	$lang['Actualització'] = 'Actualització';
	$lang['Upgrade-Kliqqi'] = 'Actualització-Kliqqi';
	$lang['Upgrade-Pligg'] = 'Actualitza-Pligg';
	$lang['Troubleshooter'] = 'Resolució de problemes';
	$lang['Step'] = 'Step';
	$lang['Readme'] = 'Llegirme';
	$lang['Admin'] = 'Tauler de control';
	$lang['Home'] = 'Home';
	$lang['Install_instruct'] = 'Teniu a mà la vostra informació MySQL. Vegeu Actualització per actualitzar un lloc existent. ';
	$lang['Upgrade_instruct'] = 'Actualització farà modificacions a la vostra base de dades MySQL. Assegureu-vos de fer còpies de seguretat abans de continuar. ';
	$lang['Troubleshooter_instruct'] = 'El solucionador de problemes detectarà problemes habituals com ara permisos de carpeta incorrectes';


// intro / step 1
	$lang['WelcomeToInstaller'] = 'Benvingut a l\'instal·lador de Plikli CMS!';
	$lang['Introducció'] = 'Introducció';
	$lang['WelcomeToThe'] = 'Benvingut a <a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer"> Plikli </a>, el CMS que subministra milers de llocs web de la comunitat. Si aquesta és la primera vegada que instal·leu el CMS de Plikli, llegeix totes les instruccions proporcionades acuradament per no perdre cap indicació important. ';
	$lang['Bugs'] = 'Familiaritzeu-vos amb la documentació proporcionada per la comunitat de Plikli al <a href="https://www.plikli.com/forum-2/"> Fòrum de Plikli </a > lloc web. També us suggerim que us registreu un compte perquè tingueu accés a assistència gratuïta, mòduls, widgets, plantilles i altres recursos fantàstics. ';
	$lang['Installation'] = 'Instal·lació (llegeix amb atenció)';
	$lang['OnceFamiliar'] = '<p> Si aquesta és la primera vegada que instal·leu Plikli, heu de continuar en aquesta pàgina després de seguir acuradament les indicacions a continuació. Si necessiteu <a href="./upgrade.php"> actualitzar el vostre lloc </a> des d\'una versió anterior, executeu l\'script d\'actualització fent clic a l\'enllaç Actualitza a dalt. ADVERTIMENT: executar el procés d\'instal·lació en una base de dades del lloc Plikli existent sobreescriurà totes les dades, així que assegureu-vos que voleu realitzar una instal·lació si voleu continuar a continuació. </ P> <br />
	<ol>
<li> Canvia el nom de settings.php.default a settings.php </ li>
<li> Canviar el nom de /languages/lang_english.conf.default a lang_english.conf </ li>
<li> Canviar el nom de /libs/dbconnect.php.default a dbconnect.php </ li>
<li> Canvieu el nom del directori /logs.default to / logs </ li>
		<li> CHMOD 0777 les següents carpetes:
		<ol>
			<li>/admin/backup/</li>
			<li>/avatars/groups_uploaded/</li>
			<li>/avatars/user_uploaded/</li>
			<li>/cache/</li>
<li> / languages ​​/ (CHMOD 0777 tots els fitxers inclosos en aquesta carpeta) </ li>
		</ol>
		</li>
		<li> CHMOD 0666 els següents fitxers:
		<ol>
			<li>/libs/dbconnect.php</li>
			<li>settings.php</li>
		</ol>
		</li>
	</ol>
	<p> Ara ha superat la part més difícil! Continueu fins al següent pas per instal·lar Plikli a la vostra base de dades MySQL. </P> ';

	// step 2
	$lang['EnterMySQL'] = 'Introduïu la vostra configuració de la base de dades MySQL a continuació. Si no coneixeu la vostra configuració de la base de dades MySQL, hauríeu de comprovar la documentació de la vostra pàgina web o contactar-los directament. ';
	$lang['DatabaseName'] = 'Nom de la base de dades';
	$lang['DatabaseUsername'] = 'Nom d\'usuari de la base de dades';
	$lang['DatabasePassword'] = 'Contrasenya de la base de dades';
	$lang['DatabaseServer'] = 'Servidor de bases de dades';
	$lang['TablePrefix'] = 'Prefix de la taula';
	$lang['PrefixExample'] = '(és a dir: "plikli_" fa que les taules dels usuaris es converteixin en plikli_users)';
	$lang['CheckSettings'] = 'Comprovar configuració';
	$lang['Errors'] = '<br /> <br /> Corregiu els errors anteriors, llavors <a class = "btn btn-default btn-xs" onClick = "document.location.reload (true )"> Actualitza la pàgina</a>';
	$lang['LangNotFound'] = 'no s\'ha trobat. Suprimiu l\'extensió \'.Default\' de tots els fitxers d\'idiomes i torneu-ho a provar. ';


// step 3
	$lang['ConnectionEstab'] = 'S\'ha establert la connexió a la base de dades ...';
	$lang['FoundDb'] = 'Base de dades trobada ...';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\'s\'ha actualitzat correctament.';
	$lang['NoErrors'] = 'No hi ha hagut cap error, continueu al següent pas ...';
	$lang['Next'] = 'Següent pas';
	$lang['GoBack'] = 'Tornar enrere i tornar-ho a provar';
	$lang['Error2-1'] = 'No s\'ha pogut escriure al fitxer \'libs/dbconnect.php\'.';
	$lang['Error2-2'] = 'No s\'ha pogut obrir el fitxer \'/libs/dbconnect.php\' per escriure\'l.';
	$lang['Error2-3'] = 'Connectat a la base de dades, però el nom de la base de dades no és correcte.';
	$lang['Error2-4'] = 'No es pot connectar al servidor de la base de dades amb la informació proporcionada.';

// step 4
	$lang['CreatingTables'] = 'Creant taules de base de dades';
	$lang['TablesGood'] = '<strong> Les taules s\'han creat amb èxit! </strong>';
	$lang['Error3-1'] = '<p> Hi ha hagut un problema en crear les taules. </p>';
	$lang['Error3-2'] = '<p>No s\'ha pogut connectar a la base de dades.</p>';
	$lang['EnterAdmin'] = '<p> <strong> Introduïu els detalls del vostre compte d\'administrador a continuació: </strong> <br /> Escriviu aquesta informació del compte perquè serà necessària per iniciar sessió i configurar el vostre lloc. </p> ';
	$lang['AdminLogin'] = 'Login de l\'administrador';
	$lang['AdminPassword'] = 'Contrasenya d\'administrador';
	$lang['ConfirmPassword'] = 'Confirmar contrasenya';
	$lang['AdminEmail'] = 'E-mail administrador';
	$lang['SiteTitleLabel'] = 'Nom del lloc web';
	$lang['CreateAdmin'] = 'Crear compte d\'administrador';
	$lang['pwndPassword'] = 'Heu introduït una contrasenya comuna i insegura.';

// Step 5
	$lang['Error5-1'] = 'Ompliu tots els camps del compte d\'administrador.';
	$lang['Error5-2'] = 'Els camps de contrasenya no coincideixen. Torneu enrere i torneu a introduir els camps de la contrasenya. ';
	$lang['AddingAdmin'] = 'Afegint el compte d\'usuari de l\'administrador ...';
	$lang['InstallSuccess'] = 'Instal·lació completa';
	$lang['InstallSuccessMessage'] = 'Enhorabona, heu configurat un lloc web de Plikli CMS. Tot i que el vostre lloc és completament funcional en aquest moment, voldreu fer una mica de neteja seguint les instruccions següents per garantir el vostre lloc. ';
	$lang['WhatToDo'] = 'Què fer a continuació:';
	$lang['WhatToDoList'] = '<li> chmod "/libs/dbconnect.php" de tornada a 644, no necessitem tornar a canviar aquest fitxer. </li>
	<li> <strong> SUPREN </strong> el directori "/install" del vostre servidor si heu instal·lat Plikli. </li>
	<li> Inicieu la sessió al <a href="../admin/admin_index.php"> tauler </a> mitjançant la informació de l\'usuari que heu introduït al pas anterior. Un cop hagueu iniciat la sessió, heu de presentar més informació sobre com utilitzar Plikli. </Li>
	<li> <a href="../admin/admin_config.php"> Configura el vostre lloc </a> mitjançant el tauler de control. </li>
	<li> Visita el lloc web <a href="https://www.plikli.com/forum-2/"> Plikli Support </a> si teniu alguna pregunta. </li> ';
	$lang['ContinueToSite'] = 'Continuar al vostre nou lloc web';
	
// Upgrade
	$lang['UpgradeHome'] = '<p style = "text-decoration: underline"> L\'actualització fa canvis <STRONG> IRREVERSIBLE </STRONG> a la vostra base de dades. SEGUIR LES DADES DE LA BASE DE LA BASE DE DADES DAVANT QUE PROCEDEIXI QUALSEVOL MÉS i Assegureu-vos que la vostra base de dades s\'exporta amb la "TABLA DE MOSTRES SI ESTÀ EXISTÈNCIA" CONTROLADA! </P> <p> NO PROCEDI AMB LA ACTUALITZACIÓ SI HA REALITZAT CAP PERSONALITZACIÓ PER A ELS NÚMEROS CORE. PRIMERA, HI HA DE FER EL SEGÜENT: </p>
	<ul>
	<li> UTILITZANT UNA CÒPIA DELS ARXIUS COMPLETOS, MERGE ELS vostres fitxers amb els nous fitxers KLIQQI que utilitzen WINMERGE o un SOFTWARE SIMILAR! </li>
	<li> SI ESTÀ UTILITZANT A PARTIR DE LA TEMPLE DEFAULT BOOTSTRAP, assegureu-vos de combinar els vostres fitxers amb el model de KLIQQI BOOTSTRAP, PER QUALSEVOL ALTRES CANVIOS DEL CÓDIG. (VEURE NOTES DESPRÉS D\'ACTUALITZAR LA ACTUALITZACIÓ) </li>
	</ul>
	<br />
	Actualització de les vostres versions antigues de Pligg o Kliqqi o Plikli 4.0.0 a Plikli '. $lang['plikli_version']. 'modificarà les vostres taules de base de dades a la darrera versió. <br />';
	$lang['UpgradeAreYouSure'] = 'Esteu segur que voleu actualitzar la vostra base de dades i el fitxer d\'idioma?';
	$lang['UpgradeYes'] = 'Continuar amb l\'actualització';
	$lang['UpgradeLanguage'] = 'Èxit, Plikli ha actualitzat el fitxer d\'idioma. Ara inclou els darrers elements d\'idioma. ';
	$lang['UpgradingTables'] = '<strong> Actualització de la base de dades ... </strong>';
	$lang['LanguageUpdate'] = '<strong> Actualització del fitxer d\'idioma ... </strong>';
	$lang['IfNoError'] = 'Si no es van mostrar errors, l\'actualització s\'ha completat.';
	$lang['PleaseFix'] = 'Corregiu els errors anteriors, l\'actualització s\'ha aturat!';
	
// Errors
	$lang['NotFound'] = 'no s\'ha trobat!';
	$lang['CacheNotFound'] = 'no s\'ha trobat! Creeu un directori anomenat /cache cau al directori arrel i configureu-lo a CHMOD 777. ';
	$lang['DbconnectNotFound'] = 'no s\'ha trobat! Intenta canviar el nom dbconnect.php.default a dbconnect.php ';
	$lang['SettingsNotFound'] = 'no s\'ha trobat! Intenta canviar el nom de settings.php.default a settings.php ';
	$lang['ZeroBytes'] = 'és de 0 bytes.';
	$lang['NotEditable'] = 'no es pot escriure. Si us plau, CHMOD el 777 ';
	
?>