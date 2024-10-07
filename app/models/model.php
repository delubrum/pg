<?php
class Model {
    private $pdo;
    public function __CONSTRUCT() {
        try {
            $this->pdo = Database::Conectar();
            $pdo = null;
        }
            catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function log($query) {
        // if(!isset($_SESSION)) { 
        //     session_start(); 
        // }
        // $userId = $_SESSION["id-APP"];
        // $query = addslashes($query);
        // $query = trim(preg_replace( "/\r|\n/", "", $query));
        // $query = preg_replace('/\s+/', ' ', $query);
        // try {
        //     $sql = "INSERT INTO log (query,userId) VALUES (
        //         '$query',
        //         '$userId'
        //     )";
		// 	$this->pdo->prepare($sql)->execute();
        // }
        //     catch (Exception $e) {
        //     die($e->getMessage());
        // }    
    }

    public function save($table,$item) {
        $keys = '';
        $vals = '';
        foreach ($item as $k => $v) {
            $vals .= "'$v" . "',";
            $keys .= $k .',';
        }
        $keys = rtrim($keys, ",");
        $vals = rtrim($vals, ",");
        try {
            $sql = "INSERT INTO $table ($keys) VALUES ($vals)";
			$this->pdo->prepare($sql)->execute();
            $id = $this->pdo->lastInsertId();
            $this->log($sql);
            return $id;
        }
            catch (Exception $e) {
            die($e->getMessage());
        }    
    }

    public function update($table,$item,$id) {
        $vals = '';
        foreach ($item as $k => $v) {
            $vals .= $k . " = '$v" . "',";
        }
        $vals = rtrim($vals, ",");
        try {
            $sql = "UPDATE $table 
                SET $vals 
                WHERE id = '$id'";
            $this->log($sql);
            return $this->pdo->prepare($sql)->execute();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function updateAll($table,$item,$ids) {
        $vals = '';
        foreach ($item as $k => $v) {
            $vals .= $k . " = '$v" . "',";
        }
        $vals = rtrim($vals, ",");
        try {
            $sql = "UPDATE $table 
                SET $vals 
                WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
            $this->log($sql);
            return $this->pdo->prepare($sql)->execute();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function delete($table,$filters) {
        try {
            $sql = "DELETE FROM $table 
            WHERE $filters
            ";
            $this->pdo->prepare($sql)->execute();
            $this->log($sql);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function list($fields,$table,$filters = '',$joins = '') {
        try {
            $stm = $this->pdo->prepare("SELECT $fields
            FROM $table
            $joins
            WHERE 1=1
            $filters
            ");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        }
            catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function get($fields,$table,$filters = '',$joins = '') {
        try {
            $sql = "SELECT $fields
            FROM $table
            $joins
            WHERE 1=1
            $filters
            ";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch(PDO::FETCH_OBJ);
        }
            catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function redirect() {
        header("location: 403.php");
    }

    public function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $token .= $codeAlphabet[$this->cryptoRandSecure(0, $max)];
        }
        return $token;
    }
    
    public function cryptoRandSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
    
    public function clearAuthCookie() {
        if (isset($_COOKIE["user_login"])) {
            setcookie("user_login", "");
        }
        if (isset($_COOKIE["random_password"])) {
            setcookie("random_password", "");
        }
        if (isset($_COOKIE["random_selector"])) {
            setcookie("random_selector", "");
        }
    }
    
}