<?php
	require 'view/header.php';
	require 'view/footer.php';
?>
<html>
<header>
	<meta charset="UTF-8">
	<meta author="Nicolas Chourot">
	<meta http-equiv="Content-Type" content="text/html;">
	<?php include 'stylesBundle.php'; ?>
</header>
<body >
	<div class="main">
		<?php echo $viewHead; ?>
		<div class="section">			
			<?php
				echo "<h4>$viewtitle</h4>";
				echo "<hr>";
				echo $viewContent;
			?>
		</div>
		<?php echo $viewFooter; ?>	
	</div>
	<?php include 'scriptsBundle.php'; ?>
	<script> <?php if (isset($viewScript)) include $viewScript; ?> </script>
</body>
</html>