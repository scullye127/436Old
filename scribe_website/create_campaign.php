<?php

require_once('includes/database-connection.php');
require_once('includes/session.php'); // ← FIXED: was missing, so $_SESSION was never populated

// Redirect if not logged in
if (!$logged_in) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $campaign_name = isset($_POST['campaign_name']) ? trim($_POST['campaign_name']) : '';
    $user = $_SESSION['username'];

    if ($campaign_name !== '') {

        // 1. Create a new top-level Location to represent the campaign world
        $sql = "INSERT INTO Locations (name, description, img_src)
                VALUES (:name, :description, :img_src)";

        pdo($pdo, $sql, [
            ':name'        => $campaign_name,
            ':description' => '',
            ':img_src'     => 'imgs/world_default.png'
        ]);

        $newID = $pdo->lastInsertId();

        // 2. Register the creator as GM in PlayingIn
        $sql = "INSERT INTO PlayingIn (user, plays, world, role)
                VALUES (:user, NULL, :world, 'gm')";

        pdo($pdo, $sql, [
            ':user'  => $user,
            ':world' => $newID
        ]);

        // 3. FIXED: POST redirect to location.php so $msg is read correctly
        //    location.php reads $_POST['msg'], not GET params
        echo '
        <form id="redirect" method="POST" action="location.php">
            <input type="hidden" name="msg" value="' . $newID . '&gm&' . $newID . '">
        </form>
        <script>document.getElementById("redirect").submit();</script>
        ';
        exit;
    }
}

include("./includes/header.php");
?>

<div id="content" class="login-container animate-bottom">
    <h1>New Campaign</h1>
    <hr />

    <form method="POST" action="create_campaign.php" class="login-form">
        <div class="form-group">
            <label for="campaign_name">Campaign Name:</label>
            <input type="text" id="campaign_name" name="campaign_name" placeholder="Campaign Name" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Create Campaign" class="submit-btn">
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>