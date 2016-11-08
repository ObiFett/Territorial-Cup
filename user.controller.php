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

    private function login(){
     
        $return_message['status'] = "success";
        if(isset($_POST['user']) && isset($_POST['pword'])){
            $this->model = new UserModel();
            $results = $this->model->get($_POST['user'], $_POST['pword']);
            if(!empty($results)){
                $_SESSION['username'] = $_POST['user'];
                $_SESSION['pl_id'] = $results[0]['pl_ID'];
            } else {
                $return_message['login_error'] = "Invalid Username/Password combination";
                $return_message['user_value'] = $_POST['user'];
                $return_message['status'] = "error";
            }
            
            echo json_encode($return_message);
        }
    }
    


    private function create(){
        
        $return_message['status'] = "success";
        
        if(!($_POST['email'] === $_POST['emailconfirm']))
        {
            $return_message['status'] = "E-mail addresses do not match";
        }
        elseif(!($_POST['password'] === $_POST['passwordconfirm']))
        {
            $return_message['status'] = "Passwords do not match";
        }
        else
        {
        
        if(isset($_POST['username'])&& isset($_POST['password']) && isset($_POST['email'])){
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
            } else {
                $return_message['status'] = "Username Already Exists";
            }
        } else {
            $return_message['status'] = "Please fill in all the fields";
        }
        
        }
        
        echo json_encode($return_message);
        
        
    }
    
    private function changePassword(){
        $return_message['status'] = "success";
        if(isset($_POST['newPW'])){
            $this->model = new UserModel();
            $update_info['pl_pword'] = $_POST['newPW'];
            $this->model->update($_SESSION['username'], $update_info);
        } else {
            $return_message['status'] = "Please fill out all fields";
        }
        echo json_encode($return_message);
    }
    
    private function changeEmail(){
        $return_message['status'] = "success";
        if(isset($_POST['email'])){
            $this->model = new UserModel();
            $update_info['pl_email'] = $_POST['email'];
            $this->model->update($_SESSION['username'], $update_info);
        } else {
            $return_message['status'] = "Please fill out all fields";
        }
        echo json_encode($return_message);
    }
    
        private function emailPass(){
        $return_message['status'] = "fail";
        if(isset($_POST['email'])){
            $this->model = new UserModel();
            $results = $this->model->get($_POST['email']);
            
            $to = $_POST['email'];
            $subject = "Lost Password";
            $body = $results[0]['pl_pword'];
            if (mail($to, $subject, $body)) {
                $return_message['status'] = "Email successfully sent!";
            } else {
                $return_message['status'] ="Email delivery failed…";
            }
 
        }
        return json_encode($return_message);
    }
}
?>
