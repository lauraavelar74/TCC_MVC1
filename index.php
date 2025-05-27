<!DOCTYPE html>
<html>
<head>
    <title>Login Professor</title>
    <style>
        body {
            background-image: url('./img/hello.jpg');
        }
    </style>
</head>
<body>
<h2>Login de Professor</h2>
    <form method="post" action="login.php">
            CPF: <input type="text" name="cpf" required><br>
         Senha: <input type="password" name="senha" required><br>
       <button type="submit">Login</button >
    </form>
    
    <a href="./backend/registrar_professor.php">Não tem uma conta? Cadastre-se</a>
</body>
</html>