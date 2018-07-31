<?php
// header
	$lang['plikli_version'] = '4.1.0'; 
	$lang['installer'] = 'Instalador'; 
	$lang['Welcome'] = 'Bienvenido'; 
	$lang['Install'] = 'Instalar'; 
	$lang['Upgrade'] = 'Actualizar'; 
	$lang['Upgrade-Kliqqi'] = 'Upgrade-Kliqqi'; 
	$lang['Upgrade-Pligg'] = 'Actualizar-Pligg'; 
	$lang['Troubleshooter'] = 'Solucionador de problemas'; 
	$lang['Step'] = 'Paso'; 
	$lang['Readme'] = 'Léame'; 
	$lang['Admin'] = 'Panel'; 
	$lang['Home'] = 'Inicio'; 
	$lang['Install_instruct'] = 'Tenga a mano su información de MySQL. Consulte Actualizar para actualizar un sitio existente. '; 
	$lang['Upgrade_instruct'] = 'La actualización hará modificaciones a su base de datos MySQL. Asegúrese de realizar una copia de seguridad antes de continuar. '; 
	$lang['Troubleshooter_instruct'] = 'El Solucionador de Problemas detectará problemas comunes tales como permisos incorrectos de la carpeta'; 

// intro / step 1
	$lang['WelcomeToInstaller'] = 'Bienvenido a Plikli CMS Installer!'; 
	$lang['Introduction'] = 'Introducción'; 
	$lang['WelcomeToThe'] = 'Bienvenido a Plikli , el CMS que impulsa a miles de sitios web de la comunidad. Si esta es la primera vez que instala Plikli CMS, lea cuidadosamente todas las instrucciones proporcionadas para no perder ninguna instrucción importante. '; 
	$lang['Bugs'] = 'Familiarícese con parte de la documentación provista por la comunidad Plikli en el sitio web de Plikli Forum . También sugerimos que registre una cuenta para que tenga acceso a soporte gratuito, módulos, widgets, plantillas y otros recursos geniales. '; 
	$lang['Installation'] = 'Instalación (Lea con atención)'; 
	$lang['OnceFamiliar'] = '<p> Si esta es la primera vez que instala Plikli, debe continuar en esta página después de seguir cuidadosamente las instrucciones a continuación. Si necesita <a href="./upgrade.php"> actualizar su sitio </a> desde una versión anterior, ejecute el script de actualización haciendo clic en el enlace Actualizar que se encuentra más arriba. ADVERTENCIA: la ejecución del proceso de instalación en una base de datos de sitios Plikli existente sobrescribirá todos los datos, por lo que debe asegurarse de realizar una instalación si elige continuar más abajo. </P> <br />
	<ol>
		<li> Cambiar el nombre de settings.php.default a settings.php </li>
		<li> Cambie el nombre de /languages/lang_english.conf.default a lang_english.conf </li>
		<li> Cambiar el nombre de /libs/dbconnect.php.default a dbconnect.php </li>
		<li> Cambie el nombre del directorio /logs.default a / logs </li>
		<li> CHMOD 0777 las siguientes carpetas:
			<ol>
				<li>/admin/backup/ </li>
				<li>/avatars/groups_uploaded/ </li>
				<li>/avatars/user_uploaded/ </li>
				<li>/cache/ </li>
				<li>/languages/ (CHMOD 0777 todos los archivos contenidos en esta carpeta) </li>
			</ol>
		</li>
		<li> CHMOD 0666 los siguientes archivos:
			<ol>
				<li> /libs/dbconnect.php </li>
				<li> settings.php </li>
			</ol>
		</li>
	</ol>
	<p> ¡Ya has pasado la parte más difícil! Continúe con el próximo paso para instalar Plikli en su base de datos MySQL. </P> ';

