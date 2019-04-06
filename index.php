<?php

require_once __DIR__.'/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Aura\Sql\ExtendedPdo;

$config = [];
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'mysql';
$config['db']['user']   = getenv('MYSQL_USER');
$config['db']['pass']   = getenv('MYSQL_PASSWORD');
$config['db']['dbname'] = 'login';

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['db'] = function ($container) {
    $db = $container['settings']['db'];
    $pdo = new PDO('mysql:host='.$db['host'].';dbname='.$db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
};

$container['session'] = function ($container) {
    return new \SlimSession\Helper;
};

$container['view'] = function ($container) {
    return new \Slim\Views\PhpRenderer(__DIR__.'/templates');
};

$container['users'] = function ($container) {
    return new \Workshop\User\UserMapper($container['db']);
};

$app->add(new \Slim\Middleware\Session([
    'name' => 'user_session',
    'autorefresh' => true,
]));


$app->get('/login', function (Request $request, Response $response, array $args) {
    $response = $this->view->render($response, 'login.phtml');

    return $response;
});
$app->post('/login', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $bindValues = [
        'user' => $data['user_name'],
        'pass' => md5($data['user_password']),
    ];
    
    $result = $this->users->findByCriteria($bindValues);

    if (!$result) {
        $response = $this->view->render($response, 'login.phtml', ['error' => 'Login failed']);
    } else {
        $this->session->set('user', $result['username']);

        return $response->withRedirect('/content');
    }

    return $response;
});

$app->get('/content', function (Request $request, Response $response) {
    if (!$this->session->get('user')) {
        return $response->withRedirect('/login');
    }

    $response = $this->view->render($response, 'content.phtml', ['username' => $this->session->get('user')]);

    return $response;
});

$app->get('/logout', function (Request $request, Response $response) {
    $this->session->delete('user');
    $this->session::destroy();

    return $response->withRedirect('/login');
});


$app->run();

