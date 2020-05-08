<?php
if(isset($_POST) && !empty($_POST)) {
    $buff = [
        'site' => $_POST['site'],
        'user' => $_POST['user'],
        'salt' => $_POST['salt'],
    ];
    $preHash = [];
    $hashString = '';
    unset($_POST);
    // Step 1: md5 all data
    foreach($buff as $val) {
        $preHash[] = md5($val);
    };
    // Step 2: concate all md5
    foreach($preHash as $md5) {
        $hashString .= $md5;
    }
    // Step 3: md5 result
    $token = md5($hashString);
    $args = [
        'token' => $token
    ];
    $isset = $db->search($args);
    if(isset($isset[0])) {
        echo json_encode([
            'result' => [
                'status' => 'true',
                'response' => $token
            ]
        ]);
    } else {
        echo json_encode([
            'result' => [
                'status' => 'false'
            ]
        ]);
    }
} else {
    exit(
        header("HTTP/1.1 415 Unsupported Media Type")
    );
}