// step 2
	$lang['EnterMySQL'] = 'Ingrese la configuración de su base de datos MySQL a continuación. Si no conoce la configuración de su base de datos MySQL, debe consultar la documentación de su servidor de Internet o contactarlos directamente. '; 
	$lang['DatabaseName'] = 'Nombre de la base de datos'; 
	$lang['DatabaseUsername'] = 'Nombre de usuario de la base de datos'; 
	$lang['DatabasePassword'] = 'Contraseña de la base de datos'; 
	$lang['DatabaseServer'] = 'Servidor de base de datos'; 
	$lang['TablePrefix'] = 'Prefijo de tabla'; 
	$lang['PrefixExample'] = '(es decir: "plikli_" hace que las tablas para los usuarios se conviertan en plikli_users)'; 
	$lang['CheckSettings'] = 'Comprobar configuraciones'; 
	$lang['Errors'] = 'Corrija los errores anteriores, luego actualice la página '; 
	$lang['LangNotFound'] = 'no se encontró. Elimine la extensión \'. Default\' de todos los archivos de idioma e inténtelo de nuevo. '; 

// step 3
	$lang['ConnectionEstab'] = 'Conexión de la base de datos establecida ...'; 
	$lang['FoundDb'] = 'Base de datos encontrada ...'; 
	$lang['dbconnect'] = '\'/libs/ dbconnect.php\' se actualizó correctamente.'; 
	$lang['NoErrors'] = 'No hubo errores, continúe con el próximo paso ...'; 
	$lang['Next'] = 'Siguiente paso'; 
	$lang['GoBack'] = 'Volver e intentar de nuevo'; 
	$lang['Error2-1'] = 'No se pudo escribir en \'libs/dbconnect.php\' file.'; 
	$lang['Error2-2'] = 'No se pudo abrir el archivo \'/libs/dbconnect.php\' para escribir.'; 
	$lang['Error2-3'] = 'Conectado a la base de datos, pero el nombre de la base de datos es incorrecto.'; 
	$lang['Error2-4'] = 'No se puede conectar al servidor de la base de datos usando la información provista.'; 

// step 4
	$lang['CreatingTables'] = 'Crear tablas de base de datos'; 
	$lang['TablesGood'] = '¡Las tablas se crearon con éxito! '; 
	$lang['Error3-1'] = 'Hubo un problema al crear las tablas.'; 
	$lang['Error3-2'] = 'No se pudo conectar a la base de datos.'; 
	$lang['EnterAdmin'] = 'Ingrese los detalles de su cuenta de administrador a continuación: Por favor, escriba la información de esta cuenta porque será necesaria para iniciar sesión y configurar su sitio.'; 
	$lang['AdminLogin'] = 'Inicio de sesión de administrador'; 
	$lang['AdminPassword'] = 'Contraseña de administrador'; 
	$lang['ConfirmPassword'] = 'Confirmar contraseña'; 
	$lang['AdminEmail'] = 'E-mail de administrador'; 
	$lang['SiteTitleLabel'] = 'Nombre del sitio web'; 
	$lang['CreateAdmin'] = 'Crear cuenta de administrador'; 
	$lang['pwndPassword'] = '¡Ingresó una contraseña común e insegura!'; 

// Step 5
	$lang['Error5-1'] = 'Complete todos los campos para la cuenta de administrador.';
	$lang['Error5-2'] = 'Los campos de contraseña no coinciden. Regrese y vuelva a ingresar los campos de contraseña. ';
	$lang['AddingAdmin'] = 'Agregar la cuenta de usuario de administrador ...';
	$lang['InstallSuccess'] = '¡Instalación completa!';
	$lang['InstallSuccessMessage'] = '¡Felicitaciones, ha configurado un sitio web de Plikli CMS! Si bien su sitio es completamente funcional en este momento, querrá hacer un poco de limpieza siguiendo las instrucciones a continuación para proteger su sitio. ';
	$lang['WhatToDo'] = 'Qué hacer a continuación:';
	$lang['WhatToDoList'] = '<li> chmod "/libs/dbconnect.php" de vuelta a 644, no necesitaremos cambiar este archivo de nuevo. </li><li> <strong> ELIMINE </strong> el directorio "/ install" de su servidor si ha instalado correctamente Plikli. </li><li> Inicie sesión en el <a href="../admin/admin_index.php"> panel </a> con la información del usuario que ingresó en el paso anterior. Una vez que inicie sesión, deberá recibir más información sobre cómo usar Plikli. </Li>
	<li> <a href="../admin/admin_config.php"> Configure su sitio </a> utilizando el tablero. </li>	<li> Visite el <a href="https://www.plikli.com/forum-2/"> sitio web de asistencia de Plikli </a> si tiene alguna pregunta. </li> ';
	$lang['ContinueToSite'] = 'Continuar a su nuevo sitio web';
	
