<?php
/**
 * filename: $Source$
 * begin: Friday, Jul 01, 2005
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Ricardo Luiz Costa <ricardo@winger.com.br>
 * @copyright (C) 2005 Ricardo Luiz Costa
 * @package Language
 * @version $Id$
 */


/**
 * Global
 */
$lng['translator'] = 'Ricardo Luiz Costa';
$lng['panel']['edit'] = 'Editar';
$lng['panel']['delete'] = 'Deletar';
$lng['panel']['create'] = 'Criar';
$lng['panel']['save'] = 'Salvar';
$lng['panel']['yes'] = 'Sim';
$lng['panel']['no'] = 'N&atilde;o';
$lng['panel']['emptyfornochanges'] = 'Sair sem salvar';
$lng['panel']['emptyfordefault'] = 'Restaurar padr&atilde;o';
$lng['panel']['path'] = 'Caminho';
$lng['panel']['toggle'] = 'Toggle';
$lng['panel']['next'] = 'Pr&oacute;ximo';
$lng['panel']['dirsmissing'] = 'Direct&oacute;rio n&atilde;o dispon&iacute;vel ou ileg&iacute;vel';

/**
 * Login
 */
$lng['login']['username'] = 'Usu&aacute;rio';
$lng['login']['password'] = 'Senha';
$lng['login']['language'] = 'Idioma';
$lng['login']['login'] = 'Login';
$lng['login']['logout'] = 'Sair';
$lng['login']['profile_lng'] = 'Idioma padr&atilde;o';

/**
 * Customer
 */
$lng['customer']['documentroot'] = 'Diretorio home';
$lng['customer']['name'] = 'Sobrenome';
$lng['customer']['firstname'] = 'Primeiro nome';
$lng['customer']['company'] = 'Empresa';
$lng['customer']['street'] = 'Endere&ccedil;o';
$lng['customer']['zipcode'] = 'Cep';
$lng['customer']['city'] = 'Cidade';
$lng['customer']['phone'] = 'Telefone';
$lng['customer']['fax'] = 'Fax';
$lng['customer']['email'] = 'E-mail';
$lng['customer']['customernumber'] = 'Cliente ID';
$lng['customer']['diskspace'] = 'Espa&ccedil;o de disco (MB)';
$lng['customer']['traffic'] = 'Tr&aacute;fego (GB)';
$lng['customer']['mysqls'] = 'Bancos de dados-MySQL';
$lng['customer']['emails'] = 'Endere&ccedil;os de e-mail';
$lng['customer']['accounts'] = 'Contas de e-mail';
$lng['customer']['forwarders'] = 'Redirecionamentos de e-mail';
$lng['customer']['ftps'] = 'Contas de FTP';
$lng['customer']['subdomains'] = 'Sub-Dominio(s)';
$lng['customer']['domains'] = 'Dominio(s)';
$lng['customer']['unlimited'] = 'ilimitados';

/**
 * Customermenue
 */
$lng['menue']['main']['main'] = 'Principal';
$lng['menue']['main']['changepassword'] = 'Trocar senha';
$lng['menue']['main']['changelanguage'] = 'Trocar idioma';
$lng['menue']['email']['email'] = 'e-mail';
$lng['menue']['email']['emails'] = 'Endere&ccedil;os';
$lng['menue']['email']['webmail'] = 'WebMail';
$lng['menue']['mysql']['mysql'] = 'MySQL';
$lng['menue']['mysql']['databases'] = 'Banco de dados';
$lng['menue']['mysql']['phpmyadmin'] = 'phpMyAdmin';
$lng['menue']['domains']['domains'] = 'Dominios';
$lng['menue']['domains']['settings'] = 'Configura&ccedil;&otilde;es';
$lng['menue']['ftp']['ftp'] = 'FTP';
$lng['menue']['ftp']['accounts'] = 'Contas';
$lng['menue']['ftp']['webftp'] = 'WebFTP';
$lng['menue']['extras']['extras'] = 'Extras';
$lng['menue']['extras']['directoryprotection'] = 'Diretorio protegido';
$lng['menue']['extras']['pathoptions'] = 'op&ccedil;&otilde;es de caminhos';

