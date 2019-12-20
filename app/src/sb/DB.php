<?php

namespace SB;

class DB extends Model{
    private $api = null;
    protected $table = null;
    protected $select = null;
    protected $where = null;
    protected $order = null;
    protected $group = null;
    protected $limit = null;
    protected $join = null;
    private $_sql = '';

    private $_where_data = [];



    public function __construct(){
        parent::__construct();
    }




    public static function table($name = null){
        $self = new static;
        
        if(!$name){
            info('Pass argument on table `table name` ');
        }
        else{
            $self->table = DB_PREFIX.$name;
        }

        return $self;
   }





   public function setApi($name){
        $this->table = DB_PREFIX.$name;
        $this->api = true;
   }

   
   public function insertMeta($data=[]){
        $this->addMeta($this->table,$data);
   }


   public function select($column = '*'){
       $select = ''; 
      
       if(is_array($column)){
           $count = count($column);
        foreach($column as $i=>$col):
            if($i<$count-1){
                $select .= $col.', ';
            }else{
                $select .= $col;
            }
        endforeach;
        $this->select .= $select.', ';
       }else{
        $this->select .= $column.', ';
        
       }

       
        return $this;
    }


    public function moreSql($sql){
        $this->_sql = ' '.$sql.' ';
        return $this;
    }

 
    public function where($first, $second=null, $third = null){
     
        if(!$this->where){
            $this->where = 'WHERE '; 
        }else{
            $this->where .= ' AND '; 
        }

        if(is_array($first)){
            foreach ($first as $key => $value) {
                $this->where .=  $key .' = '. ":$key";
                $this->_where_data[$key] = $value;
            }
          
        }else{

        if(!$third){
            $this->where .=  $first .' = '. ":$first";
            $this->_where_data[$first] = $second;
        }else{
            $this->where .=  $first .' '. $second .' '. ":$first";
            $this->_where_data[$first] = $third;
        }
    }
        
       return $this;
    }




    public function orWhere($first, $second, $third = null){
     
        if(!$this->where){
            $this->where = 'WHERE '; 
        }else{
            $this->where .= ' OR '; 
        }

        if(!$third){
            $this->where .=  $first .' = '. ":$first";
            $this->_where_data[$first] = $second;
        }else{
            $this->where .=  $first .' '. $second .' '. ":$first";
            $this->_where_data[$first] = $third;
        }
       return $this;
    }


    /**
    *======================================
    * first get all Meta record function
    *======================================
    */


   public function getMeta($type = 1){
     
    $statement = '';
    $this->select = rtrim( $this->select,', ');
    if($this->select == null){
        $this->select = '*';
    }
   
    if($this->_sql == ''):
        if(!$this->where) 
        $data = $this->fetch_all(  $this->table,$this->select ,$this->join.$this->group.$this->order.$this->limit,$type);
        else   
        $data= $this->fetch_all($this->table,$this->select,$this->join.$this->where.$this->group.$this->order.$this->limit,$type,$this->_where_data); 
    else:
         
        $data= $this->fetch_all($this->table,$this->select,$this->join.$this->_sql.$this->group.$this->order.$this->limit,$type,$this->_where_data); 
   
    endif;    
    $this->close();
    
     if($this->api)
        return json_encode($data);

    $rt = [];
    foreach($data as  $k=>$d){
        $key = $d['meta_key'];
        $val = $d['meta_value'];

            if($this->isJson($val)){
                $rt[$key][] = json_decode($val,true);
                
            }else{
                if(array_key_exists($key,$rt)){
                    if(!is_array($rt[$key]))
                        $rt[$key] = array($rt[$key],$val);
                        else
                        $rt[$key][] = $val;
                }else{
                    $rt[$key] = $val;
                }
            }
            
       
    }   
    
    unset($data);
    
    return $rt;
   }

   function isJson($string) {
    return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
   }


    /**
    *======================================
    * first get all record function
    *======================================
    */


   public function get($type = 2){
    
    $statement = '';
    $this->select = rtrim( $this->select,', ');
    if($this->select == null){
        $this->select = '*';
    }
   
    if($this->_sql == ''):
        if(!$this->where) 
        $data = $this->fetch_all(  $this->table,$this->select ,$this->join.$this->group.$this->order.$this->limit,$type);
        else   
        $data= $this->fetch_all($this->table,$this->select,$this->join.$this->where.$this->group.$this->order.$this->limit,$type,$this->_where_data); 
    else:
         
        $data= $this->fetch_all($this->table,$this->select,$this->join.$this->_sql.$this->group.$this->order.$this->limit,$type,$this->_where_data); 
   
    endif;    
    $this->close();
    
     if($this->api)
        return json_encode($data);

        
    return $data;
   }

