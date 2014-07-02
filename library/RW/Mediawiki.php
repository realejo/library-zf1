<?php
/**
 * Funções para se acessar o wiki através de usuários preconfigurados
 *
 * @link      http://github.com/realejo/library-zf1
 * @copyright Copyright (c) 2011-2014 Realejo Design Ltda. (http://www.realejo.com.br)
 */
class RW_Mediawiki
{
    /**
     * Recupera o usuário a ser usado
     */
    static function getUser($userType = 'leitor')
    {
        // Opções de localização do application.ini
        $configs = array(
                    APPLICATION_PATH . "/../configs/application.ini",
                    APPLICATION_PATH . "/configs/application.ini"
                  );

        // Verifica se a constante da marca (BFFC) esta definida
        if (defined('MARCA')) {
            $configs[] = APPLICATION_PATH . "/../configs/application.".BFFC_Marca::getCssClass(MARCA).".ini";
            $configs[] = APPLICATION_PATH . "/configs/application.".BFFC_Marca::getCssClass(MARCA).".ini";
        }

        // Carrega as configurações do config
        $configpath = false;
        foreach($configs as $c) {
            if ( file_exists($c) ) {
                $configpath = $c;
            }
        }

        // Verifica se uma das opções foi localizada
        if ( $configpath === false ) {
            require_once 'Zend/Config/Exception.php';
            $marca = (defined('MARCA')) ? '(marca='.BFFC_Marca::getCssClass(MARCA) .')': '' ;
            throw new Exception("Nenhum arquivo de configuração application.ini encontrado do diretório '/configs' $marca");
        }

        // Instância o arquivo aplication.ini
        $config = new Zend_Config_Ini($configpath, APPLICATION_ENV);

        // Recupera as configurações
        $apiurl = $config->wiki->apiurl;
        if (!isset($config->wiki->$userType)) {
            throw new Exception("usuario wiki $userType não configurado");
        }

        $user_name     = $config->wiki->$userType->user_name;
        $user_password = $config->wiki->$userType->user_password;

        return array(
                    'apiurl'        => $apiurl,
                    'user_name'     => $user_name,
                    'user_password' => $user_password
                );
    }

    /**
     * Faz o login na wiki usando a API
     *
     * @param string $url
     * @param string $user
     * @param string $password
     *
     * @return boolean|string   Retorna TRUE ou o erro encontrado
     */
    static function login($url, $user = null, $password = null)
    {
        // Se não foi fornecido um usuário/senha, irá considerar que a url é o tipo de usuário
        if (is_null($user)) {
            $usuario  = self::getUser($url);
            $url      = $usuario['apiurl'];
            $user     = $usuario['user_name'];
            $password = $usuario['user_password'];
        }

        // Cria o cliente
        $client = new Zend_Http_Client();

        // Faz o login inicial
        $client->setUri($url);
        $client->setHeaders('Accept-Encoding',  'none');
        $client->setParameterPost('action',     'login');
        $client->setParameterPost('lgname',     $user);
        $client->setParameterPost('lgpassword', $password);
        $client->setParameterPost('format',     'php');


        $response = $client->request(Zend_Http_Client::POST);
        $result = unserialize($response->getBody());
        //RW_Debug::dump($response);
        //RW_Debug::dump($result);

        $client->setParameterPost('lgtoken', $result['login']['token']);
        $client->setCookie($result['login']['cookieprefix'].'_session', $result['login']['sessionid']);

        // Recupera o token
        $response = $client->request(Zend_Http_Client::POST);
        $result = unserialize($response->getBody());
        //RW_Debug::dump($response);
        //RW_Debug::dump($result);

        //RW_Debug::dump($client->getLastRequest());

        // Configura o Cookie da Sessão
        if ($result['login']['result'] === 'Success') {
            setcookie($result['login']['cookieprefix'].'LoggedOut', '-',  1, '/', $_SERVER['HTTP_HOST'], false, true);
            setcookie($result['login']['cookieprefix'].'_session', $result['login']['sessionid'],  time() + 7200, '/', $_SERVER['HTTP_HOST'], false, true);
            setcookie($result['login']['cookieprefix'].'UserName', $result['login']['lgusername'], time() + 7200, '/', $_SERVER['HTTP_HOST'], false, true);
            setcookie($result['login']['cookieprefix'].'UserID',   $result['login']['lguserid'],   time() + 7200, '/', $_SERVER['HTTP_HOST'], false, true);
            setcookie($result['login']['cookieprefix'].'Token',    $result['login']['lgtoken'],    time() + 7200, '/', $_SERVER['HTTP_HOST'], false, true);

            return true;
        } else {
            return $result['login']['result'];
        }
    }

    /**
     * Faz o logou da WIKI e remove os cookies
     * @param string $url (OPCIONAL) URL da API da wiki, se não fornecida irá utilizar a do application.ini
     * @throws Exception
     */
    static function logout($url = null)
    {
        // Verifica se a constante da marca esta definida
        $marca = (defined('MARCA')) ? '.'.BFFC_Marca::getCssClass(MARCA) : '' ;

        // Carrega as configurações do config
        $configpath = APPLICATION_PATH . "/../configs/application".$marca.".ini";
        if ( !file_exists($configpath) ) {
            // procura dentro do application
            $configpath = APPLICATION_PATH . "/configs/application".$marca.".ini";
        }

        if ( !file_exists($configpath) ) {
            require_once 'Zend/Config/Exception.php';
            throw new Exception("Arquivo de configuração application$marca.ini não encontrado do diretório /configs");
        }

        // Instância o arquivo aplication.ini
        $config = new Zend_Config_Ini($configpath, APPLICATION_ENV);

        // Recupera as configurações
        $apiurl = (is_null($url)) ? $config->wiki->apiurl : $url;

        // Cria o cliente
        $client = new Zend_Http_Client();

        // Recupera os cookies
        $cookies = new Zend_Http_CookieJar();
        foreach ($_COOKIE as $c=>$val) {
            $cookies->addCookie(new Zend_Http_Cookie($c, $val, $_SERVER['HTTP_HOST']));
        }

        // Faz o logout
        $client->setUri($apiurl);
        $client->setHeaders('Accept-Encoding', 'none');
        $client->setParameterPost('action',    'logout');
        $client->setParameterPost('format',     'php');
        $client->setCookieJar($cookies);
        $response = $client->request(Zend_Http_Client::POST);
        $result = unserialize($response->getBody());
        //RW_Debug::dump($response->getBody());
        //RW_Debug::dump($response->getHeader('Set-Cookie'));
        //RW_Debug::dump($result);

        // Remove os cookies
        $cookies = $response->getHeader('Set-Cookie');
        foreach ($cookies as $c) {
            $c = explode('=',$c,2);
            setcookie($c[0],'deleted',1, '/', $_SERVER['HTTP_HOST'], false, true);
        }

       // RW_Debug::dump($_COOKIE);
    }
}
