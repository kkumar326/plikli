<?php
// header
	$lang['plikli_version'] = '4.1.0';
	$lang['installer'] = 'Instalador';
	$lang['Welcome'] = 'Bem-vindo';
	$lang['Install'] = 'Instalar';
	$lang['Upgrade'] = 'Atualizar';
	$lang['Upgrade-Kliqqi'] = 'Atualizar-Kliqqi';
	$lang['Upgrade-Pligg'] = 'Atualizar-Pligg';
	$lang['Troubleshooter'] = 'Solucionador de problemas';
	$lang['Step'] = 'Etapa';
	$lang['Readme'] = 'Leiame';
	$lang['Admin'] = 'Painel de controle';
	$lang['Home'] = 'Início';
	$lang['Install_instruct'] = 'Por favor, tenha suas informações do MySQL à mão. Consulte Atualizar para atualizar um site existente. ';
	$lang['Upgrade_instruct'] = 'A atualização fará modificações no seu banco de dados MySQL. Certifique-se de fazer backup antes de prosseguir. ';
	$lang['Troubleshooter_instruct'] = 'O Solucionador de problemas detectará problemas comuns, como permissões de pasta incorretas';

// intro / step 1
	$lang['WelcomeToInstaller'] = 'Bem-vindo ao Plikli CMS Installer!';
	$lang['Introduction'] = 'Introdução';
	$lang['WelcomeToThe'] = 'Bem-vindo ao <a href="https://www.plikli.com" target="_blank" rel="noopener noreferrer"> Plikli </a>, o CMS que alimenta milhares de pessoas sites da comunidade. Se esta é a primeira vez que instala o Plikli CMS, leia atentamente todas as instruções fornecidas, para não perder quaisquer orientações importantes. ';
	$lang['Bugs'] = 'Por favor, familiarize-se com parte da documentação fornecida pela comunidade Plikli no <a href="https://www.plikli.com/forum-2/"> Fórum do Plikli </a > site. Também sugerimos que você registre uma conta para que você tenha acesso a suporte gratuito, módulos, widgets, modelos e outros recursos excelentes. ';
	$lang['Installation'] = 'Instalação (Por favor, leia com atenção)';
	$lang['OnceFamiliar'] = '<p> Se esta é a primeira vez que instala o Plikli, você deve continuar nesta página depois de seguir cuidadosamente as instruções abaixo. Se você precisar <a href="./upgrade.php"> atualizar seu site </a> de uma versão anterior, execute o script de upgrade clicando no link Upgrade acima. AVISO: a execução do processo de instalação em um banco de dados do site Plikli existente sobrescreverá todos os dados, portanto, certifique-se de realizar uma instalação se optar por continuar abaixo. </P> <br />
	<ol>
		<li> Renomeie settings.php.default para settings.php </li>
		<li> Renomeie /languages/lang_english.conf.default para lang_english.conf </li>
		<li> Renomeie /libs/dbconnect.php.default para dbconnect.php </li>
		<li> Renomeie o diretório /logs.default para /logs </li>
		<li> CHMOD 0777 as seguintes pastas:
			<ol>
				<li>/admin/backup/ </li>
				<li>/avatars/groups_uploaded/ </li>
				<li>/avatars/user_uploaded/ </li>
				<li>/cache/ </li>
				<li>/languages​​/ (CHMOD 0777 todos os arquivos contidos nesta pasta) </li>
			</ol>
		</li>
		<li> CHMOD 0666 os seguintes arquivos:
			<ol>
				<li>/libs/dbconnect.php </li>
				<li>settings.php </li>
			</ol>
		</li>
	</ol>
	<p> Você passou da parte mais difícil! Prossiga para o próximo passo para instalar o Plikli em seu banco de dados MySQL. </P> ';

