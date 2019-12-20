<?php 

class RedirectToUserHome{

    public function handle() {
        $data = Session::get('isUserLogged');
            if($data){
                return 'user-dashboard';
            }
            return null;  
    }
}