/**
 * Index
 */
$lng['index']['customerdetails'] = 'Detalhes dos Clientes';
$lng['index']['accountdetails'] = 'Detalhes das Contas';

/**
 * Change Password
 */
$lng['changepassword']['old_password'] = 'Senha atual';
$lng['changepassword']['new_password'] = 'Nova senha';
$lng['changepassword']['new_password_confirm'] = 'Repita a nova senha';
$lng['changepassword']['new_password_ifnotempty'] = 'Nova senha (em branco = n&atilde;o alterar)';
$lng['changepassword']['also_change_ftp'] = ' trocar tambem a senha da conta principal de FTP';

/**
 * Domains
 */
$lng['domains']['description'] = 'Aqui voce pode criar(sub-)dominios e alterar seu destino.<br />O sistema ir&aacute; levar algum tempo para aplicar as novas configura&ccedil;&otilde;es depois de salvas.';
$lng['domains']['domainsettings'] = 'Configurar Dominio';
$lng['domains']['domainname'] = 'Nome do dominio';
$lng['domains']['subdomain_add'] = 'Criar Sub-dominio';
$lng['domains']['subdomain_edit'] = 'Editar (sub)dominio';
$lng['domains']['wildcarddomain'] = 'Criar um wildcarddomain?';
$lng['domains']['aliasdomain'] = 'Ali&aacute;s para o dominio';
$lng['domains']['noaliasdomain'] = 'N&atilde;o dominio do ali&aacute;s';

/**
 * eMails
 */
$lng['emails']['description'] = 'Aqui voce pode criar e alterer seus e-mails.<br />Uma conta &eacute; como uma caixa de correio na frente de sua casa. Quando alguem envia para voce um e-mail, ele &eacute; colocado nesta conta.<br /><br />Para baixar seus e-mails use as seguintes configura&ccedil;&otilde;es no seu propraga de e-mails favorito: (Os dados em <i>italico</i> devem ser substituidos pelo equivalente da conta que voce criou!)<br />Hostname: <b><i>Nome de seu dominio</i></b><br />Usu&aacute;rio: <b><i>Nome da conta / Endere&ccedil;o de e-mail</i></b><br />Senha: <b><i>a senha que voce escolheu</i></b>';
$lng['emails']['emailaddress'] = 'Endere&ccedil;os de e-mail';
$lng['emails']['emails_add'] = 'Criar e-mail';
$lng['emails']['emails_edit'] = 'Editar e-mail';
$lng['emails']['catchall'] = 'Pega tudo';
$lng['emails']['iscatchall'] = 'Definir como endere&ccedil;o pega tudo?';
$lng['emails']['account'] = 'Conta';
$lng['emails']['account_add'] = 'Criar conta';
$lng['emails']['account_delete'] = 'Excluir conta';
$lng['emails']['from'] = 'Origem';
$lng['emails']['to'] = 'Destino';
$lng['emails']['forwarders'] = 'Redirecionamentos';
$lng['emails']['forwarder_add'] = 'Criar redirecionamento';

/**
 * FTP
 */
$lng['ftp']['description'] = 'Aqui voce pode criar e alterar suas contas de FTP.<br />As altera&ccedil;&otilde;es s&atilde;o instant&acirc;neas e podem ser utilizadas imediatamente depois de salvas.';
$lng['ftp']['account_add'] = 'Criar conta';

/**
 * MySQL
 */
