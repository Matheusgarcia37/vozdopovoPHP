<?php
//conexão
include './functions.php';
$pdo = pdo_connect_mysql();
//sessão
session_start();
//verificação
if (!isset($_SESSION['logado'])) :
    header('Location: index.php');
endif;
//dados
$id = $_SESSION['id_usuario'];
$stmt = $pdo->prepare("SELECT * FROM usuarioadmin WHERE id = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);
//Desloga
if (isset($_POST['sair'])) {

    disconnect();
}
?>

<?= template_header('Página do Administrador') ?>
<div class="content">

    <body>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?> " method="POST">

            <h1>ACESSO ADMINISTRADOR!</h1>
            <h1>Olá <?php echo $dados['nome']; ?>. Seja bem vindo! </h1>
            <button type="submit" name="sair"> Sair</button>

        </form>
    </body>
</div>
<?= template_footer() ?>