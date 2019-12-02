<?php
require "vendor/autoload.php";
use P5\Model\{
  Backend\AdminManager,
  Backend\UserManager,
  Frontend\ArticlesManager,
  Frontend\CommentsManager
};
session_start();

// ================ ARTICLE ADMIN => CRUD ======================================

function sendGarden($title, $mail, $text, $adress, $city){

            $UserManager = new UserManager();
            $user = $UserManager -> getUser($_SESSION['NewName']);
            $AdminManager = new AdminManager();
            $extraitContent =  html_entity_decode(htmlentities($_POST['text'], ENT_NOQUOTES, 'UTF-8'));
            $data = $AdminManager->createArticle($_POST['title'], $extraitContent, $user['id'], $_POST['adress'], $_POST['city'], $_POST['email']);
}
// READ => Article
function readingArticles(){
          $UserManager = new UserManager();
          $user = $UserManager -> getUser($_SESSION['userName']);

          $adminManager = new AdminManager();
          $gardenArray = $adminManager->userGarden($user['id']);

          header('Location: index.php?action=readgarden&id='. $user['id']);
}
function readGarden($userId){
          $adminManager = new AdminManager();
          $garden = $adminManager -> userGarden($userId);
          return $garden;
}
// UPDATE => Article
function openArticleUpdate($id){
          $postManager = new ArticlesManager();
          $post = $postManager->getPost($_GET['id']);
          return $post;
}
function updatingArticle($id, $title, $content, $adress, $city, $email){
          $AdminManager = new AdminManager();
          $Content =  html_entity_decode(htmlentities($content, ENT_NOQUOTES, 'UTF-8'));
          $newUpdate = $AdminManager->updateArticle($id, $title, $Content, $adress, $city, $email);
          $userManager = new UserManager();
          $user = $userManager->getUser($_SESSION['userName']);
          return $user;
}
// DELETE => Article
function deletingArticle($id, $userId){
          $deleteManager = new AdminManager();
          $rowDelete = $deleteManager->deleteArticle($id, $userId);
          $userManager = new UserManager();
          $user = $userManager->getUser($_SESSION['userName']);
}

// ============ COMMENTS ADMIN  ================================================

function openCommentAdmin($id){
          $commentManager = new CommentsManager();
          $comments = $commentManager->getComments($_GET['id']);
          $postManager = new ArticlesManager();
          $post = $postManager->getPost($_GET['id']);
          $commentTrue = $comments->fetchAll();
          return $commentTrue;
}

function deletingComment($id){
  $AdminManager = new AdminManager();
  $newUpdate = $AdminManager->deleteComment($id);

}
function openNotify(){
  $userManager = new UserManager();
  $user = $userManager->getUser($_SESSION['userName']);
  $post = new ArticlesManager();
  $postUser = $post->getPostOfUser($user['id']);
  $commentManager = new CommentsManager();
  $notifyComments = $commentManager->notifyComments($postUser['id']);
  return $notifyComments->fetchAll();
}
