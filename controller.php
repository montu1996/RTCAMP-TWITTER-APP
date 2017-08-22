<?php
    session_start();
    require './model.php';
    $model = new Model();

    // Login With Twitter
    if( isset($_POST['login']) ) {
        $model->twitter_connect();
    }

    // CallBack
    if ( isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) ) {
        $model->callback();
    }

    //GetSelected User Profile
    if(isset($_GET['followers']) ) {
        $id = $_GET['usr_id'];
        $model->getFollowerInfo($id);
    }

    // Download Public User Tweets
    if( isset($_POST['search_public_user']) ) {
        $key = $_POST['key'];
        $model->downloadPublicUserTweets($key);
        header('location: ./home.php');
    }

    if( isset($_GET['fetchFollowers']) ) {
        $screen_name = $_GET['fetchFollowers'];
        $model->getFollowers($screen_name);
    }

    if( isset($_GET['userdata']) && $_GET['userdata']==true ) {
        $model->getUserData();
    }

    // Download
    if( isset($_GET['download']) && $_GET['download']==true ) {
        $type=$_GET['type'];
        switch ($type) {
            case "csv":
                $model->downloadCSV();
                break;
            case "xls":
                $model->downloadXLS();
                break;
            case "json":
                $model->downloadJSON();
                break;
            case "google-spread-sheet":
                $_SESSION['user-tweets'] = $model->uploadGoogleDrive();
                header('location:lib\google-drive-api/index.php');
                break;
        }
    }
        
    // Logout
    if( isset($_GET['logout']) && $_GET['logout']==true ) {
        $model->logout();
    }

?>