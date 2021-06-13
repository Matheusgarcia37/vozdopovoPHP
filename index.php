<?php
include './functions.php';
// Connect to MySQL using the below function
$pdo = pdo_connect_mysql();
// MySQL query that retrieves  all the tickets from the databse
$stmt = $pdo->prepare('SELECT * FROM tickets ORDER BY created DESC');
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= template_header('Voz Do Povo') ?>

<div class="content home">
	<div class="button-buscar"><label for="id"></label>
		<input class="input-buscar" type="text" name="id" id="protocolo" placeholder="Digite aqui seu protocolo">
		<input name="submit" type="submit" value="Buscar" class="btn-buscar">
	</div>
	<div class="lottie">
		<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
		<lottie-player src="https://assets8.lottiefiles.com/packages/lf20_pivt1poq.json" background="transparent" speed="1" style="width: 550px; height: 550px;" loop autoplay></lottie-player>
	</div>
	<h2>Crie ou acompanhe suas denuncias!</h2>

	<div class="btns">


		<a href="create.php" class="btn-create">Criar denúncia</a>
		<div class="buscar-denuncia">
			<form action="view.php" method="get">

			</form>
		</div>
	</div>

</div>

<?= template_footer() ?>