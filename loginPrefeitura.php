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
    <div class="login">
        <div class="contentLogin">
            <div class="lottie-esquerda">
                <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_yupefrh2.json" background="transparent" speed="1" style="width: 950px; height: 950px;" loop autoplay></lottie-player>
            </div>
            <div class="login-direita">
                <h2>Voz do Povo</h2>
                <h1>Ola, seja bem vindo(a)!</h1>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?> " method="POST">
                    <label for="login"></label> <input placeholder="Login" type="text" name="login"><br>
                    <div class="senha"><label for="senha"></label> <input placeholder="Senha" type="password" name="senha"><br></div>

                    <button class="btn-login" type="submit" name="btn-entrar">Entrar</button>
                </form>
                <div class="erros">
                    <?php
                    if (!empty($erros)) :
                        foreach ($erros as $erro) :
                            echo $erro;
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>

        </div>
    </div>


</body>
<?= template_footer() ?>