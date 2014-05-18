<!DOCTYPE html>
<html>
<head>
<title>{if "{$dynamic.title}" != "false"}{$dynamic.title}{else}{$app.title}{endif}</title>
<meta charset="UTF-8" />
<meta name="publisher" content="MagicPHP" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="MagicPHP, simple and fast like magic!">
<meta name="keywords" content="PHP, framework, simple, fast, magic, back-end, backend, web development">
<meta name="author" content="André Ferreira, Jonathan Lamim Antunes, Fabiano Monte, and MagicPHP contributors">
<meta name="google-site-verification" content="i2uH5OXJ2d9KQUNUrSAqO7xnFZESYEZf4xwFTDzaMJ8" />
<link rel="stylesheet" href="{$cache.css}" type="text/css" />
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0px">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{$route.root}">MagicPHP</a>
            </div>
            
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    {list:navbar}
                        {if $navbar["position"] == "left"}
                        <li><a href="{$route.root}{list.navbar.route}">{list.navbar.title}</a></li>
                        {endif}
                    {end}
                </ul>
                
                <ul class="nav navbar-nav navbar-right">
                    {list:navbar}
                        {if $navbar["position"] == "right"}
                        <li><a href="{$route.root}{list.navbar.route}">{list.navbar.title}</a></li>
                        {endif}
                    {end}
                    <!--<li><a href="http://blog.magicphp.org/" target="_blank">Blog</a></li>-->  
                    <!--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="{$route.root}modules/docs/shell/img/{$user.language}.png" /> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{$route.root}language/pt" title="Alterar idioma para Português"><img src="{$route.root}modules/docs/shell/img/pt.png" /> Português</a></li>
                            <li><a href="{$route.root}language/us" title="Change language to English"><img src="{$route.root}modules/docs/shell/img/us.png" /> English</a></li>
                        </ul>
                    </li>-->  
                    
                    {if "{$user.id}" != "false"}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">{$user.name} <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="{$route.root}logout"> Sair</a></li>
                            </ul>
                        </li>
                    {endif}
                </ul>
            </div>
        </div>
    </nav>
                        
    {$template}
                        
    <script src="{$cache.js}" type="text/javascript"></script>
</body>
</html>