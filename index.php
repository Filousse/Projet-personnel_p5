<?php

require_once 'vendor/autoload.php';

require('controller/frontend/BlogController.php');
require('controller/frontend/HomeController.php');
require('controller/backend/AdminController.php');
require('controller/backend/LoginController.php');

$loader = new \Twig_Loader_Filesystem(__DIR__  . '/view');

$twig = new \Twig_Environment($loader, [
    'debug' => true,
    'cache' => false
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());

// =============================================================================
// ============== ALL ACTIONS ROUTER OF WEBSITE ================================
// =============================================================================
try {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
// ==================== FRONTEND REQUESTS ======================================
// HOME PAGE
            case 'home':
              $lastPost = addHomePost();
              $content = $lastPost[0]['content'];
              $extraitContent = substr($content, 0, 700);
              echo $twig->render('homeView.twig', ['lastPost' => $lastPost[0], 'extraitContent' => $extraitContent]);
            break;
// BLOG PAGE
            case 'blog':
              $posts = addBlogPosts();
              echo $twig->render('blogView.twig', ['posts' => $posts]);
            break;
// METEO PAGE
            case 'meteo':
              echo $twig->render('meteoView.twig');
            break;
// COMMENTS BY CHAPITRE PAGE
            case 'post':
                    if (isset($_GET['id']) && $_GET['id'] >= 0) {
                      $post = addPost();
                      $comments = gettingComments();
                      $post['content'] = strip_tags($post['content']);
                      echo $twig->render('postView.twig', ['post' => $post, 'comments' => $comments]);
                    }
                    else {
                      echo $twig->render('errorView.twig', ['errorMessage' => "Aucun identifiant de billet n\'a été envoyé"]);
                    }
            break;
// ADD COMMENT IN COMMENTS BY CHAPITRE PAGE
            case 'addComment':
              if (isset($_GET['id']) && $_GET['id'] >= 0) {
                if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                  $author = htmlspecialchars($_POST['author']);
                  $comment = strip_tags($_POST['comment']);
                  addComment($_GET['id'], $author, $comment);
                }
                else {
                  echo $twig->render('errorView.twig', ['errorMessage' => "Tous les champs ne sont pas remplis !"]);
                }
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
            break;
// ==================== LOGIN CONTROL REQUESTS =================================
// LOGIN PAGE
            case 'login':
              echo $twig->render('loginView.twig');
            break;
// NEW USER
            case 'newUser':
            if (!empty($_POST['NewName']) && !empty($_POST['NewPassword'])){
              newUser($_POST['NewName'], password_hash($_POST['NewPassword'], PASSWORD_DEFAULT));
              echo $twig->render('checkingUserView.twig');
            }
            else {
              echo $twig->render('errorView.twig', ['errorMessage' => "Tous les champs ne sont pas remplis !"]);
            }
            break;
// OPENING ADMIN PAGE TO MAKING PREPARE $_SESSION CONTROL
            case 'admin':
              if (!empty($_POST['UName']) && !empty($_POST['Password'])){
                $UName = htmlspecialchars($_POST['UName']);
                $Password = htmlspecialchars($_POST['Password']);
                $error =  openAdmin($UName , $Password);
                if(!$error){
                  echo $twig->render('errorView.twig', ['errorMessage' => "Mot de passe et/ou pseudo erronés!"]);
                  }
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Tous les champs ne sont pas remplis !"]);
              }
            break;
// OPENING LOGOUT PAGE AND REDIRECTION TO LOGIN
            case "logout":
              if (isset($_SESSION['userName']) && isset($_SESSION['Password'])) {
                session_destroy();
                header("location: index.php?action=login");
            }
            break;
            case "readgarden":
              if (isset($_GET['id']) && $_GET['id'] >= 0 && isset($_SESSION['userName'])) {
                $garden = readGarden($_GET['id']);
                echo $twig->render('adminView.twig', ['garden' => $garden, 'userName' => $_SESSION['userName'], 'userId' => $_GET['id']]);
             } else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
             }
             break;
// ============== REQUESTS => CRUD + ($_SESSION CONTROL) ==============
//  OPENING FORM CREATE ARTICLE
            case "createGarden":
              if (isset($_SESSION['NewName']) && isset($_SESSION['NewPassword']) || isset($_SESSION['userName']) && isset($_SESSION['Password'])) {
                echo $twig->render('createGardenView.twig');
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
            break;
// SEND ARTICLE
            case 'sendGarden':
              if (isset($_SESSION['userName']) && isset($_SESSION['Password']) || isset($_SESSION['NewName']) && isset($_SESSION['NewPassword'])) {
                    if(!empty($_POST['title']) && !empty($_POST['email']) && !empty($_POST['text']) && !empty($_POST['adress']) && !empty($_POST['city'])) {
                      $title = htmlspecialchars($_POST['title']);
                      $email = htmlspecialchars($_POST['email']);
                      $text = strip_tags(htmlspecialchars($_POST['text']));
                      $adress = htmlspecialchars($_POST['adress']);
                      $city = htmlspecialchars($_POST['city']);

                      sendGarden($title, $email, $text, $adress, $city);
                      echo $twig->render('chekingCreateArticleView.twig');
                    }else {
                      echo $twig->render('errorView.twig', ['errorMessage' => "Veuillez remplir tout les champs!"]);
                  }
              }else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
              break;
// OPEN DASHBOARD AND READ ARTICLES
            case 'dashboard':
              if (isset($_SESSION['userName']) && isset($_SESSION['Password'])){
                readingArticles();
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
            break;
// UPDATE ARTICLE
            case 'openupdate':   /* OPEN ARTICLE UPDATE PAGE */
              if (isset($_SESSION['userName']) && isset($_SESSION['Password'])) {
                $post = openArticleUpdate($_GET['id']);
                echo $twig->render('updateArticleView.twig', ['post' => $post]);
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
            break;
            case 'update':    /* UPDATE ARTICLE */
              if (isset($_SESSION['userName']) && isset($_SESSION['Password'])) {
                  $user = updatingArticle($_GET['id'], $_POST['title'], $_POST['email'], $_POST['text'], $_POST['adress'], $_POST['city']);
                  $title = htmlspecialchars($_POST['title']);
                  $email = htmlspecialchars($_POST['email']);
                  $text = strip_tags(htmlspecialchars($_POST['text']));
                  $adress = htmlspecialchars($_POST['adress']);
                  $city = htmlspecialchars($_POST['city']);
                  echo $twig->render('chekingUpdateArticleView.twig', ['user' => $user]);
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
              break;
// DELETE ARTICLE
            case 'delete':
              if (isset($_SESSION['userName']) && isset($_SESSION['Password'])) {
                deletingArticle($_GET['id'], $_GET['user_id']);
                echo $twig->render('chekingDeleteArticleView.twig');
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
            break;
// COMMENT ADMIN PAGE
            case 'comment':
              if (isset($_SESSION['userName']) && isset($_SESSION['Password'])) {
                $commentTrue = openCommentAdmin($_GET['id']);
                if(!empty($commentTrue)){
                  echo $twig->render('deleteCommentView.twig', ['comments' => $commentTrue]);
                } else {
                  echo $twig->render('infoCommentView.twig');
                }
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
            break;
// DELETE COMMENT
            case 'deletecomment':
              if (isset($_SESSION['userName']) && isset($_SESSION['Password'])) {
                deletingComment($_GET['id']);
                echo $twig->render('chekingDeleteCommentView.twig');
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
            break;
// COMMENT SIGNALER FRONTEND PAGE
            case 'openNotify':
              if (isset($_SESSION['userName']) && isset($_SESSION['Password'])) {
                $notifiedComments = openNotify();
                if (!empty($notifiedComments)) {
                  echo $twig->render('notifyView.twig', ['notifiedComments' => $notifiedComments]);
                }
                else {
                  echo $twig->render('errorView.twig', ['errorMessage' => "Il n'y a aucun commentaire signalé."]);
                }
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Vous n'êtes pas connecté"]);
              }
            break;
// READ NOTIFY
            case 'signaler':
              if (isset($_GET['id']) && isset($_GET['post_id'])) {
                postCommentNotify($_GET['id'], $_GET['post_id']);
                $postId = $_GET['post_id'];
                echo $twig->render('chekingNotifyCommentView.twig', ['postId' => $postId]);
              }
              else {
                echo $twig->render('errorView.twig', ['errorMessage' => "Il n'y a pas d'id séléctionner"]);
              }
            break;
          }
    }
// ============= IF NO ACTION => HOME PAGE =====================================
    else {
      $lastPost = addHomePost();
      $content = $lastPost[0]['content'];
      $extraitContent = strip_tags(substr($content, 0, 700));
      echo $twig->render('homeView.twig', ['lastPost' => $lastPost[0], "extraitContent" => $extraitContent]);
    }
}
catch(Exception $e) {
    $errorMessage = $e->getMessage();
    echo $twig->render('errorView.twig', ['errorMessage', $errorMessage]);
}
