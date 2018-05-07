<?php

class API_ACCOUNTS {

    public function execute($request, $conn) {
        if ($request['method'] == 'GET' && count($request['args']) == 1) {
            return $this->get_items($conn);
        }
        else if ($request['method'] == 'GET' && count($request['args']) == 2) {
            return $this->get_item($conn, $request['args'][1]);
        }
        else if ($request['method'] == 'POST' && count($request['args']) == 1) {
            return $this->put_item($conn, $request['input']);
        }
        else {
            $response = array();
            $response['status'] = 404;
            $response['message'] = 'API path not found';
            return $response;
        }
    }
    
    public function get_items($conn) {
        $sql = "SELECT * FROM accounts";
        $result = $conn->query($sql);
        $temp = array();
        while ($row = $result->fetch_assoc()) {
            array_push($temp, $row);
        }
        
        $response = array();
        $response['status'] = 200;
        $response['data'] = $temp;
        return $response;
    }
    
    public function get_item($conn, $id) {
        $sql = "SELECT * FROM accounts WHERE id='" . $id . "'";
        $result = $conn->query($sql);
        
        $response = array();
        if ($result->num_rows > 0) {
            $response['status'] = 200;
            $response['data'] = $result->fetch_assoc();
        }
        else {
            $response['status'] = 403;
            $response['message'] = "Id '" . $id . "' not found";
        }
        
        return $response;
    }
    
    public function put_item($conn, $input) {
        $isOkId = true;
        $isOkName = true;
        $response = array();
        if (!array_key_exists('id', $input)) {
            $isOkId = false;
        }
        if (!array_key_exists('name', $input)) {
            $isOkName = false;
        }
        
        if ($isOkId && $isOkName) {
            $sql = "INSERT INTO accounts VALUES ('" . $input['id'] . "', '" . $input['name'] . "', 0)";
            
            if ($conn->query($sql) === TRUE) {
                $response['status'] = 200;
                $response['message'] = 'Insert success';
            } else {
                $response['status'] = 503;
                $response['message'] = $conn->error;
            }
        }
        else {
            $msg = "Missing parameters: ";
            if (!$isOkId) {
                $msg = $msg . 'id';
            }
            else if (!$isOkName) {
                $msg = $msg . 'name';
            }
            $response['status'] = 403;
            $response['message'] = $msg;
        }
        
        return $response;
    }
}
