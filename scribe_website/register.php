<?php

require_once 'includes/database-connection.php';
require_once 'includes/session.php';

if ($logged_in) {  
  header('Location: dashboard.php');  
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  try {
    $stmt = $pdo->prepare("INSERT INTO Users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashedPassword]);

    // Optional: auto-login after registration
    $user = ['username' => $username];
    login($user);

    header('Location: dashboard.php');
    exit;

  } catch (PDOException $e) {
    $error = "Username already exists.";
  }
}

include("./includes/header.php");
?>

<div id="content" class="login-container animate-bottom">
    <h1>Register</h1>
    <hr />

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="register.php" class="login-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <input type="submit" value="Register" class="submit-btn">
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