$lng['mysql']['description'] = 'Aqui voce pode criar e alterar seus bancos de dados MySQL.<br />As altera&ccedil;&otilde;es s&atilde;o instant&acirc;neas e podem ser utilizadas imediatamente depois de salvas.<br />No menu do lado esquerdo voce pode encontrar a ferramenta phpMyAdmin e com ela facilmente administrar seus bancos de dados.<br /><br />Para usar seu banco de dados com scripts em PHP use as seguintes configura&ccedil;&otilde;es: (Os dados em <i>italico</i> devem ser substituidos pelo equivalente do banco de dados que voce criou!)<br />Hostname: <b>localhost</b><br />Usuario: <b><i>Nome do banco de dadose</i></b><br />Senha: <b><i>a senha que voce escolheu</i></b><br />Banco de dados: <b><i>Nome do banco de dados';
$lng['mysql']['databasename'] = 'Usuario / Nome do banco de dados';
$lng['mysql']['databasedescription'] = 'Descri&ccedil;&atilde;o do banco de dados';
$lng['mysql']['database_create'] = 'Criar banco de dados';

/**
 * Extras
 */
$lng['extras']['description'] = 'Aqui voce pode adicoionar alguns recursos extras, como por exemplo um diret&oacute;rio protegido.<br />O sistema ira precisar de algum tempo para aplicar suas altera&ccedil;&otilde;es depois de salvas.';
$lng['extras']['directoryprotection_add'] = 'Adicionar diret&oacute;rio pretogido';
$lng['extras']['view_directory'] = 'Mostrar conte&uacute;do do diret&oacute;rio';
$lng['extras']['pathoptions_add'] = 'Adicionar op&ccedil;&otilde;es de caminho';
$lng['extras']['directory_browsing'] = 'Pesquizar conte&uacute;do de diret&oacute;rio';
$lng['extras']['pathoptions_edit'] = 'Esitar op&ccedil;&otilde;es de caminhos';
$lng['extras']['error404path'] = '404';
$lng['extras']['error403path'] = '403';
$lng['extras']['error500path'] = '500';
$lng['extras']['error401path'] = '401';
$lng['extras']['errordocument404path'] = 'URL para p&aacute;gina de erro 404';
$lng['extras']['errordocument403path'] = 'URL para p&aacute;gina de erro 403';
$lng['extras']['errordocument500path'] = 'URL para p&aacute;gina de erro 500';
$lng['extras']['errordocument401path'] = 'URL para p&aacute;gina de erro 401';

/**
 * Errors
 */
$lng['error']['error'] = 'Erro';
$lng['error']['directorymustexist'] = 'O diret&oacute;rio %s deve existir. Por favor crie ele primeiro com seu programa de FTP.';
$lng['error']['filemustexist'] = 'O arquivo %s deve existir.';
$lng['error']['allresourcesused'] = 'Voce j&aacute; usou todos os seus recursos.';
$lng['error']['domains_cantdeletemaindomain'] = 'Voce n&atilde;o pode deletar um dominio que esta sendo usado como email-domain.';
$lng['error']['domains_canteditdomain'] = 'Voce n&atilde;o pode editar este dominio. Ele foi desabilitado pelo administrador.';
$lng['error']['domains_cantdeletedomainwithemail'] = 'Voce n&atilde;o pode deletar um dominio que &eacute; usado como email-domain. Delete todos as contas de e-mail primeiro.';
$lng['error']['firstdeleteallsubdomains'] = 'Voce deve deletar todos subdominios antes de poder criar um wildcard domain.';
$lng['error']['youhavealreadyacatchallforthisdomain'] = 'Voce j&aacute; definiu uma conta pega tudo para este dominio.';
$lng['error']['ftp_cantdeletemainaccount'] = 'Voce n&atilde;o pode deletar a conta principal de FTP';
$lng['error']['login'] = 'O usu&aacute;rio ou senha digitados, n&atilde;o est&atilde;o corretos. Por favor tente novamente!';
$lng['error']['login_blocked'] = 'Esta conta est&aacute; suspensa por exceder as tentativas de login permitidas. <br />Por favor tente novamente em '.$settings['login']['deactivatetime'].' segundos.';
$lng['error']['notallreqfieldsorerrors'] = 'Voce n&atilde;o preencheu todos os campos ou preencheu algum campo incorretamente.';
$lng['error']['oldpasswordnotcorrect'] = 'A senha antiga n&atilde;o confere.';
$lng['error']['youcantallocatemorethanyouhave'] = 'Voce n&atilde;o pode alocar mais recursos do que voce mesmo possui.';
$lng['error']['youcantdeletechangemainadmin'] = 'Voce n&atilde;o pode deletar ou editar o administrador principal por raz&otilde;es de seguran&ccedil;a.';

