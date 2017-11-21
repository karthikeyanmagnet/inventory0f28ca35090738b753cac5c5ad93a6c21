<?php
	
	class PDOClass
	{
//		private $server 	  =  'localhost';
//		private $database_user =  'root';
//		private $database_password =  '';
//                private $database_name = 'db_inventory'; //'budget_live';
                
                private $server 	  =  'localhost';
		private $database_user =  'udumalai_invento';
		private $database_password =  'PL~L}OCG*fzc';
                private $database_name = 'udumalai_inventory'; //'budget_live';
		
		
		function __construct()
		{  
			$this->pdoObj = new PDO('mysql:host='.$this->server.';dbname='.$this->database_name.'', $this->database_user, $this->database_password);
		}
		
		public function execute($sql_qry, $bind_arr=array()) 
		{  
			$qry_id=$this->pdoObj->prepare($sql_qry);
			
			if(count($bind_arr)>0)
			{
				foreach($bind_arr as $bindKey => &$bindVal )
				{
					if($bindVal["type"]=="int") 
					{ 	
						$qry_id->bindParam($bindKey, $bindVal["value"], PDO::PARAM_INT );  
					}
					else 
					{  
						$qry_id->bindParam($bindKey, $bindVal["value"] );  
					} 
				}
			}
			
			if($qry_id->execute())	
			{	
				return $qry_id; 
			}
			else
			{
				$errors = $qry_id->errorInfo();
				
				echo '--sql--'.$sql_qry;
				print_r($bind_arr);
				echo($errors[2]);
			}
		}
		
		public function fetch($sql_qry, $bind_arr=array(), $opt) /* $opt = 1 single record*/
		{ 
			 
			$qry_id = $this->execute($sql_qry, $bind_arr);
			
			$rsArr = array();
			
			while($rs=$qry_id->fetch())
			{
				$rsArr[] = $rs;
			}
			
			return ($opt=='1')?$rsArr[0]:$rsArr; 
		}
		
		public function fetchMultiple($sql_qry, $bind_arr=array()) 
		{
			 return $this->fetch($sql_qry, $bind_arr);
		}
		
		public function fetchSingle($sql_qry, $bind_arr=array())
		{
			 return $this->fetch($sql_qry, $bind_arr, 1);
		}
		
		public function rowCount($sql_qry, $bind_arr=array()) 
		{  
			$qry_id = $this->execute($sql_qry, $bind_arr);
			
			$count = $qry_id->rowCount(); 
			
			return $count; 
		}
		
		
		public function getMaxRecord($field='', $table, $all_cols='') 
		{ 
		
			$bindArr=array(); 
			
			if($field)
			{
			
				$sql_qry=" select max($field) as id from $table ";   
				$rs_op=$this->fetchSingle($sql_qry, $bindArr=array());  			
				$max_id=$rs_op['id']?$rs_op['id']:0; 
			}
			else
			{	
				$sql_qry=" select * from $table order by 1 desc limit 0,1";   
				$rs_op=$this->fetchSingle($sql_qry, $bindArr); 
				
				if($all_cols == 'all_cols'){ $max_id=$rs_op; }
				else { $max_id=$rs_op[0]?$rs_op[0]:0; }
			}	
			
			return $max_id; 
		}
		
		
	}
	
?>