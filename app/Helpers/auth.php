<?php

function usuarioLogado()
{
    return isset($_SESSION['usuario_id']);
}

function exigirLogin()
{
    if (!usuarioLogado()) {
        header('Location: ' . url('login'));
        exit;
    }
}

function logout()
{
    session_destroy();
    header('Location: ' . url('login'));
    exit;
}

