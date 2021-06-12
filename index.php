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

	<h2>Denuncias</h2>

	<p>Bem vindo a página principal, você pode buscar por sua denúncia ou criar uma.</p>

	<div class="btns">
		<a href="create.php" class="btn">Criar denúncia</a>
		<div class="buscar-denuncia">
			<form action="view.php" method="get">
				<label for="id">Protocolo:</label>
				<input type="text" name="id" id="protocolo" placeholder="Digite aqui seu protocolo">
				<input name="submit" type="submit" value="Buscar" class="btn">
			</form>
		</div>
	</div>


	<div class="ACESSOS">
		<a href="./loginAdmin.php">Login Admin</a></p>
		<a href="./loginPrefeitura.php">Login Prefeitura</a></p>
	</div>

</div>

<?= template_footer() ?>