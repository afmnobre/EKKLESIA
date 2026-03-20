<?php

namespace App\Controllers;

use App\Models\Usuario;

class AuthController
{
    public function login()
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function autenticar()
    {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['usuario_senha'])) {

            $_SESSION['usuario_id'] = $usuario['usuario_id'];
            $_SESSION['usuario_nome'] = $usuario['usuario_nome'];
            $_SESSION['usuario_igreja_id'] = $usuario['usuario_igreja_id'];

            header("Location: " . url('dashboard'));
            exit;

        } else {
            $_SESSION['erro'] = "Email ou senha inválidos";
            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        logout();
    }
}

