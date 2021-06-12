<?php
include 'functions.php';
// Connect to MySQL using the below function
$pdo = pdo_connect_mysql();
// Check if the ID param in the URL exists
if (!isset($_GET['id'])) {
    exit('Essa denúncia não existe!');
}
// Buscando tickets
$stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = ?');
$stmt->execute([ $_GET['id'] ]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
// Buscando dados do autor
if(isset($ticket['cidadao_id'])){//verificando se existe autor
    $stmt = $pdo->prepare("SELECT * FROM registrodecidadaos WHERE id = ?");
    $stmt->execute([$ticket['cidadao_id']]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check if ticket exists
if (!$ticket) {
    exit('Essa denúncia não existe!');
}

if (isset($_GET['status']) && in_array($_GET['status'], array('aberto', 'fechado', 'resolvido'))) {
    $stmt = $pdo->prepare('UPDATE tickets SET status = ? WHERE id = ?');
    $stmt->execute([ $_GET['status'], $_GET['id'] ]);
    header('Location: view.php?id=' . $_GET['id']);
    exit;
}

// Check if the comment form has been submitted
if (isset($_POST['msg']) && !empty($_POST['msg'])) {
    // Insert the new comment into the "tickets_comments" table
    $stmt = $pdo->prepare('INSERT INTO tickets_comments (ticket_id, msg, author) VALUES (?, ?, ?)');
    $stmt->execute([ $_GET['id'], $_POST['msg'], $dados['nome'] ]);
    header('Location: view.php?id=' . $_GET['id']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM tickets_comments WHERE ticket_id = ? ORDER BY created');
$stmt->execute([ $_GET['id'] ]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Ticket')?>

<div class="content view">

	<h2><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?> <span class="<?=$ticket['status']?>">(<?=$ticket['status']?>)</span></h2>

    <div class="ticket">
        <p class="created"><?=date('F dS, G:ia', strtotime($ticket['created']))?></p>
        <p class="msg"><?=nl2br(htmlspecialchars($ticket['msg'], ENT_QUOTES))?></p>
    </div>

    <div class="btns">
        <a href="view.php?id=<?=$_GET['id']?>&status=fechado" class="btn red">Fechado</a>
        <a href="view.php?id=<?=$_GET['id']?>&status=resolvido" class="btn">Resolvido</a>
    </div>

    <div class="comments">
        <?php foreach($comments as $comment): ?>
        <div class="comment">
            <div>
                <i class="fas fa-comment fa-2x"></i>
            </div>
            <p>
                <span>
                <strong> <?=htmlspecialchars($comment['author'], ENT_QUOTES)?></strong>
                <?=date('F dS, G:ia', strtotime($comment['created']))?></span>
                <?=nl2br(htmlspecialchars($comment['msg'], ENT_QUOTES))?>
            </p>
        </div>
        <?php endforeach; ?>
        <form action="" method="post">
            <textarea name="msg" placeholder="Digite aqui sua mensagem!"></textarea>
            <input type="submit" value="Enviar mensagem">
        </form>
    </div>

</div>

<?=template_footer()?>