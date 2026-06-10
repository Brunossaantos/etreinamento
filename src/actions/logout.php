<?php
session_start();

// Destrua a sessão (logout)
session_destroy();

// Redirecione para a página de login (index.php)
header("Location: ../../index.php");
exit();