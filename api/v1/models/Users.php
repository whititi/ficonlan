<?php
    class Users{
        public $dni;
        public $nick;
        public $email;
        public $pass;
        public $name;
        public $lastname1;
        public $lastname2;
        public $image;
        public $date_birth;
        public $size;
        public $date_reg;
        public $rol;

        function getAll($db){
            try{
                $qry=$db->conn->prepare("SELECT * FROM users");
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            return $qry->fetchAll(PDO::FETCH_ASSOC);
        }

        function getById($db, $id){
            try{
                $qry=$db->conn->prepare("SELECT * FROM users WHERE dni LIKE :id");
                $qry->bindValue("id", $id);
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            return $qry->fetch(PDO::FETCH_ASSOC);
        }

        function insert($db, $data){
            try{
                $qry=$db->conn->prepare("INSERT INTO 
                                            users(dni, nick, email, pass, name, lastname_1, size) 
                                        VALUES 
                                            (:dni, :nick, :email, :pass, :name, :lastname1, :size)");
                $qry->bindValue("dni", $data["dni"]);
                $qry->bindValue("nick", $data["nick"]);
                $qry->bindValue("email", $data["email"]);
                $qry->bindValue("pass", password_hash($data["pass"], PASSWORD_ARGON2I));
                $qry->bindValue("name", $data["name"]);
                $qry->bindValue("lastname1", $data["lastname1"]);
                $qry->bindValue("size", $data["size"]);
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            return $qry;
        }

        function update($db, $data, $id){
            $user=$this->getById($db, $id);
            try{
                $qry=$db->conn->prepare("UPDATE users
                                        SET nick=:nick, email=:email, pass=:pass, name=:name, lastname_1=:lastname1, size=:size, date_birth=:date_birth
                                        WHERE dni=:dni");
                                        
                $qry->bindValue("dni", $id);
                $qry->bindValue("nick", isset($data["nick"]) ? $data["nick"] : $user["nick"]);
                $qry->bindValue("email", isset($data["email"]) ? $data["email"] : $user["email"]);
                $qry->bindValue("pass", isset($data["pass"]) ? password_hash($data["pass"], PASSWORD_ARGON2I)  : $user["pass"]);
                $qry->bindValue("name", isset($data["name"]) ? $data["name"] : $user["name"]);
                $qry->bindValue("lastname1", isset($data["lastname1"]) ? $data["lastname1"] : $user["lastname1"]);
                $qry->bindValue("size", isset($data["size"]) ? $data["size"] : $user["size"]);
                $qry->bindValue("date_birth", isset($data["date_birth"]) ? $data["date_birth"] : $user["date_birth"]);
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            return $this->getById($db, $id);
        }

        function login($db, $data){
            try{
                $qry=$db->conn->prepare("SELECT * FROM users WHERE nick LIKE :nick");
                $qry->bindValue("nick", $data["nick"]);
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            $user=$qry->fetch();
            return password_verify($data['pass'], $user['pass']);
        }

        function disable($db, $id){
            $user=$this->getById($db, $id);
            try{
                $qry=$db->conn->prepare("DELETE FROM users WHERE dni=:dni");
                $qry->bindValue("dni", $user["dni"]);
                $qry->execute();
            }
            catch(Exception $e){
                return false;
            }

            return true;
        }
    }
?>