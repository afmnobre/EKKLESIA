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
        // AJUSTE: Inicia a sessão antes de ler ou gravar dados nela
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['usuario_senha'])) {
            // 1. Buscamos TODOS os perfis vinculados a este ID de usuário
            $perfis = $usuarioModel->buscarPerfis($usuario['usuario_id']);

            $_SESSION['usuario_id'] = $usuario['usuario_id'];
            $_SESSION['usuario_nome'] = $usuario['usuario_nome'];
            $_SESSION['usuario_igreja_id'] = $usuario['usuario_igreja_id'];

            // 2. Salvamos a lista de perfis na sessão (ex: ['Admin', 'Tesoureiro'])
            $_SESSION['usuario_perfis'] = $perfis;

            // 3. Mantemos este apenas para compatibilidade
            $_SESSION['usuario_perfil'] = !empty($perfis) ? $perfis[0] : 'Visitante';

            header("Location: " . url('dashboard'));
            exit;
        } else {
            $_SESSION['erro'] = "Email ou senha inválidos";
            header('Location: ' . url('login'));
            exit;
        }
    }

    public function logout()
    {
        logout();
    }
}
