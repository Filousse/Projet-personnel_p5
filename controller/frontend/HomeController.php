<?php
require "vendor/autoload.php";
use P5\Model\Frontend\ArticlesManager;

// OPEN HOME PAGE AND LAST POST ARTICLE
function addHomePost(){
          $postManager = new ArticlesManager();
          $lastPost = $postManager->getLastPost();
          return $lastPost;
}
