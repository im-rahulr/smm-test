<?php include 'header.php'; ?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<center><strong style="color:#000;font-size:20px;">Script By  : <a href='https://hostwala.in/' data-toggle='https://hostwala.in/' data-target='https://hostwala.in/' style="color:red;">AbhijeetFix</a></strong> <img src="https://i.imgur.com/YLalTAd.png" width="20" height="20"></center><br><br>

<div class="container">
  <div class="row">
    <?php if( ( route(2) == "themes" && !route(3) ) || route(2) != "themes"  ):  ?>
          <div class="col-md-2 col-md-offset-1">
            <ul class="nav nav-pills nav-stacked p-b">
              <?php foreach($menuList as $menuName => $menuLink ): ?>
                <li class="appearance_menus <?php if( $route["2"] == $menuLink ): echo "active"; endif; ?>"><a href="<?=site_url("admin/appearance/".$menuLink)?>"><?=$menuName?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
    <?php  endif;
          if( $access ):
            include admin_view('appearance/'.route(2));
          else:
            include admin_view('settings/access');
          endif;
    ?>


  </div>
</div>


<?php include 'footer.php'; ?>
