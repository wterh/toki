<?php
$buff = [
    'intro' => [
        'title' => 'Toki Documentation',
        'description' => 'Toki - microservice for authorization on a client web-application by api request'
    ],
    'methods' => [
        'signin' => [
            'title' => 'SignIn by API',
            'description' => 'Each authorization is an API request. Each entry request expects a salt parameter. This parameter is used as a key for verification. To simplify authorization, we have a small module that independently receives this data when called. Calculation for access to the browser. Cli mode is not supported',
            'link' => "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/module/"
        ],
        'signup' => [
            'title' => 'SignUp by API',
            'description' => 'Registration expects the following parameters to enter: site, user and salt in POST method. More examples you might see in our small module',
        ]
    ],
    'algorimic' => "See scheme this: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/schema/",
    'outro' => [
        'title' => 'Contact us',
        'description' => 'If you might an any ideas, please contact me. Project license MIT, see our github',
        'telegram' => 'https://t.me/wterh',
        'vkontakte' => 'https://vk.com/wterh',
        'github' => 'https://github.com/wterh/toki'
    ]
];
header('Content-type: text/json');
echo json_encode($buff);