<?php
    require_once('includes/database-connection.php');
    require_once('includes/session.php');

    // Reading POST request
    $prop_ID;
	$role;
    $world_ID;
    $locations;
    $characters;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$msg = explode('&', $_POST['msg']);
		$prop_ID = $msg[0];
		$role = $msg[1];
        $world_ID = $msg[2];
        $locations = get_locations($pdo, $world_ID);
        $characters = get_characters($pdo, $locations);

        // Check if msg is sent with extra 'edit' string
        if(count($msg) == 4){
            if($role == 'gm'){
                $sql =  "UPDATE Props
                        SET Props.name = :propName, Props.description = :descr, Props.gmNotes = :gmNotes, Props.itemType = :itemType, Props.rarity = :rarity, Props.quantity = :quantity
                        WHERE Props.ID = :ID;";
                pdo($pdo, $sql, [':propName' => $_POST['name'], ':descr' => $_POST['description'], ':gmNotes' => $_POST['gmNotes'], ':itemType' => $_POST['itemType'], ':rarity' => $_POST['rarity'], ':quantity' => $_POST['quantity'],':ID' => $prop_ID]);
            } elseif($role == 'player'){
                $sql = "UPDATE Props
                        SET Props.partyNotes = :partyNotes
                        WHERE Props.ID = :ID;";
                pdo($pdo, $sql, [':partyNotes' => $_POST['partyNotes'], ':ID' => $prop_ID]);
            }

            if(key_exists('isIn', $_POST) && $_POST['isIn'] != 'Select Location'){
                $sql = "UPDATE Props
                        SET Props.isIn = :newLoc
                        WHERE Props.ID = :ID;";
                pdo($pdo, $sql, [':newLoc' => $locations[$_POST['isIn']], ':ID' => $prop_ID]);
            }

            if(key_exists('owner', $_POST) && $_POST['owner'] != 'Select Character'){
                $sql = "UPDATE Props
                        SET Props.owner = :newOwner
                        WHERE Props.ID = :ID;";
                pdo($pdo, $sql, [':newOwner' => $characters[$_POST['owner']], ':ID' => $prop_ID]);
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
    function get_prop_info(PDO $pdo, $prop_id) {
        $sql = "SELECT * FROM Props WHERE ID = :id;";

        $prop_info = pdo($pdo, $sql, ['id' => $prop_id])->fetch();

        return $prop_info;
    }


    /* TO-DO: Call function to retrieve toy information */
    $item = get_prop_info($pdo, $prop_ID);

?>

<section class="toy-details-page container">
    <div class="toy-details-container">
        <div class="toy-image">

            <!-- Commented out displaying image for expo -->
            <!--<img src="imgs/props_default.png" alt="<?= $item["name"] ?>">-->

        </div>

        <!-- Back button -->
        <form method="POST" action="location.php">
            <button type="submit" name="msg" value="<?=$prop_ID?>&<?=$role?>&<?=$world_ID?>&propBack">Back</button>
        </form>

        <form class="toy-details" method="POST" action="prop.php">
            <?php if($role == 'gm'){ ?>
                <div class="form-row">
                    <label for="name">Name:</label>
					<input type="text" value="<?=$item["name"]?>" id="name" name="name">
                </div>
                <div class="form-row">
                    <label for="description">Description:</label>
					<input type="text" value="<?=$item["description"]?>" id="description" name="description">
                </div>
                <div class="form-row">
                    <label for="itemType">Type:</label>
					<input type="text" value="<?=$item["itemType"]?>" id="itemType" name="itemType">
                </div>
                <div class="form-row">
                    <label for="rarity">Rarity:</label>
					<input type="text" value="<?=$item["rarity"]?>" id="rarity" name="rarity">
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
                    <label for="quantity">Quantity:</label>
					<input type="number" value="<?=$item["quantity"]?>" id="quantity" name="quantity">
                </div>

                <div class="form-row">
                    <label for="isIn">Send To:</label>
					<select name="isIn" id="isIn">
                        <option>Select Location</option>
                        <?php foreach($locations as $locName => $locID){ ?>
                        <option><?= $locName ?></option>
                    <?php } ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="owner">Give To:</label>
                    <select name="owner" id="owner">
                        <option>Select Character</option>
                        <?php foreach($characters as $charName => $charID){ ?>
                            <option><?= $charName ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } else { ?>
                <!-- TO-DO: Display the toy name -->
                <h1><?= $item["name"] ?></h1>

                <h3>Item Information</h3>

                <!-- TO-DO: Display the item description -->
                <p><strong>Description:</strong> <?= $item["description"] ?></p>

                <!-- TO-DO: Display item type -->
                <p><strong>Type:</strong> <?= $item["itemType"] ?></p>

                <!-- TO-DO: Display the item rarity -->
                <p><strong>Rarity:</strong> <?= $item["rarity"] ?></p>

                <!-- TO-DO: Display item quantity -->
                <p><strong>Quantity:</strong> <?= $item["quantity"] ?></p>

                <div class="form-row">
                    <label for="partyNotes">Party's Notes:</label>
					<input type="text" value="<?=$item["partyNotes"]?>" id="partyNotes" name="partyNotes">
                </div>
            <?php } ?>
            <button type="submit" name="msg" value="<?=$prop_ID?>&<?=$role?>&<?=$world_ID?>&edit">Save Changes</button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>