<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false, // 是否携带 Cookie
    'allowedOrigins' => ['*'], // 允许的域名
    'allowedOriginsPatterns' => [], // 通过正则匹配允许的域名
    'allowedHeaders' => ['*'],// 允许的 Header
    'allowedMethods' => ['*'],// 允许的 HTTP 方法
    'exposedHeaders' => [],// 除了 6 个基本的头字段，额外允许的字段
    'maxAge' => 0,// 预检请求的有效期

];
