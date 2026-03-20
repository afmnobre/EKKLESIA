<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public static function handle()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . url('login'));
            exit;
        }
    }
}

