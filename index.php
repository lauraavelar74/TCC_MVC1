<!DOCTYPE html>
<html>
<head>
    <title>Login Professor</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('./img/hello.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        input {
            display: block;
            margin: 10px auto;
            padding: 8px;
            width: 200px;
        }

        button {
            padding: 8px 16px;
            cursor: pointer;
        }

        a {
            display: block;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login de Professor</h2>
        <form method="post" action="login.php">
            CPF: <input type="text" name="cpf" required><br>
            Senha: <input type="password" name="senha" required><br>
            <button type="submit">Login</button>
        </form>
        <a href="./backend/registrar_professor.php">Não tem uma conta? Cadastre-se</a>
    </div>
</body>
</html>
