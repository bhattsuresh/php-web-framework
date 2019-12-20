<?php 

class RedirectToUserLogin{

    public function handle() {
        $data = Session::get('isUserLogged');
            if(!$data){
                return '/';
            }
            return null;
    }
}