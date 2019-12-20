<?php 

class RedirectToAdminLogin{

    public function handle() {
        $data = Session::get('isAdminLogged');
            if(!$data){
                 return '/';
            }
            return null;  
    }
}