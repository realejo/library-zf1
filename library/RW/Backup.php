<?php

declare(strict_types=1);

/**
 * Controle de backup
 *
 * @todo verificar se exite o mysqldump e zip instalado
 */
class RW_Backup
{
    /**
     * Cria um dump das tabelas do banco de dados.
     *
     * @param array|null $tables OPCIONAL Tabelas para criar o backuop.
     *                      Se não informado será feito backup de todas as tabelas.
     * @throws Exception
     */
    public static function create(array $tables = null)
    {
        $config = RW_Config::getApplicationIni();

        // Grava os dados de acesso a banco de dados
        $dbhost = $config->resources->db->params->host;
        $dbuser = $config->resources->db->params->username;
        $dbpass = $config->resources->db->params->password;
        $dbname = $config->resources->db->params->dbname;

        // Define o diretório dos dumps
        $dumpPath = self::getPath();

        // Verifica se é para criar um dump com um arquivo por tabela ou um arquivo único
        if (empty($tables)) {
            $backupFile = date("Y-m-d-H-i-s") . '.sql';
            $backupPath = $dumpPath . '/' . $backupFile;

            // Faz o dump completo em um arquivo SQL e cria um ZIP
            $command = "mysqldump --opt --quote-names --host=$dbhost --user$dbuser --password='$dbpass' --default-character-set=utf8 --dump-date $dbname > $backupPath;";
            system($command);

            // Cria um ZIP com o arquivo SQL criado
            $command = "zip -mjq  $backupPath.zip $backupPath";
            system($command);
        } else {
            // Cria o nome do arquivo e seu diretório
            $backupFile = date("Y-m-d-H-i-s");
            $backupPath = $dumpPath . '/' . $backupFile;
            $zipTables = '';

            foreach ($tables as $k => $tbl_name) {
                $table = $dumpPath . '/' . $tbl_name . '.sql';

                // Monta a linha de comando
                //@todo --result-filename=?
                $command = "mysqldump --opt --quote-names --host=$dbhost --user=$dbuser --password='$dbpass' --default-character-set=utf8 --dump-date $dbname $tbl_name > $table";

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
     */
    public static function restore(string $file): string
    {
        $config = RW_Config::getApplicationIni();

        // Grava os dados de acesso a banco de dados
        $dbhost = $config->resources->db->params->host;
        $dbuser = $config->resources->db->params->username;
        $dbpass = 'PASSWORD'; //$config->resources->db->params->password;
        $dbname = $config->resources->db->params->dbname;

        // Recupera o diretório dos dumps
        $dumpPath = self::getPath();

        // Define o caminho do arquivo
        $filepath = $dumpPath . '/' . str_replace('.zip', '', $file);

        // Verifica se o zip existe
        if (!file_exists($filepath . '.zip')) {
            require_once 'Zend/Controller/Action/Exception.php';
            throw new Exception('Arquivo não encontrado: ' . $filepath . '.zip');
        }

        // Cria o script
        $script = <<<'RESTORESCRIPT'
#!/bin/bash
echo "***********"
echo "* RESTORE *"
echo "***********"
echo
echo "Confirme a configuracao:"

read -p "Restore all files [Y,n]: " RESTOREALL
case $RESTOREALL in
	N|n)
		RESTOREALL="N"
		;;
	*)
		RESTOREALL="Y"
		;;
esac

read -p "HOST[{{host}}]: " HOST
if [ "$HOST" = "" ];	then
	HOST="{{host}}"
fi

read -p "DATABASE[{{database}}]: " DATABASE
if [ "$DATABASE" = "" ];	then
	DATABASE="{{database}}"
fi

read -p "USER [{{user}}]: " USER
if [ "$USER" = "" ]; then
	USER="{{user}}"
fi

read -s -p "PASSWORD: " PASSWORD

TEMPCONFIG="
[client]
host = $HOST
database = $DATABASE
user = $USER
password = $PASSWORD
"

echo
echo -n "Verificando conectividade $USER@$HOST, DATABASE:$DATABASE ... "

if mysql --defaults-extra-file=<(printf "$TEMPCONFIG") -e ";"; then
	echo "ok"
else
	echo "Nao foi possivel se conectar ao servidor MYSQL"
	exit 1
fi
echo

echo -n "Criando diretorio temporario... "
TEMPDIR="$(mktemp -d $(basename $0).XXXXXXXXXX)"
echo "ok"
echo

echo -n "Extraindo arquivo do arquivo {{file}} ... "
if unzip -q "{{file}}" -d $TEMPDIR; then
	echo "ok"
else
	echo "arquivo ZIP nao encontrado"
	rmdir $TEMPDIR;
	exit 1
fi
echo

for SQLFILE in "$TEMPDIR"/*.sql
do
	RESTOREFILE="Y"
	if [ "$RESTOREALL" == "N" ]; then
		echo
		read -p "Restore $(basename "$SQLFILE") [y,N]: " RESTOREFILE
		case $RESTOREFILE in
			Y|y)
				RESTOREFILE="Y"
				;;
			*)
				RESTOREFILE="N"
				;;
		esac
	fi
	if [ "$RESTOREFILE" == "N" ]; then
		echo -n -e "\e[2mSkipping $(basename "$SQLFILE") ... "
	else
		echo -n -e "Restoring \e[1m$(basename "$SQLFILE")\e[0m ... "
		mysql --defaults-extra-file=<(printf "$TEMPCONFIG") < $SQLFILE;
	fi
	rm -f $SQLFILE;
	echo -e "ok\e[0m"
done

echo "Finalizando..."
rmdir $TEMPDIR;

echo
echo "*** Restore Completo*** "
echo
RESTORESCRIPT;

        $script = str_replace('{{file}}', basename($filepath), $script);
        $script = str_replace('{{host}}', basename($dbhost), $script);
        $script = str_replace('{{database}}', basename($dbname), $script);
        $script = str_replace('{{user}}', basename($dbuser), $script);

        // Retorna o comando ao usuário
        return $script;
    }

    /**
     * Retorna o caminho padrão até a pasta onde são salvas os arquivos de backup
     */
    public static function getPath(): string
    {
        // Verifica se a pasta de upload existe
        if (!defined('APPLICATION_DATA') || !realpath(APPLICATION_DATA)) {
            throw new Exception('A pasta raiz do data não está definido em APPLICATION_DATA em RW_Backup::getPath()');
        }

        // Verifica se a pasta do cache existe
        $cachePath = APPLICATION_DATA . '/dumps';
        if (!file_exists($cachePath)) {
            $oldumask = umask(0);
            mkdir($cachePath, 0777, true);
            umask($oldumask);
        }

        return $cachePath;
    }
}
