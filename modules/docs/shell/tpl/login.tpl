<!DOCTYPE html>
<html>
<head>
<title>{$title}</title>
<meta charset="UTF-8" />
<meta name="publisher" content="MagicPHP" />
<link rel="stylesheet" href="{$cache.css}" type="text/css" />
</head>
<body>
    <div class="maBackgroundLoginBox"></div>
    <div class="maLoginBox">
        <h1>MagicPHP</h1>
        <form action="{$route.root}login" method="POST">
            <input name="login_user" id="login_user" type="text" placeholder="Usuário" />
            <br/>
            <input name="login_pwd" id="login_pwd" type="password" placeholder="Senha" />
            <input type="submit" value="Entrar" />
        </form>
    </div>
</body>
</html>