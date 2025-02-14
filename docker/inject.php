<?php
session_start();
global $result;
global $valid_token;
$result = 2;
// If god mode is active then session token is what you give through csrf
$_SESSION["token"] = ((isset($_POST["god"]) && isset($_POST["csrf"])) ? $_POST["csrf"] : $_SESSION["token"]);

$valid_token = false;

	if (isset($_POST["csrf"]) && $_POST["csrf"] == $_SESSION["token"] ) {
		$_SESSION["token"] = md5(uniqid(mt_rand(), true));
		$valid_token = true;
		$conn = new mysqli("database", "passbolt", "P4ssb0lt", "passboltdb");
		if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
		}


		$query = "SELECT * FROM shop WHERE article = ";
		$query = $query . $_POST["action"];
		$result = $conn->query($query) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error($conn), E_USER_ERROR);
		
	}
	else{
		header('Location: /error.php', true, 302);
		exit();
	}
    $_SESSION["token"] = md5(uniqid(mt_rand(), true));
?>

<html>
	<head>
		<title>CSRF Request CTF</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" integrity="sha512-8bHTC73gkZ7rZ7vpqUQThUDhqcNFyYi2xgDgPDHc+GXVGHXq+xPjynxIopALmOPqzo9JZj0k6OqqewdGO3EsrQ==" crossorigin="anonymous" />
	</head>
	<body>
		<h3 class="ui dividing header">
			CSRF Token bypass using SQLMap
		</h3>
		<div class="ui grid">
			<div class="three column row">
				<div class="column">
                <div class="ui message">
						<div class="header">
							Notes:
						</div>
						<p>SQL Query test page</p>
					</div>
				</div>
				<div class="column">
					<form class="ui form" action="inject.php" method="post">
						<div class="field">
							<label>ID</label>
							<input type="text" name="action" value="1" size="50">
                            <input readonly name='csrf' id='csrf' type='hidden' value='<?php echo $_SESSION['token'] ?>' />
                            <br><br>
						</div>
						<button class="ui button" type="submit">Submit</button>
					</form>
				</div>
				<div class="column"> 
				<div class="seven column row">
					<?php 
					$alert_class = ($valid_token ? "positive" : "negative");
					$found = ($valid_token ? "V" : "Inv");
					echo "
					<div class='ui {$alert_class} message'>
						<i class='close icon'></i>
						<div class='header'>
							Query result
						</div>
						<p>{$found}alid token</p>
					</div>
					";
					?>
					<?php 
					$alert_class = ($result && $result->num_rows > 0 ? "positive" : "negative");
					$found = ($result && $result->num_rows > 0 ? "" : " NOT");
					echo "
					<div class='ui {$alert_class} message'>
						<i class='close icon'></i>
						<div class='header'>
							Query result
						</div>
						<p>Result{$found} found</p>
					</div>
					";
					?>
				</div>
			</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha512-dqw6X88iGgZlTsONxZK9ePmJEFrmHwpuMrsUChjAw1mRUhUITE5QU9pkcSox+ynfLhL15Sv2al5A0LVyDCmtUw==" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
	</body>
</html>