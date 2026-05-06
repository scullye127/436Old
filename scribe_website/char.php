<?php
    require_once('includes/database-connection.php');
    require_once('includes/session.php');

    // Reading POST request
    $char_ID;
	$role;
    $world_ID;
    $locations;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$msg = explode('&', $_POST['msg']);
		$char_ID = $msg[0];
		$role = $msg[1];
        $world_ID = $msg[2];
        $locations = get_locations($pdo, $world_ID);

        if(count($msg) > 3){
            // Player editing their own character
            if($role == 'player' && $_POST['name']){
                $sql = "UPDATE Characters
                        SET Characters.name = :charName, Characters.race = :race, Characters.description = :descr, Characters.partyNotes = :partyNotes
                        WHERE Characters.ID = :ID;";
                pdo($pdo, $sql, [':charName' => $_POST['name'], ':race' => $_POST['race'], ':descr' => $_POST['description'], ':partyNotes' => $_POST['partyNotes'], ':ID' => $char_ID]);
            } elseif($role == 'gm'){
                $sql = "UPDATE Characters
                        SET Characters.name = :charName, Characters.race = :race, Characters.description = :descr, Characters.gmNotes = :gmNotes
                        WHERE Characters.ID = :ID;";
                pdo($pdo, $sql, [':charName' => $_POST['name'], ':race' => $_POST['race'], ':descr' => $_POST['description'], ':gmNotes' => $_POST['gmNotes'], ':ID' => $char_ID]);
            } else {
                $sql = "UPDATE Characters
                        SET Characters.partyNotes = :partyNotes
                        WHERE Characters.ID = :ID;";
                pdo($pdo, $sql, [':partyNotes' => $_POST['partyNotes'], ':ID' => $char_ID]);
            }

            if(key_exists('level', $_POST)){
                $sql = "UPDATE Players
                        SET Players.level = :lvl, Players.combat_style = :style
                        WHERE Players.ID = :ID;";
                pdo($pdo, $sql, [':lvl' => $_POST['level'], ':style' => $_POST['combat_style'], ':ID' => $char_ID]);
            }

            if(key_exists('population', $_POST)){
                $sql = "UPDATE Creatures
                        SET Creatures.population = :pop, Creatures.ability = :ability
                        WHERE Creatures.ID = :ID;";
                pdo($pdo, $sql, [':pop' => $_POST['population'], ':ability' => $_POST['ability'], ':ID' => $char_ID]);
            }

            if(key_exists('occupation', $_POST)){
                $sql = "UPDATE NPCs
                        SET NPCs.opinions = :opinions, NPCs.occupation = :occupation, NPCs.gold = :gold
                        WHERE NPCs.ID = :ID;";
                pdo($pdo, $sql, [':opinions' => $_POST['opinions'], ':occupation' => $_POST['occupation'], ':gold' => $_POST['gold'], ':ID' => $char_ID]);
            }

            if(key_exists('isAt', $_POST) && $_POST['isAt'] != 'Select Location'){
                $sql = "UPDATE Characters
                        SET Characters.isAt = :newLoc
                        WHERE Characters.ID = :ID;";
                pdo($pdo, $sql, [':newLoc' => $locations[$_POST['isAt']], ':ID' => $char_ID]);
            }
        }
	}

    /* TO-DO: Include header.php
              Hint: header.php is inside the includes folder and already connects to the database
    */

    include("./includes/header.php");

    /* TO-DO: Create a function that retrieves ALL toy and manufacturer information 
              from the database based on the itemnum parameter from the URL.

              Your function should:
                1. Query the appropriate database table to retrieve toy and manufacturer info based on toynum
                2. Execute the SQL query using the pdo() helper function and fetch the result
                3. Return toy information
	*/
    function get_char_info(PDO $pdo, $char_id) {
        $sql = "SELECT Characters.*, 
                Players.level, Players.playedBy, Players.combat_style,
                Creatures.population, Creatures.ability,
                NPCs.opinions, NPCs.occupation, NPCs.gold
                FROM Characters
                LEFT JOIN Players ON Players.ID = Characters.ID
                LEFT JOIN Creatures ON Creatures.ID = Characters.ID
                LEFT JOIN NPCs ON NPCs.ID = Characters.ID
                WHERE Characters.ID = :id;";

        $char_info = pdo($pdo, $sql, ['id' => $char_id])->fetch();

        return $char_info;
    }


    /* TO-DO: Call function to retrieve character information */
    $item = get_char_info($pdo, $char_ID);
?>

