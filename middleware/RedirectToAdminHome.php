<?php 

class RedirectToAdminHome{

    public function handle() {
        $data = Session::get('isAdminLogged');
            if($data){
                 return 'admin/dashboard';
            }
            return null;   
    }
}