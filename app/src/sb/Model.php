<?php
namespace SB;
use PDO;
use PDOException;
class Model {
    private  $pdo = null;
    public function __construct() {
        try {
           $this->pdo = new PDO(DSN, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
           
        } catch (PDOException $e) {
          $this->db_error($e);
        }
    }




    /**
     * Create table
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     */

    function create_table($table, $data) {
        $sql = "CREATE TABLE IF NOT EXISTS $table (";
        $num = count($data);
        $sql .= "`_id` bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT, ";
        for ($i = 0; $i < $num; $i++):
            $sql .= $data[$i] . ", ";
        endfor;
       $sql .= "`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
       $sql .="`updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

        $this->pdo->exec($sql);
        
        return  '<big>This code was executed. Please check manually if no table is created for the database<big> <br> '.$sql ;
            
       
    }

    /**
     * insert
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     */
    public function add($table, $data) {
        ksort($data);
        $this->pdo->beginTransaction();
        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
        $sth = $this->pdo->prepare("INSERT INTO `$table` (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $val = ltrim($value," ");
            $sth->bindValue(":$key", $val);
        }

        $s = $sth->execute();
        $this->pdo->commit();
        return $s;
    }




    /**
     * insert with get auto increment _id
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     */
    public function addGetId($table, $data) {
        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
        $sth = $this->pdo->prepare("INSERT INTO `$table` (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $val = ltrim($value," ");
            $sth->bindValue(":$key", $val);
        }

        $res = $sth->execute();
        
        if($res){
           return  $this->pdo->lastInsertId();
        }else{
            return $res;
        }
    }

    /**
     * update
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     * @param string $where the WHERE query part
     */
    public function modify($table, $data,$where,$where_data = []) {
        ksort($data);
      
        $fieldDetails = NULL;
        foreach ($data as $key => $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
       
        $sth = $this->pdo->prepare("UPDATE `$table` SET $fieldDetails  $where");

        foreach ($data as $key => $value) {
            $val = ltrim($value," ");
            $val = rtrim($val," ");
            $sth->bindValue(":$key", $val);
        }

        foreach ($where_data as $key => $value) {
            $sth->bindValue(":".$key, $value);
        }

        return $sth->execute();
    }

    /**
     * Fetch all
     * @param string $table A name of table to get all data
     * @param string $cols the WHERE query part
     * @param string $where the WHERE query part
     * @param string $type the return data type 
     */


     public function fetch_all($table,$cols = '*',$where = false, $type = null,$where_data = []) {
        $statement = '';

        if(!$where){
            $statement = "SELECT $cols FROM $table";   
        }else{
          
            $statement =  "SELECT $cols FROM $table $where";
        }

        $pre = $this->pdo->prepare($statement);

        $pre->execute($where_data);


         if(gettype($type) == 'string'){
            $type = strtoupper($type);
         }

        if(!$type || $type == 'NUM'){
           
           
            return $pre->fetchAll(PDO::FETCH_NUM);
        }
            
        else if($type == 1 || $type == 'ASSOC')
          {  
           
            return $pre->fetchAll(PDO::FETCH_ASSOC);
          }
        else {
            
            return $pre->fetchAll(PDO::FETCH_OBJ);  
        } 
            



    }


    /**
     * Fetch one
     * @param string $table A name of table to get all data
     * @param string $cols the WHERE query part
     * @param string $where the WHERE query part
     * @param string $type the return data type 
     */

    public function fetch_one($table,$cols = '*',$where = false, $type = null,$where_data = []) {
        if(!$where){
            $pre = $this->pdo->prepare("SELECT $cols FROM $table");
            
        }else{
          
              $pre = $this->pdo->prepare("SELECT $cols FROM $table $where");
             
             
        }
        $pre->execute($where_data);

        if(gettype($type) == 'string'){
            $type = strtoupper($type);
         }


        if(!$type || $type == 'NUM')
            return $pre->fetch(PDO::FETCH_NUM);
        else if($type == 1 || $type == 'ASSOC' )
            return $pre->fetch(PDO::FETCH_ASSOC);
        else  
            return $pre->fetch(PDO::FETCH_OBJ);
      
    }














    public function fetch_some($table, $cols, $where, $operator) {
        ksort($where);
                $fields = '';
                $count = count($where);
                $i = 0;
                foreach($where as $key=>$val):
                
                   if($i<$count-1){
                   $fields .= $key.' '.$operator.' :'. $key.', ' ;
                   }else{
                       $fields .= $key.' '.$operator.' :'. $key;
                   } $i++;
                endforeach;
              
                $pre = $this->pdo->prepare("SELECT $cols FROM $table WHERE $fields");
                foreach ($where as $key => $value):
                    
                     $pre->bindValue(":$key", $value);
                 endforeach;
                 $pre->execute();
                 return $pre->fetch(PDO::FETCH_ASSOC); 
         
    }
    
    
    /**
     * Fetch row
     * @param string $table A name of table to get all data
     * @param string $cols the WHERE query part
     */
    public function fetch_row($table, $cols = '*', $where = false, $operator = '=') {
        if(!$where){
            
        $pre = $this->pdo->prepare("SELECT $cols FROM $table");
        $pre->execute();
        return $pre->fetch(PDO::FETCH_ASSOC); 
        }else{
            if(!is_array($where)){
                
               $pre = $this->pdo->prepare("SELECT $cols FROM $table $where");
               $pre->execute();
               return $pre->fetch(PDO::FETCH_ASSOC);  
            }else{
                
               return $this->pdo->fetch_some($table, $cols, $where, $operator);
            }
        }
    }
    
    /**
     * Fetch rows
     * @param string $table A name of table to get all data
     * @param string $cols the WHERE query part
     */
    public function fetch_rows($table, $cols = '*',$where = false) {
        if(!$where){
            $pre = $this->pdo->prepare("SELECT $cols FROM $table");
            
        }else{
          
            $pre = $this->pdo->prepare("SELECT $cols FROM $table $where");
        }
        $pre->execute();
        return $pre->fetchAll(PDO::FETCH_OBJ);
      
    }
    
    public function fetch_one_assoc($table,$cols = '*',$where = false) {
        if(!$where){
            $pre = $this->pdo->prepare("SELECT $cols FROM $table");
            
        }else{
          
            $pre = $this->pdo->prepare("SELECT $cols FROM $table $where");
        }
        $pre->execute();
        return $pre->fetch(PDO::FETCH_ASSOC);
      
    }

    
   
    public function fetch_one_object($table,$cols = '*',$where = false,$where_data = []) {
        if(!$where){
            $pre = $this->pdo->prepare("SELECT $cols FROM $table");
            
        }else{
          
            $pre = $this->pdo->prepare("SELECT $cols FROM $table $where");
        }
        $pre->execute($where_data);

        return $pre->fetch(PDO::FETCH_OBJ);
      
    }
    
   

    public function fetch_all_assoc($table,$cols = '*',$where = false) {
        if(!$where){
            $pre = $this->pdo->prepare("SELECT $cols FROM $table");
            
        }else{
          
            $pre = $this->pdo->prepare("SELECT $cols FROM $table $where");
        }
        $pre->execute();
        return $pre->fetchAll(PDO::FETCH_ASSOC);
      
    }
    public function fetch_all_object($table,$cols = '*',$where = false) {
        if(!$where){
            $pre = $this->pdo->prepare("SELECT $cols FROM $table");
            
        }else{
         
            $pre = $this->pdo->prepare("SELECT $cols FROM $table $where");
        }
        $pre->execute();
        return $pre->fetchAll(PDO::FETCH_OBJ);
      
    }
    

    /**
     * Fetch type
     * @param string $table A name of table to get all data
     * @param string $where the WHERE query part
     */
    public function fetch_type($table, $type = PDO::FETCH_OBJ, $limit = false,$cols = '*',$where = 1) {
      
        $pre = $this->pdo->prepare("SELECT $cols FROM $table $where");
        $pre->execute();
        if(!$limit){
          
        return $pre->fetchAll($type);
        }else{
           return $pre->fetch($type); 
        }
    }
    
    
    public function fetch_sql($sql,$type = PDO::FETCH_OBJ) {
        $pre = $this->pdo->prepare($sql);
        $pre->execute();
        return $pre->fetchAll($type);
      }
    
    
    public function delete_row($table,$where,$operator = '=') {
     
        ksort($where);
                $fields = '';
                $count = count($where);
                $i = 0;
                foreach($where as $key=>$val):
                
                   if($i<$count-1){
                   $fields .= $key.' '.$operator.' ? AND ' ;
                   }else{
                       $fields .= $key.' '.$operator.' ?';
                   } $i++;
                endforeach;
              
                $pre = $this->pdo->prepare("DELETE FROM $table WHERE $fields");
                foreach ($where as $key => $value):
                     $a[] = $value;
                 endforeach;
                 
            return   $pre->execute($a);
         
       }


       protected function deleteData($table,$where,$where_data=[]) {
       
        $pre = $this->pdo->prepare("DELETE FROM $table  $where");
        foreach ($where_data as $key => $value) {
            $pre->bindValue(":".$key, $value);
        }

        return  $pre->execute();
         
       }

       
	   public function customeDate($date=false) {
		   $date=date_create("$date");
			return date_format($date,"dS-M-Y");
			
       }
       
       public function get_json($table){
        $rows = $this->pdo->fetch_all_assoc($table);


        $out = "";
        
        foreach($rows as $row) {
            
            $cols = array_keys($row);
             if ($out != "") {
                $out .= ",";
                }
            foreach($cols as $i=>$col){
           
                if($i==0){
            $out .= '{"'.$col.'":"'  . $row[$col] . '",';
            }
            else{
            $out .= '"'.$col.'":"'  . $row[$col] . '",';
            }	
        
            if($i==count($cols)-1){
            $out .= '"'.$col.'":"'. $row[$col]     . '"}';
        }
            }
        }
        
        $out ='{"records":['.$out.']}';
            
        
        return  $out;
       }


       protected function connection_close(){
        $this->pdo = null;
       }


       private function db_error($e) {
            die('
                <br><h2><br>
                <center>!Config Error.<br>
                <small style="color:gray">Setup your .env file. Read Following Error</small>
                </center></h2>
                <h3>.env file variables</h3>
                <ul>
                <li>DB_HOST="Enter database host name"</li>
                <li>DB_USER="Enter here database user name"</li>
                <li>DB_PASS="enter Database Password"</li>
                <li>DB_NAME="enter Database Name"</li>
                <li>DB_DRIVER="DB DIRVER like `mysql`"</li>
                </ul>
                <br><div style="padding:50px;"><small style="color:lightgray"><pre>' . $e . '</pre></small></div>'
            );
        
       }
}