$lng['error']['mustbeurl'] = 'Voce n&atilde;o digitou uma URL v&aacute;lida (ex. http://seudominio.com/erro404.htm)';
$lng['error']['invalidpath'] = 'Optou por um URL n&atilde;o v&aacute;lido (eventuais problemas na lista do direct&oacute;rio)';
$lng['error']['stringisempty'] ='Faltando informa&ccedil;&atilde;o no campo';
$lng['error']['stringiswrong'] ='Erro na informa&ccedil;&atilde;o do campo';
$lng['error']['myloginname'] = '\''.$lng['login']['username'].'\'';
$lng['error']['mypassword'] = '\''.$lng['login']['password'].'\'';
$lng['error']['oldpassword'] = '\''.$lng['changepassword']['old_password'].'\'';
$lng['error']['newpassword'] = '\''.$lng['changepassword']['new_password'].'\'';
$lng['error']['newpasswordconfirm']= '\''.$lng['changepassword']['new_password_confirm'].'\'';
$lng['error']['newpasswordconfirmerror']='A nova senha e a confirma&ccedil;&atilde;o n&atilde;o conferem';
$lng['error']['myname'] = '\''.$lng['customer']['name'].'\'';
$lng['error']['myfirstname'] = '\''.$lng['customer']['firstname'].'\'';
$lng['error']['emailadd'] = '\''.$lng['customer']['email'].'\'';
$lng['error']['mydomain'] = '\'Dominio\'';
$lng['error']['mydocumentroot'] = '\'Documento principal\'';
$lng['error']['loginnameexists']= 'Login %s j&aacute; existe';
$lng['error']['emailiswrong']= 'E-mail %s contem caracteres inv&aacute;lidos ou est&aacute; incompleto';
$lng['error']['loginnameiswrong']= 'Login %s contem caracteres inv&aacute;lidos';
$lng['error']['userpathcombinationdupe']='Usuario e caminho j&aacute; existem';
$lng['error']['patherror']='Erro geral! o caminho não pode ficar em branco';
$lng['error']['errordocpathdupe']='Opção de caminho %s j&aacute; existe';
$lng['error']['adduserfirst']='Por favor crie um cliente primeiro';
$lng['error']['domainalreadyexists']= 'O dominio %s j&aacute; est&aacute; apontado para outro cliente';
$lng['error']['nolanguageselect']='Nenhum idioma selecionado.';
$lng['error']['nosubjectcreate']='Voce deve definir um nome para este e-mail template.';
$lng['error']['nomailbodycreate']='Voce deve definir o texto para este e-mail template.';
$lng['error']['templatenotfound']='Template n&atilde;o encontrado.';
$lng['error']['alltemplatesdefined']='Voce n&atilde;o pode definir mais templates, todos idiomas j&aacute; suportados.';
$lng['error']['wwwnotallowed']='www n&atilde;o &eacute; permitido como nome de subdominio.';
$lng['error']['subdomainiswrong']='O subdominio %s cont&eacute;m caracteres inv&aacute;lidos.';
$lng['error']['domaincantbeempty']='O nome do dominio n&atilde;o pode estar vazio.';
$lng['error']['domainexistalready']='O dominio %s j&aacute; existe.';
$lng['error']['domainisaliasorothercustomer']='O dom&iacute;nio-alias escolhido &eacute; ele pr&oacute;prio um dom&iacute;nio-alias ou este pertence a um outro cliente.';
$lng['error']['emailexistalready']='O E-mail %s j&aacute; existe.';
$lng['error']['maindomainnonexist']='O dominio principal %s n&atilde;o existe.';
$lng['error']['destinationnonexist']='Por favor crie seu redirecionamento no campo \'Destino\'.';
$lng['error']['destinationalreadyexistasmail']='O redirecionamento %s j&aacute; existe como uma conta de e-mail.';
$lng['error']['destinationalreadyexist']='Voce j&aacute; definiu um redirecionamento para %s .';
$lng['error']['destinationiswrong']= 'O redirecionamento %s contem caracteres inv&aacute;lidos ou incompletos.';
$lng['error']['domainname']=$lng['domains']['domainname'];

