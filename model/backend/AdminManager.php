<?php
namespace P5\Model\Backend;
require_once("model/Manager.php");

class AdminManager extends \P5\Model\Manager {

// ====================== ADMIN CRUD ==========================================

      public function userGarden($userId) {
        $db = $this->dbConnect();
        $garden = $db->prepare('SELECT id, title, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\')  AS articles_date_fr FROM articles WHERE user_id = ? ORDER BY articles_date_fr DESC');
        $garden->execute(array($userId));

        return $garden ->fetchAll();

      }
// CREATE
      function createArticle($title, $content, $userId, $adress, $city, $email) {
        $db = $this->dbConnect();
        $newArticles = $db->prepare('INSERT INTO articles( title,  content, date_creation, user_id, adress_street, adress_city, email) VALUES(?, ?, NOW(), ?, ?, ?, ?)');
        $affectedLines = $newArticles->execute(array( $title, $content, $userId, $adress, $city, $email));

        return $affectedLines;
      }


// UPDATE
    function updateArticle($id, $title, $content, $adress, $city, $email) {
        $db = $this->dbConnect();
        $newArticles = $db->prepare('UPDATE articles SET title=?, email=?, content=?, adress_street=?, adress_city=? WHERE id= '.$id.'');
        $updateLines = $newArticles->execute(array($title, $content, $adress, $city, $email));

        return $updateLines;
    }

// DELETE
    public function deleteArticle($id, $userId) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM articles WHERE id=? LIMIT 1');
        $req->execute(array($id));
        $reqUser = $db->prepare('DELETE FROM users WHERE id=?');
        $reqUser->execute(array($userId));

        return $req;
    }

// ====================== DELETE COMMENT =======================================

    public function deleteComment($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM comments WHERE id='.$id.' LIMIT 1');
        $req->execute(array($id));

        return $req;
    }
}
