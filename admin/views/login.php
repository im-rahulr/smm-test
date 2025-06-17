 <html>
<head></head>
<body style="background-image: linear-gradient(to right top, #252024, #322428, #3f2827, #472f22, #48391d);">

</body>

<div id="displayDivId" style="display: none;">
    <?php include 'header.php'; ?>
</div>
<br><br><br><br>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<center><a href='https://hostwala.in/' data-toggle='https://hostwala.in/' data-target='https://hostwala.in/'><img src='https://i.postimg.cc/MZfh3wgL/e00da03b685a0dd18fb6a08af0923de0-removebg-preview.png' width='280' height='60'> </a><br><br><strong style="color:#fff;font-size:20px;">Script By  : <a href="#" target="_blank" style="color:red;">AbhijeetFix</a></strong> <img src="https://i.imgur.com/YLalTAd.png" width="20" height="20"></center>
	 <br>
	 

<div class="container" style="color:#fff;">
    <div class="container container-fluid" role="main">
        <div class="col-sm-offset-4 col-sm-4 m-t">
<?php if (!$_SESSION["username"]) : ?>

            <form id="yw0" action="" method="post">
<input name="check" value="1" type="hidden" />

           <div class="form-group">
                    <label for="exampleInputEmail1"><i class="fa fa-user-circle" aria-hidden="true"></i> Username</label>
                    <input class="form-control" name="username" id="AdminUsers_login"  type="text" maxlength="300" /> </div>


                



                <?php if ($_SESSION["recaptcha"]) : ?>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="<?php echo $settings["recaptcha_key"] ?>"></div>
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-default"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</button>
            </form>
        </div>
    </div>

</form>
<?php endif; ?>

<?php if ($_SESSION["username"]) : ?>
           

     


<form id="yw0" action="" method="post" style="color:#fff;">
                 

     
           <div class="form-group" >
                    <label for="exampleInputEmail1"><i class="fa fa-user-circle" aria-hidden="true"></i> Username</label>
                    <input class="form-control" name="username" id="AdminUsers_login" type="text" value="<?php echo $_COOKIE['username'] ?>" maxlength="300" /> </div>


                <div class="form-group">
                    <label for="exampleInputPassword1"><i class="fa fa-lock" aria-hidden="true"></i> Password</label>
                    <input class="form-control" name="password" id="AdminUsers_passwd" type="password" maxlength="300" /> </div>




                <?php if ($_SESSION["recaptcha"]) : ?>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="<?php echo $settings["recaptcha_key"] ?>"></div>
                    </div>
                <?php endif; ?>
                <div class="checkbox">
                    <label>
                        <input type="hidden" name="remember" value="1">

                         <input type="checkbox" name="AdminUsers[remember]" id="remember" value="1"> Remember me 
                    </label>
                </div>
                <button type="submit" class="btn btn-default"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</button>
            </form>
	 
        </div>
    </div>
    <br><br><br><br><br><br>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<center><a href='https://hostwala.in/' data-toggle='https://hostwala.in/' data-target='https://hostwala.in/'><img src="https://i.postimg.cc/s23qDztK/Fire-3-removebg-preview.png"> </a></center>
    <?php endif; ?>
    
</div>

																			<img src="" class="img-fluid">
																	


<script src="//www.google.com/recaptcha/api.js?hl=en"></script>

<?php include 'footer.php'; ?>