<section class="toy-details-page container">
    <div class="toy-details-container">
        <div class="toy-image">

            <!-- Commented out displaying image for expo -->
            <!--<img src="<?= $item["img_src"] ?>" alt="<?= $item["name"] ?>"> -->

        </div>

        <form method="POST" action="location.php">
            <button type="submit" name="msg" value="<?=$char_ID?>&<?=$role?>&<?=$world_ID?>&charBack">Back</button>
        </form>

        <form method="POST" action="char.php" class="toy-details">
            <!-- GM View -->
            <?php if($role == 'gm') { ?>
                <div class="form-row">
                    <label for="name">Name:</label>
                    <input type="text" value="<?=$item["name"]?>" id="name" name="name">
                </div>
                <div class="form-row">
                    <label for="race">Race:</label>
                    <input type="text" value="<?=$item["race"]?>" id="race" name="race">
                </div>
                <div class="form-row">
                    <label for="description">Description:</label>
                    <input type="text" value="<?=$item["description"]?>" id="description" name="description">
                </div>
                <div class="form-row">
                    <label for="gmNotes">Private Notes:</label>
                    <input type="text" value="<?=$item["gmNotes"]?>" id="gmNotes" name="gmNotes">
                </div>
                <div class="form-row">
                    <label for="partyNotes">Party's Notes:</label>
                    <p type="text" id="partyNotes"><?=$item["partyNotes"]?></p>
                </div>
                <div class="form-row">
                    <label for="isAt">Send To:</label>
                    <select name="isAt" id="isAt">
                    <option>Select Location</option>
                    <?php foreach($locations as $locName => $locID){ ?>
                        <option><?= $locName ?></option>
                    <?php } ?>
                    </select>
                </div>

                <!-- Player specific info -->
                <?php if ($item["playedBy"] !== null) { ?>
                    <br />
                    <h3>Player Information</h3>
                    <div class="form-row">
                        <label>Played By:</label>
                        <p><?= $item["playedBy"] ?></p>
                    </div>
                    <div class="form-row">
                        <label for="level">Level:</label>
                        <input type="number" value="<?=$item["level"]?>" id="level" name="level">
                    </div>
                    <div class="form-row">
                        <label for="combat_style">Combat Style:</label>
                        <input type="text" value="<?=$item["combat_style"]?>" id="combat_style" name="combat_style">
                    </div>
                <?php } ?>

                <!-- Creature specific info -->
                <?php if ($item["population"] !== null) { ?>
                    <br />
                    <h3>Species Information</h3>
                    <div class="form-row">
                        <label for="population">Population:</label>
                        <input type="number" value="<?=$item["population"]?>" id="population" name="population">
                    </div>
                    <div class="form-row">
                        <label for="ability">Ability:</label>
                        <input type="text" value="<?=$item["ability"]?>" id="ability" name="ability">
                    </div>
                <?php } ?>

                <!-- NPC specific info -->
                <?php if ($item["occupation"] !== null) { ?>
                    <br />
                    <h3>NPC Information</h3>
                    <div class="form-row">
                        <label for="occupation">Occupation:</label>
                        <input type="text" value="<?=$item["occupation"]?>" id="occupation" name="occupation">
                    </div>
                    <div class="form-row">
                        <label for="opinions">Opinions:</label>
                        <input type="text" value="<?=$item["opinions"]?>" id="opinions" name="opinions">
                    </div>
                    <div class="form-row">
                        <label for="gold">Gold:</label>
				        <input type="number" value="<?=$item["gold"]?>" id="gold" name="gold">
                    </div>
                <?php } ?>

            <!-- Player's Character View -->
            <?php } elseif(($item["playedBy"] && $item["playedBy"] == $_SESSION['username'])) { ?>
                <div class="form-row">
                    <label for="name">Name:</label>
					<input type="text" value="<?=$item["name"]?>" id="name" name="name">
                </div>
                <div class="form-row">
                    <label for="race">Race:</label>
					<input type="text" value="<?=$item["race"]?>" id="race" name="race">
                </div>
                <div class="form-row">
                    <label for="description">Description:</label>
                    <input type="text" value="<?=$item["description"]?>" id="description" name="description">
                </div>
                <div class="form-row">
                    <label for="partyNotes">Party's Notes:</label>
                    <input type="text" id="partyNotes" name="partyNotes" value=<?= $item["partyNotes"]?>></p>
                </div>

                <br />
                <h3>Player Information</h3>
                <p><strong>Played By:</strong> <?= $item["playedBy"] ?></p>
                <div class="form-row">
                    <label for="level">Level:</label>
                    <input type="number" value="<?=$item["level"]?>" id="level" name="level">
                </div>
                <div class="form-row">
                    <label for="combat_style">Combat Style:</label>
                    <input type="text" value="<?=$item["combat_style"]?>" id="combat_style" name="combat_style">
                </div>
            <?php } else { ?>
                <!-- TO-DO: Display the toy name -->
                <h1><?= $item["name"] ?></h1>

                <h3>Character Information</h3>
                <p><strong>Race:</strong> <?= $item["race"] ?></p>
                <p><strong>Description:</strong> <?= $item["description"] ?></p>
                <p><strong>Party Notes:</strong> <Input id="partyNotes" name="partyNotes" value=<?= $item["partyNotes"]?>></p>

                <!-- Player specific info -->
                <?php if ($item["playedBy"]) { ?>
                    <br />
                    <h3>Player Information</h3>
                    <p><strong>Played By:</strong> <?= $item["playedBy"] ?></p>
                    <p><strong>Level:</strong> <?= $item["level"] ?></p>
                    <p><strong>Combat Style:</strong> <?= $item["combat_style"] ?></p>
                <?php } ?>

                <!-- Creature specific info -->
                <?php if ($item["population"]) { ?>
                    <br />
                    <h3>Species Information</h3>
                    <p><strong>Population:</strong> <?= $item["population"] ?></p>
                    <p><strong>Ability:</strong> <?= $item["ability"] ?></p>
                <?php } ?>

                <!-- NPC specific info -->
                <?php if ($item["occupation"]) { ?>
                    <br />
                    <h3>NPC Information</h3>
                    <p><strong>Occupation:</strong> <?= $item["occupation"] ?></p>
                    <p><strong>Opinions:</strong> <?= $item["opinions"] ?></p>
                    <p><strong>Gold:</strong> <?= $item["gold"] ?></p>
                <?php } ?>
            <?php } ?>
            <button type="submit" name="msg" value="<?=$char_ID?>&<?=$role?>&<?=$world_ID?>&edit">Save Changes</button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>