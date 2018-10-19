<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    include 'model.php';
    
    $array = explode("/", $_SERVER['REQUEST_URI']);

    $request = file_get_contents("php://input"); //RECIBIR JSON PARA MODIFICAR/INSERTAR

    foreach ($array as $key => $value) { //VACIAR BLANCOS
        if(empty($value)) {
            unset($array[$key]);
        }
    }

    if( count($array) > 3 ) {
        $id = $array[count($array)];
        $entity = $array[count($array) - 1];
    } else {
        $entity = $array[count($array)];
    }

    $obj = new model;
    $obj->entity = $entity;

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if(isset($id)) {
                $data = $obj->get($id);
            } else {
                $data = $obj->get();
            }
   
            //array_pop($data);

            if(count($data)==0) {
                if(isset($id)) {
                    print_json(404, "Not Found", null);
                } else {
                    print_json(204, "Not Content", null);
                }
            } else {
                print_json(200, "OK", $data);
            }
        break;
        case 'POST':
            $obj->data=$_POST;
            if(isset($id)) {
                $data = $obj->post($id);
                if(count($data)==0) {
                    if(isset($id)) {
                        print_json(404, "Not Found", null);
                    } else {
                        print_json(204, "Not Content", null);
                    }
                } else {
                    print_json(200, "OK", $data);
                }
            } else {
                $data = $obj->post();
                if($data) {
                    if($obj->conn->lastInsertId() != 0) {
                        $data = $obj->get($obj->conn->lastInsertId());
                        if(count($data)==0) {
                            print_json(201, false, null);
                        } else {
                            array_pop($data);
                            print_json(201, "Created", $data);
                        }
                    } else {
                        print_json(201, false, null);
                    }
                } else {
                    print_json(201, false, null);
                }
            }
        break;
        case 'PUT':
            if(isset($id)) {
                $info = $obj->get($id);
                if(count($info)!=0) {
                    $obj->data = getPutInfo($request);
                    $data = $obj->put($id);
                    if($data) {
                        if(count($data)==0) {
                            print_json(200, false, null);
                        } else {
                            print_json(200, "OK", $data);
                        }
                    } else {
                        print_json(200, false, null);
                    }
                } else {
                    print_json(404, "Not Found", $info);
                }
            } else {
                print_json(405, "Method Not Allowed", null);
            }
        break;
        case 'DELETE':
            if(isset($id)) {
                $info = $obj->get($id);
                if(count($info)!=0) {
                    $data = $obj->delete($id);
                    if($data) {
                        if(count($data)==0) {
                            print_json(200, false, null);
                        } else {
                            print_json(200, "OK", $data);
                        }
                    } else {
                        print_json(200, false, null);
                    }
                } else {
                    print_json(404, "Not Found", $info);
                }
            } else {
                print_json(405, "Method Not Allowed", null);
            }
        break;
        default:
            print_json(405, "Method Not Allowed", null);
        break;
    }
    
    function getPutInfo($values){
        $info=array();
        $data=array();
        $first=explode("name=", $values);
        unset($first[0]);
        foreach($first as $value){
            $info[]=explode("\r\n", $value);
            $title=filter_var ( $info[0][0], FILTER_SANITIZE_EMAIL);
            $data[$title]=$info[0][2];
        }

        return $data;
    }

    function print_json($status, $mensaje, $data) {
        header("HTTP/1.1 $status $mensaje");
        header("Content-Type: application/json; charset=UTF-8");

        $response['statusCode'] = $status;
        $response['statusMessage'] = $mensaje;
        $response['data'] = $data;

        echo json_encode($response, JSON_PRETTY_PRINT);
    }
?>