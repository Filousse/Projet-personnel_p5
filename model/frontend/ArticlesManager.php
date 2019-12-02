<?php

namespace P5\Model\Frontend;
require_once("model/Manager.php");

class ArticlesManager extends \P5\Model\Manager {

// ======================== ARTICLE FRONTENT REQUEST ===========================

// LAST POST
    public function getLastPost() {
        $db = $this->dbConnect();
        $req = $db->query('SELECT id, title, content,adress_street, adress_city, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM articles ORDER BY date_creation DESC LIMIT 0, 1');

        return $req->fetchAll();
    }
// ID POST SELECTED
    public function getPost($postId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, email, content, adress_street, adress_city, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM articles WHERE id = ?');
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }

    public function getPostOfUser($userId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, email, content, adress_street, adress_city, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM articles WHERE user_id = ?');
        $req->execute(array($userId));
        $postUser = $req->fetch();

        return $postUser;
    }

// TITLES ARTICLES LIST
    public function getBlogPosts() {
    $db = $this->dbConnect();
    $req = $db->query('SELECT id, title, content, adress_street, adress_city, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM articles ORDER BY date_creation DESC LIMIT 0, 1000000000 ');

    return $req;
    }

}
