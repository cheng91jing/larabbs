<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function make_excerpt($value, $length = 200)
{
    //从字符串总去除html和php标记 strip_tags
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', '', strip_tags($value)));
    return str_limit($excerpt, $length);
}