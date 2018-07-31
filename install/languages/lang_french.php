<?php
// header
	$lang['plikli_version'] = '4.1.0';
	$lang['installer'] = 'Installer';
	$lang['Welcome'] = 'accueil';
	$lang['Install'] = 'Installez';
	$lang['Upgrade'] = 'Mise à jour';
	$lang['Upgrade-Plikli'] = 'Upgrade-Plikli';
	$lang['Upgrade-Pligg'] = 'Upgrade-Pligg';
	$lang['Troubleshooter'] = 'dépanneur';
	$lang['Step'] = 'étape';
	$lang['Readme'] = 'Lisez';
	$lang['Admin'] = 'tableau de bord';
	$lang['Home'] = 'maison';
	$lang['Install_instruct'] = 'S\'il vous plaît avoir vos informations MySQL pratique. Voir Mets à jour un site existant.';
	$lang['Upgrade_instruct'] = 'Mise à niveau va apporter des modifications à votre base de données MySQL. Veillez à sauvegarder avant de continuer ..';
	$lang['Troubleshooter_instruct'] = 'Le dépannage permet de détecter des problèmes communs tels que les autorisations de dossier incorrectes';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'Bienvenue dans le programme d\'installation de Plikli CMS!';
	$lang['Introduction'] = 'Introduction';
	$lang['WelcomeToThe'] = 'Bienvenue sur <a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer"> Plikli </a>, le CMS qui alimente des milliers de sites communautaires. Si vous installez Plikli CMS pour la première fois, veuillez lire attentivement toutes les instructions fournies afin de ne manquer aucune directive importante. ';
	$lang['Bugs'] = 'Familiarisez-vous avec la documentation fournie par la communauté Plikli sur le <a href="https://www.plikli.com/forum-2/"> Forum Plikli </a > site web. Nous vous suggérons également d\'ouvrir un compte afin que vous ayez accès à un support gratuit, à des modules, à des widgets, à des modèles et à d\'autres ressources. ';
	$lang['Installation'] = 'Installation (Veuillez lire attentivement)';
	$lang['OnceFamiliar'] = '<p> Si c\'est la première fois que vous installez Plikli, vous devriez continuer sur cette page après avoir soigneusement suivi les instructions ci-dessous. Si vous devez <a href="./upgrade.php"> mettre à niveau votre site </a> d\'une version antérieure, veuillez exécuter le script de mise à niveau en cliquant sur le lien Mettre à niveau ci-dessus. AVERTISSEMENT: l\'exécution du processus d\'installation sur une base de données de site existante de Plikli écrase toutes les données, alors assurez-vous que vous voulez effectuer une installation si vous choisissez de continuer ci-dessous. </P> <br />
	<ol>
		<li> Renommez settings.php.default en paramètres.php </li>
		<li> Renommez /languages/lang_english.conf.default en lang_english.conf </li>
		<li> Renommez /libs/dbconnect.php.default en dbconnect.php </li>
		<li> Renommez le répertoire /logs.default en /logs </li>
		<li> CHMOD 0777 les dossiers suivants:
			<ol>
				<li> /admin/backup/</li>
				<li> /avatars/groups_uploaded/ </li>
				<li> /avatars/user_uploaded/ </li>
				<li> /cache/ </li>
				<li> /languages​​/ (CHMOD 0777 tous les fichiers contenus dans ce dossier) </li>
			</ol>
		</li>
		<li> CHMOD 0666 les fichiers suivants </li>
			<ol>
				<li> /libs/dbconnect.php </li>
				<li> settings.php </li>
			</ol>
		</li>
	</ol>
	<p> Vous êtes maintenant passé la partie la plus difficile! Passez à l\'étape suivante pour installer Plikli sur votre base de données MySQL. </P> ';

