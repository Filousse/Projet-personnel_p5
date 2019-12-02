<?php
require "vendor/autoload.php";
use P5\Model\{
  Frontend\ArticlesManager,
  Frontend\CommentsManager
};

// OPENING BLOG PAGE WITH CHAPITRE LIST
function addBlogPosts() {
          $postManager = new ArticlesManager();
          $posts = $postManager->getBlogPosts();
          return $posts->fetchAll();
}
// OPENING POST PAGE WITH COMMENT SYSTEME
function addPost() {
          $postManager = new ArticlesManager();
          $post = $postManager->getPost($_GET['id']);
          return $post;
}
function gettingComments() {
          $commentManager = new CommentsManager();
          $comments = $commentManager->getComments($_GET['id']);
          return $comments->fetchAll();
}
// ADD NEW COMMENT
function addComment($postId, $author, $comment) {
          $commentManager = new CommentsManager();
          $comment =  html_entity_decode(htmlentities($comment, ENT_NOQUOTES, 'UTF-8'));

          $affectedLines = $commentManager->postComment($postId, $author, $comment);

          header('Location: index.php?action=post&id=' . $postId);
}
// NOTIFY BUTTON SENDING FOR ADMIN
function postCommentNotify($id, $postId){
          $commentManager = new CommentsManager();
          $notify = $commentManager->postNotify($id);
}
