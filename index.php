<?PHP
if(isset($_COOKIE['bnp_ips_user'])){
    $login_user_data = unserialize($_COOKIE['bnp_ips_user']);

    if($login_user_data['user_username'] != '' && $login_user_data['user_password'] != ''){
        ?>
        <script> window.location = "admin/" </script>
        <?PHP 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>BNP ERP System</title>
        <link rel="icon" href="upload/logo.png" type="image/png">

        <!-- Bootstrap Core CSS -->
        <link href="template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="template/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="template/dist/css/admin.css" rel="stylesheet">

        <script>
            function refresh(){
                window.location = "admin/"
            }

            function error(){
                alert("Can not login. Please check your username and password.");
                document.getElementById("error").innerHTML = "username and password.";
            }

            function check(){
                var user = document.getElementById("username").value;
                var pass = document.getElementById("password").value;
                
                if(user == ""){
                    alert("Please input username.");
                    document.getElementById("error").innerHTML = "Please input username.";
                    return false;
                }else if (pass == ''){
                    alert("Please input password.");
                    document.getElementById("error").innerHTML = "Please input password.";
                    return false;
                }

                return true;
            }
        </script>
    </head>

    <style>
        input{
            outline: none;
            font-size: 15px;
            font-weight: 400;
            color: #fff;
            background-color: unset;
            border: none;
            border-bottom-color: currentcolor;
            border-bottom-style: none;
            border-bottom-width: medium;
            border-bottom: 1px solid #fff;
            padding: 12px 13px 13px 44px;
            width: 100%;
            display: inline-block;
        }

        #username{
            background: url(template/images/user.png) no-repeat 0px 10px;
        }

        #password{
            background: url(template/images/pass.png) no-repeat 0px 10px;
        }
    </style>

    <body style="background-image: url(template/images/bnp-background.jpg);background-repeat: no-repeat;background-size: cover; height: 100vh;">
        <div style="background-color: #252324bd; height: 100%;">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default" style="margin-top: 40%; background-color: unset; border: unset;">
                            <div class="panel-body" style="padding: 2% 10% 8% 10%;">
                                <div align="center">
                                    <h3 style="color: #fff; background-color: #5c5c5c4d; padding: 20px 0px;">BNP Admin Panel</h3>
                                </div>
                                <iframe id="checklogin" name="checklogin" src="" style="width:0px;height:0px;border:0"></iframe>
                                <form role="form" method="post" action="check_login.php" onSubmit="return check();" target="checklogin">
                                    <fieldset>
                                        <div class="form-group" method="post" action="">
                                            <input placeholder="Username" id="username" name="username" type="text" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <input placeholder="Password" id="password" name="password" type="password" value="">
                                        </div>
                                        <div align="center" id="error" name="error" style="color:#F00;padding:8px;"></div>
                                        <button type="submit" class="btn btn-lg btn-block" style="background-color: #92d050;color: #fff; border-radius: unset; margin-top: 5%;">Login</button>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>