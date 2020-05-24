<?php
	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "70r853eka2";
		const DB = "mercusa";
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
				mysql_select_db(self::DB,$this->db);
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
			$email = $this->_request['username'];		
			$password = $this->_request['password'];
			
			// Input validations
			if(!empty($email) and !empty($password)){
				
					$sql = mysql_query("SELECT * FROM mercusa_clienti WHERE cod_fiscale = '$email' AND password = '$password' ", $this->db);
					if(mysql_num_rows($sql) > 0){
						$result = mysql_fetch_array($sql,MYSQL_ASSOC);
						
						// If success everythig is good send header as "OK" and user details
						$this->response($this->json($result), 200);
					}
					$this->response('', 204);	// If no records "No Content" status
				
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}
		
		private function itemsList(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$contab = $_REQUEST['contab'];
			$limit = $_REQUEST['limit'];
			$limit_passo = $_REQUEST['limit_passo'];
			if ($limit_passo == 6) {
				$sql = mysql_query("
					SELECT * FROM mercusa_articoli a LEFT JOIN mercusa_movimenti m ON a.id = m.id_articolo 
					LEFT JOIN mercusa_movimenti_resi r ON a.id = r.id_articolo
					WHERE a.url_img <> '' AND contabilita = '$contab' AND a.flag_homepage = '1' GROUP BY a.id 
					HAVING (count(m.id_articolo) + count(r.id_articolo)) < a.quantita 
					ORDER BY a.id DESC LIMIT $limit,$limit_passo
					", $this->db); 
			} else {
				$sql = mysql_query("
					SELECT * FROM mercusa_articoli a LEFT JOIN mercusa_movimenti m ON a.id = m.id_articolo 
					LEFT JOIN mercusa_movimenti_resi r ON a.id = r.id_articolo
					WHERE a.url_img <> '' AND contabilita = '$contab' GROUP BY a.id 
					HAVING (count(m.id_articolo) + count(r.id_articolo)) < a.quantita 
					ORDER BY a.id DESC LIMIT $limit,$limit_passo
					", $this->db); 
			}
			if ($contab == 'all') {
				$sql = mysql_query("
					SELECT * FROM mercusa_articoli a LEFT JOIN mercusa_movimenti m ON a.id = m.id_articolo
					LEFT JOIN mercusa_movimenti_resi r ON a.id = r.id_articolo
					WHERE a.url_img <> '' AND a.flag_homepage = '1' GROUP BY a.id 
					HAVING (count(m.id_articolo) + count(r.id_articolo)) < a.quantita  
					ORDER BY a.id DESC LIMIT $limit,$limit_passo
					", $this->db); 
			}
			
			$sql_tot = mysql_query("
				SELECT * FROM mercusa_articoli a LEFT JOIN mercusa_movimenti m ON a.id = m.id_articolo
				LEFT JOIN mercusa_movimenti_resi r ON a.id = r.id_articolo
				WHERE a.url_img <> '' AND contabilita = '$contab' GROUP BY a.id 
				HAVING (count(m.id_articolo) + count(r.id_articolo)) < a.quantita 
				ORDER BY a.id DESC
				", $this->db);
			$tot = mysql_num_rows($sql_tot);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_BOTH)){
					$result[] = $rlt;
				}
				$result['tot'] = $tot;
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function itemsSearch(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
			$term = $_REQUEST['search'];
			$sql = mysql_query("
				SELECT * FROM mercusa_articoli a LEFT JOIN mercusa_movimenti m ON a.id = m.id_articolo 
				LEFT JOIN mercusa_movimenti_resi r ON a.id = r.id_articolo
				WHERE a.url_img <> '' AND (a.nome LIKE '%$term%' OR a.descrizione LIKE '%$term%' OR a.id LIKE '%$term%') GROUP BY a.id 
				HAVING (count(m.id_articolo) + count(r.id_articolo)) < a.quantita
				ORDER BY a.id DESC
				", $this->db);
			
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_BOTH)){
					$result[] = $rlt;
				}
				
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function itemDetails(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
			$table = $_REQUEST['table'];
			$id = $_REQUEST['id'];
			$campo = $_REQUEST['campo'];
			$sql = mysql_query("
				SELECT * FROM $table WHERE $campo = '$id' ORDER BY id DESC
				", $this->db);
			
		
				$result = array();
				while($rlt = mysql_fetch_row($sql,MYSQL_BOTH)){
					$result[] = $rlt;
				}
		//	$query = mysql_fetch_array($sql,MYSQL_BOTH);
  		//	foreach($query as $rlt){
   		//		 $result[] .= $rlt;
  		//	}
			//foreach(mysql_fetch_array($sql,MYSQL_BOTH) as $rlt){
 			//  $result[] = $rlt;
			// }echo mysql_num_rows($sql); 
				
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function movUser(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id_cliente = $_REQUEST['id_cliente'];
			$sql = mysql_query("
				SELECT * FROM mercusa_articoli WHERE id_cliente = '$id_cliente' AND contabilita = 'cv' ORDER BY id DESC
				", $this->db);
			
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_BOTH)){
					$result[] = $rlt;
					$id_articolo = $rlt['id'];
					
					$sqlMov = mysql_query("
						SELECT * FROM mercusa_movimenti WHERE id_articolo = '$id_articolo'
						", $this->db);
			
					if(mysql_num_rows($sqlMov) > 0){
						$resultMov = array();
						while($rltMov = mysql_fetch_array($sqlMov,MYSQL_BOTH)){
							$resultMov[] = $rltMov;
						}
						$mov = $this->response($this->json($resultMov), 200);
					}
				}
				$tot = array($result,$mov);
				$this->response($this->json($tot), 200);
					
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function deleteUser(){
			// Cross validation if the request method is DELETE else it will return "Not Acceptable" status
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){				
				mysql_query("DELETE FROM users WHERE user_id = $id");
				$success = array('status' => "Success", "msg" => "Successfully one record deleted.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>