// Upgrade
	$lang['UpgradeHome'] = '<p style = "text-decoration: underline"> La actualización hace cambios <STRONG> IRREVERSIBLE </STRONG> a su base de datos. HAGA UNA COPIA DE SEGURIDAD DE SUS TABLAS DE BASES DE DATOS ANTES DE CONTINUAR Y ASEGÚRESE DE QUE SU BASE DE DATOS SE EXPORTA CON LA "TABLA DE DESCARGAS SI EXISTE". </P> <p> NO PROCEDA CON LA ACTUALIZACIÓN SI HA REALIZADO CUALQUIER PERSONALIZACIÓN EN SUS ARCHIVOS PRINCIPALES. PRIMERO, DEBE HACER LO SIGUIENTE: </p>
	<ul>
	<li> USANDO UNA COPIA DE LOS ARCHIVOS RESPALDADOS, ¡FUSIONE SUS ARCHIVOS CON LOS NUEVOS ARCHIVOS KLIQQI USANDO WINMERGE O UN SOFTWARE SIMILAR! </li>
	<li> SI USTED ESTÁ UTILIZANDO UNA PLANTILLA DE BOOTSTRAP POR OMISIÓN, ASEGÚRESE DE COMBINAR SUS ARCHIVOS CON LA PLANTILLA DE BOOTSTRAP DE KLIQQI PORQUE SE HAN HECHO ALGÚN ALGÚN CAMBIO EN EL CÓDIGO. (VEA LAS NOTAS DESPUÉS DE QUE LA ACTUALIZACIÓN HAYA TERMINADO) </li>
	</ul>
	<br />
	Actualizando sus viejas versiones de Pligg o Kliqqi o Plikli 4.0.0 a Plikli '. $lang['plikli_version']. 'modificará las tablas de su base de datos a la última versión. <br />';
	$lang['UpgradeAreYouSure'] = '¿Está seguro de que desea actualizar su base de datos y archivo de idioma?';
	$lang['UpgradeYes'] = 'Proceder con la actualización';
	$lang['UpgradeLanguage'] = 'Correcto, Plikli actualizó su archivo de idioma. Ahora incluye los últimos elementos de idioma. ';
	$lang['UpgradingTables'] = '<strong> Actualizando la base de datos ... </strong>';
	$lang['LanguageUpdate'] = '<strong> Actualizando archivo de idioma ... </strong>';
	$lang['IfNoError'] = '¡Si no se muestran errores, la actualización está completa!';
	$lang['PleaseFix'] = 'Por favor, corrija los errores anteriores, actualice suspendido!';
	
// Errors
	$lang['NotFound'] = 'no se encontró!'; 
	$lang['CacheNotFound'] = 'no fue encontrado! Cree un directorio llamado / cache en su directorio raíz y configúrelo en CHMOD 777. '; 
	$lang['DbconnectNotFound'] = 'no fue encontrado! Intente cambiar el nombre de dbconnect.php.default a dbconnect.php '; 
	$lang['SettingsNotFound'] = 'no fue encontrado! Intente cambiar el nombre de settings.php.default a settings.php '; 
	$lang['ZeroBytes'] = 'es 0 bytes.'; 
	$lang['NotEditable'] = 'no se puede escribir. Por favor CHMOD a 777 '; 
	
?>