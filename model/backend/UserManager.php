<?php
namespace P5\Model\Backend;
require_once("model/Manager.php");

class UserManager extends \P5\Model\Manager {

// ====================== LOGIN CONTROL ========================================

// INSER USER
// CREATE
    function createUser($newName, $newPassword) {
      $db = $this->dbConnect();
      $newUser = $db->prepare('INSERT INTO users ( pseudo, password) VALUES(?, ?)');
      $affectedLines = $newUser->execute(array( $newName, $newPassword));

      return $affectedLines;
    }

// CONTROL USER
    public function getUser($pseudo) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, pseudo, password FROM users WHERE pseudo = ? ');
        $req->execute(array($pseudo));
        $data = $req->fetch();

        return $data;
    }
}
