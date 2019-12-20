<?php 
namespace SB;
class Response {
    public static function back(){
    echo '<script>window.history.back();</script>';
    }

    public static function json($req = []){
        header('Content-type:Application/json');
        echo json_encode($req);
    }
}