// step 2
	$lang['EnterMySQL'] = 'Entrez les paramètres de votre base de données MySQL ci-dessous. Si vous ne connaissez pas les paramètres de votre base de données MySQL, vous devriez consulter la documentation de votre hébergeur ou les contacter directement. ';
	$lang['DatabaseName'] = 'Nom de la base de données';
	$lang['DatabaseUsername'] = 'Nom d\'utilisateur de la base de données';
	$lang['DatabasePassword'] = 'Mot de passe de la base de données';
	$lang['DatabaseServer'] = 'Serveur de base de données';
	$lang['TablePrefix'] = 'Préfixe de table';
	$lang['PrefixExample'] = '(ie: "plikli_" fait que les tables pour les utilisateurs deviennent plikli_users)';
	$lang['CheckSettings'] = 'Vérifier les paramètres';
	$lang['Errors'] = '<br /> <br /> Veuillez corriger les erreurs ci-dessus, puis <a class = "btn btn-default btn-xs" onClick = "document.location.reload (true ) "> Actualiser la page </a> ';
	$lang['LangNotFound'] = 'n\'a pas été trouvé. Veuillez supprimer l\'extension \'.Default\' de tous les fichiers de langue et réessayer. ';

// step 3
	$lang['ConnectionEstab'] = 'Connexion à la base de données établie ...';
	$lang['FoundDb'] = 'Base de données trouvée ...';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\' a été mis à jour avec succès.';
	$lang['NoErrors'] = 'Il n\'y a pas eu d\'erreur, continuez à l\'étape suivante ...';
	$lang['Next'] = 'Prochaine étape';
	$lang['GoBack'] = 'Revenez et réessayez';
	$lang['Error2-1'] = 'Impossible d\'écrire dans le fichier \'libs/dbconnect.php\'.';
	$lang['Error2-2'] = 'Impossible d\'ouvrir le fichier \'libs/dbconnect.php\' pour l\'écriture.';
	$lang['Error2-3'] = 'Connecté à la base de données, mais le nom de la base de données est incorrect.';
	$lang['Error2-4'] = 'Impossible de se connecter au serveur de base de données en utilisant les informations fournies.';

// step 4
	$lang['CreatingTables'] = 'Créer des tables de base de données';
	$lang['TablesGood'] = '<strong> Les tables ont été créées avec succès! </strong>';
	$lang['Error3-1'] = '<p> Un problème est survenu lors de la création des tables. </p>';
	$lang['Error3-2'] = '<p> Impossible de se connecter à la base de données. </p>';
	$lang['EnterAdmin'] = '<p> <strong> Entrez les détails de votre compte d\'administrateur ci-dessous: </strong> <br /> Veuillez noter les informations de ce compte car il sera nécessaire pour vous connecter et configurer votre site.</p> ';
	$lang['AdminLogin'] = 'Admin Login';
	$lang['AdminPassword'] = 'Mot de passe administrateur';
	$lang['ConfirmPassword'] = 'Confirmer le mot de passe';
	$lang['AdminEmail'] = 'Admin E-mail';
	$lang['SiteTitleLabel'] = 'Nom du site Web';
	$lang['CreateAdmin'] = 'Créer un compte administrateur';
	$lang['pwndPassword'] = 'Vous avez entré un mot de passe commun et dangereux!';

// Step 5
	$lang['Error5-1'] = 'Veuillez remplir tous les champs pour le compte administrateur.';
	$lang['Error5-2'] = 'Les champs de mot de passe ne correspondent pas. Revenez en arrière et entrez à nouveau les champs du mot de passe. ';
	$lang['AddingAdmin'] = 'Ajouter le compte administrateur ...';
	$lang['InstallSuccess'] = 'Installation terminée!';
	$lang['InstallSuccessMessage'] = 'Félicitations, vous avez créé un site web Plikli CMS! Alors que votre site est entièrement fonctionnel à ce stade, vous voudrez faire un peu de nettoyage en suivant les instructions ci-dessous pour sécuriser votre site. ';
	$lang['WhatToDo'] = 'Que faire ensuite:';
	$lang['WhatToDoList'] = '<li> chmod "/libs/dbconnect.php" retour à 644, nous n\'aurons plus besoin de changer ce fichier. </li>
	<li> <strong> SUPPRIMER </strong> le répertoire "/ install" de votre serveur si vous avez installé Plikli avec succès. </li>
	<li> Connectez-vous au <a href="../admin/admin_index.php"> tableau de bord </a> à l\'aide des informations utilisateur que vous avez entrées à l\'étape précédente. Une fois connecté, vous devriez recevoir plus d\'informations sur l\'utilisation de Plikli. </Li>
	<li> <a href="../admin/admin_config.php"> Configurez votre site </a> en utilisant le tableau de bord. </li>
	<li> Visitez le site Web <a href="https://www.plikli.com/forum-2/"> Support de Plikli </a> si vous avez des questions. </li> ';
	$lang['ContinueToSite'] = 'Continuer vers votre nouveau site web';
	
