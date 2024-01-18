<?php
require_once 'app/models/model.php';

$model = new Model();
// Get Current date, time
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);
// Set Cookie expiration for 1 month
$cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month
$isLoggedIn = false;
// Check if loggedin session and redirect if session exists
if (!empty($_SESSION["id-APP"])) {
  $isLoggedIn = true;
}
// Check if loggedin session exists
else if (!empty($_COOKIE["user_login"]) && !empty($_COOKIE["random_password"]) && !empty($_COOKIE["random_selector"])) {
  // Initiate auth token verification diirective to false
  $isPasswordVerified = false;
  $isSelectorVerified = false;
  $isExpiryDateVerified = false;
  // Get token for username
  $cookie = $_COOKIE["user_login"];
  $userToken = $model->get('*','tokenAuth'," and email = '$cookie' and is_expired = '0'");
  if(!empty($userToken)) {
    // Validate random password cookie with database
    if (password_verify($_COOKIE["random_password"], $userToken->password_hash)) {
      $isPasswordVerified = true;
    }
    // Validate random selector cookie with database
    if (password_verify($_COOKIE["random_selector"], $userToken->selector_hash)) {
      $isSelectorVerified = true;
    }
    // check cookie expiration by date
    if($userToken->expiry_date >= $current_date) {
      $isExpiryDateVerified = true;
    }      
    // Redirect if all cookie based validation retuens true
    // Else, mark the token as expired and clear cookies
    if (!empty($userToken->id) && $isPasswordVerified && $isSelectorVerified && $isExpiryDateVerified) {
      $isLoggedIn = true;
      $user = $model->get('id,password,lang','users'," and email = '$userToken->email' and status = '1'");
      session_start();
      $_SESSION["id-APP"] = $user->id;
      session_write_close();
      $load_lang = $user->lang;
      $lang_json = file_get_contents("app/assets/lang/".$load_lang.".json");
      $lang = json_decode($lang_json, true);
    } else {
      if(!empty($userToken->id)) {
        $item = new stdClass();
        $item->is_expired = 1;
        $model->update('tokenAuth',$item,$userToken->id);
      }
      // clear cookies
      $model->clearAuthCookie();
    }
  }
}

?>