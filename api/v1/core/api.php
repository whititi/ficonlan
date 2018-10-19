<?php 
    interface api{
        public function get();
        public function post();
        public function put($id);
        public function delete($id);
    }
?>