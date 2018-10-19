<?php
    include 'core/db.php';
    include 'core/api.php';
    include 'models/Users.php';
    include 'models/Events.php';

    class model extends db_model implements api {
        public $entity;
        public $data;
  
        function get($id = NULL) {
            switch($this->entity){
                case "users": 
                    if($id!=NULL){
                        return (new Users())->getById($this, $id);
                    }
                    
                    return (new Users())->getAll($this);
                case "events": 
                    if($id!=NULL){
                        return (new Events())->getById($this, $id);
                    }
                    
                    return (new Events())->getAll($this);
                default: return NULL;
            }
        }

        function post( $function = NULL ) {
            switch($this->entity){
                case "users": 
                    $user=new Users();
                    if($function == NULL){
                        return $user->insert($this, $this->data);
                    }
                    else{
                        switch($function){
                            case "login": return $user->login($this, $this->data);
                            default: return NULL;
                        }
                    }
                case "events": 
                    $event=new Events();
                    if($function == NULL){
                        return $event->insert($this, $this->data);
                    }
                    else{
                        switch($function){
                            default: return NULL;
                        }
                    }
                default: return NULL;
            }
        }

        function put($id) {
            switch($this->entity){
                case "users":
                    return (new Users())->update($this, $this->data, $id);
                case "events":
                    return (new Events())->update($this, $this->data, $id);
                default: return NULL;
            }
        }

        function delete($id) {
            switch($this->entity){
                case "users": 
                    return (new Users())->disable($this, $id);
                case "events":
                    return (new Events())->disable($this, $id);
                default: return NULL;
            }
        }
    }
?>