   public function all($type = 2){
    return $this->get($type);
}


    /**
    *======================================
    * first get one record function
    *======================================
    */


   public function first($type = 2){
    $this->select = rtrim( $this->select,', ');
    if($this->select == null){
        $this->select='*';
    }
    if(!$this->where)
        $data = $this->fetch_one($this->table,$this->select,$this->order,$type);
    else  
        $data = $this->fetch_one($this->table,$this->select,$this->where.$this->order,$type,$this->_where_data); 
  
    $this->close();    

    if($this->api)
        return json_encode($data);
   
    return $data;
   }


    /**
    *======================================
    *  get one record function
    *======================================
    */


    public function one($type = 2){
         return $this->first($type);
    }



    /**
    *======================================
    * count function
    *======================================
    */


   public function count($column = '*'){
    if(!$this->where)
        $data = $this->fetch_one_object($this->table,'COUNT('.$column.') AS total'); 
    else 
        $data = $this->fetch_one_object($this->table,'COUNT('.$column.') AS total',$this->where,$this->_where_data); 

   if(isset($data->total))
    return $data->total;
    else
    return  false;
    
   }

   
    /**
    *======================================
    * limit function
    *======================================
    */


   public function limit($offset, $count = null){
     if(!$count){
        $this->limit = " LIMIT 0, $offset ";
     }else{
        $this->limit = " LIMIT  $offset, $count ";
     }
    
    return $this;
   }


    /**
    *======================================
    * Order By function
    *======================================
    */

   public function orderBy($order, $val = "ASC"){
    
       $this->order = " ORDER BY $order $val ";
    
   
   return $this;
  }


  
     /**
    *======================================
    * Group By function
    *======================================
    */


  public function groupBy($column){
    
    $this->group = " GROUP BY $column ";
 

        return $this;
    }


     /**
    *======================================
    * Join Functions Start
    *======================================
    */


    public function join($table, $first, $second, $third = null){
        if($third == null) 
            $this->join = ' JOIN '.DB_PREFIX.$table.' ON '. $first .' = ' .$second.' ';
        else
            $this->join = ' JOIN '.DB_PREFIX.$table.' ON '. $first .' '. $second . ' ' .$third.' ';

        return $this;    
    }


    public function leftJoin($table, $first, $second, $third = null){
        if($third == null) 
            $this->join = ' LEFT JOIN '.DB_PREFIX.$table.' ON '. $first .' = ' .$second.' ';
        else
            $this->join = ' LEFT JOIN '.DB_PREFIX.$table.' ON '. $first .' '. $second . ' ' .$third.' ';
            
        return $this;    
    }


    public function rightJoin($table, $first, $second, $third = null){
        if($third == null) 
            $this->join = ' RIGHT JOIN '.DB_PREFIX.$table.' ON '. $first .' = ' .$second.' ';
        else
            $this->join = ' RIGHT JOIN '.DB_PREFIX.$table.' ON '. $first .' '. $second . ' ' .$third.' ';
            
        return $this;    
    }



    
    public function fullJoin($table, $first, $second, $third = null){
        if($third == null) 
            $this->join = ' FULL OUTER JOIN '.DB_PREFIX.$table.' ON '. $first .' = ' .$second.' ';
        else
            $this->join = ' FULL OUTER JOIN '.DB_PREFIX.$table.' ON '. $first .' '. $second . ' ' .$third.' ';
            
        return $this;    
    }




    /**
    *======================================
    *create table  Function
    *======================================
    */
 
    public function create($data){
        $res =  $this->create_table($this->table,$data);
        $this->close();
        return $res;
    }


    /**
    *======================================
    *Insert Function
    *======================================
    */

    public function insert($data){
        $res =  $this->add($this->table,$data);
        $this->close();
        return $res;
    }


    public function insertGetId($data){
        $res =  $this->addGetId($this->table,$data);
        $this->close();
        return $res;

        
    }


    /**
    *======================================
    *Update Function
    *======================================
    */

    public function  update($data){
        $res =  $this->modify($this->table, $data, $this->where, $this->_where_data);
        $this->close();
        return $res;
    }

    


     /**
    *======================================
    * Delete Function
    *======================================
    */

    public function delete(){
        $res = $this->deleteData($this->table,$this->where,$this->_where_data);
        $this->close();
        return $res;
    }
    

     /**
    *======================================
    * Connection Close Function
    *======================================
    */

    public function close(){
        $this->connection_close();
        return $this;
    }

}
