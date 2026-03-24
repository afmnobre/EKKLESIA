<?php

function base_path()
{
    static $base = null;

    if ($base === null) {
        $config = require __DIR__ . '/../../config/app.php';
        $base = rtrim($config['base_path'], '/');
    }

    return $base;
}

function url($path = '')
{
    return base_path() . '/' . ltrim($path, '/');
}

function asset($path = '')
{
    return base_path() . '/assets/' . ltrim($path, '/');
}

function full_url($path = '')
{
    static $baseUrl = null;

    if ($baseUrl === null) {
        $config = require __DIR__ . '/../../config/app.php';
        // Pegamos a base_url (ex: http://ekklesia.local) em vez do base_path
        $baseUrl = rtrim($config['base_url'], '/');
    }

    return $baseUrl . '/' . ltrim($path, '/');
}
