<?php ob_start();
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
$db = null;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $res['name'] = "Hello, $name";
    return $response->withJson($res, 200);
});
$app->get('/users/list', function (Request $request, Response $response) {
    try
    {
        $db = new DbHandler();
        // $data = json_decode($request->getBody());
        // $username = $data->username;
        // $password = $data->password;
        $res = [];
        
        $sql = "select * from users";
        $result = $db->getRecords($sql);
        $res["status"] = true;
        $res['message'] = 'Fetch Success';
        $res['data'] = $result;
    }
    catch(Exception $e) {
        $res["status"] = false;
        $res['message'] = $e;
        $res['data'] = null;
    }
    
    return $response->withJson($res, 200);
});

$app->get('/questions/list', function (Request $request, Response $response) {
    try
    {
        $db = new DbHandler();
        // $data = json_decode($request->getBody());
        // $username = $data->username;
        // $password = $data->password;
        $res = [];
        
        $sql = "select q.*, r.selectedrank from questions q left join 
                results r on 
                (q.id = r.questionid and r.userid is null) order by q.id";
        $result = $db->getRecords($sql);
        $res["status"] = true;
        $res['message'] = 'Fetch Success';
        $res['data'] = array("email"=>"","questions"=>$result);
    }
    catch(Exception $e) {
        $res["status"] = false;
        $res['message'] = $e;
        $res['data'] = null;
    }
    
    return $response->withJson($res, 200);
});
$app->post('/result/save', function (Request $request, Response $response) {
    $db = new DbHandler();
    $data = json_decode($request->getBody());
    $email = $data->email;
    $questions = $data->questions;
    //return $response->withJson($questions, 200);

    try
    {

        $sql = "insert into users (email) values('".$email."')";
        
        $userid = $db->executeInsert($sql);
        if($userid>0)
        {
            foreach ($questions as $question) {
                $sql = "insert into results (questionid, selectedrank, userid) values(".$question->id.",".$question->selectedrank.",".$userid.")";
                $db->executeInsert($sql);
            }

            $res["status"] = true;
            $res['message'] = 'Fetch Success';
            $res['data'] = array("userid"=>base64_encode($userid));
        }
    }
    catch(Exception $e) {
        $res["status"] = false;
        $res['message'] = $e;
        $res['data'] = null;
        
    }
    
    return $response->withJson($res, 200);
});

$app->get('/result/get/{userid}', function (Request $request, Response $response, array $args) {
    $userid = $args['userid'];
    $userid = base64_decode($userid);
    try
    {
        $db = new DbHandler();
        $res = [];

        $sql = "select * 
                from users 
                where id=".$userid;
        $user = $db->getOneRecord($sql);
        
        $result = $db->getRecords($sql);
        
        $sql = "select q.*, r.selectedrank 
                from users u 
                join results r 
                on (u.id = r.userid) 
                join questions q on 
                (q.id = r.questionid)
                where u.id=".$userid;
        
        $result = $db->getRecords($sql);

        $sql = "select * from perspective";
        
        $perspective = $db->getRecords($sql);

        $res["status"] = true;
        $res['message'] = 'Fetch Success';
        $res['data'] = array("user"=>$user,"answers"=>$result, "perspective"=>$perspective);
    }
    catch(Exception $e) {
        $res["status"] = false;
        $res['message'] = $e;
        $res['data'] = null;
    }
    return $response->withJson($res, 200);
});



if ($db != null)
    $db->disconnect();

?>