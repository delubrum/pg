<?php
require_once 'app/models/model.php';

class HomeController{
  private $model;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

	public function Index() {
		require_once "lib/auth.php";
		if ($isLoggedIn) {
			$notifications = $this->model->list('title,itemId,url,target,permissionId','notifications', "and status = 1");
			require_once "lib/check.php";
      require_once 'app/components/layout/index.php';
		} else {
			if (isset($_REQUEST['pass']) && $_REQUEST['pass'] !== '') {
				$isAuthenticated = false;
				$password = strip_tags($_REQUEST['pass']);
				$email = strip_tags($_REQUEST['email']);
				if ($this->model->get('id,password,lang','users',"and email = '$email' and status = 1")) {
					$user = $this->model->get('id,password,lang','users',"and email = '$email' and status = 1");
					if (password_verify($password, $user->password)) {
						$isAuthenticated = true;
					}
					if ($isAuthenticated) {
						session_start();
						$_SESSION["id-APP"] = $user->id;
						session_write_close();
						setcookie("user_login", $email, $cookie_expiration_time);
						$random_password = $this->model->getToken(16);
						setcookie("random_password", $random_password, $cookie_expiration_time);
						$random_selector = $this->model->getToken(32);
						setcookie("random_selector", $random_selector, $cookie_expiration_time);
						$random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
						$random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
						$expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
						
						// Mark existing token as expired
						$userToken = $this->model->get('*','tokenAuth',"and email = '$email' and is_expired = 0");
						if (! empty($userToken->id)) {
							$item = new stdClass();
							$item->is_expired = 1;
							$this->model->update('tokenAuth',$item,$userToken->id);
						}
						
						// Insert new token
						$item = new stdClass();
						$item->email = $email;
						$item->password_hash = $random_password_hash;
						$item->selector_hash = $random_selector_hash;
						$item->expiry_date = $expiry_date;
						$this->model->save('tokenAuth',$item);
						echo 'ok';
						exit();
					} else {
						echo "Error"; // Opcional: mostrar un mensaje de error
					}
				} else {
						echo "Error"; // Opcional: mostrar un mensaje de error
				}
			} else {
				require_once 'app/views/login/index.php';
			}
		}
	}

	public function Notifications() {
		$notifications = $this->model->list('title,itemId,url,target,permissionId','notifications', "and status = 1");
		if ($_REQUEST['list'] == 0) {
			echo count($notifications);
		} else {
			require_once "app/components/layout/notifications-list.php";
		}
	}

  public function Sidebar() {
    require_once "lib/check.php";
		require_once "app/components/layout/sidebar-menu.php";
	}

  public function Logout() {
    session_start();
    $_SESSION["id-APP"] = "";
    session_destroy();
    $this->model->clearAuthCookie();
    header('Location: ?c=Home&a=Index');
  }

}