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
    $table1 = "table_".rand().rand();
    $table2 = "table_".rand().rand();
    $table3 = "table_".rand().rand();
        
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


        $db->executeSQL("drop table if exists ".$table1);
        $sql1 = "create table ".$table1."
        select 
        r.selectedrank, 
        q.dimension,
        (
           case WHEN r.selectedrank=4 THEN
            null
           WHEN r.selectedrank>4 THEN q.higherchar
           WHEN r.selectedrank<4 THEN q.lowerchar
           else null
           end
        ) score,
        p.id pid,
        p.leftchar, p.rightchar,p.leftname, p.rightname
      from 
        results r 
        join questions q 
        on (r.questionid=q.id) 
        join perspective p
        on (q.dimension = p.dimension)
      where 
        userid=".$userid;

        $db->executeSQL($sql1);
        
        $db->executeSQL("drop table if exists ".$table2);
        $sql2 = " create table ".$table2."
        select pid, dimension,leftchar, rightchar,leftname, rightname, score, count(*) cnt from 
        ".$table1."
          where score is not null
        group by pid, dimension,leftchar, rightchar,leftname, rightname,score
        having count(*)>1;";
        $db->executeSQL($sql2);


        $db->executeSQL("drop table if exists ".$table3);
        $sql3 = " create table ".$table3."
        select pid, dimension,leftchar, rightchar,leftname, rightname, score, count(*) cnt from 
        ".$table1."
          where score is not null
        group by pid, dimension,leftchar, rightchar,leftname, rightname,score
        having count(*)=1;";
        $db->executeSQL($sql3);
        
        

        $sql4=" select * from ".$table2."
        union 
        select id,dimension, leftchar, rightchar,leftname, rightname,
        (
          case when
            (select count(*) from ".$table3." t where t.dimension = p.dimension)=1
            AND (select score from ".$table3." t where t.dimension = p.dimension limit 1) is not null 
          THEN 
            (select score from ".$table3." t where t.dimension = p.dimension limit 1)
          else left(dimension,1)
          end 
        ) score,
        -1 from perspective p
        where id not in (select pid from ".$table2.")
        order by pid";

        $perspective = $db->getRecords($sql4);

        $db->executeSQL("drop table if exists ".$table1);
        $db->executeSQL("drop table if exists ".$table2);
        $db->executeSQL("drop table if exists ".$table3);

        $res["status"] = true;
        $res['message'] = 'Fetch Success';
        $res['data'] = array("user"=>$user,"answers"=>$result, "perspective"=>$perspective);
    }
    catch(Exception $e) {
        $res["status"] = false;
        $res['message'] = $e;
        $res['data'] = null;
    }
    finally
    {
      $db->executeSQL("drop table if exists ".$table1);
        $db->executeSQL("drop table if exists ".$table2);
        $db->executeSQL("drop table if exists ".$table3);
       
    }
    return $response->withJson($res, 200);
});



if ($db != null)
    $db->disconnect();

?>