<?php
/**
 * Controle de backup
 *
 * @category   RW
 * @package    RW_Base
 * @author     Realejo
 * @version    $Id: Backup.php 405 2012-09-10 19:42:32Z rodrigo $
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class RW_Backup
{
    /**
     * Cria um dump das tabelas do banco de dados.
     *
     * @param array $tables OPCIONAL Tabelas para criar o backuop.
     *                      Se não informado será feito backup de todas as tabelas.
     * @throws Exception
     */
    static public function create($tables = null)
    {
        // Verifica se a constante da marca esta definida
        $marca = (defined('MARCA')) ? '.'.BFFC_Marca::getCssClass(MARCA) : '' ;

        // Carrega as configurações do config
        $configpath = APPLICATION_PATH . "/../configs/application$marca.ini";
        if ( !file_exists($configpath) ) {
            // procura dentro do application
            $configpath = APPLICATION_PATH . "/configs/application$marca.ini";
        }

        if ( !file_exists($configpath) ) {
            require_once 'Zend/Config/Exception.php';
            throw new Exception("Arquivo de configuração application$marca.ini não encontrado do diretório /configs");
        }

        // Instância o arquivo aplication.ini
        $config = new Zend_Config_Ini($configpath, APPLICATION_ENV);

        // Grava os dados de acesso a banco de dados
        $dbhost	= $config->resources->db->params->host;
        $dbuser	= $config->resources->db->params->username;
        $dbpass	= $config->resources->db->params->password;
        $dbname = $config->resources->db->params->dbname;

        // Define o diretório dos dumps
        $dumpPath = self::getPath();

        // Verifica se é para criar um dump com um arquivo por tabela ou um arquivo único
        if (empty($tables)) {
            $backupFile = date("Y-m-d-H-i-s") . '.sql';
            $backupPath = $dumpPath .'/'. $backupFile;

            // Faz o dump completo em um arquivo SQL e cria um ZIP
            $command = "mysqldump --opt --quote-names --host=$dbhost --user$dbuser --password=$dbpass --default-character-set=utf8 --dump-date $dbname > $backupPath;";
            system($command);

            // Cria um ZIP com o arquivo SQL criado
            $command = "zip -mjq  $backupPath.zip $backupPath";
            system($command);

        } else {

            // Cria o nome do arquivo e seu diretório
            $backupFile = date("Y-m-d-H-i-s");
            $backupPath = $dumpPath .'/'. $backupFile;
            $zipTables = '';

            foreach ($tables as $k=>$tbl_name) {
                $table = $dumpPath .'/'.$tbl_name.'.sql';

                // Monta a linha de comando
                //@todo --result-filename=?
                $command = "mysqldump --opt --quote-names --host=$dbhost --user=$dbuser --password=$dbpass --default-character-set=utf8 --dump-date $dbname $tbl_name > $table";

                // Executa a linha de comando shell para realizar o dump
                system($command);

                // Guarda o caminho completo de cada arquivo criado
                $zipTables .= " $table";
            }

            // Cria um ZIP com os SQL criados e os apaga
            $command = "zip -mjq  $backupPath.zip $zipTables";
            system($command);
        }

        // Retorna o arquivo criado
        return $backupPath . '.zip';
    }

    /**
     * Cria um script para fazer um restore a partir dos dumps contidos no ZIP
     *
     * @param string $file nome do arquivo ZIP dentro da pasta dumps
     *
     * @throws Exception
     *
     * @return string
     */
    static public function restore($file)
    {
        // Verifica se a constante da marca esta definida
        $marca = (defined('MARCA')) ? '.'.BFFC_Marca::getCssClass(MARCA) : '' ;

        // Carrega as configurações do config
        $configpath = APPLICATION_PATH . "/../configs/application$marca.ini";
        if ( !file_exists($configpath) ) {
            // procura dentro do application
            $configpath = APPLICATION_PATH . "/configs/application$marca.ini";
        }

        if ( !file_exists($configpath) ) {
            require_once 'Zend/Config/Exception.php';
            throw new Exception("Arquivo de configuração application$marca.ini não encontrado do diretório /configs");
        }

        // Instância o arquivo aplication.ini
        $config = new Zend_Config_Ini($configpath, APPLICATION_ENV);

        // Grava os dados de acesso a banco de dados
        $dbhost	= $config->resources->db->params->host;
        $dbuser	= $config->resources->db->params->username;
        $dbpass	= 'PASSWORD'; //$config->resources->db->params->password;
        $dbname = $config->resources->db->params->dbname;

        // Recupera o diretório dos dumps
        $dumpPath = self::getPath();

        // Define o caminho do arquivo
		$filepath = $dumpPath.'/'.str_replace('.zip', '', $file);

        // Verifica se o zip existe
    	if (!file_exists($filepath.'.zip')){
    		require_once 'Zend/Controller/Action/Exception.php';
    		throw new Exception('Arquivo não encontrado: '.$filepath.'.zip');
	    }

        // Define o deiratório temporario
        $temp = 'temp'. time();

        // Cria o script
        $script = "#!/bin/bash\n";
        $script .= "echo \"***********\"\n";
        $script .= "echo \"* RESTORE *\"\n";
        $script .= "echo \"***********\"\n";
        $script .= "echo \n";
        $script .= "echo \"Confirme a configuracao:\"\n";
        $script .= "\n";
        $script .= "read -p \"HOST[$dbhost]: \" HOST\n";
        $script .= "if [ \"\$HOST\" = \"\" ];	then\n";
        $script .= "\tHOST=\"$dbhost\"\n";
        $script .= "fi\n";
        $script .= "\n";
        $script .= "read -p \"DATABASE[$dbname]: \" DATABASE\n";
        $script .= "if [ \"\$DATABASE\" = \"\" ];	then\n";
        $script .= "\tDATABASE=\"$dbname\"\n";
        $script .= "fi\n";
        $script .= "\n";
        $script .= "read -p \"USER [$dbuser]: \" USER\n";
        $script .= "if [ \"\$USER\" = \"\" ]; then\n";
        $script .= "\tUSER=\"$dbuser\"\n";
        $script .= "fi\n";
        $script .= "\n";
        $script .= "read -s -p \"PASSWORD: \" PASSWORD\n";
        $script .= "if [ \"\$PASSWORD\" = \"\" ]; then\n";
        $script .= "\techo \"Senha invalida\"\n";
        $script .= "\texit 1\n";
        $script .= "else\n";
        $script .= "echo \n";
        $script .= "fi\n";
        $script .= "\n\n";

        $script .= "echo \n";

        $script .= "echo -n \"Verificando conectividade \$USER@\$HOST, PASSWORD:[yes], DATABASE:\$DATABASE ... \"\n";
        $script .= "\n";
        $script .= "if mysql --host=\$HOST --database=\$DATABASE --user=\$USER --password=\$PASSWORD -e \";\"; then\n";
        $script .= "\techo \"ok\"\n";
        $script .= "else\n";
        $script .= "\techo \"Nao foi possivel se conectar ao servidor MYSQL\"\n";
        $script .= "\texit 1\n";
        $script .= "fi\n";
        $script .= "echo \n\n";

        // Cria o diretório temporário
        $script .= "echo \"Criando diretorio temporario $temp\"\n";
        $script .= "mkdir $temp;\n";
        $script .= "echo \n\n";

        // Extrai os arquivos
        $script .= "echo -n \"Extraindo arquivo do arquivo $file ... \"\n";
        $script .= "if unzip -q \"$file\" -d $temp; then\n";
        $script .= "\techo \"ok\"\n";
        $script .= "else\n";
        $script .= "\techo \"arquivo ZIP nao encontrado\"\n";
        $script .= "\trmdir $temp;\n";
        $script .= "\texit 1\n";
        $script .= "fi\n\n";
        $script .= "echo \n\n";

        // Processa as arquivos do ZIP
        $za = new ZipArchive();
        $za->open($filepath.'.zip');
        for ($i=0; $i<$za->numFiles;$i++) {
            // Recupera os daods do arquivo no ZIP
            $file = $za->statIndex($i);

            // Inicia a mensagem
            $script .= "echo -n \"Restoring {$file['name']}...\"\n";

            // Faz o restore no BD
            $script .= "mysql --host=\$HOST --user=\$USER --password=\$PASSWORD \$DATABASE < $temp/{$file['name']};\n";

            // Apaga o arquivo
            $script .= "rm -f $temp/{$file['name']};\n";

            // Finaliza a mensagem
            $script .= "echo \" ok\"\n\n";
        }

        // Remove o diretório temporário
        $script .= "echo \"Finalizando...\"\n";
        $script .= "rmdir $temp;\n";

        $script .= "\n";
        $script .= "echo \n";
        $script .= "echo \"*** Restore Completo*** \"\n";
        $script .= "echo \n";

	    // Retorna o comando ao usuário
	    return $script;
    }

    /**
     * Retorna o caminho padrão até a pasta onde são salvas os arquivos de backup
     *
     * @return string
     */
    static public function getPath()
    {
        return realpath(APPLICATION_PATH . '/../data/dumps');
    }
}
