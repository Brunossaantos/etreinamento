<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="icon" href="imagens/favicon.ico" type="image/x-icon">
    <!-- Inclua os arquivos CSS e JavaScript do Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .card{
            border: 1px solid #115391;
        }
        .card-header{
            background-color: #115391;
            color: #ffffff;
        }
        .btn{
            width: 100%;
        }
        .logotipo{
            width: 300px;
            align-items: 50%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">        
        <div class="row justify-content-center">
            <div class="col-md-6">
            <img src="imagens/udLog.png" alt="" class="logotipo">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="post" action="src/actions/processar_login.php">
                            <div class="form-group">
                                <label for="username">Usuário</label>
                                <input type="text" class="form-control username" id="login" name="login" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="password">Senha</label>
                                <input type="password" class="form-control password" id="senha" name="senha" required>
                            </div>
                            <button type="submit" class="btn btn-success">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
