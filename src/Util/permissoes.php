<?php

function isAdmin()
{
    return isset($_SESSION["perfil"]) && (int) $_SESSION["perfil"] === 1;
}

function isUsuario()
{
    return isset($_SESSION["perfil"]) && (int) $_SESSION["perfil"] === 2;
}

function bloquearSeNaoAdmin()
{
    if (!isAdmin()) {
        header("Location: ../index2.php");
        exit();
    }
}