/**
 * Questions
 */
$lng['question']['question'] = 'Pergunta de seguran&ccedil;a';
$lng['question']['admin_customer_reallydelete'] = 'Voce realmente deseja deletar o cliente %s? Este comando n&atilde;o poder&aacute; ser cancelado!';
$lng['question']['admin_domain_reallydelete'] = 'Voce realmente deseja deletar o dominio %s?';
$lng['question']['admin_domain_reallydisablesecuritysetting'] = 'Voce realmente deseja desativar estas configura&ccedil;&otilde;es de seguran&ccedil;a (OpenBasedir e/ou SafeMode)?';
$lng['question']['admin_admin_reallydelete'] = 'Voce realmente deseja deletar o administrador %s? Todos clientes e dominios ser&atilde;o realocados para o administrador principal.';
$lng['question']['admin_template_reallydelete'] = 'Voce realmente deseja deletar o template \'%s\'?';
$lng['question']['domains_reallydelete'] = 'Voce realmente deseja deletar o dominio %s?';
$lng['question']['email_reallydelete'] = 'Voce realmente deseja deletar o e-mail %s?';
$lng['question']['email_reallydelete_account'] = 'Voce realmente deseja deletar a conta de e-mail %s?';
$lng['question']['email_reallydelete_forwarder'] = 'Voce realmente deseja deletar o redirecionamento %s?';
$lng['question']['extras_reallydelete'] = 'Voce realmente deseja deletar a prote&ccedil;&atilde;o do diret&oacute;rio %s?';
$lng['question']['extras_reallydelete_pathoptions'] = 'Voce realmente deseja deletar o caminho %s?';
$lng['question']['ftp_reallydelete'] = 'Voce realmente deseja deletar a conta de FTP %s?';
$lng['question']['mysql_reallydelete'] = 'Voce realmente deseja deletar o banco de dados %s? Este comando n&atilde;o poder&aacute; ser cancelado!';
$lng['question']['admin_configs_reallyrebuild'] = 'Est&aacute; certo que quer deixar reconfigurar os ficheiros de configura&ccedil;&atilde;o de Apache e Bind?';

/**
 * Mails
 */
$lng['mails']['pop_success']['mailbody'] = 'Ol&aacute;,\n\n sua conta de e-mail {EMAIL}\n foi criada com sucesso.\n\nEsta &eacute; uma mensagem autom&aacute;tica\neMail, por favor n&atilde;o responda!\n\nAtenciosamente, Equipe de desenvolvimento do SysCP';
$lng['mails']['pop_success']['subject'] = 'Conta de e-mail criada com sucesso!';
$lng['mails']['createcustomer']['mailbody'] = 'Ol&aacute; {FIRSTNAME} {NAME},\n\nseguem os detalhes de sua nova conta de e-mail:\n\nUsuario: {USERNAME}\nSenha: {PASSWORD}\n\nObrigado,\nEquipe de desenvolvimento do SysCP';
$lng['mails']['createcustomer']['subject'] = 'Informa&ccedil;&otilde;es da conta';

/**
 * Admin
 */
