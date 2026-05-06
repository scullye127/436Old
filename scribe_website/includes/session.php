<?php

use const Dom\STRING_SIZE_ERR;

	session_start();										// Start/renew session									 	
	$logged_in = $_SESSION['logged_in'] ?? false; 			// Is user logged in?      



	function login($user)									// Remember user passed login
	{
	    $_SESSION['logged_in'] = true;						// Set logged_in key to true
	    $_SESSION['username'] = $user['username'];			// Set username key to username from database 
		//$_SESSION['custID']   = $user['custID'];			// Set custID key to custID from database 

		session_regenerate_id(true); 						// Update session id
	}



	
	function require_login($logged_in)						// Check if user logged in				
	{
	    if ($logged_in == false) {							// If not logged in						
	    	header('Location: login.php');					// Send to login page 			
	        exit;    										// Stop rest of page running								
	    }
	}


	
	function logout() 										// Terminate the session 
	{
	    $_SESSION = [];										// Clear contents of array
	    $params = session_get_cookie_params();				// Get session cookie parameters

															// Delete session cookie
	    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'],
	        $params['secure'], $params['httponly']);	

	    session_destroy();									// Delete session file							
	}

	

	/* TO-DO: Create a function called authenticate() that:
          1. Accepts $pdo, username, and password as parameters
          2. Queries the customer table to find a row matching the provided username and password
          3. Executes the SQL query using the pdo() helper function and fetches the result
          4. Returns the matching user row if found
	*/
	function authenticate(PDO $pdo, string $username, string $password) {
    $sql = "SELECT * FROM Users WHERE username = :uName";
    
    $user = pdo($pdo, $sql, ["uName" => $username])->fetch();

    // Verify hashed password
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}

	function update_POST(string | null $locID, string | null $charID, string | null $propID, string | null $role = null){
		$_POST['locID'] = $locID;
		$_POST['charID'] = $charID;
		$_POST['propID'] = $propID;
		$_POST['role'] = $role;
	}

	function update_session(){
		$_SESSION['locID'] = $_POST['locID'];
		$_SESSION['charID'] = $_POST['charID'];
		$_SESSION['propID'] = $_POST['propID'];
		$_SESSION['role'] = $_POST['role'];
	}

	// Get all possible location IDs in a world
	// Recurses through all locations, adding them to array of locationName: locationID pairs
	function get_locations($pdo, $worldID){
		$sql = "SELECT Locations.name AS locName, Locations.ID AS locID
				FROM Locations
				JOIN Contains on Locations.ID = Contains.containee
				WHERE Contains.container = :worldID;";
		
		$results = pdo($pdo, $sql, [":worldID" => $worldID])->fetchAll();
		$locs = [];

		foreach ($results as $result){
			$locs[$result["locName"]] = $result["locID"];
			$locs = [...$locs, ...get_locations($pdo, $result["locID"])];
		}

		return $locs;
	}

	// Get a list of all possible characters in array of characterName: characterID pairs
	// Input is array of of locationName: locationID pairs
	function get_characters($pdo, $locations){
		$sql = "SELECT Characters.name as charName, Characters.ID as charID
				FROM Characters
				LEFT JOIN Players ON Players.ID = Characters.ID
				LEFT JOIN Creatures ON Creatures.ID = Characters.ID
				LEFT JOIN NPCs ON NPCs.ID = Characters.ID
				WHERE Characters.isAt = :id;";
		$chars = [];

		// For each location
		foreach($locations as $locName => $locID){
			$results = pdo($pdo, $sql, [":id" => $locID]);

			// For each character in the location
			foreach ($results as $result){
				$chars[$result["charName"]] = $result["charID"];
			}
		}

		return $chars;
	}
