<?php

	use \PDO;
   define(CONFIG_DATABASE_HOST,"localhost");
   define(CONFIG_DATABASE_NAME,"test");
   define(CONFIG_DATABASE_USER,"root");
   define(CONFIG_DATABASE_PASS,"root");

	abstract class  ModelDB{
		private $connection;
		private $table_name;
		private $fields_validate = array('lft','rgt','name');

		public function __construct($nombreTabla = null)
		{
			try {
			if ($this->connection === null) {
				$opt = array(
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				);

				$this->connection = new PDO(
					'mysql:host='.CONFIG_DATABASE_HOST.';dbname='.CONFIG_DATABASE_NAME,
					CONFIG_DATABASE_USER,
					CONFIG_DATABASE_PASS,
					$opt
				);
				$this->connection->setAttribute(
					PDO::ATTR_ERRMODE,
					PDO::ERRMODE_EXCEPTION
				);
				$this->table_name = $nombreTabla;
				if($this->verificar_estructura_tabla()){

				}else{

					echo 'no';
				}

			}
		} catch (Exception $e) {
			print_r($e);
	}
		}//fin de constructor

		private function verificar_estructura_tabla(){

			$q = $this->connection->prepare("DESCRIBE ".$this->table_name);
			$q->execute();
			$table_fields = $q->fetchAll(PDO::FETCH_COLUMN);

			//print_r(array_intersect($this->fields_validate,$table_fields));
			 return count(array_intersect($this->fields_validate,$table_fields))==count($this->fields_validate)? true: false;

		}//fin de verificar_estructura_tabla

		public function insertar_nodo(){

		}

		public function obtener_hojas(){
			$q = $this->connection->prepare("Select * from ".$this->table_name." where lft = ( rgt - 1 )");
			$q->execute();
			$table_fields = $q->fetchAll();
			return count($table_fields)> 0 ? $table_fields : false;
		}//fin obtener_hojas()


		public function raiz(){
			$q = $this->connection->prepare("select * from ".$this->table_name." where lft = 1");

			$q->execute();
			$table_fields = $q->fetchAll();
			return count($table_fields)> 0 ? $table_fields : false;
		}

		public function subArbol_por_nombre($nombre=''){
			$q = $this->connection->prepare("SELECT node.name
														FROM ".$this->table_name." AS node,
        												".$this->table_name." AS parent
														WHERE node.lft BETWEEN parent.lft AND parent.rgt
        												AND parent.name = '".$nombre."'
														ORDER BY node.lft");

			 //print_r($q);
			//exit;
			$q->execute();
			$table_fields = $q->fetchAll();
			return count($table_fields)> 0 ? $table_fields : false;
		}
	}