<?php
// header
	$lang['installer'] = 'Installer';
	$lang['Welcome'] = 'accueil';
	$lang['Install'] = 'Installez';
	$lang['Upgrade'] = 'Mise à jour';
	$lang['Troubleshooter'] = 'dépanneur';
	$lang['Step'] = 'étape';
	$lang['Readme'] = 'Lisez';
	$lang['Admin'] = 'tableau de bord';
	$lang['Home'] = 'maison';
	$lang['Install_instruct'] = 'S\'il vous plaît avoir vos informations MySQL pratique. Voir Mets à jour un site existant.';
	$lang['Upgrade_instruct'] = 'Mise à niveau va apporter des modifications à votre base de données MySQL. Veillez à sauvegarder avant de continuer ..';
	$lang['Troubleshooter_instruct'] = 'Le dépannage permet de détecter des problèmes communs tels que les autorisations de dossier incorrectes';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'Bienvenue au programme d\'installation Kliqqi CMS!';
	$lang['Introduction'] = 'présentation';
	$lang['WelcomeToThe'] = 'Bienvenue à <a href="http://kliqqi.com" target="_blank">Kliqqi Content Management System</a>, le CMS qui alimente des milliers de sites communautaires. Si c\'est votre première fois d\'installer Kliqqi, s\'il vous plaît lire toutes les instructions fournies avec soin afin que vous ne manquez aucune des orientations importantes.';
	$lang['Bugs'] = 'S\'il vous plaît vous familiariser avec une partie de la documentation fournie par la communauté Kliqqi au <a href="http://kliqqi.com/support/">Kliqqi Support</a> site. Nous vous suggérons également de créer un compte de sorte que vous aurez accès au support, des modules, des widgets, des modèles et d\'autres grandes ressources.';
	$lang['Installation'] = 'Installation (veuillez lire attentivement)';
	$lang['OnceFamiliar'] = '<p>Si c\'est votre première fois d\'installer Kliqqi vous devez continuer sur cette page après avoir suivi attentivement les instructions ci-dessous. Si vous avez besoin de <a href="./upgrade.php">mettre à jour votre site</a> à partir d\'une version précédente, s\'il vous plaît exécuter le script de mise à jour en cliquant sur ??le lien de mise à jour ci-dessus. ATTENTION: l\'exécution du processus d\'installation sur une base de données du site Kliqqi existant écrasera toutes les histoires et les paramètres, de sorte s\'il vous plaît assurez-vous que vous souhaitez effectuer une installation si vous choisissez de continuer ci-dessous.
	<ol>
		<li>renommez settings.php.default to settings.php</li>
		<li>renommez /languages/lang_english.conf.default to lang_english.conf</li>
		<li>renommez /libs/dbconnect.php.default to dbconnect.php</li>
		<li>renommez the directory /logs.default to /logs</li>
		<li>CHMOD 0777 les dossiers suivants:</li>
		<ol>
			<li>/admin/backup/</li>
			<li>/avatars/groups_uploaded/</li>
			<li>/avatars/user_uploaded/</li>
			<li>/cache/</li>
			<li>/languages/ (CHMOD 0777 tous les fichiers contenus dans ce dossier)</li>
		</ol>
		<li>CHMOD 0666 les fichiers suivants</li>
		<ol>
			<li>/libs/dbconnect.php</li>
			<li>settings.php</li>
		</ol>
	</ol>
	Vous avez passez l\'étape la plus dure! Passez à l\'étape suivante pour installer Kliqqi sur votre base de données MySQL.</p>';

// step 2
	$lang['EnterMySQL'] = 'Entrez vos paramètres de base de données MySQL ci-dessous. Si vous ne connaissez pas vos paramètres de base de données MySQL, vous devriez vérifier la documentation de votre hébergeur ou communiquer directement avec eux.';
	$lang['DatabaseName'] = 'Nom de base de données';
	$lang['DatabaseUsername'] = 'base de données Nom d\'utilisateur';
	$lang['DatabasePassword'] = 'base de données Mot de passe';
	$lang['DatabaseServer'] = 'Serveur de base de données';
	$lang['TablePrefix'] = 'Préfixe des Tables';
	$lang['PrefixExample'] = '(ie: "kliqqi_" rend la table des utilisateurs kliqqi_users)';
	$lang['CheckSettings'] = 'Vérifiez les paramètres';
	$lang['Errors'] = 'S\'il vous plaît corriger les erreurs ci-dessus, puis <a class="btn btn-default btn-xs" onClick="document.location.reload(true)">actualiser la page</a>';
	$lang['LangNotFound'] = 'n\'a pas été trouvé. S\'il vous plaît supprimer l\'extension \'.default\' de tous les fichiers de langues et et essayez à nouveau.';

// step 3
	$lang['ConnectionEstab'] = 'Connexion de base de données établie...';
	$lang['FoundDb'] = 'base de données a été trouvé...';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\'a été mis à jour.';
	$lang['NoErrors'] = 'Il n\'y avait pas d\'erreurs, continuer à l\'étape suivante...';
	$lang['Next'] = 'Étape suivante';
	$lang['GoBack'] = 'Revenez en arrière et essayez à nouveau';
	$lang['Error2-1'] = 'Impossible d\'écrire dans  \'libs/dbconnect.php\' fichier.';
	$lang['Error2-2'] = 'Impossible d\'ouvrir \'/libs/dbconnect.php\' fichier.';
	$lang['Error2-3'] = 'Connecté à la base de données, mais le nom de la base de données est incorrecte.';
	$lang['Error2-4'] = 'Impossible de se connecter au serveur de base de données <b> </ b> en utilisant les informations fournies.';