$lng['admin']['overview'] = 'Vis&atilde;o geral';
$lng['admin']['ressourcedetails'] = 'Recursos usados';
$lng['admin']['systemdetails'] = 'Detalhes do sistema';
$lng['admin']['syscpdetails'] = 'Detalhes do SysCP';
$lng['admin']['installedversion'] = 'Vers&atilde;o instalada';
$lng['admin']['latestversion'] = 'Ultima Vers&atilde;o';
$lng['admin']['lookfornewversion']['clickhere'] = 'procurar pela internet';
$lng['admin']['lookfornewversion']['error'] = 'Erro de leitura';
$lng['admin']['resources'] = 'Recursos';
$lng['admin']['customer'] = 'Cliente';
$lng['admin']['customers'] = 'Clientes';
$lng['admin']['customer_add'] = 'Criar cliente';
$lng['admin']['customer_edit'] = 'Editar cliente';
$lng['admin']['domains'] = 'Dominios';
$lng['admin']['domain_add'] = 'Criar dominio';
$lng['admin']['domain_edit'] = 'Editar dominio';
$lng['admin']['subdomainforemail'] = 'Subdominio como &quot;emaildomains&quot;';
$lng['admin']['admin'] = 'Administrador';
$lng['admin']['admins'] = 'Administradores';
$lng['admin']['admin_add'] = 'Criar administrador';
$lng['admin']['admin_edit'] = 'Editar administrador';
$lng['admin']['customers_see_all'] = 'Mostrar todos os clientes';
$lng['admin']['domains_see_all'] = 'Mostrar todos os dominios';
$lng['admin']['change_serversettings'] = 'Alterar configura&ccedil;&ccedil;es do servidor?';
$lng['admin']['server'] = 'Servidor';
$lng['admin']['serversettings'] = 'Configura&ccedil;&ccedil;es';
$lng['admin']['rebuildconf'] = 'Escrever de novo os configs';
$lng['admin']['stdsubdomain'] = 'Subdominio padr&atilde;o';
$lng['admin']['stdsubdomain_add'] = 'Criar Subdominio padr&atilde;o';
$lng['admin']['deactivated'] = 'Desativado';
$lng['admin']['deactivated_user'] = 'Desativar usu&aacute;rio';
$lng['admin']['sendpassword'] = 'Enviar senha';
$lng['admin']['ownvhostsettings'] = 'Own vHost-Settings';
$lng['admin']['configfiles']['serverconfiguration'] = 'Configura&ccedil;&otilde;es';
$lng['admin']['configfiles']['files'] = '<b>Configfiles:</b> Por favor altere os seguintes arquivos ou crie eles com<br />o seguinte conte&uacute;do se ele n&atilde;o existir.<br /><b>Por favor observe:</b> A senha do MySQL não foi alterada por raz&otilde;es de seguran&ccedil;a.<br />Por favor substitua &quot;MYSQL_PASSWORD&quot; por uma sua. Se voce esqueceu a senha do MySQL<br />voce pode verificar em &quot;lib/userdata.inc.php&quot;.';
$lng['admin']['configfiles']['commands'] = '<b>Commands:</b> Por favor execute as seguintes comandos no shell.';
$lng['admin']['configfiles']['restart'] = '<b>Restart:</b> Por favor execute as seguintes comandos no shell para carregar aas novas configura&ccedil;&otilde;es.';
$lng['admin']['templates']['templates'] = 'Templates';
$lng['admin']['templates']['template_add'] = 'Adicionar template';
$lng['admin']['templates']['template_edit'] = 'Editar template';
$lng['admin']['templates']['action'] = 'A&ccedil;&atilde;o';
$lng['admin']['templates']['email'] = 'E-Mail';
$lng['admin']['templates']['subject'] = 'Assunto';
$lng['admin']['templates']['mailbody'] = 'Mensagem';
$lng['admin']['templates']['createcustomer'] = 'E-mail de boas-vindas para novos clientes';
$lng['admin']['templates']['pop_success'] = 'E-mail de boas-vindas para nova conta de e-mail';
$lng['admin']['templates']['template_replace_vars'] = 'Variaveis para serem substituidas no template:';
$lng['admin']['templates']['FIRSTNAME'] = 'Altere para o primeiro nome do cliente.';
$lng['admin']['templates']['NAME'] = 'Altere para o nome do cliente.';
$lng['admin']['templates']['USERNAME'] = 'Altere para nome da conta do cliente.';
$lng['admin']['templates']['PASSWORD'] = 'Altere com a senha da conta do cliente.';
$lng['admin']['templates']['EMAIL'] = 'Altere com os dados do servidor POP3/IMAP.';