// Upgrade
	$lang['UpgradeHome'] = '<p style = "text-decoration: underline"> La mise à niveau rend <STRONG> IRREVERSIBLE </STRONG> les modifications apportées à votre base de données. SAUVEGARDEZ LES TABLEAUX DE VOTRE BASE DE DONNÉES AVANT DE PROCÉDER AUTREMENT ET ASSUREZ-VOUS L\'EXPORTATION DE VOTRE BASE DE DONNÉES AVEC LA TABLE "DROP TABLE IF EXISTS". </P> <p> NE PAS PROCÉDER À LA MISE À NIVEAU SI VOUS AVEZ FAIT UNE PERSONNALISATION À VOS FICHIERS. PREMIER, VOUS DEVEZ FAIRE CE QUI SUIT: </p>
	<ul>
	<li> EN UTILISANT UNE COPIE DES FICHIERS SUPPORTÉS, FUSIONNEZ VOS FICHIERS AVEC LES NOUVEAUX FICHIERS KLIQQI EN UTILISANT WINMERGE OU UN LOGICIEL SIMILAIRE! </li>
	<li> SI VOUS UTILISEZ UN AUTRE MODÈLE DE BOOTSTRAP PAR DÉFAUT, ASSUREZ-VOUS DE FUSIONNER SES FICHIERS AVEC LE MODÈLE DE BOOTSTRAP DE KLIQQI PARCE QUE CERTAINS CHANGEMENTS ONT ÉTÉ FAITS AU CODE. (VOIR NOTES APRES LA FIN DE LA MISE A JOUR) </li>
	</ul>
	<br />
	Mise à niveau de vos anciennes versions Pligg ou Kliqqi ou Plikli 4.0.0 vers Plikli '. $lang['plikli_version']. ' va modifier vos tables de base de données à la dernière version. <br />';
	$lang['UpgradeAreYouSure'] = 'Êtes-vous sûr de vouloir mettre à jour votre base de données et votre fichier de langue?';
	$lang['UpgradeYes'] = 'Poursuivre la mise à niveau';
	$lang['UpgradeLanguage'] = 'Succès, Plikli a mis à jour votre fichier de langue. Il inclut désormais les derniers éléments de langage. ';
	$lang['UpgradingTables'] = '<strong> Mise à niveau de la base de données ... </strong>';
	$lang['LanguageUpdate'] = '<strong> Mise à jour du fichier de langue ... </strong>';
	$lang['IfNoError'] = 'S\'il n\'y avait pas d\'erreurs affichées, la mise à niveau est terminée!';
	$lang['PleaseFix'] = 'Veuillez corriger les erreurs ci-dessus, mise à jour arrêtée!';
	
// Errors
	$lang['NotFound'] = 'n\'a pas été trouvé!';
	$lang['CacheNotFound'] = 'n\'a pas été trouvé! Créez un répertoire appelé / cache dans votre répertoire racine et réglez-le sur CHMOD 777. ';
	$lang['DbconnectNotFound'] = 'n\'a pas été trouvé! Essayez de renommer dbconnect.php.default en dbconnect.php ';
	$lang['SettingsNotFound'] = 'n\'a pas été trouvé! Essayez de renommer settings.php.default en settings.php ';
	$lang['ZeroBytes'] = 'est 0 octet.';
	$lang['NotEditable'] = 'n\'est pas accessible en écriture. S\'il vous plaît CHMOD à 777 ';
	
?>