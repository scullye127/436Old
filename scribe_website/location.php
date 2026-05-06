<?php 
	require_once('includes/database-connection.php');

	$loc_ID;
	$world_ID;
	$role;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$msg = explode('&', $_POST['msg']);
		$loc_ID = $msg[0];
		$role = $msg[1];
		$world_ID = $msg[2];

		if(count($msg) >= 4){
			switch($msg[3]){
				case 'back':
					$sql = "SELECT container FROM Contains WHERE Contains.containee = :ID;";
					$result = pdo($pdo, $sql, [':ID' => $loc_ID])->fetch();
					if($result){
						$loc_ID = $result['container'];
					} else {
						header('Location: dashboard.php');
					}
				break;
				case 'propBack':
					$sql = "SELECT isIn FROM Props WHERE Props.ID = :ID;";
					$result = pdo($pdo, $sql, [':ID' => $loc_ID])->fetch();
					$loc_ID = $result['isIn'];
				break;
				case 'charBack':
					$sql = "SELECT isAt FROM Characters WHERE Characters.ID = :ID;";
					$result = pdo($pdo, $sql, [':ID' => $loc_ID])->fetch();
					$loc_ID = $result['isAt'];
				break;
				case 'edit':
					switch($role){
						case 'gm':
							$sql = "UPDATE Locations SET Locations.name = :locName, Locations.description = :descr, Locations.gmNotes = :gmNotes WHERE Locations.ID = :ID;";
							pdo($pdo, $sql, [':locName' => $_POST['name'], ':descr' => $_POST['description'], ':gmNotes' => $_POST['gmNotes'], ':ID' => $loc_ID]);
						break;
						case 'player':
							$sql = "UPDATE Locations SET Locations.partyNotes = :partyNotes WHERE Locations.ID = :ID;";
							pdo($pdo, $sql, [':partyNotes' => $_POST['partyNotes'], ':ID' => $loc_ID]);
						break;
					}
				break;
				case 'add':
					$type = $msg[4];
					switch($type){
						case 'location':
							$sql = "INSERT INTO Locations (name, description, img_src) VALUES (:name, :description, :img_src)";
							pdo($pdo, $sql, [':name' => $_POST['name'], ':description' => $_POST['description'], ':img_src' => 'imgs/location_default.png']);
							$newID = $pdo->lastInsertId();
							$sql = "INSERT INTO Contains (container, containee) VALUES (:container, :containee)";
							pdo($pdo, $sql, [':container' => $loc_ID, ':containee' => $newID]);
						break;
						case 'npc':
							$sql = "INSERT INTO Characters (name, description, img_src, isAt) VALUES (:name, :description, :img_src, :isAt)";
							pdo($pdo, $sql, [':name' => $_POST['name'], ':description' => $_POST['description'], ':img_src' => 'imgs/npc_default.png', ':isAt' => $loc_ID]);
							$newID = $pdo->lastInsertId();
							$sql = "INSERT INTO NPCs (ID) VALUES (:id)";
							pdo($pdo, $sql, [':id' => $newID]);
						break;
						case 'creature':
							$sql = "INSERT INTO Characters (name, description, img_src, isAt) VALUES (:name, :description, :img_src, :isAt)";
							pdo($pdo, $sql, [':name' => $_POST['name'], ':description' => $_POST['description'], ':img_src' => 'imgs/creature_default.png', ':isAt' => $loc_ID]);
							$newID = $pdo->lastInsertId();
							$sql = "INSERT INTO Creatures (ID) VALUES (:id)";
							pdo($pdo, $sql, [':id' => $newID]);
						break;
						case 'player':
							$sql = "INSERT INTO Characters (name, description, img_src, isAt) VALUES (:name, :description, :img_src, :isAt)";
							pdo($pdo, $sql, [':name' => $_POST['name'], ':description' => $_POST['description'], ':img_src' => 'imgs/players_default.png', ':isAt' => $loc_ID]);
							$newID = $pdo->lastInsertId();
							// Set level=1 by default, and link the character to the user via playedBy
							$sql = "INSERT INTO Players (ID, level, playedBy) VALUES (:id, 1, :playedBy)";
							pdo($pdo, $sql, [':id' => $newID, ':playedBy' => $_POST['username']]);
							// Add user to PlayingIn so they can see this campaign on their dashboard
							$sql = "INSERT INTO PlayingIn (user, plays, world, role) VALUES (:user, :plays, :world, 'player')";
							pdo($pdo, $sql, [':user' => $_POST['username'], ':plays' => $newID, ':world' => $world_ID]);
						break;
						case 'prop':
							$sql = "INSERT INTO Props (name, description, img_src, isIn) VALUES (:name, :description, :img_src, :isIn)";
							pdo($pdo, $sql, [':name' => $_POST['name'], ':description' => $_POST['description'], ':img_src' => 'imgs/props_default.png', ':isIn' => $loc_ID]);
						break;
					}
				break;
				case 'toggle':
					$type = $msg[4];
					$entryID = $msg[5];
					switch($type){
						case 'location':
							$sql = "UPDATE Locations SET visible = NOT visible WHERE ID = :ID";
							pdo($pdo, $sql, [':ID' => $entryID]);
						break;
						case 'character':
							$sql = "UPDATE Characters SET visible = NOT visible WHERE ID = :ID";
							pdo($pdo, $sql, [':ID' => $entryID]);
						break;
						case 'prop':
							$sql = "UPDATE Props SET visible = NOT visible WHERE ID = :ID";
							pdo($pdo, $sql, [':ID' => $entryID]);
						break;
					}
				break;
			}
		}
	}

	include("./includes/header.php");

	function get_nested_entries(PDO $pdo, $loc_ID, $role) {
		// Added visibility filter so GM can choose what items are visible to players
		$visibleFilter = $role == 'player' ? 'AND visible = 1' : '';

		$location = pdo($pdo, "SELECT * FROM Locations WHERE ID = :locID", [":locID" => $loc_ID])->fetch();

		$players = pdo($pdo, "SELECT Characters.*, Players.level, Players.playedBy, Players.combat_style 
							FROM Characters 
							JOIN Players ON Players.ID = Characters.ID
							WHERE Characters.isAt = :locID", [":locID" => $loc_ID])->fetchAll();

		$creatures = pdo($pdo, "SELECT Characters.*, Creatures.population, Creatures.ability 
								FROM Characters 
								JOIN Creatures ON Creatures.ID = Characters.ID
								WHERE Characters.isAt = :locID $visibleFilter", [":locID" => $loc_ID])->fetchAll();

		$npcs = pdo($pdo, "SELECT Characters.*, NPCs.opinions, NPCs.occupation, NPCs.gold 
							FROM Characters 
							JOIN NPCs ON NPCs.ID = Characters.ID
							WHERE Characters.isAt = :locID $visibleFilter", [":locID" => $loc_ID])->fetchAll();

		$props = pdo($pdo, "SELECT * FROM Props WHERE isIn = :locID $visibleFilter", [":locID" => $loc_ID])->fetchAll();

		$sublocations = pdo($pdo, "SELECT Locations.* FROM Locations 
									JOIN Contains ON Contains.containee = Locations.ID
									WHERE Contains.container = :locID $visibleFilter", [":locID" => $loc_ID])->fetchAll();

		return [
			"location" => $location,
			"players" => $players,
			"creatures" => $creatures,
			"npcs" => $npcs,
			"props" => $props,
			"sublocations" => $sublocations
		];
	}

$items = get_nested_entries($pdo, $loc_ID, $role);

?>

<div class="location-header">
	<form method="POST" action="location.php">
		<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&back">Back</button>
		<?php if($role == "player"){ ?>
			<h1><?= $items["location"]["name"] ?></h1>	
			<p><?= $items["location"]["description"] ?></p>
			<div style="display: flex; align-items: center; gap: 0.6rem; width: 100%;">
				<label for="partyNotes">Party's Notes:</label>
				<input type="text" value="<?=$items["location"]["partyNotes"]?>" id="partyNotes" name="partyNotes">
			</div>
		<?php } else { ?>
			<div style="display: flex; align-items: center; gap: 0.6rem; width: 100%;">
				<label for="name">Name:</label>
				<input type="text" value="<?=$items["location"]["name"]?>" id="name" name="name">
			</div>
			<div style="display: flex; align-items: center; gap: 0.6rem; width: 100%;">
				<label for="description">Description:</label>
				<input type="text" value="<?=$items["location"]["description"]?>" id="description" name="description">
			</div>
			<div style="display: flex; align-items: center; gap: 0.6rem; width: 100%;">
				<label for="gmNotes">Private Notes:</label>
				<input type="text" value="<?=$items["location"]["gmNotes"]?>" id="gmNotes" name="gmNotes">
			</div>
			<div style="display: flex; align-items: center; gap: 0.6rem; width: 100%;">
				<label for="partyNotes">Party's Notes:</label>
				<input type="text" value="<?=$items["location"]["partyNotes"]?>" id="partyNotes" disabled>
			</div>
		<?php } ?>
		<button type="submit" name="msg" style="align-self: center;" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&edit">Save Changes</button>
	</form>
</div>

<section class="toy-catalog">

	<!-- Sublocations -->
	<h2 class="section-label">Locations
		<?php if($role == "gm"){ ?>
			<button onclick="this.nextElementSibling.style.display='block'; this.style.display='none';">+ Add</button>
			<form method="POST" action="location.php" style="display:none;">
				<input type="text" name="name" placeholder="Name" required>
				<input type="text" name="description" placeholder="Description">
				<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&add&location">Save</button>
				<button type="button" onclick="this.parentElement.style.display='none'; this.parentElement.previousElementSibling.style.display='inline';">Cancel</button>
			</form>
		<?php } ?>
	</h2>
	<?php foreach ($items["sublocations"] as $sub) { ?>
		<div class="toy-card">
			<form method="POST" action="location.php">
				<button type="submit" name="msg" value="<?=$sub["ID"]?>&<?=$role?>&<?=$world_ID?>">
					<img src="imgs/location_default.png" alt="<?= $sub["name"] ?>">
				</button>
			</form>
			<h2><?= $sub["name"] ?></h2>
			<p><?= $sub["description"] ?></p>
			<!-- Toggle visibility -->
			<?php if($role == 'gm'){ ?>
				<form method="POST" action="location.php">
					<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&toggle&location&<?=$sub["ID"]?>">
						<?= $sub["visible"] ? "Hide from Players" : "Reveal to Players" ?>
					</button>
				</form>
			<?php } ?>
		</div>
	<?php } ?>

	<!-- Players -->
	<h2 class="section-label">Players
		<?php if($role == "gm"){ ?>
			<button onclick="this.nextElementSibling.style.display='block'; this.style.display='none';">+ Add</button>
			<form method="POST" action="location.php" style="display:none;">
				<input type="text" name="name" placeholder="Character Name" required>
				<input type="text" name="description" placeholder="Description">
				<input type="text" name="username" placeholder="Player Username" required>
				<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&add&player">Save</button>
				<button type="button" onclick="this.parentElement.style.display='none'; this.parentElement.previousElementSibling.style.display='inline';">Cancel</button>
			</form>
		<?php } ?>
	</h2>
	<?php foreach ($items["players"] as $char) { ?>
		<div class="toy-card">
			<form method="POST" action="char.php">
				<button type="submit" name="msg" value="<?=$char["ID"]?>&<?=$role?>&<?=$world_ID?>">
					<img src="imgs/players_default.png" alt="<?= $char["name"] ?>">
				</button>
			</form>
			<h2><?= $char["name"] ?></h2>
			<p><?= $char["description"] ?></p>
		</div>
	<?php } ?>

	<!-- Creatures -->
	<h2 class="section-label">Creatures
		<?php if($role == "gm"){ ?>
			<button onclick="this.nextElementSibling.style.display='block'; this.style.display='none';">+ Add</button>
			<form method="POST" action="location.php" style="display:none;">
				<input type="text" name="name" placeholder="Name" required>
				<input type="text" name="description" placeholder="Description">
				<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&add&creature">Save</button>
				<button type="button" onclick="this.parentElement.style.display='none'; this.parentElement.previousElementSibling.style.display='inline';">Cancel</button>
			</form>
		<?php } ?>
	</h2>
	<?php foreach ($items["creatures"] as $char) { ?>
		<div class="toy-card">
			<form method="POST" action="char.php">
				<button type="submit" name="msg" value="<?=$char["ID"]?>&<?=$role?>&<?=$world_ID?>">
					<img src="imgs/creature_default.png" alt="<?= $char["name"] ?>">
				</button>
			</form>
			<h2><?= $char["name"] ?></h2>
			<p><?= $char["description"] ?></p>
			<!-- Toggle visibility -->
			<?php if($role == 'gm'){ ?>
				<form method="POST" action="location.php">
					<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&toggle&character&<?=$char["ID"]?>">
						<?= $char["visible"] ? "Hide from Players" : "Reveal to Players" ?>
					</button>
				</form>
			<?php } ?>
		</div>
	<?php } ?>

	<!-- NPCs -->
	<h2 class="section-label">NPCs
		<?php if($role == "gm"){ ?>
			<button onclick="this.nextElementSibling.style.display='block'; this.style.display='none';">+ Add</button>
			<form method="POST" action="location.php" style="display:none;">
				<input type="text" name="name" placeholder="Name" required>
				<input type="text" name="description" placeholder="Description">
				<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&add&npc">Save</button>
				<button type="button" onclick="this.parentElement.style.display='none'; this.parentElement.previousElementSibling.style.display='inline';">Cancel</button>
			</form>
		<?php } ?>
	</h2>
	<?php foreach ($items["npcs"] as $char) { ?>
		<div class="toy-card">
			<form method="POST" action="char.php">
				<button type="submit" name="msg" value="<?=$char["ID"]?>&<?=$role?>&<?=$world_ID?>">
					<img src="imgs/npc_default.png" alt="<?= $char["name"] ?>">
				</button>
			</form>
			<h2><?= $char["name"] ?></h2>
			<p><?= $char["description"] ?></p>
			<!-- Toggle visibility -->
			<?php if($role == 'gm'){ ?>
				<form method="POST" action="location.php">
					<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&toggle&character&<?=$char["ID"]?>">
						<?= $char["visible"] ? "Hide from Players" : "Reveal to Players" ?>
					</button>
				</form>
			<?php } ?>
		</div>
	<?php } ?>

	<!-- Props -->
	<h2 class="section-label">Props
		<?php if($role == "gm"){ ?>
			<button onclick="this.nextElementSibling.style.display='block'; this.style.display='none';">+ Add</button>
			<form method="POST" action="location.php" style="display:none;">
				<input type="text" name="name" placeholder="Name" required>
				<input type="text" name="description" placeholder="Description">
				<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&add&prop">Save</button>
				<button type="button" onclick="this.parentElement.style.display='none'; this.parentElement.previousElementSibling.style.display='inline';">Cancel</button>
			</form>
		<?php } ?>
	</h2>
	<?php foreach ($items["props"] as $prop) { ?>
		<div class="toy-card">
			<form method="POST" action="prop.php">
				<button type="submit" name="msg" value="<?=$prop["ID"]?>&<?=$role?>&<?=$world_ID?>">
					<img src="imgs/props_default.png" alt="<?= $prop["name"] ?>">
				</button>
			</form>
			<h2><?= $prop["name"] ?></h2>
			<p><?= $prop["description"] ?></p>
			<!-- Toggle visibility -->
			<?php if($role == 'gm'){ ?>
				<form method="POST" action="location.php">
					<button type="submit" name="msg" value="<?=$loc_ID?>&<?=$role?>&<?=$world_ID?>&toggle&prop&<?=$prop["ID"]?>">
						<?= $prop["visible"] ? "Hide from Players" : "Reveal to Players" ?>
					</button>
				</form>
			<?php } ?>
		</div>
	<?php } ?>

</section>

<?php include 'includes/footer.php'; ?>