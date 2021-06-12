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

//dados da prefeitura
$id = $_SESSION['id_usuario'];
$stmt = $pdo->prepare("SELECT * FROM usuariosPrefeitura WHERE id = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

//dados do ticket identificado
$stmt = $pdo->prepare('SELECT * FROM tickets WHERE cidadao_id IS NOT NULL ORDER BY created DESC');
$stmt->execute();
$tickets_identificado = $stmt->fetchAll(PDO::FETCH_ASSOC);

//dados do ticket anonimo
$stmt = $pdo->prepare('SELECT * FROM tickets WHERE cidadao_id IS NULL ORDER BY created DESC');
$stmt->execute();
$tickets_anonimo = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<?= template_header('Página Prefeitura') ?>

<div class="content home">

    <body>
        <h1>ACESSO PREFEITURA!</h1>
        <h1>Olá, <?php echo $dados['nomePrefeitura']; ?>. Seja bem vindo! </h1>
        <form action="<?php disconnect() ?> " method="POST">
            <button type="submit" name="sair"> Sair</button>
        </form>
        <div class="btns">
            <button class="btn" value="btn_identificado" name="btn_identificado" onclick="tipoDeConsulta(this.value)">Denúncia identificada</button>
            <button class="btn" value="btn_anonimo" name="btn_anonimo" onclick="tipoDeConsulta(this.value)">Denúncia anônima</button>          
        </div>


        <div class="tickets-list" id="tickets-list-identificado" style="display: none">
            <?php foreach ($tickets_identificado as $ticket) : ?>
                <a href="viewPrefeitura.php?id=<?= $ticket['id'] ?>" class="ticket">
                    <span class="con">
                        <?php if ($ticket['status'] == 'aberto') : ?>
                            <i class="far fa-clock fa-2x"></i>
                        <?php elseif ($ticket['status'] == 'resolvido') : ?>
                            <i class="fas fa-check fa-2x"></i>
                        <?php elseif ($ticket['status'] == 'fechado') : ?>
                            <i class="fas fa-times fa-2x"></i>
                        <?php endif; ?>
                    </span>
                    <span class="con">
                        <span class="title"><?= htmlspecialchars($ticket['title'], ENT_QUOTES) ?></span>
                        <span class="msg"><?= htmlspecialchars($ticket['msg'], ENT_QUOTES) ?></span>
                    </span>
                    <span class="con created"><?= date('F dS, G:ia', strtotime($ticket['created'])) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="tickets-list" id="tickets-list-anonimo" style="display: none">
            <?php foreach ($tickets_anonimo as $ticket) : ?>
                <a href="viewPrefeitura.php?id=<?= $ticket['id'] ?>" class="ticket">
                    <span class="con">
                        <?php if ($ticket['status'] == 'aberto') : ?>
                            <i class="far fa-clock fa-2x"></i>
                        <?php elseif ($ticket['status'] == 'resolvido') : ?>
                            <i class="fas fa-check fa-2x"></i>
                        <?php elseif ($ticket['status'] == 'fechado') : ?>
                            <i class="fas fa-times fa-2x"></i>
                        <?php endif; ?>
                    </span>
                    <span class="con">
                        <span class="title"><?= htmlspecialchars($ticket['title'], ENT_QUOTES) ?></span>
                        <span class="msg"><?= htmlspecialchars($ticket['msg'], ENT_QUOTES) ?></span>
                    </span>
                    <span class="con created"><?= date('F dS, G:ia', strtotime($ticket['created'])) ?></span>
                </a>
            <?php endforeach; ?>
        </div>


    </body>
</div>
<?= template_footer() ?>

<script>
	function tipoDeConsulta(tipo) {
        if(tipo == 'btn_identificado'){
            document.querySelector('#tickets-list-anonimo').setAttribute('style', 'display: none')
            document.querySelector('#tickets-list-identificado').setAttribute('style', 'display: flex')
        }if(tipo == 'btn_anonimo'){
            document.querySelector('#tickets-list-identificado').setAttribute('style', 'display: none')
            document.querySelector('#tickets-list-anonimo').setAttribute('style', 'display: flex')
        }
    }
</script>