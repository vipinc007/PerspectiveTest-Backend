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
        $table4 = "table_".rand().rand();
        $table5 = "table_".rand().rand();
        $table6 = "table_".rand().rand();
        $table7 = "table_".rand().rand();
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

    //     $sql = "       
    //     select a.* from 
    //      (
    //        select  pdimension,leftname, rightname,leftchar, rightchar,pid,k.dimension, k.score, count(*) cnt from 
    //        (
    //          select 
    //            r.selectedrank, 
    //            q.*,
    //            (
    //               case WHEN r.selectedrank=4 THEN
    //                case WHEN q.direction=-1 THEN q.higherchar else q.lowerchar end
    //               WHEN r.selectedrank>4 THEN q.higherchar
    //               WHEN r.selectedrank<4 THEN q.lowerchar
    //               else null
    //               end
    //            ) score,
    //            p.dimension pdimension, p.id pid,
    //            p.leftchar, p.rightchar, p.leftname, p.rightname
    //          from 
    //            results r 
    //            join questions q 
    //            on (r.questionid=q.id) 
    //            join perspective p
    //            on (q.dimension = p.dimension)
    //          where 
    //            userid=".$userid."
             
    //        )
    //        k
    //        group by pid,pdimension,leftname, rightname, k.leftchar, k.rightchar, k.pid,k.dimension, k.score
    //        having count(*)>1
    //      ) a
    //      join 

    //      (
    //        select k.dimension,max(cnt) cnt from 
    //        (
    //            select  k.dimension, k.score, count(*) cnt from 
    //            (
    //              select 
    //                r.selectedrank, 
    //                q.*,
    //                (
    //                   case WHEN r.selectedrank=4 THEN
    //                    case WHEN q.direction=-1 THEN q.higherchar else q.lowerchar end
    //                   WHEN r.selectedrank>4 THEN q.higherchar
    //                   WHEN r.selectedrank<4 THEN q.lowerchar
    //                   else null
    //                   end
    //                ) score,
    //                p.dimension pdimension, p.id pid,
    //                p.leftchar, p.rightchar
    //              from 
    //                results r 
    //                join questions q 
    //                on (r.questionid=q.id) 
    //                join perspective p
    //                on (q.dimension = p.dimension)
    //              where 
    //                userid=".$userid."
                 
    //            )
    //            k
    //            group by k.dimension, k.score
               
    //        ) k

    //        group by dimension

    //      ) b
    //      on (a.dimension=b.dimension)
        
    //        union 

    //                  select distinct a.* from 
    //      (
    //        select  pdimension,leftname, rightname,leftchar, rightchar,pid,k.dimension, leftchar score, count(*) cnt from 
    //        (
    //          select 
    //            r.selectedrank, 
    //            q.*,
    //            (
    //               case WHEN r.selectedrank=4 THEN
    //                case WHEN q.direction=-1 THEN q.higherchar else q.lowerchar end
    //               WHEN r.selectedrank>4 THEN q.higherchar
    //               WHEN r.selectedrank<4 THEN q.lowerchar
    //               else null
    //               end
    //            ) score,
    //            p.dimension pdimension, p.id pid,
    //            p.leftchar, p.rightchar,leftname, rightname
    //          from 
    //            results r 
    //            join questions q 
    //            on (r.questionid=q.id) 
    //            join perspective p
    //            on (q.dimension = p.dimension)
    //          where 
    //            userid=".$userid."
             
    //        )
    //        k
    //        group by pid,pdimension,leftname, rightname, k.leftchar, k.rightchar, k.pid,k.dimension, k.score
    //        having count(*)=1
    //      ) a
    //      join 

    //      (
    //        select k.dimension,max(cnt) cnt from 
    //        (
    //            select  k.dimension, k.score, count(*) cnt from 
    //            (
    //              select 
    //                r.selectedrank, 
    //                q.*,
    //                (
    //                   case WHEN r.selectedrank=4 THEN
    //                    case WHEN q.direction=-1 THEN q.higherchar else q.lowerchar end
    //                   WHEN r.selectedrank>4 THEN q.higherchar
    //                   WHEN r.selectedrank<4 THEN q.lowerchar
    //                   else null
    //                   end
    //                ) score,
    //                p.dimension pdimension, p.id pid,
    //                p.leftchar, p.rightchar
    //              from 
    //                results r 
    //                join questions q 
    //                on (r.questionid=q.id) 
    //                join perspective p
    //                on (q.dimension = p.dimension)
    //              where 
    //                userid=".$userid."
                 
    //            )
    //            k
    //            group by k.dimension, k.score
               
    //        ) k

    //        group by dimension

    //      ) b
    //      on (a.dimension=b.dimension)
    //      where a.dimension not in 
    //        (

    //                              select a.dimension from 
    //                  (
    //                    select  leftchar, rightchar,pid,k.dimension, k.score, count(*) cnt from 
    //                    (
    //                      select 
    //                        r.selectedrank, 
    //                        q.*,
    //                        (
    //                           case WHEN r.selectedrank=4 THEN
    //                            case WHEN q.direction=-1 THEN q.higherchar else q.lowerchar end
    //                           WHEN r.selectedrank>4 THEN q.higherchar
    //                           WHEN r.selectedrank<4 THEN q.lowerchar
    //                           else null
    //                           end
    //                        ) score,
    //                        p.dimension pdimension, p.id pid,
    //                        p.leftchar, p.rightchar
    //                      from 
    //                        results r 
    //                        join questions q 
    //                        on (r.questionid=q.id) 
    //                        join perspective p
    //                        on (q.dimension = p.dimension)
    //                      where 
    //                        userid=".$userid."
                         
    //                    )
    //                    k
    //                    group by pid, k.leftchar, k.rightchar, k.pid,k.dimension, k.score
    //                    having count(*)>1
    //                  ) a
    //                  join 
           
    //                  (
    //                    select k.dimension,max(cnt) cnt from 
    //                    (
    //                        select  k.dimension, k.score, count(*) cnt from 
    //                        (
    //                          select 
    //                            r.selectedrank, 
    //                            q.*,
    //                            (
    //                               case WHEN r.selectedrank=4 THEN
    //                                case WHEN q.direction=-1 THEN q.higherchar else q.lowerchar end
    //                               WHEN r.selectedrank>4 THEN q.higherchar
    //                               WHEN r.selectedrank<4 THEN q.lowerchar
    //                               else null
    //                               end
    //                            ) score,
    //                            p.dimension pdimension, p.id pid,
    //                            p.leftchar, p.rightchar
    //                          from 
    //                            results r 
    //                            join questions q 
    //                            on (r.questionid=q.id) 
    //                            join perspective p
    //                            on (q.dimension = p.dimension)
    //                          where 
    //                            userid=".$userid."
                             
    //                        )
    //                        k
    //                        group by k.dimension, k.score
                           
    //                    ) k
           
    //                    group by dimension
           
    //                  ) b
    //                  on (a.dimension=b.dimension)
                    
                     

    //        )
        
    //      order by pid
         
    // ";

        

        $db->executeSQL("drop table if exists ".$table1);
        $sql1 = "create table ".$table1."
                select  pid,pdimension,leftname, rightname,leftchar, rightchar,k.dimension, k.score, count(*) cnt from 
                (
                  select * from 
                    (
                      select 
                        r.selectedrank, 
                        q.*,
                        (
                          case WHEN r.selectedrank=4 THEN
                            null
                          WHEN r.selectedrank>4 THEN q.higherchar
                          WHEN r.selectedrank<4 THEN q.lowerchar
                          else null
                          end
                        ) score,
                        p.dimension pdimension, p.id pid,
                        p.leftchar, p.rightchar, p.leftname, p.rightname
                      from 
                        results r 
                        join questions q 
                        on (r.questionid=q.id) 
                        join perspective p
                        on (q.dimension = p.dimension)
                      where 
                        userid=".$userid."
                  )
                    j
                )
                k
                group by pid,pdimension,leftname, rightname, k.leftchar, k.rightchar, k.pid,k.dimension, k.score";

        $db->executeSQL($sql1);
        
        $db->executeSQL("drop table if exists ".$table2);
        $sql2 = " create table ".$table2." select * from ".$table1;
        $db->executeSQL($sql2);
        
        $db->executeSQL("drop table if exists ".$table3);
        $sql3 = "create table ".$table3."
        select 
          *, 
          (
            case 
                when score is null then 
                  (select d.score from ".$table1." d where d.dimension=b.dimension and d.score is not null limit 1)
            else 
              score end 
          ) ascore
        from ".$table2." b
        where exists (select 1 cnt from ".$table1." c where c.dimension=b.dimension and c.score is null)";

        $db->executeSQL($sql3);
        $sql4 = "update ".$table3." set score = ascore";
        $db->executeSQL($sql4);
        
        $db->executeSQL("drop table if exists ".$table4);

        $sql5 = "create table ".$table4." 
        select pid,pdimension,leftname, rightname, leftchar, rightchar, dimension, score, count(*) cnt
        from ".$table3."
        group by pid,pdimension,leftname, rightname, leftchar, rightchar, dimension, score";
        $db->executeSQL($sql5);

        
        $db->executeSQL("drop table if exists ".$table5);
        $sql6 = "create table ".$table5."
        select * from ".$table4." where cnt>1
          union
        select * from ".$table1." where cnt>1";
        $db->executeSQL($sql6);

        $db->executeSQL("drop table if exists ".$table6);
        $sql7="create table ".$table6."
        select pid,pdimension,leftname, rightname, leftchar, rightchar, dimension,dimension score, count(*) cnt
        from ".$table1." where cnt=1
        and score is not null
        group by pid,pdimension,leftname, rightname, leftchar, rightchar, dimension
        having count(*)>1";
        $db->executeSQL($sql7);

        $sql8="update ".$table6." set score = leftchar";
        $db->executeSQL($sql8);

        $db->executeSQL("drop table if exists ".$table7);

        $sql9="create table ".$table7." select * from ".$table5."
        union
        select * from ".$table1." where cnt=1 and dimension not in (select dimension from ".$table5.") and dimension not IN
          (select dimension from ".$table6.")
        union select * from ".$table6."
        order by pid";

        $db->executeSQL($sql9);

        $sql10="update ".$table7." set score=left(dimension,1) where score is null";
        $db->executeSQL($sql10);

        $sql11="select * from ".$table7;

        $perspective = $db->getRecords($sql11);

        

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
        $db->executeSQL("drop table if exists ".$table4);
        $db->executeSQL("drop table if exists ".$table5);
        $db->executeSQL("drop table if exists ".$table6);
        $db->executeSQL("drop table if exists ".$table7);
    }
    return $response->withJson($res, 200);
});



if ($db != null)
    $db->disconnect();

?>