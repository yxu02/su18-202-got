<?php

date_default_timezone_set('America/Los_Angeles');

define("API_KEY", "cmpe202kungfubelikewater"); // key to protect our API from random requests


require 'db.php';


function getUserIdFromToken($token){
	return $token; // for this first version, we are going to use the userId as token
}



// - - - - - - - - - - - - -
// Database functions


function doQueryInDatabase($query) {
	global $mysqli;

	$res = $mysqli->query($query);

	if ($res)
		return $res;

	returnErrorDatabase();
}


function getRewardsBalanceFromUserWithId($userId){
	global $mysqli;
    $email = mysqli_real_escape_string($mysqli, $email);
    $res = doQueryInDatabase("SELECT balance FROM rewards WHERE user_id = '$userId' LIMIT 1");
    if ($res->num_rows == 0)
        return null;

    $row = $res->fetch_assoc();

    return $row['balance'];
}


// - - - - - - - - - - - - -
// Aux functions


function checkAuthorization($onlyCheckAPIKey = false) {
	if (!isset($_REQUEST["apiKey"]) || $_REQUEST["apiKey"] !== API_KEY )
		returnErrorNotAuthenticated("Wrong API Key");

	if ($onlyCheckAPIKey)
		return true;

	if (!isset($_REQUEST["token"]))
		returnErrorNotAuthenticated("Missing User Token");

	$userId = getUserIdFromToken($_REQUEST["token"]);
	if ($userObj== false)
		returnErrorNotAuthenticated("Invalid User Token");

	return $userId;
}




// - - - - - - - - - - - - -
// Return Errors


function returnErrorDatabase(){
	global $mysqli;
	returnError(1, "An error occurred communicating with the database", $mysqli->error);
}

function returnErrorNotAuthenticated($errorMsgDetail){
    returnError(2, "You are not logged in. Please sign in.", $errorMsgDetail);
}

function returnError($code, $msg, $msgDetail = null){
	$ret = new StdClass();
	$ret->errorCode = $code;
	$ret->errorMessage = $msg;
	$ret->errorMessageDetail = $msgDetail;
    returnJSON($ret);
}


// - - - - - - - - - - - - -
// Return Json

function returnJSON($obj) {
    header("Content-type: application/json");
    $out = json_encode($obj, JSON_PRETTY_PRINT);
    print $out;
    exit;
}


?>