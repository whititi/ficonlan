<?php
    class Events{
        public $id;
        public $name;
        public $description;
        public $image;
        public $date_start;
        public $date_end;
        public $edition;
        public $enable;
        public $date_reg;

        function getAll($db){
            try{
                $qry=$db->conn->prepare("SELECT * FROM events");
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            return $qry->fetchAll(PDO::FETCH_ASSOC);
        }

        function getById($db, $id){
            try{
                $qry=$db->conn->prepare("SELECT * FROM events WHERE id LIKE :id");
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
                                            events(name, description, image, date_start, date_end, edition, enable) 
                                        VALUES 
                                            (:name, :description, :image, :date_start, :date_end, :edition, :enable)");
                $qry->bindValue("name", $data["name"]);
                $qry->bindValue("description", $data["description"]);
                $qry->bindValue("image", $data["image"]);
                $qry->bindValue("date_start", $data["date_start"]);
                $qry->bindValue("date_end", $data["date_end"]);
                $qry->bindValue("edition", $data["edition"]);
                $qry->bindValue("enable", 1);
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            return $qry;
        }

        function update($db, $data, $id){
            $event=$this->getById($db, $id);
            try{
                $qry=$db->conn->prepare("UPDATE events
                                        SET name=:name, description=:description, image=:image, date_start=:date_start, date_end=:date_end, edition=:edition, enable=:enable
                                        WHERE id=:id");
                                        
                $qry->bindValue("id", $id);
                $qry->bindValue("name", isset($data["name"]) ? $data["name"] : $event["name"]);
                $qry->bindValue("description", isset($data["description"]) ? $data["description"] : $event["description"]);
                $qry->bindValue("image", isset($data["image"]) ? $data["image"] : $event["image"]);
                $qry->bindValue("date_start", isset($data["date_start"]) ? $data["date_start"]  : $event["date_start"]);
                $qry->bindValue("date_end", isset($data["date_end"]) ? $data["date_end"] : $event["date_end"]);
                $qry->bindValue("edition", isset($data["edition"]) ? $data["edition"] : $event["edition"]);
                $qry->bindValue("enable", isset($data["enable"]) ? $data["enable"] : $event["enable"]);
                $qry->execute();
            }
            catch(Exception $e){
                return $e;
            }

            return $this->getById($db, $id);
        }

        function disable($db, $id){
            $user=$this->getById($db, $id);
            try{
                $qry=$db->conn->prepare("DELETE FROM events WHERE id=:id");
                $qry->bindValue("id", $user["id"]);
                $qry->execute();
            }
            catch(Exception $e){
                return false;
            }

            return true;
        }
    }
?>