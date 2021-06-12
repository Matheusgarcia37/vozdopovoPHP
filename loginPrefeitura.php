<?php
include './functions.php';
$pdo = pdo_connect_mysql();
session_start();
//botão enviar
if (isset($_POST['btn-entrar'])) {
    $erros = array();
    if (empty($_POST['login'] || $_POST['senha'])) {
        $erros[] = "<li> O campo login/senha precisa ser preenchido</li>";
    } else {
        $stmt = $pdo->prepare("SELECT login FROM usuariosPrefeitura WHERE login = ?");
        $stmt->execute([$_POST['login']]);
        if ($stmt->rowCount() > 0) {
            $senha = md5($_POST['senha']);
            $stmt = $pdo->prepare("SELECT * FROM usuariosPrefeitura WHERE login = ? AND senha = ?");
            $stmt->execute([$_POST['login'], $senha]);
            if ($stmt->rowCount() == 1) {
                $dados = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['logado'] = true;
                $_SESSION['id_usuario'] = $dados['id'];
                header('Location: acessoPrefeitura.php');
            } else {
                $erros[] = "<li> Usuário e senha não conferem </li>";
            }
        } else {
            $erros[] = "<li> Usuário inexistente </li>";
        }
    }
}


?>
<?= template_header('Login Prefeitura') ?>

<body>
    <h1>Login Prefeitura</h1>
    <?php
    if (!empty($erros)) :
        foreach ($erros as $erro) :
            echo $erro;
        endforeach;
    endif;
    ?>
    <hr>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?> " method="POST">
        Login: <input type="text" name="login"><br>
        Senha: <input type="password" name="senha"><br>
        <button type="submit" name="btn-entrar">Entrar</button>
    </form>
</body>
<?= template_footer() ?>