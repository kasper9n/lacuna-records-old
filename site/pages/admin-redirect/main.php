<?

function post_to_sql_string($post_index) {
	if (isset($_POST["$post_index"])) {
		$post_value = $_POST["$post_index"];
		global $db_connection;
		$post_value = mysqli_real_escape_string($db_connection, $post_value);
		global $$post_index;
		$$post_index = $post_value;
	}
}

post_to_sql_string("track_id");
post_to_sql_string("catalog_id");
post_to_sql_string("artist");
post_to_sql_string("title");
post_to_sql_string("cover_art_filename");

// 2. Database query
if (isset($_POST["submit_tracks_add"])) {
	$query = "	INSERT INTO tracks (catalog_id, artist, title, cover_art_filename)
				VALUES ('{$catalog_id}', '{$artist}', '{$title}', '{$cover_art_filename}')";
	$result = mysqli_query($db_connection, $query);
	// Test for query error
	if ($result) {
		// Success
		echo "Success!";
	} else {
		// Failure
		die("Datatabse query failed. " . mysqli_error($db_connection));
	}
}

// 2. Database query
if (isset($_POST["submit_tracks_edit"])) {
	$query = " UPDATE tracks SET catalog_id = '{$catalog_id}', artist = '{$artist}', title = '{$title}', cover_art_filename = '{$cover_art_filename}' WHERE track_id = {$track_id} ";
	$result = mysqli_query($db_connection, $query);
	// Test for query error
	if ($result && mysqli_affected_rows($db_connection) == 1) {
		// Success
		echo "Success!";
	} else {
		// Failure
		die("Datatabse query failed. Possibly you change antyhing before clicking edit" . mysqli_error($db_connection));
	}
}

// 2. Database query
if (isset($_POST["submit_tracks_delete"])) {
	$query = "DELETE FROM tracks WHERE track_id = {$track_id} LIMIT 1";
	$result = mysqli_query($db_connection, $query);
	// Test for query error
	if ($result && mysqli_affected_rows($db_connection) == 1) {
		// Success
		echo "Success!";
	} else {
		// Failure
		die("Datatabse query failed. " . mysqli_error($db_connection));
	}
}


$newURL = "admin";
header('Location: '.$newURL);

?>
