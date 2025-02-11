<?php
use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true,true,true);

$app->get('/', function(Request $request,Response $response){
    $response->getBody()->write('Executando método GET');
    return $response;
});

$app->get('/users', function(Request $request,Response $response){
    
    $sql = 'SELECT NM_LOGIN, DS_PASSWORD FROM TB_USER';

    try{
        $db = new DB();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($users));
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message" => $e->getMessage()
        );
    }
    $response->getBody()->write(json_encode($error));
    return $response
    ->withHeader('content-type', 'application/json')
    ->withStatus(500);
});

$app->post('/validarlogin', function(Request $request,Response $response){
    $OPA = $request->getParsedBody();
    $USUARIONOME = $OPA['nomeusuario'];
    $USUARIOSENHA = $OPA['senhausuario'];
   

    $sql = 'SELECT NM_LOGIN, DS_PASSWORD FROM TB_USER';
    $sql = $sql." WHERE NM_LOGIN = '$USUARIONOME'  AND  DS_PASSWORD = '$USUARIOSENHA' ";

    echo $sql;

    try{
        $db = new DB();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($users));
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message" => $e->getMessage()
        );
    }
    $response->getBody()->write(json_encode($error));
    return $response
    ->withHeader('content-type', 'application/json')
    ->withStatus(500);
});

$app->patch('/', function(Request $request,Response $response){
    $response->getBody()->write('Executando método PATCH');
    return $response;
});

$app->put('/', function(Request $request,Response $response){
    $response->getBody()->write('Executando método PUT');
    return $response;
});

$app->delete('/', function(Request $request,Response $response){
    $response->getBody()->write('Executando método DELETE');
    return $response;
});

$app->run();
//php -S localhost:8888 -t public
//utilizado para rodar no prompt de comando(CMD)
?>