<?

function get_slug() { // retrieve slug
	$slug = $_SERVER["REQUEST_URI"];
	$slug = substr($slug, 1);
	$slug = explode("?", $slug, 2);
	$slug = $slug[0];
	return $slug;
}

function db_connect() {
	// 1. Connect to database
	global $db_connection;
	$dbhost = "localhost";
	$dbuser = "web";
	$dbpass = "pass";
	$dbname = "lacuna_records";
	$db_connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// Test database connection
	if (mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
	}
}

db_connect();

function db_disconnect() {
	// 5. Disconnect from database
	global $db_connection;
	mysqli_close($db_connection);
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
	    <link rel="stylesheet" type="text/css" href="common/global.css">
	</head>
	<body>
		<header>
			<? include("common/header.php"); ?>
		</header>
		<main>
			<div class="main wrapper">
				<?
				$slug = get_slug();
				if ($slug == "" || $slug == "home") {
					include("pages/main.php"); // home page
				} elseif (file_exists("pages/$slug/main.php")) { // if file exists, go to it
					include("pages/$slug/main.php");
				} elseif (file_exists("tracks/singles/$slug")) {
					include("tracks/singles/$slug/data.php");
					include("tracks/singles/main.php");
				} else {
					include("common/404.php");
				}
				$query_for_slug = "SELECT arist, title FROM tracks";
				$result_for_slug = mysqli_query($db_connection, $query_for_slug);
				// Test for query error
				if (!$result_for_slug) {
					die("Database query failed");
				}
				while($track = mysqli_fetch_assoc($result_for_slug)) {
					$artist = $track["artist"];
					$title = $track["title"];
					if ($slug == "$artist/$title" || $slug == "$artist-$title") {
						echo "slug: $slug";
					}
				}

				?>
			</div>
		</main>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="global.js"></script>
	</body>
</html>

<?db_disconnect();?>