// step 2
	$lang['EnterMySQL'] = 'Insira suas configurações do banco de dados MySQL abaixo. Se você não conhece as configurações do banco de dados MySQL, verifique a documentação da sua hospedagem ou entre em contato diretamente com elas. ';
	$lang['DatabaseName'] = 'Nome do banco de dados';
	$lang['DatabaseUsername'] = 'Nome de usuário do banco de dados';
	$lang['DatabasePassword'] = 'Senha do banco de dados';
	$lang['DatabaseServer'] = 'Servidor de banco de dados';
	$lang['TablePrefix'] = 'Prefixo da Tabela';
	$lang['PrefixExample'] = '(ex .: "plikli_" faz com que as tabelas para usuários se tornem plikli_users)';
	$lang['CheckSettings'] = 'Verificar configurações';
	$lang['Errors'] = '<br /> <br /> Por favor corrija o (s) erro (s) acima, então <a class = "btn btn-default btn-xs" onClick = "document.location.reload (true ) "> Atualizar a Página </a> ';
	$lang['LangNotFound'] = 'não foi encontrado. Por favor, remova a extensão \'.Default\' de todos os arquivos de idiomas e tente novamente. ';

// step 3
	$lang['ConnectionEstab'] = 'Conexão de banco de dados estabelecida ...';
	$lang['FoundDb'] = 'Banco de dados encontrado ...';
	$lang['dbconnect'] = '\'/libs/dbconnect.php\' foi atualizado com sucesso.';
	$lang['NoErrors'] = 'Não houve erros, continue na próxima etapa ...';
	$lang['Next'] = 'Próximo passo';
	$lang['GoBack'] = 'Volte e tente novamente';
	$lang['Error2-1'] = 'Não foi possível gravar no arquivo \'/libs/dbconnect.php\'.';
	$lang['Error2-2'] = 'Não foi possível abrir o arquivo \'/libs/dbconnect.php\'para escrita.';
	$lang['Error2-3'] = 'Conectado ao banco de dados, mas o nome do banco de dados está incorreto.';
	$lang['Error2-4'] = 'Não é possível conectar ao servidor de banco de dados usando as informações fornecidas.';

// step 4
	$lang['CreatingTables'] = 'Criando tabelas de banco de dados';
	$lang['TablesGood'] = 'As tabelas <strong> foram criadas com sucesso! </strong>';
	$lang['Error3-1'] = '<p> Ocorreu um problema ao criar as tabelas. </p>';
	$lang['Error3-2'] = '<p> Não foi possível conectar ao banco de dados. </p>';
	$lang['EnterAdmin'] = '<p> <strong> Insira os detalhes da sua conta de administrador abaixo: </strong> <br /> Anote as informações desta conta, pois ela será necessária para fazer login e configurar seu site. / p> ';
	$lang['AdminLogin'] = 'Login do Administrador';
	$lang['AdminPassword'] = 'Senha do Administrador';
	$lang['ConfirmPassword'] = 'Confirme a senha';
	$lang['AdminEmail'] = 'E-mail do administrador';
	$lang['SiteTitleLabel'] = 'Nome do site';
	$lang['CreateAdmin'] = 'Criar conta de administrador';
	$lang['pwndPassword'] = 'Você digitou uma senha comum e insegura!';

// Step 5
	$lang['Error5-1'] = 'Por favor, preencha todos os campos da conta admin.';
	$lang['Error5-2'] = 'Os campos da senha não coincidem. Por favor, volte e volte a introduzir os campos de senha. ';
	$lang['AddingAdmin'] = 'Adicionando a conta do usuário Admin ...';
	$lang['InstallSuccess'] = 'Instalação concluída!';
	$lang['InstallSuccessMessage'] = 'Parabéns, você configurou um site do Plikli CMS! Enquanto o seu site está totalmente funcional neste momento, você vai querer fazer um pouco de limpeza, seguindo as instruções abaixo para proteger o seu site. ';
	$lang['WhatToDo'] = 'O que fazer em seguida:';
	$lang['WhatToDoList'] = '<li> chmod "/libs/dbconnect.php" de volta para 644, não precisaremos alterar este arquivo novamente. </li>
	<li> <strong> EXCLUI </strong> o diretório "/install" do seu servidor se você instalou com êxito o Plikli. </li>
	<li> Faça login no <a href="../admin/admin_index.php"> painel </a> usando as informações do usuário que você inseriu na etapa anterior. Depois de fazer o login, você deve receber mais informações sobre como usar o Plikli. </Li>
	<li> <a href="../admin/admin_config.php"> Configure seu site </a> usando o painel. </li>
	<li> Visite o site <a href="https://www.plikli.com/forum-2/"> Suporte da Plikli </a> se tiver alguma dúvida. </li> ';
	$lang['ContinueToSite'] = 'Continuar para o seu novo site';
	
