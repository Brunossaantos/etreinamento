<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de treinamentos</title>
    <link rel="icon" href="imagens/favicon.ico" type="image/x-icon">
    <!-- Inclua os arquivos CSS do Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .logotipo {
            height: 150px;
            background-color: #FFFFFF;
            margin: 5px;
            border-radius: 5px;
        }

        .list-group {
            border: 1px solid #115391;
        }

        h1 {
            background-color: #115391;
            color: #FFFFFF;
            border-radius: 5px;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center">Bem vindo ao E-Treinamento</h1>
                <div class="list-group">
                    <!-- Botões para gerenciar diferentes recursos -->
                    <a href="layout/gerenciarColaboradores.php" class="list-group-item list-group-item-action">Gerenciar
                        Colaboradores</a>
                    <a href="layout/gerenciarEmpresas.php" class="list-group-item list-group-item-action">Gerenciar
                        Empresas</a>
                    <a href="layout/gerenciarDepartamentos.php" class="list-group-item list-group-item-action">Gerenciar
                        Departamentos</a>
                    <a href="layout/gerenciarTreinamento.php" class="list-group-item list-group-item-action">Gerenciar
                        Treinamentos</a>
                    <a href="layout/gerenciarInstrutores.php" class="list-group-item list-group-item-action">Gerenciar
                        instrutores</a>
                    <a href="layout/gerenciarConta.php" class="list-group-item list-group-item-action">Gerenciar
                        minha conta</a>
                    <a href="layout/cadastrarUsuario.php" class="list-group-item list-group-item-action">cadastrar novo usuário</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclua os arquivos JavaScript do Bootstrap 4 (opcional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>