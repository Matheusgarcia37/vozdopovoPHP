<?php
function pdo_connect_mysql()
{
    // Update the details below with your MySQL details
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'vozdopovo';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to database!');
    }
}
//Encerrando a sessão
function disconnect()
{
    if (isset($_POST['sair'])) {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
    }
}

//função para gerar key
function generateKey()
{
    $str = uniqid('', true);
    return $str;
}


function template_header($title)
{
    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>$title</title>
            <link href="style.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        </head>
        <body>
        <nav class="navtop">
            <div>
                <h1>Voz do Povo</h1>
                <a href="/vozdopovoPHP/loginPrefeitura.php"><i class="fas fa-sign-in-alt"></i>Acesso Restrito</a>
            </div>
        </nav>
    EOT;
}


//Tipo da denúncia
function template_Denuncia_ident()
{
    echo '
    <form action="create.php" method="post" style="display: none" id="identificado" enctype="multipart/form-data">
        <label for="title">Titulo da denúncia</label>
        <input type="text" name="title" placeholder="Titulo da denuncia" id="title" required>
        
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" placeholder="Digite seu nome" required>
        
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="meuemail@example.com" id="email" required>
        
        <label for="telefone">Telefone</label>
        <input type="tel" name="telefone" id="telefone" placeholder="Digite seu telefone" required>

        <label for="endereco">Endereço</label>
        <input type="text" name="endereco" id="endereco" placeholder="Digite Seu endereço" required>
        
        <label for="msg">Mensagem</label>
        <textarea name="msg" placeholder="Detalhe sua denúncia aqui" id="msg" required></textarea>
        
        <input type="file" name="file[]" id="file" multiple>
        <input type="submit" value="Create" onclick="notificarIdentificado(<?php $idTicket?>)">
    </form>

    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    ';
}

function template_Denuncia_anonimo()
{
    echo '
    <form action="create.php" method="post" style="display: none" id="anonimo" enctype="multipart/form-data">
        <label for="title">Titulo da denúncia</label>
        <input type="text" name="title" placeholder="Titulo da denuncia" id="title" required>
        
        <label for="msg">Mensagem</label>
        <textarea name="msg" placeholder="Detalhe sua denúncia aqui" id="msg" required></textarea>
        
        <input type="file" name="file[]" id="file" multiple>
        <input type="submit" value="Create">
    </form>

    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    ';
}




// Template footer
function template_footer()
{
    echo <<<EOT
    </body>
</html>
    <footer class="footer">

    <p>© 2021 - Voz do Povo - <a href="./loginAdmin.php">&nbsp;Todos os direitos resevados.</a>
    </p>
    </footer>
EOT;
}