// Upgrade
	$lang['UpgradeHome'] = '<p style = "text-decoration: sublinhado"> A atualização torna <STRONG> IRREVERSIBLE </STRONG> alterações em seu banco de dados. FAÇA O BACKUP DAS TABELAS DE BANCO DE DADOS ANTES DE UTILIZAR ALGUMA E VERIFIQUE SEU BANCO DE DADOS É EXPORTADO COM A "TABELA DE QUEDA SE EXISTE" VERIFICADA! </P> <p> NÃO REALIZE A ATUALIZAÇÃO SE TIVER FEITO QUALQUER PERSONALIZAÇÃO PARA SEUS ARQUIVOS PRINCIPAIS. PRIMEIRO, VOCÊ DEVE FAZER O SEGUINTE: </p>
	<ul>
	<li> USANDO UMA CÓPIA DOS ARQUIVOS DE BACKUP, MERGUE SEUS ARQUIVOS COM OS NOVOS ARQUIVOS KLIQQI USANDO O WINMERGE OU UM SOFTWARE SEMELHANTE! </li>
	<li> SE VOCÊ ESTIVER USANDO QUE NÃO O MODELO DE BOOTSTRAP PREDEFINIDO, CERTIFIQUE-SE DE AGIR SEUS ARQUIVOS COM O MODELO KLIQQI BOOTSTRAP PORQUE ALGUMAS ALTERAÇÕES TENHAM SIDO FEITAS AO CÓDIGO. (VEJA NOTAS APÓS O UPGRADE TERMINAR) </li>
	</ul>
	<br />
	Atualizando suas versões antigas do Pligg ou Kliqqi ou Plikli 4.0.0 para o Plikli '. $lang['plikli_version']. 'irá modificar suas tabelas de banco de dados para a versão mais recente. <br />';
	$lang['UpgradeAreYouSure'] = 'Tem certeza de que deseja atualizar seu banco de dados e arquivo de idioma?';
	$lang['UpgradeYes'] = 'Continuar com a atualização';
	$lang['UpgradeLanguage'] = 'Sucesso, o Plikli atualizou seu arquivo de idioma. Agora inclui os itens de idioma mais recentes. ';
	$lang['UpgradingTables'] = '<strong> Atualizando o Banco de Dados ... </strong>';
	$lang['LanguageUpdate'] = '<strong> Atualizando o arquivo de idioma ... </strong>';
	$lang['IfNoError'] = 'Se não houver erros, a atualização estará completa!';
	$lang['PleaseFix'] = 'Por favor corrija o (s) erro (s) acima, a atualização parou!';
	
// Errors
	$lang['NotFound'] = 'não foi encontrado!';
	$lang['CacheNotFound'] = 'não foi encontrado! Crie um diretório chamado / cache em seu diretório raiz e configure-o para o CHMOD 777. ';
	$lang['DbconnectNotFound'] = 'não foi encontrado! Tente renomear dbconnect.php.default para dbconnect.php ';
	$lang['SettingsNotFound'] = 'não foi encontrado! Tente renomear settings.php.default para settings.php ';
	$lang['ZeroBytes'] = 'é 0 bytes.';
	$lang['NotEditable'] = 'não é gravável. Por favor, CHMOD para 777 ';
	
?>