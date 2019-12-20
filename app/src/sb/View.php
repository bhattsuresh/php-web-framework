<?php 
namespace SB;
use Session;
/**
 * ==============================
 * The Base View
 * ==============================
 */

class View{

        private $flag = null;
        private $header_file = 'header';
        private $footer = 'footer';
        protected $view;
		private $_loader = null;
		private $_twig = null;

        public function __Construct($view, $flag = null, $header_file=null, $footer=null){
            $this->view = $view;
            $this->flag = $flag;
            
                if($header_file != null){
                        $this->header_file = $header_file; 
                }

                if($footer != null){
                    $this->footer = $footer; 
                }
				
				
				$this->_loader = new \Twig\Loader\FilesystemLoader('./views');
				
			   if(VIEW_CACHE)
				$this->_twig = new \Twig\Environment($this->_loader,['cache' => './storage/cache',]);
				else
				$this->_twig = new \Twig\Environment($this->_loader);	

        }


    private function header_file(){
        if($this->flag){
            $file = 'views/'.$this->header_file.'.php';
            if(file_exists($file)){
                return $this->header_file.'.php';
           }else{
               $file = 'views/'.$this->header_file.'.phtml';
               if(file_exists($file))
                    return $this->header_file.'.phtml';
               else{
                   $file = 'views/'.$this->header_file.'.html';
                   if(file_exists($file))
                       return $this->header_file.'.html'; 
                            
               }
           }
            
       }

       return null;
    }


    private function body(){
        
        $file = 'views/'.$this->view.'.php';

        $file = 'views/'.$this->view.'.php';
        if(file_exists($file)){
            return $this->view.'.php';
        }else{
            $file = 'views/'.$this->view.'.phtml';
            if(file_exists($file))
            return $this->view.'.phtml'; 
            else{
                $file = 'views/'.$this->view.'.html';
                if(file_exists($file))
                    return $this->view.'.html'; 
                   else
                   info('<h1>View <span style="color:orange">'.$this->view.'</span> Does not found!</h1>');
                    
            }
        }
        return null;
    }
	
	
	

    private function footer(){
    if($this->flag){
        $file = 'views/'.$this->footer.'.php';
        if(file_exists($file)){
            return $this->footer.'.php';
        }else{
            $file = 'views/'.$this->footer.'.phtml';
            if(file_exists($file))
                return $this->footer.'.phtml'; 
            else{
                $file = 'views/'.$this->footer.'.html';
                if(file_exists($file))
                return $this->footer.'.html'; 
                
    
                    
                }
            }
        }
        return null;

    }


    public function render($keys = null, $vals = null){
      
		$res = [];
        
        if(is_array($keys)){
			
            foreach($keys as $i=>$var):
			$res[$var] = $vals[$i];
            endforeach;
			
			
			
        }
        $res['app'] = ['name'=>APP_NAME,'url'=>URL];
        
        $res['app']['session'] = Session::all();

      
      
  

        if($this->header_file() != null){
			echo $this->_twig->render($this->header_file(), $res);
        }

		if($this->body() != null){
            echo Session::status();
			echo $this->_twig->render($this->body(),$res);
		}
		
        if($this->footer() != null){
           echo $this->_twig->render($this->footer(),$res);
        }
       
       
     
        
    }
}
