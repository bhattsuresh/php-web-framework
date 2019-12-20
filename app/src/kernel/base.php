<?php
use Dotenv\Dotenv;
use SB\Controller;
use SB\Request;
use SB\Hash;

function get($key = false){

    $app_file = __DIR__.'/../../../config/app.php';
    
    if(file_exists($app_file)){
    $app = include  $app_file;
    
    }else{
        die('missing config app.php');
    }

    if(!$key):
        return  $app; 
    else:    
        return  $app[$key]; 
    endif;    
}



define('APP_NAME',get('app'));


define('DB_HOST',get('host'));



define('DB_NAME',get('db'));



define('DSN',get('driver').':host='.DB_HOST.';dbname='.DB_NAME);




define('DB_USER',get('user'));




define('DB_PASS',get('pass'));




define('DB_PREFIX',get('db_prefix'));


define('IS_DEBUGG', get('is_debugg'));


define('URL',get('url'));




define('HASH_KEY',get('key'));



define('VIEW_CACHE', (get('cache') == 'true' || get('cache') == '1')?true:false );


define('FROM_MAIL', get('from_mail'));

define('ADMIN_MAIL', get('admin_mail'));




date_default_timezone_set(get('locale'));


/**
 * ====================================================================
 * @view function is important for helper...
 * ====================================================================
 */
function view($view = null, $flag = null, $header = null, $footer = null){
	if($view == null){
		info('View can not be null');
	}else{
		$ctr = new Controller;
		$ctr->view($view,$flag,$header,$footer);
		return $ctr;
    }
}


/**
 * ====================================================================
 * @redirect function is important for helper...
 * ====================================================================
 */



/**
 * ====================================================================
 * @redirect function is important for helper and debug...
 * @it is use for dump and die
 * ====================================================================
 */

function dd($array = null){
    if($array == null){
        die; 
    }
   echo '<pre style="color:red;">';
   print_r($array);
   die;
}


/**
 * ====================================================================
 * @redirect function is important for helper and debug...
 * @it is only dump
 * ====================================================================
 */


function d($array){
    echo '<pre style="color:green;">';
    print_r($array);
    
 }




function info($info = null){
   if(is_array($info)){
    echo '<div style="border:1px solid orange;padding:20px;font-size:12px;"><pre>' ;
    print_r($info); 
    echo '</pre></div>' ;

   }else if(!$info){
       echo '<div style="border:1px solid orange;padding:20px;font-size:12px;">Route or View not found!</div>';
   }else{
        echo '<div style="border:1px solid orange;padding:20px;font-size:12px;">'.$info.'</div>';
   }

    die;
}



function error_page($info = "This page is not found!"){
    echo '<style>
    
*, *:before, *:after {
    box-sizing: border-box;
  }
  
  body {
  
	  background-repeat: repeat;
	  color:#168cbb;
      background-size: cover;
      font-family: "Raleway", sans-serif; 
    
	  background-color:#10173e;
	  text-align:center;
  }
  
  .text-wrapper {
      height: 100%;
     display: flex;
     flex-direction: column;
     align-items: center;
     justify-content: center;
  }
  
  .title {
      font-size: 6em;
      font-weight: 700;
      color: #fff;
  }
	
  .subtitle {
      font-size: 40px;
      font-weight: 700;
      color: #168cbb;;
  }
  
  .route {
      font-size: 3em;
      font-weight: 700;
      color: #168cbb;
  }
  
  

      a.button {
          font-weight: 700;
          border: 2px solid orange;
          text-decoration: none;
          padding: 15px;
          text-transform: uppercase;
          color: orange;
          border-radius: 26px;
          transition: all 0.2s ease-in-out;
      }
          a:hover {
              background-color: orange;
              color: #fff;
              transition: all 0.2s ease-in-out;
          }
		  .icon{
			  font-size:25px;
			  
		  }
      
  @media only screen and (max-width: 600px) {
     .title {
        font-size: 4em;
    }
	 .route {
		 font-size: 1em; 
	 }
	  .subtitle {
      font-size: 20px;
	  }
}
     
  
    </style>';
   echo '<div class="text-wrapper">
   <div class="icon">😢</div>
    <div class="title" data-content="404">
        404
    </div>
    <div class="route">'.$info.'</div>
	<br>
    <div class="subtitle">

        Oops, the page you\'re looking for doesn\'t exist.
    </div>
   
<br><br><br>
    <div class="buttons">
        <a class="button" href="'.url().'">Go to homepage</a>
    </div>
	<br><br><br>
	<hr style="border-color:#00000000">
</div>';

}