/**
 * Serversettings
 */
$lng['serversettings']['session_timeout']['title'] = 'Tempo esgotado';
$lng['serversettings']['session_timeout']['description'] = 'Quanto tempo o usuario deve estar inativo para ser desconectado (segundos)?';
$lng['serversettings']['accountprefix']['title'] = 'Prefixo do cliente';
$lng['serversettings']['accountprefix']['description'] = 'Qual o prefixo &quot;customeraccounts&quot; deve ter?';
$lng['serversettings']['mysqlprefix']['title'] = 'SQL Prefixo';
$lng['serversettings']['mysqlprefix']['description'] = 'Qual prefixo as contas mysql devem ter?';
$lng['serversettings']['ftpprefix']['title'] = 'FTP Prefixo';
$lng['serversettings']['ftpprefix']['description'] = 'Qual prefixo as contas de FTP devem ter?';
$lng['serversettings']['documentroot_prefix']['title'] = 'Diret&oacute;rio de documenta&ccedil;&atilde;o';
$lng['serversettings']['documentroot_prefix']['description'] = 'Aonde os documentos dever ser gravados?';
$lng['serversettings']['logfiles_directory']['title'] = 'Diret&oacute;rio de LOG';
$lng['serversettings']['logfiles_directory']['description'] = 'Aonde os arquivos de log dever ser gravados?';
$lng['serversettings']['ipaddress']['title'] = 'Endere&ccedil;os de IP';
$lng['serversettings']['ipaddress']['description'] = 'Quais os Endere&ccedil;os IP deste servidor?';
$lng['serversettings']['hostname']['title'] = 'Hostname';
$lng['serversettings']['hostname']['description'] = 'Qual o Hostname deste servidor?';
$lng['serversettings']['apacheconf_directory']['title'] = 'Diret&oacute;rio de configura&ccedil;&atilde;o do Apache';
$lng['serversettings']['apacheconf_directory']['description'] = 'Aonde est&atilde;o os arquivos de configura&ccedil;&atilde;o do apache?';
$lng['serversettings']['apachereload_command']['title'] = 'Comando de reiniciar o Apache';
$lng['serversettings']['apachereload_command']['description'] = 'Qual o comando para reiniciar o apache?';
$lng['serversettings']['bindconf_directory']['title'] = 'Diret&oacute;rio de configura&ccedil;&atilde;o do Bind';
$lng['serversettings']['bindconf_directory']['description'] = 'Aonde est&atilde;o os arquivos de configura&ccedil;&atilde;o do bind?';
$lng['serversettings']['bindreload_command']['title'] = 'Comando de reiniciar o Bind';
$lng['serversettings']['bindreload_command']['description'] = 'Qual o comando para reiniciar o bind?';
$lng['serversettings']['binddefaultzone']['title'] = 'Bind default zone';
$lng['serversettings']['binddefaultzone']['description'] = 'Qual o nome da default zone?';
$lng['serversettings']['vmail_uid']['title'] = 'Mails-Uid';
$lng['serversettings']['vmail_uid']['description'] = 'Qual UserID os e-mails devem ter?';
$lng['serversettings']['vmail_gid']['title'] = 'Mails-Gid';
$lng['serversettings']['vmail_gid']['description'] = 'Qual GroupID os e-mails devem ter?';
$lng['serversettings']['vmail_homedir']['title'] = 'Mails-Homedir';
$lng['serversettings']['vmail_homedir']['description'] = 'Aonde os e-mails devem ser gravados?';
$lng['serversettings']['adminmail']['title'] = 'Remetente';
$lng['serversettings']['adminmail']['description'] = 'Qual o remetente dos e-mails enviados pelo painel?';
$lng['serversettings']['phpmyadmin_url']['title'] = 'phpMyAdmin URL';
$lng['serversettings']['phpmyadmin_url']['description'] = 'Qual a URL do phpMyAdmin? (deve iniciar com http://)';
$lng['serversettings']['webmail_url']['title'] = 'WebMail URL';
$lng['serversettings']['webmail_url']['description'] = 'Qual a URL do WebMail? (deve iniciar com http://)';
$lng['serversettings']['webftp_url']['title'] = 'WebFTP URL';
$lng['serversettings']['webftp_url']['description'] = 'Qual a URL do WebFTP? (deve iniciar com http://)';
$lng['serversettings']['language']['description'] = 'Qual o idioma padr&atilde;o do servidor?';
$lng['serversettings']['maxloginattempts']['title']       = 'Tentativas maximas de Login';
$lng['serversettings']['maxloginattempts']['description'] = 'Tentativas maximas de Login para a conta ser desativada.';
$lng['serversettings']['deactivatetime']['title']       = 'Tempo que a conta deve permanecer desativada';
$lng['serversettings']['deactivatetime']['description'] = 'Tempo (sec.) qua a conta permanece desativada depois de muitas tentativas de login.';
$lng['serversettings']['pathedit']['title']       = 'File-M&eacute;todo de entrada';
$lng['serversettings']['pathedit']['description'] = 'A escolha do file tem que ser feita atrav&eacute;s do Dropdown-Menu ou pode ser feita manualmente?';

