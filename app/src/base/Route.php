<?php
/**
 * ==============================
 * The Base Route
 * ==============================
 */
use SB\Request;
use SB\Response;

 class Route {
    private static $routes = []; 
    private static $get_routes = []; 
    private static $post_routes = []; 
    
    private static $get_methods = [];
    private static $post_methods = [];

    private static $middleware = null;

    public $with;

    


    public static function get($route, $method =  null){
        $request_method = Request::method();
           if($request_method == 'GET'):
                $route = rtrim($route,'/');
            
                self::$get_routes[] = $route;
                self::$routes[] = $route;

                if($method != null){
                    self::$get_methods[] = $method;
                    
                }
            endif;
        return new static;
    }

    public static function put($route, $method =  null){
           $request_method = Request::method();
           if($request_method == 'PUT'):
                $route = rtrim($route,'/');
            
                self::$get_routes[] = $route;
                self::$routes[] = $route;

                if($method != null){
                    self::$get_methods[] = $method;
                    
                }
        endif;
        return new static;
    }




    public static function post($route, $method =  null){
        
        $route = rtrim($route,'/');
    
        self::$post_routes[] = $route;
        self::$routes[] = $route;

        if($method != null){
            self::$post_methods[] = $method;
        }
    return new static;
}


   

    public function middleware($key){
        
         $index = count(self::$routes)-1;
      
         self::$middleware[$key][] = self::$routes[$index] ;
       
    }




    private static function render($exUri,$str){

        $ex = explode('@',$str);
        $params = $exUri ? array_values($exUri) : [];
   
        if(class_exists($ex[0]))
            $obj =  new $ex[0];
        else
            info('<h1>Class <span style="color:orange">'.$ex[0].'</span> Does not found!</h1>');

            
    

        if(method_exists($obj,$ex[1])){
            $ctr =  call_user_func_array(array($obj,$ex[1]),$params);
           
    }else
            info('<h1><span style="color:orange">'.$ex[0].'</span> Does not found <span style="color:orange">'.$ex[1].'()</span> method</h1>');

		
           
        if(!$ctr){
            exit;
        }

        if(gettype($ctr) == 'object'){
           $ctr->view->render($ctr->key,$ctr->val);
        }else if(gettype($ctr) == 'string'){
            header("Content-type: application/json; charset=utf-8");
            echo $ctr; 
        }else {
            info($ctr); 
        }
    }


    private static function sb_callable($exUri,$return){

        $ctr =  call_user_func($return);

        if(gettype($ctr)=='object'){
            
            $ctr->view->render($ctr->key,$ctr->val);

        }else if(gettype($ctr)=='string'){
            if(strpos($ctr,'@'))
                self::render($exUri,$ctr); 
            else
                info($ctr);
        }else{
            info($ctr);
        }
    }




    public static function boot(){
        $exUri = [];
        $flag = 0;

       
       $uri = Request::uri();
      
        $method = Request::method();
        if($method == 'POST')
            $routes = self::$post_routes;
        else 
            $routes = self::$get_routes;

       
        $route_uri = $uri; 
        if(!in_array($uri,$routes)):
            $exUri = explode('/',$uri);
            $ckUri = '';
            foreach($exUri as $i=>$ex):
                if($i==0):
                    $ckUri .=$ex;
                else:
                    $ckUri .='/'.$ex;
                endif;
                if(in_array($ckUri,$routes)):
                    $route_uri = $ckUri; 
                    $exUri = array_slice($exUri,$i+1);
                   
                   break;
                endif;
                
            endforeach;
           
        endif;
        
       
/*need to update here in case all /string if route*/ 

       if( strpos($uri,'api/') !== false):
       else:
            if(!empty($exUri)){
                $c_url = current($exUri);
                if(strpos($c_url,'-') !== false){
                    if($route_uri !== $uri){
                        $route_uri = $route_uri.'/'.array_shift($exUri);
                    }
                }else{
                if(ctype_alpha($c_url)){
                    if($route_uri !== $uri)
                    $route_uri = $route_uri.'/'.array_shift($exUri);
                }
            }
                
            }
        endif;    
           
       
       if(in_array($route_uri,$routes)):

            if(self::$middleware != null):   
                foreach(self::$middleware AS $middle=>$route):

                  
                    if(in_array($route_uri ,$route)){
                        $middlewareObj =  new $middle;
                         $return = $middlewareObj->handle();
                        
                            if($return != null){
                            $new_uri = rtrim($return,"/");
                               redirect($new_uri);
                              
                            return false;
                            }
                        
                    }else{

                    }
                endforeach;

                unset($route);
            endif;


        $key = array_search( $route_uri,$routes);
            
            if($method == 'POST')
                $return = self::$post_methods[$key];
            else
                $return = self::$get_methods[$key];

              
            if(is_callable($return)):
                self::sb_callable($exUri,$return);
            else:
                   
                self::render($exUri,$return);   
            endif;   
                
        
    else:
        if(IS_DEBUGG){
         $info = ($route_uri == "")? '/': $route_uri;
        
            info('<h1>  Route <span style="color:orange">['.$info.']</span> does not found!</h1>TYPE OF REQUEST <span style="color:orange">['.$method .']</span>');
        }else{
            echo Response::json(["msg"=>"Request route not found!","code"=>404]);
        }
    endif;    

    }


 }