function env_code(){
	$app_key = Hash::getToken();
return 'APP_NAME=SB
TIME_ZONE=Asia/Kolkata
APP_URL=http://localhost/
APP_KEY='.$app_key.'

DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=
DB_DRIVER=mysql
DB_PREFIX=
VIEW_CACHE=0
DEBUG_MODE=0';

}


function htaccess_code(){
	
return 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)$ index.php?uri=$1 [QSA,L]

<Files ~ "^.*\.([Ee][Nn][Vv])">
 order allow,deny
 deny from all
 satisfy all
</Files>';

}

 function env($name,$take = null){
	 if(!file_exists('.env')){
			$file = fopen(".env","w");
			fwrite($file,env_code());
		fclose($file);
	 }
	 
	 if(!file_exists('.htaccess')){
			$file = fopen(".htaccess","w");
			fwrite($file,htaccess_code());
		fclose($file);
	 }
     
    $dotenv = new Dotenv('./');
    $dotenv->load();
        $env = getenv($name);
        if($env == null){
            return $take;
        }
            return $env;
   
}


function url($file = null){
    
    return URL.$file;
}


function redirect($path=false){
    if(!$path){
        header('location:'.url());
    }else{
        header('location:'.url($path)); 
    }
}


function display_flush_message($msg, $type='success'){
    if($msg):
           $html = '<style>.flush-message-box{position:fixed;font-size: 16px;
            letter-spacing: 0.5px;  font-weight:600; cursor:pointer; background:#fff; top:0px; visibility:hidden; z-index:-1;  opacity:0;  min-width: 300px; left:-300px;padding: 19px 30px 20px 30px ;     -webkit-transition: all 0.4s; transition: all 0.4s;}
           .flush-message-box.success{ color:#2e9a32;  opacity:0.8; z-index:9999999999; visibility:visible; left:0;  box-shadow: inset 0 0 50px #4CAF50;  }
           .flush-message-box.error{ color: #f70b0b;  opacity:0.8;  z-index:9999999999;visibility:visible; left:0; box-shadow: inset 0 0 42px #FFC107;   }
           .flush-message-box .icon{padding-right:10px;   font-weight:600; }
           
            </style>';
          if($type == 'success'):
             $html .= '<div class="flush-message-box "  title="Click to close" onclick="closeFlush()" onmouseleave="closeFlush()"><span><i class="icon icon-check-circle-o fa fa-check-circle-o" ></i> '.$msg.' </span></div>';
          else:
            $html .= '<div class="flush-message-box " title="Click to close" onclick="closeFlush()" onmouseleave="closeFlush()"><span><i class="icon icon-error_outline" ></i> '.$msg.'  </span></div>';

          endif;

          $html .="<script>
         
          setTimeout(()=>{
            document.querySelector('.flush-message-box').classList.add('$type');
            setTimeout(()=>{
                document.querySelector('.flush-message-box').classList.remove('$type');
            },3500);
        },200);

        function  closeFlush(){
            setTimeout(()=>{
                document.querySelector('.flush-message-box').classList.remove('$type');
            },100);
        }
          
          </script>";
           return $html;
    endif;
}


class Status{
	
public static function show($msg = null){
if($msg != null):
?>
<script>    
var html = '<div id="custom-alert" class="custom-alert"><div class="status"></div></div>';
var css ='<style>\
.custom-alert{\
position: fixed;\
top: 0%;\
left: 0%;\
background: #3ac372;\
text-align: center;\
width: 100%;\
z-index: 9999999999;\
color: #fff;\
overflow: hidden;\
height:0px;\
font-size: 2em;\
display: table;}\
.status{\
	vertical-align: middle;\
	display: table-cell;\
}\
</style>';
document.write(css+html);

    $(".custom-alert").animate({
        height: '70px'
    }).css( 'box-shadow', '#8cea1e45 9px 15px 23px 0px');
    $('.status').html("<?=$msg?>");
    setTimeout(function(){
        $(".custom-alert").animate({
            height: '0px'
        }).css( 'box-shadow', '4px 5px 21px 1px #00000000');
        $('.status').html('');
    },2000);
	

</script>
<?php

endif;
}
}






