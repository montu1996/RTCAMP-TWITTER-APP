<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.css">
        <link rel="icon" type="image/png" href="https://icons.better-idea.org/icon?url=twitter.com&size=80..120..200">
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.css">
        <script src="./js/jquery-3.2.1.js"></script>
        <script src="./js/bootstrap.js"></script>
        <style>
            body {
                background-image: url("./images/login_wallpaper.jpg");
                background-repeat: no-repeat;
                background-size: cover;
            }
            #row {
                position: absolute;
                top: 50%;
            }
        </style>
    </head>
    <body class="container">
        <div class="row" id="row">
            <div class="col-sm-4">
                <form action="./controller.php" method='post'>
                    <div class="form-group">
                        <button type="submit" name="login" class="btn btn-default"><img src="./images/btn-twitter-login.png" style="width: 300px;height: 48px" /></button>
                    </div>
                </form>        
            </div>
        </div>
    </body>    
</html>