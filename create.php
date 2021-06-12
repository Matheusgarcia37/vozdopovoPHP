<?php
include 'functions.php';

$pdo = pdo_connect_mysql();
// Output message variable
$msg = '';
// Check if POST data exists (user submitted the form)
if (isset($_POST['title'], $_POST['msg'])) {
    // Validation checks... make sure the POST data is not empty
    if (empty($_POST['title']) || empty($_POST['msg'])) {
        $msg = 'Por favor complete o formulario!';
    } else {
        //criando um id para o ticket
        $id = generateKey();
        //inserindo dados do ticket
        $stmt = $pdo->prepare('INSERT INTO tickets (id, title, msg) VALUES (?, ?, ?)');
        $stmt->execute([$id, $_POST['title'], $_POST['msg']]);
        $lastid = $id;//pegando o ultimo id da denuncia

        //upando arquivos
        $min = 0;
        $max = 50000;
        $fileCount = count($_FILES['file']['name']);
        for($i=0; $i<$fileCount; $i++){
            $fileName = rand($min, $max).$_FILES['file']['name'][$i];
            $stmt = $pdo->prepare('INSERT INTO arquivos (ticket_id, arquivo) VALUES (?, ?)');
            $stmt->execute([$lastid, $fileName]);
            move_uploaded_file($_FILES['file']['tmp_name'][$i], 'upload/'.$fileName);
        }
        $idTicket = $lastid;// transferindo id do ticket
        //inserindo dados do cidadao
        if(isset($_POST['nome'], $_POST['email'], $_POST['telefone'], $_POST['endereco'],)) {
            //verificando se a pessoa já esta cadastrada
            $email = $_POST['email'];
            $pegaEmail = $pdo->prepare("SELECT * FROM registrodecidadaos WHERE email= ?");
            $pegaEmail->execute([$email]);
            $count = $pegaEmail->rowCount();
            if( $count > 0){//Cidadão Cadastrado
                //pegar dados do cidadão já cadastrado
                $pegaIdCidadao = $pdo->prepare("SELECT * FROM registrodecidadaos WHERE email = ?");
                $pegaIdCidadao->execute([$email]);
                $idCidadao = $pegaIdCidadao->fetch(PDO::FETCH_ASSOC);

                //inserir id do cidadao no ticket
                $stmt = $pdo->prepare("UPDATE tickets SET cidadao_id = ? WHERE id = ?");
                $stmt->execute([$idCidadao['id'], $idTicket]);
                //Atualizar os dados do cidadão
                $stmt = $pdo->prepare("UPDATE registrodecidadaos SET nome = ?, telefone = ?, endereco = ? WHERE id = ?");
                $stmt->execute([$_POST['nome'], $_POST['telefone'], $_POST['endereco'], $idCidadao['id']]);
              }
              else{//Cidadao não Cadastrado
                //guardar informações do cidadao
                $stmt = $pdo->prepare('INSERT INTO registrodecidadaos (nome, email, telefone, endereco) VALUES (?, ?, ?, ?)');
                $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['telefone'], $_POST['endereco']]);
                $lastid = $pdo->lastInsertId(); //id do cidadão

                //atualzar ticket inserindo o id do cidadao
                $stmt = $pdo->prepare('UPDATE tickets SET cidadao_id = ? WHERE id = ?');
                $stmt->execute([$lastid, $idTicket]);
              }
              //enviando mensagem de sucesso e o protocolo, e já redirecionando para a denúncia
              echo  "<script>alert('Sua denúncia foi enviada com sucesso, seu protocolo é: $idTicket'); location.href=\"view.php?id=$idTicket\";</script>";
              //redirecionando para vizualizar o ticket ---mudar para index!
              /* header('Location: view.php?id='.$idTicket); */
              exit;
        }
        header('Location: index.php');
        // Redirect to the view ticket page, the user will see their created ticket on this page
    }
}
?>

<?= template_header('Create Ticket') ?>

<div class="content create">
	<h2>Criar Denúncia</h2>

    <input type="radio" name="tipoautor" value="anonimo" onclick='verificaraut(this.value)'>anonimo
    <input type="radio" name="tipoautor" value="identificado" onclick='verificaraut(this.value)'>identificado


    <?=template_Denuncia_ident()?>
    <?=template_Denuncia_anonimo()?>
</div>';

<?= template_footer() ?>

<script>
    function verificaraut(autor) {
        if(autor == 'identificado'){
            document.querySelector('#anonimo').setAttribute('style', 'display: none')
            document.querySelector('#identificado').setAttribute('style', 'display: flex')
        }if(autor=='anonimo'){
            document.querySelector('#identificado').setAttribute('style', 'display: none')
            document.querySelector('#anonimo').setAttribute('style', 'display: flex')
        }
    }

    function notificarAnonimo(){
        javascript:alert('Sua denúncia foi enviada com sucesso!');
    }

</script>