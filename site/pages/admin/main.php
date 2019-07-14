<?

session_start();

$username = "Username";
$password = "";
if (isset($_POST["submit_login"])) {
	$username = $_POST["username"];
	$password = $_POST["password"];

	if ($username == "userbro" && $password == "passbro") {
		$_SESSION["admin_logged_in"] = true;
	} else {
		$_SESSION["admin_logged_in"] = false;
		$error_admin_login = "Well that didn't work :/";
	}
} elseif (isset($_SESSION["admin_logged_in"])) {
	if ($_SESSION["admin_logged_in"] != true) {
		$_SESSION["admin_logged_in"] == false;
	} else {
		$_SESSION["admin_logged_in"] = true;
	}
} else {
	$_SESSION["admin_logged_in"] = false;
}
$admin_logged_in = $_SESSION["admin_logged_in"];

?>

<title>Lacuna Records</title>

<main id="adminpage">
	<? if ($admin_logged_in == false) { ?>

		<p>Gotta login first. Sorry for how the login looks.</p>
		<form action="admin" method="post">
			<input type="text" name="username" value="<?= htmlentities($username)?>"/>
			<input type="password" name="password" value="<?= htmlentities($password)?>"/>
			<input type="submit" name="submit_login" value="Submit"/>
		</form>
	<? if (isset($error_admin_login)) { echo "<p>$error_admin_login</p>"; } ?>

	<? } elseif ($admin_logged_in == true) { ?>

		<?
		// 2. Datbase query
		$query = "SELECT * FROM tracks";
		$result = mysqli_query($db_connection, $query);
		// Test for query error
		if (!$result) {
			die("Database query failed");
		}

		// 3. Use returned data
		while($track = mysqli_fetch_assoc($result)) { ?>

			<form action="admin-redirect" method="post">
				<input type="text" name="track_id" value="<?= $track["track_id"] ?>" hidden/>
				<input type="text" name="catalog_id" value="<?= $track["catalog_id"] ?>"/>
				<input type="text" name="artist" value="<?= $track["artist"] ?>"/>
				<input type="text" name="title" value="<?= $track["title"] ?>"/>
				<input type="text" name="cover_art_filename" value="<?= $track["cover_art_filename"] ?>"/>
				<input type="submit" name="submit_tracks_edit" value="Edit"/>
				<input type="submit" name="submit_tracks_delete" value="Delete"/>
			</form>
		<?} mysqli_free_result($result); ?>
		<form action="admin-redirect" method="post">
			<input type="text" name="catalog_id"/>
			<input type="text" name="artist"/>
			<input type="text" name="title"/>
			<input type="text" name="cover_art_filename"/>
			<input type="submit" name="submit_tracks_add" value="Add"/>
		</form>

	<? } ?>
</main>
