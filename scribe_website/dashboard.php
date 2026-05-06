<?php 

    /* TO-DO: Include header.php
              Hint: header.php is inside the includes folder and already connects to the database
    */

	include("./includes/header.php");

    /*
	 * Retrieve toy information from the database based on the toy ID.
	 * 
	 * @param PDO $pdo       An instance of the PDO class.
	 * @param string $id     The ID of the toy to retrieve.
	 * @return array|null    An associative array containing the toy information, or null if no toy is found.
	 */
	function get_worlds(PDO $pdo, $user_ID) {
		                                                    // SQL query to retrieve toy information based on the toy ID
		$sql = "SELECT PlayingIn.role, Locations.ID AS loc_id, Locations.name AS loc_name, Characters.name AS char_name,
			Locations.img_src AS loc_image, Characters.img_src AS char_image, Characters.description AS char_description,
			Locations.description AS loc_description, Characters.isAt
            FROM PlayingIn
            JOIN Locations ON Locations.ID = PlayingIn.world
			LEFT JOIN Characters ON Characters.ID = PlayingIn.plays
            WHERE PlayingIn.user = :userID;";	                        // :id is a placeholder for value provided later 
		                                                    // It's a parameterized query that helps prevent SQL injection attacks and ensures safer interaction with the database

		                                                    // Execute the SQL query using the pdo function and fetch the result
		$worlds = pdo($pdo, $sql, [":userID" => $user_ID])->fetchAll();		// Associative array where 'id' is the key and $id is the value. Used to bind the value of $id to the placeholder :id in SQL query.

		return $worlds;                                        // Return the toy information (associative array)
	}

	$user_ID = $_SESSION['username'];
	$worlds = get_worlds($pdo, $user_ID);                          // Retrieve info about toy with ID '0001' from the database using provided PDO connection
?>


<section class="toy-catalog">
	<h2 class="section-label">Campaigns

		<button onclick="toggleCampaignForm()" style="display: block; margin-top: 5px;">
			+ New Campaign
		</button>
	</h2>

	<form id="campaignForm" action="create_campaign.php" method="POST" style="display:none; margin-top:5px;">
		<input type="text" name="campaign_name" placeholder="New Campaign Name" required style="padding:5px;">
		<button type="submit" style="padding:5px 10px;">Create</button>
	</form>

	<?php foreach ($worlds as $world) { ?>

		<!-- LOCATION CARD START -->
		<form class="toy-card" method="POST" action="location.php">
			<!-- TO-DO: Create a hyperlink to location.php and pass the location number as a URL parameter -->
			<?php if ($world["role"] == "gm") { ?>
				<button type="submit" name="msg" value="<?=$world["loc_id"]?>&gm&<?=$world["loc_id"]?>">

					<!-- TO-DO: Display the world image and update the alt text to the world name -->
					<img src="imgs/location_default.png" alt="<?= $world["loc_name"] ?>">
				</button>

				<!-- TO-DO: Display the name of the world -->
				<h2>Game Master: <?= $world["loc_name"] ?></h2>
			<?php } else { ?>
				<button name="msg" value="<?=$world["isAt"]?>&player&<?=$world["loc_id"]?>">

					<!-- TO-DO: Display the character image and update the alt text to the character name -->
					<img src="imgs/players_default.png" alt="<?= $world["char_name"] ?>">
				</button>
				<!-- TO-DO: Display the name of the character -->
				<h2>Player: <?= $world["char_name"] ?></h2>
			<?php } ?>
		</form>
		<!-- LOCATION CARD END -->

	<?php } ?>

</section>

<script>
function toggleCampaignForm() {
	var form = document.getElementById("campaignForm");
	if (form.style.display === "none") {
		form.style.display = "block";
	} else {
		form.style.display = "none";
	}
}
</script>

<?php include 'includes/footer.php'; ?>
