<?php

class API_TRANSFER {

    public function execute($request, $conn) {
        if ($request['method'] == 'POST' && count($request['args']) == 1) {
            return $this->put_item($conn, $request['input']);
        }
        else {
            $response = array();
            $response['status'] = 404;
            $response['message'] = 'API path not found';
            return $response;
        }
    }
       
    public function put_item($conn, $input) {
        $isOkFrom = true;
        $isOkTo = true;
        $isOkAmount = true;
        $response = array();
        if (!array_key_exists('from', $input)) {
            $isOkFrom = false;
        }
        if (!array_key_exists('to', $input)) {
            $isOkTo = false;
        }
        if (!array_key_exists('amount', $input)) {
            $isOkAmount = false;
        }
        
        if ($isOkFrom && $isOkTo && $isOkAmount) {
        
            $balanceFrom = null;
            $balanceTo = null;
            $sql = "SELECT * FROM accounts WHERE id='" . $input['from'] . "'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $balanceFrom = ($result->fetch_assoc())['balance'];
            }
            $sql = "SELECT * FROM accounts WHERE id='" . $input['to'] . "'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $balanceTo = ($result->fetch_assoc())['balance'];
            }
            
            if ($balanceFrom == null || $balanceTo == null) {
                $response['status'] = 403;
                $response['message'] = 'Id not valid';
            }
            else if ($balanceFrom < $input['amount']) {
                $response['status'] = 403;
                $response['message'] = 'Balance is not enough';
            }
            else {
                $sql = "UPDATE accounts SET balance = IF(id='" . $input['from'] . "', " . ($balanceFrom - $input['amount']) . ", " . ($balanceTo + $input['amount']) . ") WHERE id in ('" . $input['from'] . "', '" . $input['to'] . "')";
                if ($conn->query($sql) === TRUE) {
                    $response['status'] = 200;
                    $response['message'] = 'Transfer success';
                } else {
                    $response['status'] = 503;
                    $response['message'] = $conn->error;
                }
            }
            
        }
        else {
            $msg = "Missing parameters: ";
            if (!$isOkFrom) {
                $msg = $msg . 'from';
            }
            else if (!$isOkTo) {
                $msg = $msg . 'to';
            }
            else if (!$isOkAmount) {
                $msg = $msg . 'amount';
            }
            $response['status'] = 403;
            $response['message'] = $msg;
        }
        
        return $response;
    }
}
