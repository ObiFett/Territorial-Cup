<?php
include("./Models/user.model.php");

class UserController {

    private $params;
    private $model;
    private $page;

    public function __construct($p){
        $this->params = $p;
        $this->page = new View();
        $no_login = array("login", "create", "recovery");
        if(!in_array($p[2], $no_login) && !isset($_SESSION['username'])){
            $_SESSION['prev_URI'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . HOST . "/user/login");
            exit();
        } else if (in_array($p[2], $no_login) && isset($_SESSION['username'])){
            header('Location: ' . HOST);
            exit();
        }
        if(method_exists($this, $p[2]))
            $this->$p[2]();
        else
            $this->login();
    }

    private function settings(){

        $this->page->render("./User/settings.view.php");
    }

    private function recovery(){

        $this->page->render("./User/recovery.view.php");
    }

    private function password(){

        $this->page->render("./User/password.view.php");
    }

    private function options(){

        $this->page->render("./User/options.view.php");
    }

    private function login(){
        $this->page->vars['login_error'] = "";
        $this->page->vars['user_value'] = "";
        
        if(isset($_POST['user']) && isset($_POST['pword'])){
            $this->model = new UserModel();
            $results = $this->model->get($_POST['user'], $_POST['pword']);
            if(!empty($results)){
                $_SESSION['username'] = $_POST['user'];
                $_SESSION['pl_id'] = $results[0]['pl_ID'];
                header('Location: ' . HOST . $_SESSION['prev_URI']);
                exit();
            }
            $this->page->vars['login_error'] = "Invalid Username/Password combination";
            $this->page->vars['user_value'] = 'value="' . $_POST['user'] . '"';
        }
        $this->page->render("./User/login.view.php");
    }

    private function create(){
        if(isset($_POST['username'])){
            $this->model = new UserModel();
            $results = $this->model->checkavail($_POST['username'], $_POST['email']);
            if(empty($results)){
                $cols = array(  'pl_uname',
                                'pl_email',
                                'pl_pword');
                $data = array(  $_POST['username'],
                                $_POST['email'],
                                $_POST['password']);
                $insert_results = $this->model->insert($cols, $data);
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['pl_id'] = $insert_results[0][0];
                header('Location: ' . HOST);
                exit();
            }
        } else {
            $this->page->render("./User/create.view.php");
        }
    }
}
?>
