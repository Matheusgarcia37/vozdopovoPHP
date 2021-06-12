<?php
include 'functions.php';
// Connect to MySQL using the below function
$pdo = pdo_connect_mysql();
session_start();
//verificação
if (!isset($_SESSION['logado'])) :
    header('Location: loginPrefeitura.php');
endif;
// Check if the ID param in the URL exists
if (!isset($_GET['id'])) {
    exit('Essa denúncia não existe!');
}


//Dados ticket
$stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = ?');
$stmt->execute([$_GET['id']]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
//Dados da prefeitura
$stmt = $pdo->prepare("SELECT * FROM usuariosPrefeitura WHERE id = ?");
$stmt->execute([$_SESSION['id_usuario']]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);
//Dados dos arquivos
$stmt = $pdo->prepare("SELECT * FROM arquivos WHERE ticket_id = ?");
$stmt->execute([$_GET['id']]);
$arquivos = $stmt->fetchALL(PDO::FETCH_ASSOC);

// Check if ticket exists
if (!$ticket) {
    exit('Essa denúncia não existe!');
}

if (isset($_GET['status']) && in_array($_GET['status'], array('aberto', 'fechado', 'resolvido'))) {
    $stmt = $pdo->prepare('UPDATE tickets SET status = ? WHERE id = ?');
    $stmt->execute([$_GET['status'], $_GET['id']]);
    header('Location: viewPrefeitura.php?id=' . $_GET['id']);
    exit;
}

// Check if the comment form has been submitted
if (isset($_POST['msg']) && !empty($_POST['msg'])) {
    // Insert the new comment into the "tickets_comments" table
    $stmt = $pdo->prepare('INSERT INTO tickets_comments (ticket_id, msg, author) VALUES (?, ?, ?)');
    $stmt->execute([$_GET['id'], $_POST['msg'], $dados['nomePrefeitura']]);
    header('Location: viewPrefeitura.php?id=' . $_GET['id']);
    exit;
}
//pegaondo mensagens
$stmt = $pdo->prepare('SELECT * FROM tickets_comments WHERE ticket_id = ? ORDER BY created');
$stmt->execute([$_GET['id']]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?= template_header('Ticket') ?>
<form action="<?php disconnect() ?> " method="POST">
    <button type="submit" name="sair"> Sair</button>
</form>
<div class="content view">

    <h2><?= htmlspecialchars($ticket['title'], ENT_QUOTES) ?> <span class="<?= $ticket['status'] ?>">(<?= $ticket['status'] ?>)</span></h2>

    <div class="ticket">
        <p class="created"><?= date('F dS, G:ia', strtotime($ticket['created'])) ?></p>
        <p class="msg"><?= nl2br(htmlspecialchars($ticket['msg'], ENT_QUOTES)) ?></p>
    </div>

    <div class="btns">
        <a href="viewPrefeitura.php?id=<?= $_GET['id'] ?>&status=fechado" class="btn red">Fechado</a>
        <a href="viewPrefeitura.php?id=<?= $_GET['id'] ?>&status=resolvido" class="btn">Resolvido</a>
    </div>

    <div class="comments">
        <?php foreach ($comments as $comment) : ?>
            <div class="comment">
                <div>
                    <i class="fas fa-comment fa-2x"></i>
                </div>
                <p>
                    <span>
                        <strong> <?= htmlspecialchars($comment['author'], ENT_QUOTES) ?></strong>
                        <?= date('F dS, G:ia', strtotime($comment['created'])) ?></span>
                    <?= nl2br(htmlspecialchars($comment['msg'], ENT_QUOTES)) ?>
                </p>
            </div>
        <?php endforeach; ?>
        <!-- mostrando as imagens -->
        <div>
            <?php $cont = 1 ?>
            <?php foreach ($arquivos as $arquivo) : ?>
                <?php
                $nomeDiretorio = $arquivo['arquivo'];
                ?>
                <a href="<?php echo "./upload/$nomeDiretorio" ?>" target="_blank"><?php echo "arquivo$cont";
                                                                                    $cont++ ?></a>
            <?php endforeach; ?>
        </div>


        <form action="" method="post">
            <textarea name="msg" placeholder="Digite aqui sua mensagem!"></textarea>
            <input type="submit" value="Enviar mensagem">
        </form>
    </div>

</div>

<?= template_footer() ?>