// step 4
	$lang['CreatingTables'] = 'Création de tables de base de données';
	$lang['TablesGood'] = '<strong>Les tableaux ont été créés avec succès!</strong>';
	$lang['Error3-1'] = '<p>Il y avait un problème de création des tables.</p>';
	$lang['Error3-2'] = '<p>Impossible de se connecter aux bases de données.</p>';
	$lang['EnterAdmin'] = '<p><strong>Entrez votre administrateur détails de votre compte ci-dessous:</strong><br />S\'il vous plaît écrivez ces informations de compte, car il sera nécessaire de se connecter et configurer votre site.</p>';
	$lang['AdminLogin'] = 'Admin Login';
	$lang['AdminPassword'] = 'Mot de passe Admin';
	$lang['ConfirmPassword'] = 'Confirmez mot de passe';
	$lang['AdminEmail'] = 'Admin E-mail';
	$lang['SiteTitleLabel'] = 'Nom du site';
	$lang['CreateAdmin'] = 'Créer un compte Administrateur';

// Step 5
	$lang['Error5-1'] = 'S\'il vous plaît remplir tous les champs pour compte admin.';
	$lang['Error5-2'] = 'Champs de mot de passe ne correspondent pas. S\'il vous plaît revenir en arrière et ré-entrer dans les champs de mot de passe.';
	$lang['AddingAdmin'] = 'Création du compte d\'utilisateur Administrateur...';
	$lang['InstallSuccess'] = 'Installation terminée!';
	$lang['InstallSuccessMessage'] = 'Félicitations, vous avez mis en place un site web Kliqqi CMS! Alors que votre site est entièrement fonctionnel à ce point, vous aurez envie de faire un peu de nettoyage en suivant les instructions ci-dessous pour sécuriser votre site.';
	$lang['WhatToDo'] = 'Que faire ensuite.:';
	$lang['WhatToDoList'] = '		<li><p>chmod "/libs/dbconnect.php" back to 644,nous n\'aurons pas besoin de changer à nouveau ce fichier.</p></li>
		<li><p><strong>DELETE</strong> the "/install" répertoire de votre serveur si vous avez installé avec succès Kliqqi.</p></li>
		<li><p>Login to the <a href="../admin/admin_index.php">tableau de bord</a> en utilisant les informations d\'utilisateur que vous avez entré à l\'étape précédente. Une fois que vous vous connectez, vous devriez être présenté avec plus d\'informations sur la façon d\'utiliser Kliqqi.</p></li>
		<li><p><a href="../admin/admin_config.php">Configurez votre site</a> en utilisant la zone d\'administration.</p></li>
		<li><p>Visitez le <a href="http://kliqqi.com/">Kliqqi Support</a> site Web si vous avez des questions.</p></li>';
	$lang['ContinueToSite'] = 'Continuez sur votre nouveau site';
// Upgrade
	$lang['UpgradeHome'] = '<P> SAUVEGARDEZ VOS TABLES DE BASE DE DONNÉES AVANT d\'aller plus loin et vérifiez que votre base de données est EXPORTER AVEC LE "DROP TABLE IF EXISTS" VÉRIFIÉ! </ P> <p> NE CONTINUEZ PAS AVEC LA mise à jour SI VOUS AVEZ MODIFIER LES FICHIERS ORIGINAUX. Premièrement, vous devez faire ce qui suit: </ p>
<Ul>
<Li> L\'AIDE D\'UNE COPIE DES FICHIERS SAUVEGARDÉS, MERGE VOS FICHIERS AVEC LE NOUVEAU KLIQQI FICHIERS UTILISANT WinMerge OU UN LOGICIEL SIMILAIRE! </ Li>
<Li> SI VOUS UTILISEZ AUTRE QUE LE DEFAUT BOOTSTRAP TEMPLATE, ASSURER vous fusionnez ses fichiers avec le modèle KLIQQI BOOTSTRAP parce que bien des modifications ont été apportées au code. (VOIR NOTES APRÈS LA MISE A FINI) </ li>
</ Ul>
</ Div> <br />
Mise à niveau de vos anciennes versions Pligg à Kliqqi 3.0.0 modifiera vos tables de base de données à la dernière version. <br />';
	$lang['UpgradeAreYouSure'] = 'Etes-vous sûr que vous voulez vous base de données et le fichier de langue améliorer?';
	$lang['UpgradeYes'] = 'Procéder à la mise à niveau';
	$lang['UpgradeLanguage'] = 'Succès, Kliqqi mis à jour votre fichier de langue. Il comprend désormais les derniers éléments du langage.';
	$lang['UpgradingTables'] = '<strong>Mise à niveau de base de données...</strong>';
	$lang['LanguageUpdate'] = '<strong>Mise à jour du fichier de langue...</strong>';
	$lang['IfNoError'] = 'S\'il n\'y avait pas d\'erreurs affichées, mise à niveau est terminée!';
	$lang['PleaseFix'] = 'Veuillez corriger l\'erreur ci-dessus (s), améliorer arrêté!';
	
// Errors
	$lang['NotFound'] = 'n\'a pas été trouvé!';
	$lang['CacheNotFound'] = 'n\'a pas été trouvé! Créez un répertoire appelé/cache dans votre répertoire racine et le mettre à CHMOD 777.';
	$lang['DbconnectNotFound'] = 'n\'a pas été trouvé! Essayez de renommer dbconnect.php.default to dbconnect.php';
	$lang['SettingsNotFound'] = 'n\'a pas été trouvé! Essayez de renommer settings.php.default to settings.php';
	$lang['ZeroBytes'] = 'est de 0 octets.';
	$lang['NotEditable'] = 'n\'est pas accessible en écriture. s\'il vous plaît CHMOD it to 777';
	
?>