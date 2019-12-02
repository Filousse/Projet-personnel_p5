<?php
require "vendor/autoload.php";
use P5\Model\Backend\UserManager;

// NEW USER SESSION PAGE CONTROL
function newUser($newName, $newPassword){
          $loginManager = new UserManager();
          $newName = htmlspecialchars($newName);
          $newPassword = htmlspecialchars($newPassword);
          $data = $loginManager->createUser($newName, $newPassword);
          $_SESSION['NewName'] = $_POST['NewName'];
          $_SESSION['NewPassword'] = $_POST['NewPassword'];
}
// LOGIN SESSION PAGE CONTROL
try{
function openAdmin($user, $password) {
          $user = htmlspecialchars($user);
          $password = htmlspecialchars($password);
          $loginManager = new UserManager();
          $data = $loginManager->getUser($user);
          if ($data['pseudo'] === $_POST['UName']) {
                if (password_verify($password, $data['password'])) {
                  // DASHBOARD ADMIN SECURITY
                  $_SESSION['userName'] = $data['pseudo'];
                  $_SESSION['Password'] = $data['password'];
                } else {
                  return false;
                }
          } else {
            return false;
          }
          header('Location: index.php?action=readgarden&id='. $data['id']);
          }
}
catch(Exception $e) {
    $errorMessage = $e->getMessage();
    echo $twig->render('errorView.twig', ['errorMessage', $errorMessage]);
}