/**
 * ADDED BETWEEN 1.2.12 and 1.2.13
 */
$lng['admin']['cronlastrun'] = 'Ultimo Agendamento';
$lng['serversettings']['apacheconf_filename']['title'] = 'Nome do aqrquivo de configura&ccedil;&atilde;o do Apache';
$lng['serversettings']['apacheconf_filename']['description'] = 'Como o arquivo de configura&ccedil;&atilde;o do apache deve ser chamado?';
$lng['serversettings']['paging']['title']       = 'Entradas por pagina';
$lng['serversettings']['paging']['description'] = 'Quantas entradas devem ser mostradas por pagina? (0 = desabilitar paginas)';
$lng['error']['ipstillhasdomains']= 'O IP/Porta que voce quer deletar ainda possui dominios associados e eles, por favor altere o IP/Porta destes dominios antes de delet&aacute;-los.';
$lng['error']['cantdeletedefaultip'] = 'Voce n&atilde;o pode deletar o IP/Porta padr&atilde;o do revendedor, por favor defina outro IP/Porta como padr&atilde;o antes deletar o IP/Porta desejado';
$lng['error']['cantdeletesystemip'] = 'Voce n&atilde;o pode deletar o IP do sistema, nem criar uma nova combina&ccedil;&atilde;o IP/Porta para o sistema ou trocar o IP do sistema.';
$lng['error']['myipaddress'] = '\'IP\'';
$lng['error']['myport'] = '\'Porta\'';
$lng['error']['myipdefault'] = 'Voce precisa selecionar o IP/Porta que ser&aacute; padr&atilde;o.';
$lng['error']['myipnotdouble'] = 'Esta combina&ccedil;&atilde;o  IP/Porta j&aacute; existe.';
$lng['question']['admin_ip_reallydelete'] = 'Voce realmente deseja deletar este endere&ccedil;o IP?';
$lng['admin']['ipsandports']['ipsandports'] = 'IPs e Portas';
$lng['admin']['ipsandports']['add'] = 'Adicionar IP/Porta';
$lng['admin']['ipsandports']['edit'] = 'Editar IP/Porta';
$lng['admin']['ipsandports']['ipandport'] = 'IP/Porta';
$lng['admin']['ipsandports']['ip'] = 'IP';
$lng['admin']['ipsandports']['port'] = 'Porta';
$lng['admin']['ipsandports']['default'] = 'IP/Porta padr&atilde;o da revenda';

?>