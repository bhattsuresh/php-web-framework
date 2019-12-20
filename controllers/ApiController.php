<?php 
use SB\Api;
use SB\Request;
use SB\Response;
use SB\DB;

class ApiController extends Api{

    public function demo(){
        $req = Request::get();
      $res = [
        "msg"=>"API CALL SUCCESS BY GET METHOD ",
        "code"=>200,
        "data"=>$req,

      ];

      return Response::json($res);
    }

    public function updateDemo(){
         $req = Request::put();

      $res = [
        "msg"=>"API CALL SUCCESS BY PUT METHOD",
        "code"=>200,
        "data"=>$req,


      ];

     return Response::json($res);
    }


   
}