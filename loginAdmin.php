<?php
include './functions.php';
$pdo = pdo_connect_mysql();
session_start();
//botão enviar
if(isset($_POST['btn-entrar'])){
    $erros = array();
    if(empty($_POST['login'] || $_POST['senha'])){
        $erros[] = "<li> O campo login/senha precisa ser preenchido</li>";
    }else{
        $stmt = $pdo->prepare("SELECT login FROM usuarioadmin WHERE login = ?");
        $stmt->execute([$_POST['login']]);
        if($stmt->rowCount() > 0){
            $senha = md5($_POST['senha']);
            $stmt = $pdo->prepare("SELECT * FROM usuarioadmin WHERE login = ? AND senha = ?");
            $stmt->execute([$_POST['login'],$senha]);
            if($stmt->rowCount() == 1){
                $dados = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['logado']= true;
                $_SESSION['id_usuario']= $dados['id'];
                header('Location: acessoAdmin.php');
            }else {
                $erros[] = "<li> Usuário Administrador não confere </li>";
            }
        }
        else{
            $erros[] = "<li> Usuário Administrador inexistente </li>";
        }   
    }
}


?>

<html>
<head>
<title>Login Administrador</title>
<meta charset="utf-8">
</head>
<body>
    <h1>Login Administrador</h1>
<?php
    if(!empty($erros)):
        foreach($erros as $erro):
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
</html>