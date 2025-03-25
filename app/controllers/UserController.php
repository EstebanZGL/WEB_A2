<?php
require_once 'app/models/UserModel.php';

class UserController {
    public function login() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user;
                header('Location: /cesi-lebonplan/home');
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect.';
            } 
        }

        require 'app/views/user/login.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /cesi-lebonplan/login');
        exit;
    }
}
?>
