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
$app->post('/GetUserByID', function (Request $request, Response $response) {
    $db = new DbHandler();
    $data = json_decode($request->getBody());
    $userid = $data->userid;

    $sql = "select * from Portal_Users  where ID=". $userid."";
    $result = $db->getOneRecord($sql);
    if($result!=null)
    {
        
            $res["status"] = "success";
            $res['message'] = 'Fetch Success';
            $res['User'] = $result;
        
    }
    
    return $response->withJson($res, 200);
});

$app->post('/LoadChannelKwh', function (Request $request, Response $response) {
    $db = new DbHandler();
    $data = json_decode($request->getBody());
    $LoggedUser = $data->LoggedUser;
    $ParentGroupID = $data->ParentGroupID;
    $StartDate = $data->StartDate;
    $EndDate = $data->EndDate;

    $res = [];
    
    //select *, gowatt_fn_GetKwh_Used(groupid) kwh from channel where ParentId=
    // $sql = "select *, 222  kwh from channel where ParentId=".$ParentGroupID;
    $sql = "select gowatt_fn_GetKwh_Used(".$ParentGroupID.",'".$StartDate."','".$EndDate."',1) kwh, 
    gowatt_fn_GetKwh_Used(".$ParentGroupID.",'".$StartDate."','".$EndDate."',2) prev_kwh";

    $kwhData = $db->getOneRecord($sql);

    
    $res['kwhdata'] = $kwhData ;
    $res["status"] = "success";
    $res['message'] = 'Fetch Success';
    return $response->withJson($res, 200);
});


if ($db != null)
    $db->disconnect();

?>