<?php
    include 'config.php';
    
    class db_model{
        public $conn;

        function __construct() {
            try {
                $this->conn = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPWD);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                return "Connection failed: " . $e->getMessage();
                die;
            }
        }

        function get_query($sql, $entity) {
            $qry=$this->conn->prepare("SELECT * FROM ?");
            $qry->bindValue(1, $entity, PDO::PARAM_STR);
            try{
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            return $qry->fetch();
        }

        function set_query($sql) {
            $result = $this->conn->query($sql);
            return $result;
        }
    }
?>