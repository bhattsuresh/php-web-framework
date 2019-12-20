<?php 
use SB\DB;
use SB\Controller;
use SB\Request;
use SB\Hash;
use SB\Email;

class MainController extends Controller{

    public function index(){
		return view('index',1);			
	}


   
}

