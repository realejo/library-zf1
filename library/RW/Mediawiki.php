<?php
class RW_Mediawiki
{
    /**
     * Recupera o usuário a ser usado
     */
    static function getUser($userType = 'read')
    {
        // Carrega as configurações do config
        $configpath = APPLICATION_PATH . "/../configs/application.ini";
        if ( !file_exists($configpath) ) {
            // procura dentro do application
            $configpath = APPLICATION_PATH . "/configs/application.ini";
        }

        if ( !file_exists($configpath) ) {
            require_once 'Zend/Config/Exception.php';
            throw new Exception("Arquivo de configuração application.ini não encontrado do diretório /configs");
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

    static function login($url, $user, $password)
    {
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

    static function logout($url)
    {
        // Cria o cliente
        $client = new Zend_Http_Client();

        // Recupera os cookies
        $cookies = new Zend_Http_CookieJar();
        foreach ($_COOKIE as $c=>$val) {
            $cookies->addCookie(new Zend_Http_Cookie($c, $val, $_SERVER['HTTP_HOST']));
        }

        // Faz o logout
        $client->setUri($url);
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
