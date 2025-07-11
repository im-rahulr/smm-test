<?php

$action = $_POST["action"];
$languages  = $conn->prepare("SELECT * FROM languages WHERE language_type=:type");
$languages->execute(array("type"=>2));
$languages  = $languages->fetchAll(PDO::FETCH_ASSOC);

  if( $action ==  "providers_list" ):
    $smmapi   = new SMMApi();
    $provider = $_POST["provider"];
    $api      = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
    $api     -> execute(array("id"=>$provider ));
    $api      = $api->fetch(PDO::FETCH_ASSOC);
      if( $api["api_type"] == 3 ):
        echo '<div class="service-mode__block">
          <div class="form-group">
            <label>Service</label>
            <input class="form-control" name="service" placeholder="Enter the service ID">
          </div>
        </div>';
      elseif( $api["api_type"] == 1 ):
        $services = $smmapi->action(array('key' =>$api["api_key"],'action' =>'services'),$api["api_url"]);
        echo '<div class="service-mode__block">
          <div class="form-group">
          <label>Service</label>
            <select class="form-control" name="service">';
                foreach ($services as $service) {
                  echo '<option value="'.$service->service.'"'; if($_SESSION["data"]["service"]==$service->service): echo 'selected';endif; echo '>'.$service->service.' - '.$service->name.' - '.priceFormat($service->rate).'</option>';
                }
                echo '</select>
          </div>
        </div>';
      endif;
    unset($_SESSION["data"]);
elseif( $action == "coustm_rate" ):
    $id     = $_POST["id"];
    $row    = $conn->prepare("SELECT * FROM clients WHERE client_id=:id ");
    $row ->execute(array("id"=>$id));
    $row    = $row->fetch(PDO::FETCH_ASSOC);
    
    $return = '<form class="form" action="'.site_url("admin/clients/set_discount/".$id).'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Discount Percentage (%)</label>
              <input class="form-control" name="coustm_rate" placeholder="25"   value="'.$row["coustm_rate"].'"    >
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Bulk Discount (For: ".$row["username"].") "]);

elseif( $action == "allmenu-sortable" ):
    $list = $_POST["menus"];
      foreach ($list as $menu) {
$id = explode("-",$menu["id"]);
        $update = $conn->prepare("UPDATE menus SET menu_line=:line WHERE id=:id ");
        $update-> execute(array("id"=>$id,"line"=>$menu["line"] ));
      }
  elseif( $action == "paymentmethod-sortable" ):
    $list = $_POST["methods"];
      foreach ($list as $method) {
        $update = $conn->prepare("UPDATE payment_methods SET method_line=:line WHERE id=:id ");
        $update-> execute(array("id"=>$method["id"],"line"=>$method["line"] ));
      }
  elseif( $action == "service-sortable" ):
    $list = $_POST["services"];
      foreach ($list as $service) {
        $id = explode("-",$service["id"]);
        $update = $conn->prepare("UPDATE services SET service_line=:line WHERE service_id=:id ");
        $update-> execute(array("id"=>$id[1],"line"=>$service["line"] ));
      }





  elseif( $action == "category-sortable" ):
    $list = $_POST["categories"];
      foreach ($list as $category) {
        $update = $conn->prepare("UPDATE categories SET category_line=:line WHERE category_id=:id ");
        $update-> execute(array("id"=>$category["id"],"line"=>$category["line"] ));
      }

 elseif( $action == "add_internal" ):
    
    $return = '<form class="form" action="'.site_url("admin/appearance/menu/add_internal").'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Menu Name</label>
            <input type="text" class="form-control" name="name" value="">
<input type="hidden" class="form-control" name="visible" value="Internal">
          </div>

          
          <div class="form-group">
            <label class="form-group__service-name">Menu Slug</label>
         <select class="form-control"  name="slug">
        
                    
                    <option value="/">New Order</option> 
                    <option value="/massorder">Mass Order</option>
                    <option value="/orders">Orders</option>
<option value="/refill">Refills</option> 
<option value="/services">Services</option> 
<option value="/addfunds">Add Funds</option>
<option value="/api">Api</option>
<option value="/tickets">Tickets</option>
<option value="/child-panels">Child Panels</option>
<option value="/refer">Affiliates</option>
<option value="/blog">Blog</option>
<option value="/terms">Terms</option>
<option value="/updates">Updates</option>
<option value="/">External</option>

                </select>
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Menu Icon</label>
            <input type="text" class="form-control" name="icon" value="" placeholder="fas fa-icon">
<p class="help-block">Select icon or paste icon class from <a href="https://anon.ws/?https://fontawesome.com/icons?d=gallery" target="_blank">FontAwesome5</a></p>
          </div>
       
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Menu</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Add Menu"]);
elseif( $action == "edit_internal" ):
    $id         = $_POST["id"];
    $menu   = $conn->prepare("SELECT * FROM menus WHERE id=:id ");
    $menu   ->execute(array("id"=>$id));
    $menu   = $menu->fetch(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/appearance/menu/edit_menu/".$id).'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Menu Name</label>
            <input type="text" class="form-control" name="name" value="'.$menu["name"].'">
          </div>
          <div class="form-group">
            <label class="form-group__service-name">Menu Slug</label>
            <select class="form-control"  name="slug">
<option value="/api"  '; if( $menu["slug"] == "/api" ): $return.='selected'; endif;  $return.='>Api</option>
                    <option value="/"  '; if( $menu["slug"] == "/" ): $return.='selected'; endif;  $return.='>New Order</option> 
                    <option value="/massorder"  '; if( $menu["slug"] == "/massorder" ): $return.='selected'; endif;  $return.='>Mass Order</option>
                    <option value="/orders"  '; if( $menu["slug"] == "/orders" ): $return.='selected'; endif;  $return.='>Orders</option>
<option value="/refill"  '; if( $menu["slug"] == "/refill" ): $return.='selected'; endif;  $return.='>Refills</option> 
<option value="/services"  '; if( $menu["slug"] == "/services" ): $return.='selected'; endif;  $return.='>Services</option> 
<option value="/addfunds"  '; if( $menu["slug"] == "/addfunds" ): $return.='selected'; endif;  $return.='>Add Funds</option>
<option value="/tickets"  '; if( $menu["slug"] == "/tickets" ): $return.='selected'; endif;  $return.='>Tickets</option>
<option value="/child-panels"  '; if( $menu["slug"] == "/child-panels" ): $return.='selected'; endif;  $return.='>Child Panels</option>
<option value="/refer"  '; if( $menu["slug"] == "/refer" ): $return.='selected'; endif;  $return.='>Affiliates</option>
<option value="/blog"  '; if( $menu["slug"] == "/blog" ): $return.='selected'; endif;  $return.='>Blog</option>
<option value="/terms"  '; if( $menu["slug"] == "/terms" ): $return.='selected'; endif;  $return.='>Terms</option>
<option value="/terms"  '; if( $menu["slug"] == "/updates" ): $return.='selected'; endif;  $return.='>Updates</option>

    </select>      </div>
<div class="form-group">
            <label class="form-group__service-name">Menu Icon</label>
           <input type="text" class="form-control" name="icon" value="'.$menu["icon"].'">
          
        
                    
                
          </div> 
</div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

         </form>';  
    echo json_encode(["content"=>$return,"title"=>"Edit menu item (".$menu["name"].") "]);


elseif( $action == "edit_external" ):
    $id         = $_POST["id"];
    $menu   = $conn->prepare("SELECT * FROM menus WHERE id=:id ");
    $menu   ->execute(array("id"=>$id));
    $menu   = $menu->fetch(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/appearance/menu/edit_menu/".$id).'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Menu Name</label>
            <input type="text" class="form-control" name="name" value="'.$menu["name"].'">
          </div>
          <div class="form-group">
            <label class="form-group__service-name">Menu Slug</label>
<select class="form-control"  name="slug">
<option value="/signup"  '; if( $menu["slug"] == "/signup" ): $return.='selected'; endif;  $return.='>Sign Up</option>
<option value="/login"  '; if( $menu["slug"] == "/login" ): $return.='selected'; endif;  $return.='>Login</option>
<option value="/services"  '; if( $menu["slug"] == "/services" ): $return.='selected'; endif;  $return.='>Services</option>
<option value="/api"  '; if( $menu["slug"] == "/api" ): $return.='selected'; endif;  $return.='>Api</option>
<option value="/blog"  '; if( $menu["slug"] == "/blog" ): $return.='selected'; endif;  $return.='>Blog</option>
<option value="/terms"  '; if( $menu["slug"] == "/terms" ): $return.='selected'; endif;  $return.='>Terms</option>
</select>
          </div>
<div class="form-group">
            <label class="form-group__service-name">Menu Icon</label>
           <input type="text" class="form-control" name="icon" value="'.$menu["icon"].'">
          
        
        
          </div> 
</div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

         </form>';  
    echo json_encode(["content"=>$return,"title"=>"Edit menu item (".$menu["name"].") "]);



   elseif( $action == "add_external" ):
    
    $return = '<form class="form" action="'.site_url("admin/appearance/menu/add_internal").'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Menu Name</label>
            <input type="text" class="form-control" name="name" value="">
<input type="hidden" class="form-control" name="visible" value="External">
          </div>

          
          <div class="form-group">
            <label class="form-group__service-name">Menu Slug</label>
            <select class="form-control"  name="slug">
        <option value="/">Login</option>
                    <option value="/signup">Sign up</option>
                    <option value="/api">Api</option>
<option value="/blog">Blog</option>
<option value="/terms">Terms</option>
<option value="/service/USD">Services</option>

                </select> 
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Menu Icon</label>
            <input type="text" class="form-control" name="icon" value="" placeholder="fas fa-icon">
<p class="help-block">Select icon or paste icon class from <a href="https://anon.ws/?https://fontawesome.com/icons?d=gallery" target="_blank">FontAwesome5</a></p>
          </div>
       
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Menu</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Add Menu"]);


  elseif( $action == "menu-sortable" ):
    $list = $_POST["menus"];
      foreach ($list as $menu) {
        
        $update = $conn->prepare("UPDATE menus SET menu_line=:line WHERE id=:id ");
        $update-> execute(array("id"=>$menu["id"],"line"=>$menu["line"] ));
      }


  elseif( $action ==  "secret_user" ):
    $id       = $_POST["id"];
    $services = $conn->prepare("SELECT * FROM services RIGHT JOIN categories ON categories.category_id=services.category_id WHERE services.service_secret='1' || categories.category_secret='1'  ");
    $services -> execute(array("id"=>$id));
    $services = $services->fetchAll(PDO::FETCH_ASSOC);
    $grouped = array_group_by($services, 'category_id');
    $return = '<form class="form" action="'.site_url("admin/clients/export").'" method="post" data-xhr="true">
        <div class="modal-body">

        <div class="services-import__body">
               <div>
                  <div class="services-import__list-wrap services-import__list-active">
                     <div class="services-import__scroll-wrap">';
                     foreach($grouped as $category):
                       $row = ["table"=>"clients_category","where"=>["client_id"=>$id,"category_id"=>$category[0]["category_id"]]];
                        $return.='<span>
                            <div class="services-import__category">
                               <div class="services-import__category-title">
                                 <label> '; if( $category[0]["category_secret"] == 1 ): $return.='<small><i class="fa fa-lock"></i></small> <input type="checkbox"'; if( countRow($row) ): $return.='checked'; endif; $return.=' class="tiny-toggle" data-tt-palette="blue" data-url="'.site_url("admin/clients/secret_category/".$id).'" data-id="'.$category[0]["category_id"].'"> '; endif; $return.=$category[0]["category_name"].' </label>
                               </div>
                            </div>
                             <div class="services-import__packages">
                                <ul>';
                                  for($i=0;$i<count($category);$i++):
                                    $row = ["table"=>"clients_service","where"=>["client_id"=>$id,"service_id"=>$category[$i]["service_id"]]];
                                    $return.='<li id="service-'.$category[$i]["service_id"].'">
                                     <label>'; if( $category[$i]["service_secret"] == 1 ): $return.='<small><i class="fa fa-lock"></i></small> '; endif;
                                     $return.= $category[$i]["service_id"].' - '.$category[$i]["service_name"].'
                                        <span class="services-import__packages-price-edit" >';
                                        if( $category[$i]["service_secret"] == 1 ): $return.='<input type="checkbox"'; if( countRow($row) ): $return.='checked'; endif; $return.='  class="tiny-toggle" data-tt-palette="blue" data-url="'.site_url("admin/clients/secret_service/".$id).'" data-id="'.$category[$i]["service_id"].'">'; endif;
                                        $return.='</span>
                                     </label>
                                    </li>';
                                  endfor;
                                $return.='</ul>
                             </div>
                          </span>';
                        endforeach;
                      $return.='</div>
                  </div>
               </div>
            </div>
            <script src="'.site_url("public/admin/").'jquery.tinytoggle.min.js"></script>
            <link rel="stylesheet" type="text/css" href="'.site_url("public/admin/").'tinytoggle.min.css" rel="stylesheet">
            <script>
            $(".tiny-toggle").tinyToggle({
              onCheck: function() {
                var id     = $(this).attr("data-id");
                var action = $(this).attr("data-url")+"?type=on&id="+id;
                  $.ajax({
                  url:  action,
                  type: \'GET\',
                  dataType: \'json\',
                  cache: false,
                  contentType: false,
                  processData: false
                  }).done(function(result){
                    if( result == 1 ){
                      $.toast({
                          heading: "Successful",
                          text: "The transaction is successful",
                          icon: "success",
                          loader: true,
                          loaderBg: "#9EC600"
                      });
                    }else{
                      $.toast({
                          heading: "Unsuccessful",
                          text: "Operation failed",
                          icon: "error",
                          loader: true,
                          loaderBg: "#9EC600"
                      });
                    }
                  })
                  .fail(function(){
                    $.toast({
                        heading: "Unsuccessful",
                        text: "Operation failed",
                        icon: "error",
                        loader: true,
                        loaderBg: "#9EC600"
                    });
                  });
              },
              onUncheck: function() {
                var id     = $(this).attr("data-id");
                var action = $(this).attr("data-url")+"?type=off&id="+id;
                  $.ajax({
                  url:  action,
                  type: \'GET\',
                  dataType: \'json\',
                  cache: false,
                  contentType: false,
                  processData: false
                  }).done(function(result){
                    if( result == 1 ){
                      $.toast({
                          heading: "Successful",
                          text: "The transaction is successful",
                          icon: "success",
                          loader: true,
                          loaderBg: "#9EC600"
                      });
                    }else{
                      $.toast({
                          heading: "Unsuccessful",
                          text: "Operation failed",
                          icon: "error",
                          loader: true,
                          loaderBg: "#9EC600"
                      });
                    }
                  })
                  .fail(function(){
                    $.toast({
                        heading: "Unsuccessful",
                        text: "Operation failed",
                        icon: "error",
                        loader: true,
                        loaderBg: "#9EC600"
                    });
                  });
              },
            });

            </script>

        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
        echo json_encode(["content"=>$return,"title"=>"User specific services"]);
  elseif( $action == "new_user" ):
    $return = '<form class="form" action="'.site_url("admin/clients/new").'" method="post" data-xhr="true">
        <div class="modal-body">
      

          <div class="form-group">
            <label>Member E-mail</label>
            <input type="text" name="email" value="" class="form-control">
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="">
          </div>

          <div class="form-group">
            <label>Member Password</label>
            <div class="input-group">
              <input type="text" class="form-control" name="password" value="" id="user_password">
              <span class="input-group-btn">
                <button class="btn btn-default" onclick="UserPassword()" type="button">
                <span class="fa fa-random" data-toggle="tooltip" data-placement="bottom" title="" aria-hidden="true" data-original-title="Create password"></span></button>
              </span>
            </div>
          </div>

          

          <div class="service-mode__block">
            <div class="form-group">
            <label>Debit status</label>
              <select class="form-control" id="debit" name="balance_type">
                    <option value="2">Cannot make debit</option>
                    <option value="1">You can make debit</option>
                </select>
            </div>
          </div>

          <div class="form-group" id="debit_limit">
            <label>How much debit you can borrow</label>
            <input type="text" name="debit_limit" class="form-control" value="">
          </div>
          
          <div class="service-mode__block" >
            <div class="form-group" style="display: none;">
            <label>SMS Confirmation</label>
              <select class="form-control" name="tel_type">
                    <option value="1" selected>Unapproved</option>
                    <option value="2">Approved</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group" style="display": none">
            <label>E-mail Confirmation</label>
              <select class="form-control" name="email_type">
                    <option value="1" selected>Unapproved</option>
                    <option value="2">Approved</option>
                </select>
            </div>
          </div>

          
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Register user</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
<script>
            var type = $("#admin").val();
            if( type == 0 ){
              $("#admin_access").hide();
            } else{
              $("#admin_access").show();
            }
            $("#admin ").change(function(){
              var type = $(this).val();
                if( type == 0 ){
                  $("#admin_access").hide();
                } else{
                  $("#admin_access").show();
                }
            });
          </script>
          <script>
            var type = $("#debit").val();
            if( type == 2 ){
              $("#debit_limit").hide();
            } else{
              $("#debit_limit").show();
            }
            $("#debit").change(function(){
              var type = $(this).val();
                if( type == 2 ){
                  $("#debit_limit").hide();
                } else{
                  $("#debit_limit").show();
                }
            });
          </script>';
    echo json_encode(["content"=>$return,"title"=>"New user registration"]);
  elseif( $action == "edit_user" ):
    $id = $_POST["id"];
    $user   = $conn->prepare("SELECT * FROM clients WHERE client_id=:id ");
    $user ->execute(array("id"=>$id));
    $user   = $user->fetch(PDO::FETCH_ASSOC);
    $access = json_decode($user["access"],true);
    $return = '<form class="form" action="'.site_url("admin/clients/edit/".$user["username"]).'" method="post" data-xhr="true">
        </div>
<div class="modal-body">
          <div class="form-group">
            <label>User E-mail</label>
            <input type="text" name="email" value="'.$user["email"].'" class="form-control">
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control"  value="'.$user["username"].'">
          </div>

          

          <div class="service-mode__block">
            <div class="form-group">
            <label>Debit status</label>
              <select class="form-control" id="debit" name="balance_type">
                    <option value="2"'; if( $user["balance_type"] == 2 ): $return.='selected'; endif;  $return.='>Cannot make debit</option>
                    <option value="1"'; if( $user["balance_type"] == 1 ): $return.='selected'; endif;  $return.='>You can make debit</option>
                </select>
            </div>
          </div>

          <div class="form-group" id="debit_limit">
            <label>How much debit you can borrow</label>
            <input type="text" name="debit_limit" class="form-control" value="'.$user["debit_limit"].'">
          </div>
		   <div class="form-group" id="balance">
            <label>Balance</label>
            <input type="text" name="balance" class="form-control" value="'.$user["balance"].'">
          </div>

          <div class="service-mode__block">
            <div class="form-group" style="display: ;">
            <label>SMS Confirmation</label>
              <select class="form-control" name="tel_type">
                    <option value="1"'; if( $user["tel_type"] == 1 ): $return.='selected'; endif;  $return.='>Unapproved</option>
                    <option value="2"'; if( $user["tel_type"] == 2 ): $return.='selected'; endif;  $return.='>Approved</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group" style="display: ;">
            <label>E-mail Confirmation</label>
              <select class="form-control" name="email_type">
                    <option value="1"'; if( $user["email_type"] == 1 ): $return.='selected'; endif;  $return.='>Unapproved</option>
                    <option value="2"'; if( $user["email_type"] == 2 ): $return.='selected'; endif;  $return.='>Approved</option>
                </select>
            </div>
          </div>

          

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update user information</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
<script>
            var type = $("#admin").val();
            if( type == 0 ){
              $("#admin_access").hide();
            } else{
              $("#admin_access").show();
            }
            $("#admin ").change(function(){
              var type = $(this).val();
                if( type == 0 ){
                  $("#admin_access").hide();
                } else{
                  $("#admin_access").show();
                }
            });
          </script>
          <script>
            var type = $("#debit").val();
            if( type == 2 ){
              $("#debit_limit").hide();
            } else{
              $("#debit_limit").show();
            }
            $("#debit").change(function(){
              var type = $(this).val();
                if( type == 2 ){
                  $("#debit_limit").hide();
                } else{
                  $("#debit_limit").show();
                }
            });
          </script>
		 
          ';
    echo json_encode(["content"=>$return,"title"=>"Edit user"]);















  elseif( $action == "pass_user" ):
    $id = $_POST["id"];
    $user   = $conn->prepare("SELECT * FROM clients WHERE client_id=:id ");
    $user ->execute(array("id"=>$id));
    $user   = $user->fetch(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/clients/pass/".$user["username"]).'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label>Member Password</label>
            <div class="input-group">
              <input type="text" class="form-control" name="password" value="" id="user_password">
              <span class="input-group-btn">
                <button class="btn btn-default" onclick="UserPassword()" type="button">
                <span class="fa fa-random" data-toggle="tooltip" data-placement="bottom" title="" aria-hidden="true" data-original-title="Create password"></span></button>
              </span>
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update password</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Edit password"]);
  elseif( $action == "alert_user" ):
    $return = '<form class="form" action="'.site_url("admin/clients/alert").'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Member to be notified</label>
              <select class="form-control" id="user_type" name="user_type">
                    <option value="all">All members</option>
                    <option value="secret">Member specific</option>
                </select>
            </div>
          </div>

          <div class="form-group" id="username">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="">
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Notification Type</label>
              <select class="form-control" id="alert_type" name="alert_type">
                    <option value="email">E-mail</option>
                    <option value="sms">SMS</option>
                </select>
            </div>
          </div>

          <div id="email">
            <div class="form-group">
              <label>E-mail Title</label>
              <input type="text" name="subject" class="form-control" value="">
            </div>
          </div>

          <div class="form-group" id="username">
            <label>Notification Message</label>
            <textarea type="text" name="message" class="form-control" rows="5"></textarea>
          </div>



        </div>
        <script type="text/javascript">
          $("#username").hide();
          $("#user_type").change(function(){
            var type = $(this).val();
            if( type == "secret" ){
              $("#username").show();
            } else{
              $("#username").hide();
            }
          });
          $("#alert_type").change(function(){
            var type = $(this).val();
            if( type == "email" ){
              $("#email").show();
            } else{
              $("#email").hide();
            }
          });
        </script>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Notify users</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>

          ';
    echo json_encode(["content"=>$return,"title"=>"Notice to users"]);
  elseif( $action == "new_service" ):
    $categories = $conn->prepare("SELECT * FROM categories ORDER BY category_line ");
    $categories->execute(array());
    $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
    $providers  = $conn->prepare("SELECT * FROM service_api");
    $providers->execute(array());
    $providers  = $providers->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/services/new-service").'" method="post" data-xhr="true">
        <div class="modal-body">';

        if( count($languages) > 1 ):
          $translationList = '<a class="other_services"> Translations ('.(count($languages)-1).') </a>';
        else:
          $translationList  = '';
        endif;
        foreach ($languages as $language):
          if( $language["default_language"] ):
            $return.='<div class="form-group">
              <label class="form-group__service-name">Service name <span class="badge">'.$language["language_name"].'</span> '.$translationList.' </label>
              <input type="text" class="form-control" name="name['.$language["language_code"].']" value="'.$multiName[$language["language_code"]].'">
            </div>';
            if( count($languages) > 1 ):
              $return.='<div class="hidden" id="translationsList">';
            endif;
          else:
            $return.='<div class="form-group">
              <label class="form-group__service-name">Service name <span class="badge">'.$language["language_name"].'</span> </label>
              <input type="text" class="form-control" name="name['.$language["language_code"].']" value="'.$multiName[$language["language_code"]].'">
            </div>';
          endif;
        endforeach;
        if( count($languages) > 1 ):
          $return.='</div>';
        endif;

          $return.='<div class="service-mode__block">
            <div class="form-group">
            <label>Service Category</label>
              <select class="form-control" name="category">
                    <option value="0">Please select a category..</option>';
                    foreach ( $categories as $category ):
                      $return.='<option value="'.$category["category_id"].'">'.$category["category_name"].'</option>';
                    endforeach;
                $return.='</select>
            </div>
          </div>

          <div class="service-mode__wrapper">
            <div class="service-mode__block">
              <div class="form-group">
              <label>Service Type</label>
                <select class="form-control" name="package">
                      <option value="1">Service</option>
                      <option value="2">Package</option>
                      <option value="3">Special Comment</option>
                      <option value="4">Package Comment</option>
                  </select>
              </div>
            </div>
            <div class="service-mode__block">
              <div class="form-group">
              <label>Mode</label>
                <select class="form-control" name="mode" id="serviceMode">
                      <option value="1">Manual</option>
                      <option value="2">Auto (API)</option>
                  </select>
              </div>
            </div>

            <div id="autoMode" style="display: none">
              <div class="service-mode__block">
                <div class="form-group">
                <label>Service Provider</label>
                  <select class="form-control" name="provider" id="provider">
                        <option value="0">Select service provider...</option>';
                        foreach( $providers as $provider ):
                          $return.='<option value="'.$provider["id"].'">'.$provider["api_name"].'</option>';
                        endforeach;
                      $return.='</select>
                </div>
              </div>
              <div id="provider_service">
              </div>
              <div class="service-mode__block"  style="display: none">
                <div class="form-group">
                <label>Price Over the Purchase Price</label>
                  <select class="form-control" name="saleprice_cal" id="saleprice_cal>
                    <option value="normal">No</option>
                    <option value="percent">Add % to your purchase price </option>
                    <option value="amount">Add amount to your purchase price </option>
                  </select>
                </div>
              </div>
              <div class="form-group" style="display: none">
                <label class="form-group__service-name">Price</label>
                <input type="text" class="form-control" name="saleprice" value="">
              </div>
              <div class="service-mode__block">
                <div class="form-group">
                <label>Dripfeed</label>
                  <select class="form-control" name="dripfeed">
                    <option value="1">Inactive</option>
                    <option value="2">Active</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="service-mode__wrapper">
              <div class="row">
                <div class="col-md-6 service-mode__block ">
                  <div class="form-group">
                  <label>Check Instagram profile privacy?</label>
                    <select class="form-control" name="instagram_private">
                          <option value="1">No</option>
                          <option value="2">Yes</option>
                      </select>
                  </div>
                </div>
                <div class="col-md-6 service-mode__block ">
                  <div class="form-group">
                  <label>Starting number</label>
                    <select class="form-control" name="start_count">
                          <option value="none">Do not retreat</option>
                          <option value="instagram_follower">Number of Instagram followers</option>
                          <option value="instagram_photo">Instagram photo likes</option>
                      </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 service-mode__block ">
                  <div class="form-group">
                  <label>Enter the 2nd order on the same link?</label>
                    <select class="form-control" name="instagram_second">
                          <option value="2">Yes</option>
                          <option value="1">No</option>
                      </select>
                  </div>
                </div>
              </div>
			
          </div>
		
          <div class="form-group">
            <label class="form-group__service-name">Service price (1000 pieces)</label>
            <input type="text" class="form-control" name="price" value="">
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label class="form-group__service-name">Minimum order</label>
              <input type="text" class="form-control" name="min" value="">
            </div>

            <div class="col-md-6 form-group">
              <label class="form-group__service-name">Maximum order</label>
              <input type="text" class="form-control" name="max" value="">
            </div>
          </div>
<hr>
<div class="service-mode__block">
            <div class="form-group">
            <label>Refill Button</label>
              <select class="form-control" name="show_refill">
                  <option value="false">Off</option>
                  <option value="true">On</option>
              </select>
            </div>
          </div>
<div class="row" id="refill">
            <div class="col-md-6 form-group">
              <label class="form-group__service-name">Refill days</label>
              <input type="text" class="form-control" name="refill_days" value="">
            </div>

            <div class="col-md-6 form-group">
              <label class="form-group__service-name">Refill Display (in hours)</label>
              <input type="text" class="form-control" name="refill_hours" value="">
            </div>
          </div>
<div class="service-mode__block">
            <div class="form-group">
            <label>Cancel Button</label>
              <select class="form-control" name="cancelbutton">
                  <option value="2">Off</option>
                  <option value="1">On</option>
              </select>
            </div>
          </div>

          <hr>
           
              
          <div class="service-mode__block">
            <div class="form-group">
            <label>Order Link</label>
              <select class="form-control" name="want_username">
                  <option value="1">Link</option>
                  <option value="2">Username</option>
              </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Personalized Service</label>
              <select class="form-control" name="secret">
                  <option value="2">No</option>
                  <option value="1">Yes</option>
              </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Service Speed</label>
              <select class="form-control" name="speed">
                  <option value="1">Slow</option>
                  <option value="2">Sometimes Slow</option>
                  <option value="3">Normal</option>
                  <option value="4">Fast</option>
              </select>
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add new service</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <script src="'; $return.=site_url('public/admin/'); $return.='script.js"></script>
          <script>
          $(".other_services").click(function(){
            var control = $("#translationsList");
            if( control.attr("class") == "hidden" ){
              control.removeClass("hidden");
            } else{
              control.addClass("hidden");
            }
          });
          </script>
          ';
    echo json_encode(["content"=>$return,"title"=>"Add new service"]);
elseif( $action == "edit_service" ):
    $id       = $_POST["id"];
    $smmapi   = new SMMApi();
    $categories = $conn->prepare("SELECT * FROM categories ORDER BY category_line ");
    $categories->execute(array());
    $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
    $serviceInfo= $conn->prepare("SELECT * FROM services LEFT JOIN service_api ON service_api.id=services.service_api WHERE services.service_id=:id ");
    $serviceInfo->execute(array("id"=>$id));
    $serviceInfo= $serviceInfo->fetch(PDO::FETCH_ASSOC);
    $providers  = $conn->prepare("SELECT * FROM service_api");
    $providers->execute(array());
    $providers  = $providers->fetchAll(PDO::FETCH_ASSOC);
    $multiName  = json_decode($serviceInfo["name_lang"],true);

      if( in_array($serviceInfo["service_package"],["11","12","13","14","15"]) ):
        $return = '<form class="form" action="'.site_url("admin/services/edit-subscription/".$serviceInfo["service_id"]).'" method="post" data-xhr="true">
            <div class="modal-body">';


   
          if( count($languages) > 1 ):
                $translationList = '<a class="other_services"> Translations ('.(count($languages)-1).') </a>';
              else:
                $translationList  = '';
              endif;
              foreach ($languages as $language):
                if( $language["default_language"] ):
                  $return.='
          <div class="form-group">
                    <label class="form-group__service-name">Service name <span class="badge">'.$language["language_name"].'</span> '.$translationList.' </label>
                    <input type="text" class="form-control" name="name['.$language["language_code"].']" value="'.$multiName[$language["language_code"]].'">
                  </div>';
                  if( count($languages) > 1 ):
                    $return.='<div class="hidden" id="translationsList">';
                  endif;
                else:
                  $return.='<div class="form-group">
                    <label class="form-group__service-name">Service name <span class="badge">'.$language["language_name"].'</span> </label>
                    <input type="text" class="form-control" name="name['.$language["language_code"].']" value="'.$multiName[$language["language_code"]].'">
                  </div>';
                endif;
              endforeach;
              if( count($languages) > 1 ):
                $return.='</div>';
              endif;

              $return.='<div class="service-mode__block">
                <div class="form-group">
                <label>Service Category</label>
                  
                  <select class="form-control" name="category">
                        <option value="0">Please select a category..</option>';
                        foreach ( $categories as $category ):
                          $return.='<option value="'.$category["category_id"].'"'; if( $serviceInfo["category_id"] == $category["category_id"] ): $return.='selected'; endif; $return.='>'.$category["category_name"].'</option>';
                        endforeach;
                    $return.='</select>
                </div>
              </div>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Subscription Type</label>
                  <select class="form-control" disabled  id="subscription_package">
                        <option value="11"'; if( $serviceInfo["service_package"] == 11 ): $return.='selected'; endif; $return.='>Instagram Auto Likes - Unlimited</option>
                        <option value="12"'; if( $serviceInfo["service_package"] == 12 ): $return.='selected'; endif; $return.='>Instagram Auto Tracking - Unlimited</option>
                        <option value="14"'; if( $serviceInfo["service_package"] == 14 ): $return.='selected'; endif; $return.='>Instagram Auto Likes - Timed</option>
                        <option value="15"'; if( $serviceInfo["service_package"] == 15 ): $return.='selected'; endif; $return.='>Instagram Auto Watch - Timed</option>
                    </select>
                </div>
              </div>

              

              <div class="service-mode__wrapper">

                <div class="service-mode__block">
                  <div class="form-group">
                  <label>Mode</label>
                    <select class="form-control" name="mode" id="serviceMode">
                          <option value="2"'; if( $serviceInfo["service_api"] != 0 ): $return.='selected'; endif; $return.='>Auto (API)</option>
                      </select>
                  </div>
                </div>


                <div id="autoMode" style="display: none">
                  <div class="service-mode__block">
                    <div class="form-group">
                    <label>Service Provider</label>
                      <select class="form-control" name="provider" id="provider">
                            <option value="0">Select service provider...</option>';
                            foreach( $providers as $provider ):
                              $return.='<option value="'.$provider["id"].'"'; if( $serviceInfo["service_api"] == $provider["id"] ): $return.='selected'; endif; $return.='>'.$provider["api_name"].'</option>';
                            endforeach;
                          $return.='</select>
                    </div>
                  </div>
                  <div id="provider_service">';
                  $services = $smmapi->action(array('key' =>$serviceInfo["api_key"],'action' =>'services'),$serviceInfo["api_url"]);
                  if( $serviceInfo["api_type"] == 1 ):
                    $return.= '<div class="service-mode__block">
                      <div class="form-group">
                      <label>Service</label>
                        <select class="form-control" name="service">';
                            foreach ($services as $service):
                              $return.= '<option value="'.$service->service.'"'; if( $serviceInfo["api_service"] == $service->service ): $return.='selected'; endif; $return.= '>'.$service->service.' - '.$service->name.' - '.$service->rate.'</option>';
                            endforeach;
                            $return.= '</select>
                      </div>
                    </div>';
                  elseif( $serviceInfo["api_type"] == 3 ):
                    $return.= '<div class="service-mode__block">
                      <div class="form-group">
                      <label>Service</label>
                        <input class="form-control" value="'.$serviceInfo['api_service'].'" name="service">
                      </div>
                    </div>';
                  endif;
                  $return.='</div>
                </div>
              </div>

              <div id="unlimited">
                <div class="form-group">
                  <label class="form-group__service-name">Service price (1000 pieces)</label>
                  <input type="text" class="form-control" name="price" value="'.$serviceInfo["service_price"].'">
                </div>

                <div class="row">
                  <div class="col-md-6 form-group">
                    <label class="form-group__service-name">Minimum order</label>
                    <input type="text" class="form-control" name="min" value="'.$serviceInfo["service_min"].'">
                  </div>

                  <div class="col-md-6 form-group">
                    <label class="form-group__service-name">Maximum order</label>
                    <input type="text" class="form-control" name="max" value="'.$serviceInfo["service_max"].'">
                  </div>
                </div>
              </div>

              <div id="limited">
                <div class="form-group">
                  <label class="form-group__service-name">Service price</label>
                  <input type="text" class="form-control" name="limited_price" value="'.$serviceInfo["service_price"].'">
                </div>



                <div class="row">
                  <div class="col-md-6 form-group">
                    <label class="form-group__service-name">Shipment amount</label>
                    <input type="text" class="form-control" name="autopost" value="'.$serviceInfo["service_autopost"].'">
                  </div>

                  <div class="col-md-6 form-group">
                    <label class="form-group__service-name">Order amount</label>
                    <input type="text" class="form-control" name="limited_min" value="'.$serviceInfo["service_min"].'">
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-group__service-name">Package Time <small> (days)</small></label>
                  <input type="text" class="form-control" name="autotime" value="'.$serviceInfo["service_autotime"].'">
                </div>
              </div>

              <hr>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Personalized Service</label>
                  <select class="form-control" name="secret">
                      <option value="2"'; if( $serviceInfo["service_secret"] == 2 ): $return.='selected'; endif; $return.='>No</option>
                      <option value="1"'; if( $serviceInfo["service_secret"] == 1 ): $return.='selected'; endif; $return.='>Yes</option>
                  </select>
                </div>
              </div>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Service Speed</label>
                  <select class="form-control" name="speed">
                      <option value="1"'; if( $serviceInfo["service_speed"] == 1 ): $return.='selected'; endif; $return.='>Slow</option>
                      <option value="2"'; if( $serviceInfo["service_speed"] == 2 ): $return.='selected'; endif; $return.='>Sometimes Slow</option>
                      <option value="3"'; if( $serviceInfo["service_speed"] == 3 ): $return.='selected'; endif; $return.='>Normal</option>
                      <option value="4"'; if( $serviceInfo["service_speed"] == 4 ): $return.='selected'; endif; $return.='>Fast</option>
                  </select>
                </div>
              </div>

            </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update subscription information</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
              </form>
<script>
            var type = $("#show").val();
            if( show_refill == "true" ){
              $("#refill").hide();
            } else{
              $("#refill").show();
            }
            $("#admin ").change(function(){
              var type = $(this).val();
                if( show_refill == "false" ){
                  $("#refill").hide();
                } else{
                  $("#refill").show();
                }
            });
          </script>


              <script type="text/javascript">
              $(".other_services").click(function(){
                var control = $("#translationsList");
                if( control.attr("class") == "hidden" ){
                  control.removeClass("hidden");
                } else{
                  control.addClass("hidden");
                }
              });
              var site_url  = $("head base").attr("href");
                $("#provider").change(function(){
                  var provider = $(this).val();
                  getProviderServices(provider,site_url);
                });

                getProvider();
                $("#serviceMode").change(function(){
                  getProvider();
                });

                getSalePrice();
                $("#saleprice_cal").change(function(){
                  getSalePrice();
                });

                getSubscription();
                $("#subscription_package").change(function(){
                  getSubscription();
                });
                function getProviderServices(provider,site_url){
                  if( provider == 0 ){
                    $("#provider_service").hide();
                  }else{
                    $.post(site_url+"admin/ajax_data",{action:"providers_list",provider:provider}).done(function( data ) {
                      $("#provider_service").show();
                      $("#provider_service").html(data);
                    }).fail(function(){
                      alert("Hata oluştu!");
                    });
                  }
                }

                function getProvider(){
                  var mode = $("#serviceMode").val();
                    if( mode == 1 ){
                      $("#autoMode").hide();
                    }else{
                      $("#autoMode").show();
                    }
                }

                function getSalePrice(){
                  var type = $("#saleprice_cal").val();
                    if( type == "normal" ){
                      $("#saleprice").hide();
                      $("#servicePrice").show();
                    }else{
                      $("#saleprice").show();
                      $("#servicePrice").hide();
                    }
                }

                function getSubscription(){
                  var type = $("#subscription_package").val();
                    if( type == "11" || type == "12" ){
                      $("#unlimited").show();
                      $("#limited").hide();
                    }else{
                      $("#unlimited").hide();
                      $("#limited").show();
                    }
                }
              </script>
              ';


	 echo json_encode(["content"=>$return,"title"=>"Edit subscription (ID: ".$serviceInfo["service_id"].")"]);


      else:
        $return = '

        <form class="form" action="'.site_url("admin/services/edit-service/".$serviceInfo["service_id"]).'" method="post" data-xhr="true">
            <div class="modal-body">';

              if( count($languages) > 1 ):
                $translationList = '<a class="other_services"> Translations ('.(count($languages)-1).') </a>';
              else:
                $translationList  = '';
              endif;
              foreach ($languages as $language):
                if( $language["default_language"] ):
                  $return.='
				  <div class="form-group">
                    <label class="form-group__service-name">Service name <span class="badge">'.$language["language_name"].'</span> '.$translationList.' </label>
                    <input type="text" class="form-control" name="name['.$language["language_code"].']" value="'.$multiName[$language["language_code"]].'">
                  </div>';
                  if( count($languages) > 1 ):
                    $return.='<div class="hidden" id="translationsList">';
                  endif;
                else:
                  $return.='<div class="form-group">
                    <label class="form-group__service-name">Service name <span class="badge">'.$language["language_name"].'</span> </label>
                    <input type="text" class="form-control" name="name['.$language["language_code"].']" value="'.$multiName[$language["language_code"]].'">
                  </div>';
                endif;
              endforeach;
              if( count($languages) > 1 ):
                $return.='</div>';
              endif;

              $return.='<div class="service-mode__block">
                <div class="form-group">
                <label>Service Category</label>
                  <select class="form-control" name="category">
                        <option value="0">Please select a category..</option>';
                        foreach ( $categories as $category ):
                          $return.='<option value="'.$category["category_id"].'"'; if( $serviceInfo["category_id"] == $category["category_id"] ): $return.='selected'; endif; $return.='>'.$category["category_name"].'</option>';
                        endforeach;
                    



                    $return.='</select>
                </div>
              </div>

              <div class="service-mode__wrapper">
                <div class="service-mode__block">
                  <div class="form-group">
                  <label>Service Type</label>
                    <select class="form-control" name="package">
                          <option value="1"'; if( $serviceInfo["service_package"] == 1 ): $return.='selected'; endif; $return.='>Service</option>
                          <option value="2"'; if( $serviceInfo["service_package"] == 2 ): $return.='selected'; endif; $return.='>Package</option>
                          <option value="3"'; if( $serviceInfo["service_package"] == 3 ): $return.='selected'; endif; $return.='>Special Comment</option>
                          <option value="4"'; if( $serviceInfo["service_package"] == 4 ): $return.='selected'; endif; $return.='>Package Comment</option>
                      </select>
                  </div>
                </div>
                <div class="service-mode__block">
                  <div class="form-group">
                  <label>Mode</label>
                    <select class="form-control" name="mode" id="serviceMode">
                          <option value="1"'; if( $serviceInfo["service_api"] == 0 ): $return.='selected'; endif; $return.='>Manual</option>
                          <option value="2"'; if( $serviceInfo["service_api"] != 0 ): $return.='selected'; endif; $return.='>Auto (API)</option>
                      </select>
                  </div>
                </div>

                <div id="autoMode" style="display: none">
                  <div class="service-mode__block">
                    <div class="form-group">
                    <label>Service Provider</label>
                      <select class="form-control" name="provider" id="provider">
                            <option value="0">Select service provider...</option>';
                            foreach( $providers as $provider ):
                              $return.='<option value="'.$provider["id"].'"'; if( $serviceInfo["service_api"] == $provider["id"] ): $return.='selected'; endif; $return.='>'.$provider["api_name"].'</option>';
                            endforeach;
                          $return.='</select>
                    </div>
                  </div>
                  <div id="provider_service">';
                  $services = $smmapi->action(array('key' =>$serviceInfo["api_key"],'action' =>'services'),$serviceInfo["api_url"]);
                    if( $serviceInfo["api_type"] == 1 ):
                      $return.= '<div class="service-mode__block">
                        <div class="form-group">
                        <label>Service</label>
                          <select class="form-control" name="service">';
                              foreach ($services as $service):
                                $return.= '<option value="'.$service->service.'"'; if( $serviceInfo["api_service"] == $service->service ): $return.='selected'; endif; $return.= '>'.$service->service.' - '.$service->name.' - '.$service->rate.'</option>';
                              endforeach;
                              $return.= '</select>
                        </div>
                      </div>';
                    elseif( $serviceInfo["api_type"] == 3 ):
                      $return.= '<div class="service-mode__block">
                        <div class="form-group">
                        <label>Service</label>
                          <input class="form-control" value="'.$serviceInfo['api_service'].'" name="service">
                        </div>
                      </div>';
                    endif;
                  $return.='</div>
                  <div class="service-mode__block"  style="display: none">
                    <div class="form-group">
                    <label>Price Over the Purchase Price</label>
                      <select class="form-control" name="saleprice_cal" id="saleprice_cal>
                        <option value="normal">No</option>
                        <option value="percent">Add % to your purchase price</option>
                        <option value="amount">Add amount to your purchase price </option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group" style="display: none">
                    <label class="form-group__service-name">Price</label>
                    <input type="text" class="form-control" name="saleprice" value="">
                  </div>
                  <div class="service-mode__block">
                    <div class="form-group">
                    <label>Dripfeed</label>
                      <select class="form-control" name="dripfeed">
                        <option value="1"'; if( $serviceInfo["service_dripfeed"] == 1 ): $return.='selected'; endif; $return.='>Inactive</option>
                        <option value="2"'; if( $serviceInfo["service_dripfeed"] == 2 ): $return.='selected'; endif; $return.='>Active</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <div class="service-mode__wrapper">
                  <div class="row">
                    <div class="col-md-6 service-mode__block ">
                      <div class="form-group">
                      <label>Check Instagram profile privacy?</label>
                        <select class="form-control" name="instagram_private">
                              <option value="1"'; if( $serviceInfo["instagram_private"] == 1 ): $return.='selected'; endif; $return.='>No</option>
                              <option value="2"'; if( $serviceInfo["instagram_private"] == 2 ): $return.='selected'; endif; $return.='>Yes</option>
                          </select>
                      </div>
                    </div>
                    <div class="col-md-6 service-mode__block ">
                      <div class="form-group">
                      <label>Starting number</label>
                        <select class="form-control" name="start_count">
                              <option value="none"'; if( $serviceInfo["start_count"] == "none" ): $return.='selected'; endif; $return.='>Starting number</option>
                              <option value="instagram_follower"'; if( $serviceInfo["start_count"] == "instagram_follower" ): $return.='selected'; endif; $return.='>Number of Instagram followers</option>
                              <option value="instagram_photo"'; if( $serviceInfo["start_count"] == "instagram_photo" ): $return.='selected'; endif; $return.='>Number of Instagram followers</option>
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 service-mode__block ">
                      <div class="form-group">
                      <label>Enter the 2nd order on the same link?</label>
                        <select class="form-control" name="instagram_second">
                              <option value="2"'; if( $serviceInfo["instagram_second"] == 2 ): $return.='selected'; endif; $return.='>Yes</option>
                              <option value="1"'; if( $serviceInfo["instagram_second"] == 1 ): $return.='selected'; endif; $return.='>No</option>
                          </select>
                      </div>
                    </div>
                  </div>
              </div>

              <div class="form-group">
                <label class="form-group__service-name">Service price (1000 pieces)</label>
                <input type="text" class="form-control" name="price" value="'.$serviceInfo["service_price"].'">
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label class="form-group__service-name">Minimum order</label>
                  <input type="text" class="form-control" name="min" value="'.$serviceInfo["service_min"].'">
                </div>

                <div class="col-md-6 form-group">
                  <label class="form-group__service-name">Minimum order</label>
                  <input type="text" class="form-control" name="max" value="'.$serviceInfo["service_max"].'">
                </div>
              </div>

              <hr>
    <div class="service-mode__block">
                <div class="form-group">
                <label>Refill Button</label>
                  <select id="show" class="form-control" name="show_refill">
                      <option value="false"'; if( $serviceInfo["show_refill"] == "false" ): $return.='selected'; endif; $return.='>Off</option>
                      <option value="true"'; if( $serviceInfo["show_refill"] == "true" ): $return.='selected'; endif; $return.='>On</option>
                  </select>
                </div>
    </div><div class="row" id="refill">


                <div class="col-md-6 form-group" id="refill"> 
                  <label class="form-group__service-name">Refill days</label>
                  <input type="text" class="form-control" name="refill_days" value="'.$serviceInfo["refill_days"].'">
                </div>

                <div class="col-md-6 form-group" id="refill">
                  <label class="form-group__service-name">Refill Display (in hours)</label>
                  <input type="text" class="form-control" name="refill_hours" value="'.$serviceInfo["refill_hours"].'">
                </div>
              </div>

    <div class="service-mode__block">
                <div class="form-group">
                <label>Cancel Button</label>
                  <select class="form-control" name="cancelbutton">
                      <option value="2"'; if( $serviceInfo["cancelbutton"] == 2 ): $return.='selected'; endif; $return.='>Off</option>
                      <option value="1"'; if( $serviceInfo["cancelbutton"] == 1 ): $return.='selected'; endif; $return.='>On</option>
                  </select>
                </div>
              </div>

              <hr>
              
              <div class="service-mode__block">
                <div class="form-group">
                <label>Order Link</label>
                  <select class="form-control" name="want_username">
                      <option value="1"'; if( $serviceInfo["want_username"] == 1 ): $return.='selected'; endif; $return.='>Link</option>
                      <option value="2"'; if( $serviceInfo["want_username"] == 2 ): $return.='selected'; endif; $return.='>Username</option>
                  </select>
                </div>
              </div>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Personalized Service</label>
                  <select class="form-control" name="secret">
                      <option value="2"'; if( $serviceInfo["service_secret"] == 2 ): $return.='selected'; endif; $return.='>No</option>
                      <option value="1"'; if( $serviceInfo["service_secret"] == 1 ): $return.='selected'; endif; $return.='>Yes</option>
                  </select>
                </div>
              </div>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Service Speed</label>
                  <select class="form-control" name="speed">
                      <option value="1"'; if( $serviceInfo["service_speed"] == 1 ): $return.='selected'; endif; $return.='>Slow</option>
                      <option value="2"'; if( $serviceInfo["service_speed"] == 2 ): $return.='selected'; endif; $return.='>Sometimes Slow</option>
                      <option value="3"'; if( $serviceInfo["service_speed"] == 3 ): $return.='selected'; endif; $return.='>Normal</option>
                      <option value="4"'; if( $serviceInfo["service_speed"] == 4 ): $return.='selected'; endif; $return.='>Fast</option>
                  </select>
                </div>
              </div>

            </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update service information</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
                            </form>
<script>
            var type = $("#show").val();
            if( $serviceInfo["show_refill"]   == "false" ){
              $("#refill").hide();
            } else{
              $("#refill").show();
            }
            $("#show ").change(function(){
              var type = $(this).val();
                if( $serviceInfo["show_refill"]  == "false" ){
                  $("#refill").hide();
                } else{
                  $("#refill").show();
                }
            });
          </script>
              <script type="text/javascript">

               $(".other_services").click(function(){
                 var control = $("#translationsList");
                 if( control.attr("class") == "hidden" ){
                   control.removeClass("hidden");
                 } else{
                   control.addClass("hidden");
                 }
               });
              var site_url  = $("head base").attr("href");
                $("#provider").change(function(){
                  var provider = $(this).val();
                  getProviderServices(provider,site_url);
                });

                getProvider();
                $("#serviceMode").change(function(){
                  getProvider();
                });

                getSalePrice();
                $("#saleprice_cal").change(function(){
                  getSalePrice();
                });

                getSubscription();
                $("#subscription_package").change(function(){
                  getSubscription();
                });
                function getProviderServices(provider,site_url){
                  if( provider == 0 ){
                    $("#provider_service").hide();
                  }else{
                    $.post(site_url+"admin/ajax_data",{action:"providers_list",provider:provider}).done(function( data ) {
                      $("#provider_service").show();
                      $("#provider_service").html(data);
                    }).fail(function(){
                      alert("Hata oluştu!");
                    });
                  }
                }

                function getProvider(){
                  var mode = $("#serviceMode").val();
                    if( mode == 1 ){
                      $("#autoMode").hide();
                    }else{
                      $("#autoMode").show();
                    }
                }

                function getSalePrice(){
                  var type = $("#saleprice_cal").val();
                    if( type == "normal" ){
                      $("#saleprice").hide();
                      $("#servicePrice").show();
                    }else{
                      $("#saleprice").show();
                      $("#servicePrice").hide();
                    }
                }

                function getSubscription(){
                  var type = $("#subscription_package").val();
                    if( type == "11" || type == "12" ){
                      $("#unlimited").show();
                      $("#limited").hide();
                    }else{
                      $("#unlimited").hide();
                      $("#limited").show();
                    }
                }
              </script>
              ';
        echo json_encode(["content"=>$return,"title"=>"Edit service (ID: ".$serviceInfo["service_id"].")"]);
      endif;
  elseif( $action == "edit_description" ):
    $id       = $_POST["id"];
    $smmapi   = new SMMApi();
    $serviceInfo= $conn->prepare("SELECT * FROM services WHERE service_id=:id ");
    $serviceInfo->execute(array("id"=>$id));
    $serviceInfo= $serviceInfo->fetch(PDO::FETCH_ASSOC);
    $multiDesc  = json_decode($serviceInfo["description_lang"],true);

        $return = '<form class="form" action="'.site_url("admin/services/edit-description/".$serviceInfo["service_id"]).'" method="post" data-xhr="true">
            <div class="modal-body">';

              if( count($languages) > 1 ):
                $translationList = '<a class="other_services"> Translations ('.(count($languages)-1).') </a>';
              else:
                $translationList  = '';
              endif;
              foreach ($languages as $language):
                if( $language["default_language"] ):
                  $return.='<div class="form-group">
                    <label class="form-group__service-name">Explanation <span class="badge">'.$language["language_name"].'</span> '.$translationList.' </label>
                    <textarea class="form-control" rows="5" name="description['.$language["language_code"].']">'.$multiDesc[$language["language_code"]].'</textarea>
                  </div>';
                  if( count($languages) > 1 ):
                    $return.='<div class="hidden" id="translationsList">';
                  endif;
                else:
                  $return.='<div class="form-group">
                    <label class="form-group__service-name">Explanation <span class="badge">'.$language["language_name"].'</span> </label>
                    <textarea class="form-control" rows="5"  name="description['.$language["language_code"].']">'.$multiDesc[$language["language_code"]].'</textarea>
                  </div>';
                endif;
              endforeach;
              if( count($languages) > 1 ):
                $return.='</div>';
              endif;

              $return.='

            </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update description</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
              </form>
              <script type="text/javascript">

              $(".other_services").click(function(){
                var control = $("#translationsList");
                if( control.attr("class") == "hidden" ){
                  control.removeClass("hidden");
                } else{
                  control.addClass("hidden");
                }
              });

              </script>
              ';
        echo json_encode(["content"=>$return,"title"=>"Edit description (ID: ".$serviceInfo["service_id"].")"]);


elseif( $action == "edit_time" ):
    $id       = $_POST["id"];
    $smmapi   = new SMMApi();
    $serviceInfo= $conn->prepare("SELECT * FROM services WHERE service_id=:id ");
    $serviceInfo->execute(array("id"=>$id));
    $serviceInfo= $serviceInfo->fetch(PDO::FETCH_ASSOC);
    $multiDesc  = json_decode($serviceInfo["time_lang"],true);

        $return = '<form class="form" action="'.site_url("admin/services/edit-time/".$serviceInfo["service_id"]).'" method="post" data-xhr="true">
            <div class="modal-body">';

              if( count($languages) > 1 ):
                $translationList = '<a class="other_services"> Translations ('.(count($languages)-1).') </a>';
              else:
                $translationList  = '';
              endif;
              foreach ($languages as $language):
                if( $language["default_language"] ):
                  $return.='<div class="form-group">
                    <label class="form-group__service-name">Explanation <span class="badge">'.$language["language_name"].'</span> '.$translationList.' </label>
                    <textarea class="form-control" rows="5" name="description['.$language["language_code"].']">'.$multiDesc[$language["language_code"]].'</textarea>
                  </div>';
                  if( count($languages) > 1 ):
                    $return.='<div class="hidden" id="translationsList">';
                  endif;
                else:
                  $return.='<div class="form-group">
                    <label class="form-group__service-name">Explanation <span class="badge">'.$language["language_name"].'</span> </label>
                    <textarea class="form-control" rows="5"  name="description['.$language["language_code"].']">'.$multiDesc[$language["language_code"]].'</textarea>
                  </div>';
                endif;
              endforeach;
              if( count($languages) > 1 ):
                $return.='</div>';
              endif;

              $return.='

            </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update Time</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
              </form>
              <script type="text/javascript">

              $(".other_services").click(function(){
                var control = $("#translationsList");
                if( control.attr("class") == "hidden" ){
                  control.removeClass("hidden");
                } else{
                  control.addClass("hidden");
                }
              });

              </script>
              ';
        echo json_encode(["content"=>$return,"title"=>"Edit Average Time (ID: ".$serviceInfo["service_id"].")"]);





  elseif( $action == "new_subscriptions" ):
    $categories = $conn->prepare("SELECT * FROM categories ORDER BY category_line ");
    $categories->execute(array());
    $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
    $providers  = $conn->prepare("SELECT * FROM service_api");
    $providers->execute(array());
    $providers  = $providers->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/services/new-subscription").'" method="post" data-xhr="true">
        <div class="modal-body">';

        if( count($languages) > 1 ):
          $translationList = '<a class="other_services"> Translations ('.(count($languages)-1).') </a>';
        else:
          $translationList  = '';
        endif;
        foreach ($languages as $language):
          if( $language["default_language"] ):
            $return.='<div class="form-group">
              <label class="form-group__service-name">Service name <span class="badge">'.$language["language_name"].'</span> '.$translationList.' </label>
              <input type="text" class="form-control" name="name['.$language["language_code"].']" value="'.$multiName[$language["language_code"]].'">
            </div>';
            if( count($languages) > 1 ):
              $return.='<div class="hidden" id="translationsList">';
            endif;
          else:
            $return.='<div class="form-group">
              <label class="form-group__service-name">Service name <span class="badge">'.$language["language_name"].'</span> </label>
              <input type="text" class="form-control" name="name['.$language["language_code"].']" value="'.$multiName[$language["language_code"]].'">
            </div>';
          endif;
        endforeach;
        if( count($languages) > 1 ):
          $return.='</div>';
        endif;

          $return.='<div class="service-mode__block">
            <div class="form-group">
            <label>Service Category</label>
              <select class="form-control" name="category">
                    <option value="0">Please select a category..</option>';
                    foreach ( $categories as $category ):
                      $return.='<option value="'.$category["category_id"].'">'.$category["category_name"].'</option>';
                    endforeach;
                $return.='</select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Subscription Type</label>
              <select class="form-control" name="package" id="subscription_package">
                    <option value="11">Instagram Auto Likes - Unlimited</option>
                    <option value="12">Instagram Auto Tracking - Unlimited</option>
                    <option value="14">Instagram Auto Likes - Timed</option>
                    <option value="15">Instagram Auto Watch - Timed</option>
                </select>
            </div>
          </div>

          <div class="service-mode__wrapper">

            <div class="service-mode__block">
              <div class="form-group">
              <label>Mode</label>
                <select class="form-control" name="mode" id="serviceMode">
                      <option value="2">Auto (API)</option>
                  </select>
              </div>
            </div>

            <div id="autoMode" style="display: none">
              <div class="service-mode__block">
                <div class="form-group">
                <label>Service Provider</label>
                  <select class="form-control" name="provider" id="provider">
                        <option value="0">Select service provider...</option>';
                        foreach( $providers as $provider ):
                          $return.='<option value="'.$provider["id"].'">'.$provider["api_name"].'</option>';
                        endforeach;
                      $return.='</select>
                </div>
              </div>
              <div id="provider_service">
              </div>
            </div>
          </div>

          <div id="unlimited">
            <div class="form-group">
              <label class="form-group__service-name">Service price (1000 pieces)</label>
              <input type="text" class="form-control" name="price" value="">
            </div>

            <div class="row">
              <div class="col-md-6 form-group">
                <label class="form-group__service-name">Minimum order</label>
                <input type="text" class="form-control" name="min" value="">
              </div>

              <div class="col-md-6 form-group">
                <label class="form-group__service-name">Maximum order</label>
                <input type="text" class="form-control" name="max" value="">
              </div>
            </div>
          </div>

          <div id="limited">
            <div class="form-group">
              <label class="form-group__service-name">Service price</label>
              <input type="text" class="form-control" name="limited_price" value="">
            </div>



            <div class="row">
              <div class="col-md-6 form-group">
                <label class="form-group__service-name">Shipment amount</label>
                <input type="text" class="form-control" name="autopost" value="">
              </div>

              <div class="col-md-6 form-group">
                <label class="form-group__service-name">Order amount</label>
                <input type="text" class="form-control" name="limited_min" value="">
              </div>
            </div>
            <div class="form-group">
              <label class="form-group__service-name">Package Time <small> (days)</small></label>
              <input type="text" class="form-control" name="autotime" value="">
            </div>
          </div>

          <hr>


          <div class="service-mode__block">
            <div class="form-group">
            <label>Personalized Service</label>
              <select class="form-control" name="secret">
                  <option value="2">No</option>
                  <option value="1">Yes</option>
              </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Service Speed</label>
              <select class="form-control" name="speed">
                  <option value="1">Slow</option>
                  <option value="2">Sometimes Slow</option>
                  <option value="3">Normal</option>
                  <option value="4">Fast</option>
              </select>
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add new subscription</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <script type="text/javascript">

          $(".other_services").click(function(){
            var control = $("#translationsList");
            if( control.attr("class") == "hidden" ){
              control.removeClass("hidden");
            } else{
              control.addClass("hidden");
            }
          });

          var site_url  = $("head base").attr("href");
            $("#provider").change(function(){
              var provider = $(this).val();
              getProviderServices(provider,site_url);
            });

            getProvider();
            $("#serviceMode").change(function(){
              getProvider();
            });

            getSalePrice();
            $("#saleprice_cal").change(function(){
              getSalePrice();
            });

            getSubscription();
            $("#subscription_package").change(function(){
              getSubscription();
            });
            function getProviderServices(provider,site_url){
              if( provider == 0 ){
                $("#provider_service").hide();
              }else{
                $.post(site_url+"admin/ajax_data",{action:"providers_list",provider:provider}).done(function( data ) {
                  $("#provider_service").show();
                  $("#provider_service").html(data);
                }).fail(function(){
                  alert("Hata oluştu!");
                });
              }
            }

            function getProvider(){
              var mode = $("#serviceMode").val();
                if( mode == 1 ){
                  $("#autoMode").hide();
                }else{
                  $("#autoMode").show();
                }
            }

            function getSalePrice(){
              var type = $("#saleprice_cal").val();
                if( type == "normal" ){
                  $("#saleprice").hide();
                  $("#servicePrice").show();
                }else{
                  $("#saleprice").show();
                  $("#servicePrice").hide();
                }
            }

            function getSubscription(){
              var type = $("#subscription_package").val();
                if( type == "11" || type == "12" ){
                  $("#unlimited").show();
                  $("#limited").hide();
                }else{
                  $("#unlimited").hide();
                  $("#limited").show();
                }
            }
          </script>
          ';
    echo json_encode(["content"=>$return,"title"=>"Add new subscription"]);


elseif( $action == "new_category" ):
    $return = '<form class="form" action="' . site_url('admin/services/new-category') . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Category name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-refill">Position  <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">The position of a category after adding it</span></div> </label>' . "\r\n" . ' <select name="position" class="form-control"><option value="bottom">Bottom</option><option value="top">Top</option></select> ' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Hidden Category</label>' . "\r\n" . '<select class="form-control" name="secret">' . "\r\n" . '  <option value="2">no</option>' . "\r\n" . '  <option value="1">Yes</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Create category</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
    echo json_encode(['content' => $return, 'title' => '']);


  
  elseif( $action == "edit_category" ):
    $id       = $_POST["id"];
    $category = $conn->prepare("SELECT * FROM categories WHERE category_id=:id ");
    $category->execute(array("id"=>$id));
    $category = $category->fetch(PDO::FETCH_ASSOC);
    
    
    if($category['is_refill'] == "true"){
        $true = "selected";
        $false = "";
    }else{
        $false = "selected";
        $true = "";
    }
    $return = '<form class="form" action="' . site_url('admin/services/edit-category/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Category name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $category['category_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-refill">Refillable?</label>' . "\r\n" . ' <select name="is_refill" class="form-control"><option '.$true.' value="true">True</option><option '.$false.' value="false">False</option></select> ' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Hidden Category</label>' . "\r\n" . '<select class="form-control" name="secret">' . "\r\n" . '  <option value="2"';

    if ($category['category_secret'] == 2) {
        $return .= 'selected';
    }

    $return .= '>No</option>' . "\r\n" . '  <option value="1"';

    if ($category['category_secret'] == 1) {
        $return .= 'selected';
    }

    $return .= '>Yes</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update category</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
    echo json_encode(['content' => $return, 'title' => '']);

  elseif( $action == "import_services" ):

$providers  = $conn->prepare("SELECT * FROM service_api   WHERE status=:status    ");     $providers->execute(array(  "status"=>1    ));     $providers  = $providers->fetchAll(PDO::FETCH_ASSOC);
      $category  = $conn->prepare("SELECT * FROM categories");
      $category->execute(array());
      $category  = $category->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/services/get_services_add/").'" method="post" data-xhr="true">
    
        <div class="modal-body">

          <div id="firstStep">
            <div class="service-mode__block">
              <div class="form-group">
              <label>Service Provider</label>
                <select class="form-control" name="provider" id="provider">
                      <option value="0">Select service provider...</option>';
                      foreach( $providers as $provider ):
                        $return.='<option value="'.$provider["id"].'">'.$provider["api_name"].'</option>';
                      endforeach;
                    $return.='</select>
              </div>
            </div><div class="service-mode__block">
              <div class="form-group">
              <label>Select the Category to Add Services</label>
                <select class="form-control" name="selector" id="selector">
                      <option value="0">Select category...</option>';
                      foreach( $category as $cat ):
                        $return.='<option value="' . ($cat["category_id"]) . '">'.$cat["category_name"].'</option>';
                      endforeach;
                    $return.= '</select>
              </div>
            </div>
          </div>

          
          <div id="secondStep">
          </div>

          <div id="thirdStep">
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="nextStep" data-step="first">Next step</button>
            <button type="submit" class="btn btn-primary" id="submitStep">Add services</button>
          </div>

        </form>
           <script>
            $("#submitStep").hide();
            $("#nextStep").click(function(){
              var now_step = $(this).attr("data-step");
              var provider = $("#provider").val();
              var category = $("#selector").val();
              $("#secondStep").hide();
                if( now_step == "first" ){
                  if( provider == 0 ){
                    $.toast({
                        heading: "Unsuccessful",
                        text: "Please select service provider",
                        icon: "error",
                        loader: true,
                        loaderBg: "#9EC600"
                    });
                  }else{
                    $("#firstStep").hide();
                    $("#secondStep").show();
                    $.post("admin/ajax_data", {provider:provider,category:category,action:"import_services_list" }, function(data){
                      $("#secondStep").html(data);
                    });
                    $("#nextStep").attr("data-step","second");
                  }
                }else if( now_step == "second" ){
                    var array     = [];
                       $(\'[class^="selectServices-"]\').each(function () {
                            var id    = $(this).val();
                            var check = $(this).prop("checked");
                            var provider  =  $(this).attr("data-provider");
                              if( check == true ){
                                var params = {};
                                params["id"]            = id;
                                params["category"]      = $(this).attr("data-category");
                                array.push(params);
                              }
                       });
                       var count = array.length;
                     if( count ){
                       $.post("admin/ajax_data", {provider:provider,action:"import_services_last",services:array }, function(data){
                         $("#thirdStep").html(data);
                       });
                       $("#nextStep").hide();
                       $("#submitStep").show();
                     }else{
                       $("#nextStep").attr("data-step","second");
                       $("#firstStep").hide();
                       $("#secondStep").show();
                       $("#nextStep").show();
                       $("#submitStep").hide();
                       $.toast({
                           heading: "Unsuccessful",
                           text: "Please select at least 1 service you want to add",
                           icon: "error",
                           loader: true,
                           loaderBg: "#9EC600"
                       });
                     }

                }
            });
          </script>
          ';
    echo json_encode(["content"=>$return,"title"=>"Pull out services from provider"]);
  elseif( $action == "import_services_list" ):
    $provider_id  = $_POST["provider"];
    $category_id2  = $_POST["category"];
    $smmapi       = new SMMApi();
    $provider     = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
    $provider     ->execute(array("id"=>$provider_id));
    $provider     = $provider->fetch(PDO::FETCH_ASSOC);
      if( $provider["api_type"] == 1 ):
        $services   = $smmapi->action(array('key'=>$provider["api_key"],'action'=>'services'),$provider["api_url"]);
          if( $services ):
            $grouped = array_group_by($services, 'category');
            echo '<div class="">
            <div class="services-import__body">
                 <div>
                    <div class="services-import__list-wrap">
                       <div class="services-import__scroll-wrap">';
                       foreach($grouped as $category):
                         $category_id++;
                         echo '
                          <span>
                             <div class="services-import__category">
                                <div class="services-import__category-title">
                                  <label><input type="checkbox" data-id="'.$category_id.'" id="checkAll-'.$category_id.'">'.$category[0]->category.'</label>
                                                                <input type="hidden" name="category" value="'.$category_id2.'">
                                </div>
                             </div>
                             <div class="services-import__packages">
                                <ul>';
                                for($i=0;$i<count($category);$i++):
                                  echo '<li><label><input data-service="'.$category[$i]->name.'" data-provider="'.$provider["id"].'"  data-category="'.$category_id.'"  class="selectServices-'.$category_id.'" type="checkbox" value="'.$category[$i]->service.'" name="services[]">'.$category[$i]->service.' - '.$category[$i]->name.'<span class="services-import__packages-price">'.priceFormat($category[$i]->rate).'</span></label></li>';
                                endfor;
                              echo  '</ul>
                             </div>
                          </span>';
                        endforeach;
                        echo '
                       </div>
                    </div>
                 </div>
              </div>
              <script>
              $(\'[id^="checkAll-"]\').click(function () {
                var id = $(this).attr("data-id");
                 if ( $(this).prop("checked") == true ) {
                   $(".selectServices-"+id).not(this).prop("checked", true);
                 }else{
                   $(".selectServices-"+id).not(this).prop("checked", false);
                 }
               });
              </script>
              </div>';
          else:
            echo "An error occurred, please try later.";
          endif;
      endif;
  elseif( $action == "import_services_last" ):
    $provider_id  = $_POST["provider"];
    $services     = json_decode(json_encode($_POST["services"]));
    $smmapi       = new SMMApi();
    $provider     = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
    $provider     ->execute(array("id"=>$provider_id));
    $provider     = $provider->fetch(PDO::FETCH_ASSOC);
    $apiServices  = $smmapi->action(array('key'=>$provider["api_key"],'action'=>'services'),$provider["api_url"]);
    $grouped      = array_group_by($services, 'category');
      echo '
      <div class="services-import__body">
             <div>
                <div class="services-import__fields">
                   
                   <div class="services-import__step3-field">
                      <div class="services-import__placeholder-title">Select Currency</div><br>
					  <select id="raise-currency" name="currency">
        <option value="" disabled selected>Choose Provider Currency</option>
        <option value="0.0139">INR</option>
        <option value="1">USD</option>
    </select>
                     
                   </div>
                   <div class="services-import__step3-plus">+</div>
                   <div class="services-import__step3-field">
                      <div class="services-import__placeholder-title">Percent (%)</div>
                      <input type="number" placeholder="0" id="raise-percent" name="percent" value="">
                   </div>
				   
                   <div class="services-import__step3-actions"><span class="btn btn-default">Reset calculations</span></div>
                </div>
                <div class="services-import__list-wrap services-import__list-active">
                   <div class="services-import__scroll-wrap">';
                      $category_id  = 0;
                      $c=0;
                      foreach($grouped as $category):
                          foreach ($apiServices as $key => $value):
                            if( $category[$category_id]->id == $value->service ):
                              $categoryName = $value->category;
                            endif;
                          endforeach;
                          $category_id=$category_id++;
                          $c++;
                        echo '<span class="providerCategory" id="providerCategory-'.$c.'">
                           <div class="services-import__category">
                              <div class="services-import__category-title"><label>'.$categoryName.'</label></div>
                           </div>
                           <div class="services-import__packages">
                              <ul>';
                                for($i=0;$i<count($category);$i++):
                                  foreach ($apiServices as $apiService):
                                    if( $apiService->service == $category[$i]->id  ):
                                      echo '<li id="providerService-'.$apiService->service.'">
                                         <label>
                                            '.$apiService->service.' - '.$apiService->name.'
                                            <span class="services-import__packages-price-edit" >
                                               <div class="services-import__packages-price-lock" data-category="'.$c.'"  data-id="servicedelete-'.$apiService->service.'" data-service="'.$apiService->service.'">
                                                 <span class="fa fa-trash"></span>
                                               </div>
                                               <div class="services-import__packages-price-lock"  data-id="servicelock-'.$apiService->service.'" data-service="'.$apiService->service.'">
                                                 <span class="fa fa-unlock"></span>
                                               </div>
                                               <input id="servicePriceCal'.$apiService->service.'" type="text" class="services-import__price" data-rate="'.priceFormat($apiService->rate).'" data-service="'.$apiService->service.'" name="servicesList['.$apiService->service.']" value="'.priceFormat
                                               ($apiService->rate).'">
                                               <span class="services-import__provider-price">'.priceFormat($apiService->rate).'</span>
                                            </span>
                                         </label>
                                      </li>';
                                    endif;
                                  endforeach;
                                endfor;
                            echo  '</ul>
                           </div>
                        </span>';
                      endforeach;
                   echo '</div>
                </div>
             </div>
          </div>
          <script>
          function formatCurrency(total) {
              var neg = false;
              if(total < 0) {
                  neg = true;
                  total = Math.abs(total);
              }
              return parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
          }
          function sum(input){
           if (toString.call(input) !== "[object Array]")
              return false;

                      var total =  0;
                      for(var i=0;i<input.length;i++)
                        {
                          if(isNaN(input[i])){
                          continue;
                           }
                      total += Number(input[i]);
                   }
             return total;
            }
          function chargeService(){
            var add_fixed       = $("#raise-fixed").val();
            var add_percent     = $("#raise-percent").val();
			var add_currency     = $("#raise-currency").val();
            $(".services-import__price").each(function(){
              if( $(this).attr("readonly") != "readonly" ){
                var rate        = $(this).attr("data-rate");
                var service     = $(this).attr("data-service");
                var total = sum([rate,(rate*add_percent/100)])*(add_currency);
                $("#servicePriceCal"+service).val(total);

              }
            });
          }
            $(\'[data-id^="servicedelete-"]\').click(function(){
              var id        = $(this).attr("data-service");
              var category  = $(this).attr("data-category");
              $("li#providerService-"+id).remove();
                if( $("#providerCategory-"+category+" > .services-import__packages > ul > li").length == 0 ){
                  $("#providerCategory-"+category).remove();
                }
            });
            $(\'[data-id^="servicelock-"]\').click(function(){
              var service_id  = $(this).attr("data-service");
              var lock        = $(this).find("span").attr("class");
              if( lock == "fa fa-unlock" ){
                $(this).find("span").removeClass("fa fa-unlock");
                $(this).find("span").addClass("fa fa-lock");
                $(\'[data-service="\'+service_id+\'"]\').attr("readonly",true);
              } else{
                $(this).find("span").removeClass("fa fa-lock");
                $(this).find("span").addClass("fa fa-unlock");
                $(\'[data-service="\'+service_id+\'"]\').attr("readonly",false);
              }
            });

            $(".services-import__step3-actions").click(function(){
              var add_fixed       = $("#raise-fixed").val("");
              var add_percent     = $("#raise-percent").val("");
			  var add_currency     = $("#raise-currency").val("");
              $(".services-import__price").each(function(){
                if( $(this).attr("readonly") != "readonly" ){
                  var rate        = $(this).attr("data-rate");
                  var service     = $(this).attr("data-service");
                    $("#servicePriceCal"+service).val(rate);
                }
              });
            });

            $("#raise-fixed").on("keyup", function(){
              chargeService();
            });

            $("#raise-percent").on("keyup", function(){
              chargeService();
            });
			 $("#raise-currency").on("keyup", function(){
              chargeService();
            });

          </script>
          ';
  elseif( $action == "price_providerCal" ):
    $fixed    = $_POST["fixed"];
    $percent  = $_POST["percent"];
    $rate     = $_POST["rate"];
    $total    = $rate;
      if( is_numeric($percent) && $percent > 0  ):
        $total= $total+($rate*$percent/100);
      endif;
      if( is_numeric($fixed) && $fixed > 0 ):
        $total= $total+$fixed;
      endif;
      echo $total;


elseif( $action == "import_service" ):
    $providers  = $conn->prepare("SELECT * FROM service_api   WHERE status=:status    ");
    $providers->execute(array(  "status"=>1    ));
    $providers  = $providers->fetchAll(PDO::FETCH_ASSOC);

      $category  = $conn->prepare("SELECT * FROM categories");
      $category->execute(array());
      $category  = $category->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/services/get_service_add/").'" method="post" data-xhr="true">
    
        <div class="modal-body">
          <div id="firstStep">
            <div class="service-mode__block">
              <div class="form-group">
              <label>Service Provider</label>
                <select class="form-control" name="provider" id="provider">
                      <option value="0">Select service provider...</option>';
                      foreach( $providers as $provider ):
                        $return.='<option value="'.$provider["id"].'">'.$provider["api_name"].'</option>';
                      endforeach;
                    $return.='</select>
              </div>
            </div>
          </div>

          
          <div id="secondStep">
          </div>

          <div id="thirdStep">
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="nextStep" data-step="first">Next step</button>
            <button type="submit" class="btn btn-primary" id="submitStep">Add services</button>
          </div>

        </form>
           <script>
            $("#submitStep").hide();
            $("#nextStep").click(function(){
              var now_step = $(this).attr("data-step");
              var provider = $("#provider").val();
              var category = $("#selector").val();
              $("#secondStep").hide();
                if( now_step == "first" ){
                  if( provider == 0 ){
                    $.toast({
                        heading: "Unsuccessful",
                        text: "Please select service provider",
                        icon: "error",
                        loader: true,
                        loaderBg: "#9EC600"
                    });
                  }else{
                    $("#firstStep").hide();
                    $("#secondStep").show();
                    $.post("admin/ajax_data", {provider:provider,category:category,action:"import_services_list" }, function(data){
                      $("#secondStep").html(data);
                    });
                    $("#nextStep").attr("data-step","second");
                  }
                }else if( now_step == "second" ){
                    var array     = [];
                       $(\'[class^="selectServices-"]\').each(function () {
                            var id    = $(this).val();
                            var check = $(this).prop("checked");
                            var provider  =  $(this).attr("data-provider");
                              if( check == true ){
                                var params = {};
                                params["id"]            = id;
                                params["category"]      = $(this).attr("data-category");
                                array.push(params);
                              }
                       });
                       var count = array.length;
                     if( count ){
                       $.post("admin/ajax_data", {provider:provider,action:"import_services_last",services:array }, function(data){
                         $("#thirdStep").html(data);
                       });
                       $("#nextStep").hide();
                       $("#submitStep").show();
                     }else{
                       $("#nextStep").attr("data-step","second");
                       $("#firstStep").hide();
                       $("#secondStep").show();
                       $("#nextStep").show();
                       $("#submitStep").hide();
                       $.toast({
                           heading: "Unsuccessful",
                           text: "Please select at least 1 service you want to add",
                           icon: "error",
                           loader: true,
                           loaderBg: "#9EC600"
                       });
                     }

                }
            });
          </script>
          ';
    echo json_encode(["content"=>$return,"title"=>"Pull out services from provider"]);
  elseif( $action == "import_services_list" ):
    $provider_id  = $_POST["provider"];
    $category_id2  = $_POST["category"];
    $smmapi       = new SMMApi();
    $provider     = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
    $provider     ->execute(array("id"=>$provider_id));
    $provider     = $provider->fetch(PDO::FETCH_ASSOC);
      if( $provider["api_type"] == 1 ):
        $services   = $smmapi->action(array('key'=>$provider["api_key"],'action'=>'services'),$provider["api_url"]);
          if( $services ):
            $grouped = array_group_by($services, 'category');
            echo '<div class="">
            <div class="services-import__body">
                 <div>
                    <div class="services-import__list-wrap">
                       <div class="services-import__scroll-wrap">';
                       foreach($grouped as $category):
                         $category_id++;
                         echo '
                          <span>
                             <div class="services-import__category">
                                <div class="services-import__category-title">
                                  <label><input type="checkbox" data-id="'.$category_id.'" id="checkAll-'.$category_id.'">'.$category[0]->category.'</label>
                                                                <input type="hidden" name="category" value="'.$category_id2.'">
                                </div>
                             </div>
                             <div class="services-import__packages">
                                <ul>';
                                for($i=0;$i<count($category);$i++):
                                  echo '<li><label><input data-service="'.$category[$i]->name.'" data-provider="'.$provider["id"].'"  data-category="'.$category_id.'"  class="selectServices-'.$category_id.'" type="checkbox" value="'.$category[$i]->service.'" name="services[]">'.$category[$i]->service.' - '.$category[$i]->name.'<span class="services-import__packages-price">'.priceFormat($category[$i]->rate).'</span></label></li>';
                                endfor;
                              echo  '</ul>
                             </div>
                          </span>';
                        endforeach;
                        echo '
                       </div>
                    </div>
                 </div>
              </div>
              <script>
              $(\'[id^="checkAll-"]\').click(function () {
                var id = $(this).attr("data-id");
                 if ( $(this).prop("checked") == true ) {
                   $(".selectServices-"+id).not(this).prop("checked", true);
                 }else{
                   $(".selectServices-"+id).not(this).prop("checked", false);
                 }
               });
              </script>
              </div>';
          else:
            echo "An error occurred, please try later.";
          endif;
      endif;
  elseif( $action == "import_services_last" ):
    $provider_id  = $_POST["provider"];
    $services     = json_decode(json_encode($_POST["services"]));
    $smmapi       = new SMMApi();
    $provider     = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
    $provider     ->execute(array("id"=>$provider_id));
    $provider     = $provider->fetch(PDO::FETCH_ASSOC);
    $apiServices  = $smmapi->action(array('key'=>$provider["api_key"],'action'=>'services'),$provider["api_url"]);
    $grouped      = array_group_by($services, 'category');
      echo '
      <div class="services-import__body">
             <div>
                <div class="services-import__fields">
                   
                   <div class="services-import__step3-field">
                      <div class="services-import__placeholder-title">Select Currency</div><br>
					  <select id="raise-currency" name="currency">
        <option value="" disabled selected>Choose Provider Currency</option>
        <option value="0.0139">INR</option>
        <option value="1">USD</option>
    </select>
                     
                   </div>
                   <div class="services-import__step3-plus">+</div>
                   <div class="services-import__step3-field">
                      <div class="services-import__placeholder-title">Percent (%)</div>
                      <input type="number" placeholder="0" id="raise-percent" name="percent" value="">
                   </div>
				   
                   <div class="services-import__step3-actions"><span class="btn btn-default">Reset calculations</span></div>
                </div>
                <div class="services-import__list-wrap services-import__list-active">
                   <div class="services-import__scroll-wrap">';
                      $category_id  = 0;
                      $c=0;
                      foreach($grouped as $category):
                          foreach ($apiServices as $key => $value):
                            if( $category[$category_id]->id == $value->service ):
                              $categoryName = $value->category;
                            endif;
                          endforeach;
                          $category_id=$category_id++;
                          $c++;
                        echo '<span class="providerCategory" id="providerCategory-'.$c.'">
                           <div class="services-import__category">
                              <div class="services-import__category-title"><label>'.$categoryName.'</label></div>
                           </div>
                           <div class="services-import__packages">
                              <ul>';
                                for($i=0;$i<count($category);$i++):
                                  foreach ($apiServices as $apiService):
                                    if( $apiService->service == $category[$i]->id  ):
                                      echo '<li id="providerService-'.$apiService->service.'">
                                         <label>
                                            '.$apiService->service.' - '.$apiService->name.'
                                            <span class="services-import__packages-price-edit" >
                                               <div class="services-import__packages-price-lock" data-category="'.$c.'"  data-id="servicedelete-'.$apiService->service.'" data-service="'.$apiService->service.'">
                                                 <span class="fa fa-trash"></span>
                                               </div>
                                               <div class="services-import__packages-price-lock"  data-id="servicelock-'.$apiService->service.'" data-service="'.$apiService->service.'">
                                                 <span class="fa fa-unlock"></span>
                                               </div>
                                               <input id="servicePriceCal'.$apiService->service.'" type="text" class="services-import__price" data-rate="'.priceFormat($apiService->rate).'" data-service="'.$apiService->service.'" name="servicesList['.$apiService->service.']" value="'.priceFormat
                                               ($apiService->rate).'">
                                               <span class="services-import__provider-price">'.priceFormat($apiService->rate).'</span>
                                            </span>
                                         </label>
                                      </li>';
                                    endif;
                                  endforeach;
                                endfor;
                            echo  '</ul>
                           </div>
                        </span>';
                      endforeach;
                   echo '</div>
                </div>
             </div>
          </div>
          <script>
          function formatCurrency(total) {
              var neg = false;
              if(total < 0) {
                  neg = true;
                  total = Math.abs(total);
              }
              return parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
          }
          function sum(input){
           if (toString.call(input) !== "[object Array]")
              return false;

                      var total =  0;
                      for(var i=0;i<input.length;i++)
                        {
                          if(isNaN(input[i])){
                          continue;
                           }
                      total += Number(input[i]);
                   }
             return total;
            }
          function chargeService(){
            var add_fixed       = $("#raise-fixed").val();
            var add_percent     = $("#raise-percent").val();
			var add_currency     = $("#raise-currency").val();
            $(".services-import__price").each(function(){
              if( $(this).attr("readonly") != "readonly" ){
                var rate        = $(this).attr("data-rate");
                var service     = $(this).attr("data-service");
                var total = sum([rate,(rate*add_percent/100)])*(add_currency);
                $("#servicePriceCal"+service).val(total);

              }
            });
          }
            $(\'[data-id^="servicedelete-"]\').click(function(){
              var id        = $(this).attr("data-service");
              var category  = $(this).attr("data-category");
              $("li#providerService-"+id).remove();
                if( $("#providerCategory-"+category+" > .services-import__packages > ul > li").length == 0 ){
                  $("#providerCategory-"+category).remove();
                }
            });
            $(\'[data-id^="servicelock-"]\').click(function(){
              var service_id  = $(this).attr("data-service");
              var lock        = $(this).find("span").attr("class");
              if( lock == "fa fa-unlock" ){
                $(this).find("span").removeClass("fa fa-unlock");
                $(this).find("span").addClass("fa fa-lock");
                $(\'[data-service="\'+service_id+\'"]\').attr("readonly",true);
              } else{
                $(this).find("span").removeClass("fa fa-lock");
                $(this).find("span").addClass("fa fa-unlock");
                $(\'[data-service="\'+service_id+\'"]\').attr("readonly",false);
              }
            });

            $(".services-import__step3-actions").click(function(){
              var add_fixed       = $("#raise-fixed").val("");
              var add_percent     = $("#raise-percent").val("");
			  var add_currency     = $("#raise-currency").val("");
              $(".services-import__price").each(function(){
                if( $(this).attr("readonly") != "readonly" ){
                  var rate        = $(this).attr("data-rate");
                  var service     = $(this).attr("data-service");
                    $("#servicePriceCal"+service).val(rate);
                }
              });
            });

            $("#raise-fixed").on("keyup", function(){
              chargeService();
            });

            $("#raise-percent").on("keyup", function(){
              chargeService();
            });
			 $("#raise-currency").on("keyup", function(){
              chargeService();
            });

          </script>
          ';
  elseif( $action == "price_providerCal" ):
    $fixed    = $_POST["fixed"];
    $percent  = $_POST["percent"];
    $rate     = $_POST["rate"];
    $total    = $rate;
      if( is_numeric($percent) && $percent > 0  ):
        $total= $total+($rate*$percent/100);
      endif;
      if( is_numeric($fixed) && $fixed > 0 ):
        $total= $total+$fixed;
      endif;
      echo $total;




  
  elseif( $action == "new_ticket" ):
    $return = '<form class="form" action="'.site_url("admin/tickets/new").'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Username</label>
            <input type="text" class="form-control" name="username" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Topic</label>
            <input type="text" class="form-control" name="subject" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Message</label>
            <textarea class="form-control" name="message" rows="4"></textarea>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create new request</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"New support request"]);
	elseif( $action == "yeni_kupon" ):
    $return = '<form class="form" action="'.site_url("admin/kuponlar/new").'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Coupon Code</label>
            <input type="text" class="form-control" name="kuponadi" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Piece</label>
            <input type="text" class="form-control" name="adet" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Amount</label>
            <input type="text" class="form-control" name="tutar" value="">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create new coupon</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          ';
    echo json_encode(["content"=>$return,"title"=>"Create new coupon"]);
	
elseif( $action == "edit_integration" && $_POST["id"] == "whatsapp" ):
    $id    = $_POST["id"];
    $method = $conn->prepare("SELECT * FROM integrations WHERE method_get=:id ");
    $method->execute(array("id"=>$id));
    $method = $method->fetch(PDO::FETCH_ASSOC);
    $extra  = json_decode($method["method_extras"],true);
    $return = '<form class="form" action="' . site_url('admin/settings/integrations/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Whatsapp Number</label>' . "\r\n" . '  <input type="text" class="form-control" name="number" value="' . $extra['number'] . '">' . "\r\n" . ' Omit any zeroes, brackets, or dashes when adding the phone number in international format. Example: 1XXXXXXXXXX</div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Position</label>' . "\r\n" . '<select class="form-control" name="position">' . "\r\n" . '  <option value="right"';

    if ($extra['position'] == "right" ) {
        $return .= 'selected';
    }

    $return .= '>Right</option>' . "\r\n" . '  <option value="left"';

    if (extra['position'] == "left" ) {
        $return .= 'selected';
    }

    $return .= '>Left</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Status</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

    if ($method['method_type'] == 2) {
        $return .= 'selected';
    }

    $return .= '>Enabled</option>' . "\r\n" . '  <option value="1"';

    if ($method['method_type'] == 1) {
        $return .= 'selected';
    }

    $return .= '>Disabled</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="visibility">' . "\r\n" . '  <option value="2"';

    if (extra['visibility'] == 2) {
        $return .= 'selected';
    }

    $return .= '>All</option>' . "\r\n" . ' <option value="2"';

    if (extra['visibility'] == 2) {
        $return .= 'selected';
    }

    $return .= '>External</option>' . "\r\n" . '  <option value="1"';

    if ($extra['visibility'] == 1) {
        $return .= 'selected';
    }

    $return .= '>Internal</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' </div>' . "\r\n\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
    echo json_encode(['content' => $return, 'title' => 'Whatsapp Button']);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paypal") :
  $id    = $_POST["id"];
  $method = $conn->prepare("SELECT * FROM payment_methods WHERE method_get=:id ");
  $method->execute(array("id" => $id));
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra  = json_decode($method["method_extras"], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Business Email</label>' . "\r\n" . '  <input type="text" class="form-control" name="business_email" value="' . $extra['business_email'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(["content" => $return, "title" => "Arrange payment method (Method: " . $method["method_name"] . ")"]);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "stripe") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Stripe Publishable Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="stripe_publishable_key" value="' . $extra['stripe_publishable_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Stripe Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="stripe_secret_key" value="' . $extra['stripe_secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Stripe Webhooks Secret</label>' . "\r\n" . '  <input type="text" class="form-control" name="stripe_webhooks_secret" value="' . $extra['stripe_webhooks_secret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);
elseif (($action == 'edit_paymentmethod') && ($_POST['id'] == 'perfectmoney')) :

  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Alternate Passphrase</label>' . "\r\n" . '  <input type="text" class="form-control" name="passphrase" value="' . $extra['passphrase'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">USD ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="usd" value="' . $extra['usd'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Website Name</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_website" value="' . $extra['merchant_website'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "coinpayments") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Coinpayments Public Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="coinpayments_public_key" value="' . $extra['coinpayments_public_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Coinpayments Private Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="coinpayments_private_key" value="' . $extra['coinpayments_private_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Coinpayments Crypto Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="coinpayments_currency" value="' . $extra['coinpayments_currency'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_id" value="' . $extra['merchant_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">IPN Secret</label>' . "\r\n" . '  <input type="text" class="form-control" name="ipn_secret" value="' . $extra['ipn_secret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);
elseif ($action == "edit_paymentmethod" && $_POST["id"] == "2checkout") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Seller ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="seller_id" value="' . $extra['seller_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Private Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="private_key" value="' . $extra['private_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);
elseif ($action == "edit_paymentmethod" && $_POST["id"] == "payoneer") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Email</label>' . "\r\n" . '  <input type="text" class="form-control" name="email" value="' . $extra['email'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "mollie") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live API key</label>' . "\r\n" . '  <input type="text" class="form-control" name="live_api_key" value="' . $extra['live_api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paytm") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant MID</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_mid" value="' . $extra['merchant_mid'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Website</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_website" value="' . $extra['merchant_website'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '" readonly>' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif (($action == 'edit_paymentmethod') && ($_POST['id'] == 'Cashmaal')) :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Web ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="web_id" value="' . $extra['web_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" .  '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '" readonly>' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "instamojo") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_key" value="' . $extra['api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live Auth Token Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="live_auth_token_key" value="' . $extra['live_auth_token_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paystack") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_secret_key" value="' . $extra['api_secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Publish Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_publish_key" value="' . $extra['api_publish_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);




elseif ($action == "edit_paymentmethod" && $_POST["id"] == "razorpay") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_key" value="' . $extra['api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_secret_key" value="' . $extra['api_secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "iyzico") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_key" value="' . $extra['api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_secret_key" value="' . $extra['api_secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "authorize-net") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Login Id</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_login_id" value="' . $extra['api_login_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Secret Transaction Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="secret_transaction_key" value="' . $extra['secret_transaction_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);




elseif ($action == "edit_paymentmethod" && $_POST["id"] == "mercadopago") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live Access Token</label>' . "\r\n" . '  <input type="text" class="form-control" name="live_access_token" value="' . $extra['live_access_token'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "payumoney") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Salt Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="salt" value="' . $extra['salt'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);









elseif ($action == "edit_paymentmethod" && $_POST["id"] == "ravepay") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Public API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="public_api_key" value="' . $extra['public_api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Secret API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="secret_api_key" value="' . $extra['secret_api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "pagseguro") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">PagSeguro Email id</label>' . "\r\n" . '  <input type="text" class="form-control" name="email_id" value="' . $extra['email_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live Production Token</label>' . "\r\n" . '  <input type="text" class="form-control" name="live_production_token" value="' . $extra['live_production_token'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "shopier") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">apiKey</label>' . "\r\n" . '  <input type="text" class="form-control" name="apiKey" value="' . $extra['apiKey'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">apiSecret</label>' . "\r\n" . '  <input type="text" class="form-control" name="apiSecret" value="' . $extra['apiSecret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . ' <label>Callbacks</label>' . "\r\n" . '  <select class="form-control" name="website_index">' . "\r\n" . ' <option value="1"';

  if ($extra['website_index'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (1)</option>' . "\r\n" . ' <option value="2"';

  if ($extra['website_index'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (2)</option>' . "\r\n" . ' <option value="3"';

  if ($extra['website_index'] == 3) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (3)</option>' . "\r\n" . ' <option value="4"';

  if ($extra['website_index'] == 4) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (4)</option>' . "\r\n" . ' <option value="5"';

  if ($extra['website_index'] == 5) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (5)</option>' . "\r\n" . '</select>' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . ' <label>Processing fee (0,49 TL)</label>' . "\r\n" . '  <select class="form-control" name="processing_fee">' . "\r\n" . ' <option value="1"';

  if ($extra['processing_fee'] == 1) {
    $return .= 'selected';
  }

  $return .= '>User should pay this commission</option>' . "\r\n" . ' <option value="0"';

  if ($extra['processing_fee'] == 0) {
    $return .= 'selected';
  }

  $return .= '>User should not pay this commission</option>' . "\r\n" . '</select>' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paytr") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API Callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant id</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_id" value="' . $extra['merchant_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant key</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant salt</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_salt" value="' . $extra['merchant_salt'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif (($action == 'edit_paymentmethod') && ($_POST['id'] == 'paytmqr')) :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Paytm QR Image Link</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant MID</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_mid" value="' . $extra['merchant_mid'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Website</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_website" value="' . $extra['merchant_website'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paytr_havale") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/paytr');
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant id</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_id" value="' . $extra['merchant_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant key</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant salt</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_salt" value="' . $extra['merchant_salt'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paywant") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">apiKey</label>' . "\r\n" . '  <input type="text" class="form-control" name="apiKey" value="' . $extra['apiKey'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">apiSecret</label>' . "\r\n" . '  <input type="text" class="form-control" name="apiSecret" value="' . $extra['apiSecret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Paywant Commission</label>' . "\r\n" . '<select class="form-control" name="commissionType">' . "\r\n" . '  <option value="2"';

  if ($extra['commissionType'] == 2) {
    $return .= 'selected';
  }

  $return .= '>User should pay this commission</option>' . "\r\n" . '  <option value="1"';

  if ($extra['commissionType'] == 1) {
    $return .= 'selected';
  }

  $return .= '>User should not pay this commission</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label>Payment Methods</label>' . "\r\n" . '<div class="form-group col-md-12">' . "\r\n" . ' <div class="row">' . "\r\n" . '  <label class="checkbox-inline col-md-3">' . "\r\n" . '<input type="checkbox" class="access" name="payment_type[]" value="1"';

  if (in_array(1, $extra['payment_type'])) {
    $return .= ' checked';
  }

  $return .= '> Mobile Payment' . "\r\n" . '  </label>' . "\r\n" . '  <label class="checkbox-inline col-md-3">' . "\r\n" . '<input type="checkbox" class="access" name="payment_type[]" value="2"';

  if (in_array(2, $extra['payment_type'])) {
    $return .= ' checked';
  }

  $return .= '> Credit/Bank Card' . "\r\n" . '  </label>' . "\r\n" . '  <label class="checkbox-inline col-md-3">' . "\r\n" . '<input type="checkbox" class="access" name="payment_type[]" value="3"';

  if (in_array(3, $extra['payment_type'])) {
    $return .= ' checked';
  }

  $return .= '> Money Order / EFT' . "\r\n" . '  </label>' . "\r\n" . ' </div>' . "\r\n" . '</div>' . "\r\n" . '  </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "havale-eft") :
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);






  elseif( $action == "new_bankaccount" ):
    $return = '<form class="form" action="'.site_url("admin/settings/bank-accounts/new").'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group">The name of the Bank</label>
            <input type="text" name="bank_name" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">Recipient name</label>
            <input type="text" name="bank_alici" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">Branch number</label>
            <input type="text" name="bank_sube" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">Account number</label>
            <input type="text" name="bank_hesap" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">IBAN</label>
            <input type="text" name="bank_iban" class="form-control" value="">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add new bank account</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"New bank account"]);
  elseif( $action == "edit_bankaccount" ):
    $id       = $_POST["id"];
    $bank = $conn->prepare("SELECT * FROM bank_accounts WHERE id=:id ");
    $bank->execute(array("id"=>$id));
    $bank = $bank->fetch(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/settings/bank-accounts/edit/".$id).'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group">The name of the Bank</label>
            <input type="text" name="bank_name" class="form-control" value="'.$bank["bank_name"].'">
          </div>

          <div class="form-group">
            <label class="form-group">Recipient name</label>
            <input type="text" name="bank_alici" class="form-control" value="'.$bank["bank_alici"].'">
          </div>

          <div class="form-group">
            <label class="form-group">Branch number</label>
            <input type="text" name="bank_sube" class="form-control" value="'.$bank["bank_sube"].'">
          </div>

          <div class="form-group">
            <label class="form-group">Account number</label>
            <input type="text" name="bank_hesap" class="form-control" value="'.$bank["bank_hesap"].'">
          </div>

          <div class="form-group">
            <label class="form-group">IBAN</label>
            <input type="text" name="bank_iban" class="form-control" value="'.$bank["bank_iban"].'">
          </div>


        </div>

        <div class="modal-footer">
          <a id="delete-row" data-url="'.site_url("admin/settings/bank-accounts/delete/".$bank["id"]).'" class="btn btn-danger pull-left">Remove account</a>
          <button type="submit" class="btn btn-primary">Update bank account</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
        </form>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
        $("#delete-row").click(function(){
          var action = $(this).attr("data-url");
          swal({
            title: "Are you sure you want to delete?",
            text: "If you confirm, this content will be deleted, it may not be possible to restore it.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            buttons: ["Cancel", "Yes, I am sure!"],
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                url:  action,
                type: "GET",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false
              })
              .done(function(result){
                if( result.s == "error" ){
                  var heading = "Unsuccessful";
                }else{
                  var heading = "Successful";
                }
                  $.toast({
                      heading: heading,
                      text: result.m,
                      icon: result.s,
                      loader: true,
                      loaderBg: "#9EC600"
                  });
                  if (result.r!=null) {
                    if( result.time ==null ){ result.time = 3; }
                    setTimeout(function(){
                      window.location.href  = result.r;
                    },result.time*1000);
                  }
              })
              .fail(function(){
                $.toast({
                    heading: "Unsuccessful",
                    text: "The request could not be fulfilled",
                    icon: "error",
                    loader: true,
                    loaderBg: "#9EC600"
                });
              });
              /* İçerik silinmesi onaylandı */
            } else {
              $.toast({
                  heading: "Unsuccessful",
                  text: "Request for deletion denied",
                  icon: "error",
                  loader: true,
                  loaderBg: "#9EC600"
              });
            }
          });
        });
        </script>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Update bank account"]);
  elseif( $action == "new_paymentbonus" ):
    $methodList = $conn->prepare("SELECT * FROM payment_methods WHERE id!='4' ");
    $methodList->execute(array());
    $methodList = $methodList->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/settings/payment-bonuses/new").'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
          <label>Method</label>
            <select class="form-control" name="method_type">';
                  foreach ($methodList as $method):
                    $return.='<option value="'.$method["id"].'">'.$method["method_name"].'</option>';
                  endforeach;
              $return.='</select>
          </div>

          <div class="form-group">
            <label class="form-group">Bonus amount (%)</label>
            <input type="text" name="amount" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">Starts From Amount</label>
            <input type="text" name="from" class="form-control" value="">
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add new bonus</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Add new bonus"]);
  elseif( $action == "edit_paymentbonus" ):
    $id         = $_POST["id"];
    $bonus      = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_id=:id ");
    $bonus      ->execute(array("id"=>$id));
    $bonus      = $bonus->fetch(PDO::FETCH_ASSOC);
    $methodList = $conn->prepare("SELECT * FROM payment_methods  WHERE id!='4' ");
    $methodList->execute(array());
    $methodList = $methodList->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/settings/payment-bonuses/edit/".$id).'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
          <label>Method</label>
            <select class="form-control" name="method_type">';
                  foreach ($methodList as $method):
                    $return.='<option value="'.$method["id"].'"'; if( $bonus["bonus_method"] == $method["id"] ): $return.='selected'; endif; $return.='>'.$method["method_name"].'</option>';
                  endforeach;
              $return.='</select>
          </div>

          <div class="form-group">
            <label class="form-group">Bonus amount (%)</label>
            <input type="text" name="amount" class="form-control" value="'.$bonus["bonus_amount"].'">
          </div>

          <div class="form-group">
            <label class="form-group">Starts From Amount</label>
            <input type="text" name="from" class="form-control" value="'.$bonus["bonus_from"].'">
          </div>

        </div>

          <div class="modal-footer">
            <a id="delete-row" data-url="'.site_url("admin/settings/payment-bonuses/delete/".$bonus["bonus_id"]).'" class="btn btn-danger pull-left">Remove bonus</a>
            <button type="submit" class="btn btn-primary">Update bonus</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
          <script>
          $("#delete-row").click(function(){
            var action = $(this).attr("data-url");
            swal({
              title: "Are you sure you want to delete?",
              text: "If you confirm this content will be deleted, it may not be possible to restore it.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
              buttons: ["Cancel", "Yes, I am sure!"],
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                  url:  action,
                  type: "GET",
                  dataType: "json",
                  cache: false,
                  contentType: false,
                  processData: false
                })
                .done(function(result){
                  if( result.s == "error" ){
                    var heading = "Unsuccessful";
                  }else{
                    var heading = "Successful";
                  }
                    $.toast({
                        heading: heading,
                        text: result.m,
                        icon: result.s,
                        loader: true,
                        loaderBg: "#9EC600"
                    });
                    if (result.r!=null) {
                      if( result.time ==null ){ result.time = 3; }
                      setTimeout(function(){
                        window.location.href  = result.r;
                      },result.time*1000);
                    }
                })
                .fail(function(){
                  $.toast({
                      heading: "Unsuccessful",
                      text: "The request could not be fulfilled",
                      icon: "error",
                      loader: true,
                      loaderBg: "#9EC600"
                  });
                });
                /* İçerik silinmesi onaylandı */
              } else {
                $.toast({
                    heading: "Unsuccessful",
                    text: "Request for deletion denied",
                    icon: "error",
                    loader: true,
                    loaderBg: "#9EC600"
                });
              }
            });
          });
          </script>
          ';
    echo json_encode(["content"=>$return,"title"=>"Update payment bonus"]);
  elseif( $action == "new_provider" ):
    $return = '<form class="form" action="'.site_url("admin/settings/providers/new").'" method="post" data-xhr="true">

        <div class="modal-body">

          

          <div class="form-group">
            <label class="form-group__service-name">API URL</label>
            <input type="text" class="form-control" name="url" value="">
          </div>


          

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add provider</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Add new provider"]);
  elseif( $action == "edit_provider" ):
    $id         = $_POST["id"];
    $provider   = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
    $provider   ->execute(array("id"=>$id));
    $provider   = $provider->fetch(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/settings/providers/edit/".$id).'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Provider Name</label>
            <input type="text" class="form-control" name="name" value="'.$provider["api_name"].'" readonly  >
          </div>

          

          <div class="form-group">
            <label class="form-group__service-name">API Key</label>
            <input type="text" class="form-control" name="apikey" value="'.$provider["api_key"].'">
          </div>

          

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Edit provider</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>


  

          </div>
                  </form>
<script>
            
            $("#admin ").change(function(){
              var type = $(this).val();
                if( $panel["panel_type"] != "Child" ){
                  $("#admin_access").hide();
                } else{
                  $("#admin_access").show();
                }
            });
          </script>

                  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
          <script>
          $("#delete-row").click(function(){
            var action = $(this).attr("data-url");
            swal({
              title: "Are you sure you want to delete?",
              text: "If you confirm this content will be deleted, it may not be possible to restore it.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
              buttons: ["Cancel", "Yes, I am sure!"],
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                  url:  action,
                  type: "GET",
                  dataType: "json",
                  cache: false,
                  contentType: false,
                  processData: false
                })
                .done(function(result){
                  if( result.s == "error" ){
                    var heading = "Unsuccessful";
                  }else{
                    var heading = "Successful";
                  }
                    $.toast({
                        heading: heading,
                        text: result.m,
                        icon: result.s,
                        loader: true,
                        loaderBg: "#9EC600"
                    });
                    if (result.r!=null) {
                      if( result.time ==null ){ result.time = 3; }
                      setTimeout(function(){
                        window.location.href  = result.r;
                      },result.time*1000);
                    }
                })
                .fail(function(){
                  $.toast({
                      heading: "Unsuccessful",
                      text: "The request could not be fulfilled",
                      icon: "error",
                      loader: true,
                      loaderBg: "#9EC600"
                  });
                });
                /* İçerik silinmesi onaylandı */
              } else {
                $.toast({
                    heading: "Unsuccessful",
                    text: "Request for deletion denied",
                    icon: "error",
                    loader: true,
                    loaderBg: "#9EC600"
                });
              }
            });
          });
          </script>
         ';
    echo json_encode(["content"=>$return,"title"=>"Edit provider (".$provider["api_name"].") "]);
   
  elseif( $action == "export_user" ):
    $return = '<form class="form" action="'.site_url("admin/clients/export").'" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Membership Status</label>
              <select class="form-control" name="client_status">
                    <option value="all">All members</option>
                    <option value="1">Inactive</option>
                    <option value="2">Active</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Email Status</label>
              <select class="form-control" name="email_status">
                    <option value="all">All members</option>
                    <option value="1">Unapproved</option>
                    <option value="2">Approved</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Format</label>
              <select class="form-control" name="format">
                    <option value="json">JSON</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Member information</label>
              <div class="form-group">
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[client_id]" checked value="1"> ID
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[email]" checked value="1"> Email
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[name]" checked value="1"> Name surname
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[username]" checked value="1"> Username
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[telephone]" checked value="1"> Phone number
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[balance]" checked value="1"> Balance
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[spent]" checked value="1"> Spending
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[register_date]" checked value="1"> Date of registration
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" class="access" name="exportcolumn[login_date]" checked value="1"> Last entry date
                  </label>
              </div>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Backup users</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Backup users"]);
  elseif( $action == "all_numbers" ):
    $rows   = $conn->prepare("SELECT * FROM clients");
    $rows->execute(array());
    $rows   = $rows->fetchAll(PDO::FETCH_ASSOC);
    $numbers= "";
    $emails = "";
      foreach ($rows as $row):
        if( $row["telephone"] ): $numbers.=$row["telephone"]."\n"; endif;
        $emails.=$row["email"]."\n";
      endforeach;
    $return = '<form>
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Member Phone Numbers</label>
              <textarea class="form-control" rows="8" readonly>'.$numbers.'</textarea>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Member E-mail Addresses</label>
              <textarea class="form-control" rows="8" readonly>'.$emails.'</textarea>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"User information"]);

	
	elseif( $action == "details" ):
	
	$toplamkullanici      = $conn->prepare("SELECT * FROM clients");
    $toplamkullanici     -> execute();
    $toplamkullanici      = $toplamkullanici->rowCount();
	
	//Toplam Kullanılabilir Bakiye
	$query = $conn->query("SELECT sum(balance) as toplambakiye FROM clients")->fetch(PDO::FETCH_ASSOC);
	
	//Toplam Harcanan Bakiye
	$query2 = $conn->query("SELECT sum(order_charge) as order_charge FROM orders")->fetch(PDO::FETCH_ASSOC);
	
	//Negatif Bakiyeli Kullanıcılar
	$negatifbakiye      = $conn->prepare("SELECT * FROM clients where balance < 0");
    $negatifbakiye     -> execute();
    $negatifbakiye      = $negatifbakiye->rowCount();
	
	//Bakiyesi Olmayan
	$bakiyesiz      = $conn->prepare("SELECT * FROM clients where balance = 0");
    $bakiyesiz     -> execute();
    $bakiyesiz      = $bakiyesiz->rowCount();
    
   
    $return = '<form>
        <div class="modal-body">
		
          <div class="service-mode__block">
            <div class="form-group">
            <label>Total Users : '.$toplamkullanici.'</label>
            </div>
          </div>
		  
		  <div class="service-mode__block">
            <div class="form-group">
            <label>Total Available Balance : '.$query['toplambakiye'].'</label>
            </div>
          </div>
		  
		  <div class="service-mode__block">
            <div class="form-group">
            <label>Total Spent Balance : '.$query2['order_charge'].'</label>
            </div>
          </div>
		  
		  <div class="service-mode__block">
            <div class="form-group">
            <label>Negative Balance User : '.$negatifbakiye.'</label>
            </div>
          </div>
		  
		  <div class="service-mode__block">
            <div class="form-group">
            <label>Zero Balance User : '.$bakiyesiz.'</label>
            </div>
          </div>
		  

        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Detail"]);
  elseif( $action ==  "price_user" ):
    $id     = $_POST["id"];
    $price  = $conn->prepare("SELECT *,services.service_id as serviceid,services.service_price as price,clients_price.service_price as clientprice FROM services LEFT JOIN clients_price ON clients_price.service_id=services.service_id && clients_price.client_id=:id ");
    $price -> execute(array("id"=>$id));
    $price  = $price->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/clients/price/".$id).'" method="post" data-xhr="true">
        <div class="modal-body">

        <div class="services-import__body">
               <div>
                  <div class="services-import__list-wrap services-import__list-active">
                     <div class="services-import__scroll-wrap">
                        <span>
                             <div class="services-import__packages">
                                <ul>';
                                  foreach ($price as $row):
                                    $return.='<li id="service-'.$row["serviceid"].'">
                                     <label>
                                        '.$row["serviceid"].' - '.$row["service_name"].'
                                        <span class="services-import__packages-price-edit" >
                                           <div class="services-import__packages-price-lock"  data-id="servicedelete-'.$row["serviceid"].'" data-service="'.$row["serviceid"].'">
                                             <span class="fa fa-trash"></span>
                                           </div>
                                           <input type="text" class="services-import__price" name="price['.$row["serviceid"].']" value="'.$row["clientprice"].'">
                                           <span class="services-import__provider-price">'.$row["price"].'</span>
                                        </span>
                                     </label>
                                    </li>';
                                  endforeach;
                                $return.='</ul>
                             </div>
                          </span></div>
                  </div>
               </div>
            </div>
            <script>

              $(\'[data-id^="servicedelete-"]\').click(function(){
                var id        = $(this).attr("data-service");
                $("[name=\'price["+id+"]\']").val("");
                //$("ul > li#service-"+id).remove();
              });

            </script>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
        echo json_encode(["content"=>$return,"title"=>"Special Pricing"]);
  elseif( $action == "order_errors" ):
    $id     = $_POST["id"];
    $row    = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
    $row ->execute(array("id"=>$id));
    $row    = $row->fetch(PDO::FETCH_ASSOC);
    $errors = json_decode($row["order_error"]);
    $return = '<form>
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Information from the provider</label>
              <textarea class="form-control" rows="8" readonly>'; $return.=print_r($errors,true); $return.='</textarea>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Error details (ID: ".$row["order_id"].") "]);
  elseif( $action == "order_details" ):
    $id     = $_POST["id"];
    $row    = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
    $row ->execute(array("id"=>$id));
    $row    = $row->fetch(PDO::FETCH_ASSOC);
    $detail = json_decode($row["order_detail"]);
    $return = '<form>
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Information from the provider</label>
              <textarea class="form-control" rows="8" readonly>'; $return.=print_r($detail,true); $return.='</textarea>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Order ID</label>
              <input class="form-control" value="'.$row["api_orderid"].'" readonly>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Last update</label>
              <input class="form-control" value="'.$row["last_check"].'" readonly>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Order details (ID: ".$row["order_id"].") "]);

  elseif( $action == "earn_note" ):
    $id     = $_POST["id"];
    $earn    = $conn->prepare("SELECT * FROM earn WHERE earn_id=:id ");
    $earn ->execute(array("id"=>$id));
    $earn    = $earn->fetch(PDO::FETCH_ASSOC);
    $earn_note = json_decode($earn["earn_note"]);
    $return = '<form class="form" action="'.site_url("admin/earn/set_earnnote/".$id).'" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Promotion Note(ex:-20rs funds granted)</label>
              <input class="form-control" value="'.$earn["earn_note"].'" name="note">
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Promotion details (ID: ".$earn["earn_id"].") "]);



  elseif( $action == "order_orderurl" ):
    $id     = $_POST["id"];
    $row    = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
    $row ->execute(array("id"=>$id));
    $row    = $row->fetch(PDO::FETCH_ASSOC);
    $detail = json_decode($row["order_detail"]);
    $return = '<form class="form" action="'.site_url("admin/orders/set_orderurl/".$id).'" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Order Link</label>
              <input class="form-control" value="'.$row["order_url"].'" name="url">
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Order details (ID: ".$row["order_id"].") "]);
  elseif( $action == "order_startcount" ):
    $id     = $_POST["id"];
    $row    = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
    $row ->execute(array("id"=>$id));
    $row    = $row->fetch(PDO::FETCH_ASSOC);
    $detail = json_decode($row["order_detail"]);
    $return = '<form class="form" action="'.site_url("admin/orders/set_startcount/".$id).'" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Starting number</label>
              <input class="form-control" value="'.$row["order_start"].'" name="start">
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Order details (ID: ".$row["order_id"].") "]);
  elseif( $action == "order_partial" ):
    $id     = $_POST["id"];
    $row    = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
    $row ->execute(array("id"=>$id));
    $row    = $row->fetch(PDO::FETCH_ASSOC);
    $detail = json_decode($row["order_detail"]);
    $return = '<form class="form" action="'.site_url("admin/orders/set_partial/".$id).'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Not going amount</label>
              <input class="form-control" name="remains">
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Order details (ID: ".$row["order_id"].") "]);
  elseif( $action == "subscriptions_expiry" ):
    $id     = $_POST["id"];
    $row    = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
    $row ->execute(array("id"=>$id));
    $row    = $row->fetch(PDO::FETCH_ASSOC);
    $detail = json_decode($row["order_detail"]);
    $return = '<form class="form" action="'.site_url("admin/subscriptions/set_expiry/".$id).'" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Starting number</label>
              <input class="form-control datetime" value="'; if( $row["subscriptions_expiry"] != "1970-01-01" ): $return.=date("d/m/Y", strtotime($row["subscriptions_expiry"])); endif; $return.='" name="expiry">
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <link rel="stylesheet" type="text/css" href="'.site_url("public/").'datepicker/css/bootstrap-datepicker3.min.css">
          <script type="text/javascript" src="'.site_url("public/").'datepicker/js/bootstrap-datepicker.min.js"></script>
          <script type="text/javascript" src="'.site_url("public/").'datepicker/locales/bootstrap-datepicker.tr.min.js"></script>
          ';
    echo json_encode(["content"=>$return,"title"=>"Subscription end date (ID: ".$row["order_id"].") "]);
  elseif( $action == "payment_bankedit" ):
    $id = $_POST["id"];
    $payment  = $conn->prepare("SELECT * FROM payments INNER JOIN bank_accounts ON bank_accounts.id=payments.payment_bank INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_id=:id");
    $payment  -> execute(array("id"=>$id));
    $payment  = $payment->fetch(PDO::FETCH_ASSOC);
    $bank     = $conn->prepare("SELECT * FROM bank_accounts ");
    $bank    -> execute();
    $bank     = $bank->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/payments/edit-bank/".$id).'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>The paid bank</label>
              <select class="form-control" name="bank">';
                foreach( $bank as $banka ):
                  $return.= '<option value="'.$banka["id"].'"'; if( $payment["payment_bank"] == $banka["id"] ): $return.='selected'; endif; $return.='>'.$banka["bank_name"].'</option>';
                endforeach;
                $return.='</select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Payment status</label>
              <select class="form-control" '; if( $payment["payment_status"] == 3 ): $return.='disabled'; endif; $return.=' name="status">
                    <option value="1"'; if( $payment["payment_status"] == 1 ): $return.='selected'; endif; $return.='>Pending</option>
                    <option value="2"'; if( $payment["payment_status"] == 2 ): $return.='selected'; endif; $return.='>Cancel</option>
                    <option value="3"'; if( $payment["payment_status"] == 3 ): $return.='selected'; endif; $return.='>Approved</option>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Note</label>
            <input type="text" class="form-control" name="note" value="'.$payment["payment_note"].'">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Arrange a bank payment (ID: ".$id.") "]);
  elseif( $action == "payment_banknew" ):
    $bank     = $conn->prepare("SELECT * FROM bank_accounts ");
    $bank    -> execute();
    $bank     = $bank->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/payments/new-bank/").'" method="post" data-xhr="true">

        <div class="modal-body">


          <div class="form-group">
            <label class="form-group__service-name">Username</label>
            <input type="text" class="form-control" name="username" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Amount</label>
            <input type="text" class="form-control" name="amount" value="">
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>The paid bank</label>
              <select class="form-control" name="bank">';
                foreach( $bank as $banka ):
                  $return.= '<option value="'.$banka["id"].'">'.$banka["bank_name"].'</option>';
                endforeach;
                $return.='</select>
            </div>
          </div>


          <div class="form-group">
            <label class="form-group__service-name">Note</label>
            <input type="text" class="form-control" name="note" value="">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add payment</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Add bank payment "]);

elseif( $action == "edit_w" ):
    $id         = $_POST["id"];
    $integration      = $conn->prepare("SELECT * FROM integrations WHERE id=:id ");
    $integration      ->execute(array("id"=>"1"));
    $integration      = $integration->fetch(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/settings/integrations/edit/1").'" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">WhatsApp Number</label>
          <input class="form-control" value="'.$integration["w_num"].'" name="w_num">
Omit any zeroes, brackets, or dashes when adding the phone number in international format. Example: 1XXXXXXXXXX
          </div> 

<div class="service-mode__block">
            <div class="form-group">
            <label>Position</label>
              <select class="form-control" '; if( $integration["w_position"] == 1 ): $return.='Right'; endif; $return.=' name="w_position">
                    <option value="1"'; if( $integration["w_position"] == 1 ): $return.='selected'; endif; $return.='>Right</option>
                    <option value="2"'; if( $integration["w_position"] == 2 ): $return.='selected'; endif; $return.='>Left</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Status</label>
              <select class="form-control" '; if( $integration["w_status"] == 1 ): $return.='Enabled'; endif; $return.=' name="w_status">
                    <option value="1"'; if( $integration["w_status"] == 1 ): $return.='selected'; endif; $return.='>Enabled</option>
                    <option value="2"'; if( $integration["w_status"] == 2 ): $return.='selected'; endif; $return.='>Disabled</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Visibility</label>
              <select class="form-control" '; if( $integration["w_type"] == 1 ): $return.='All'; endif; $return.=' name="w_type">
                    <option value="1"'; if( $integration["w_type"] == 1 ): $return.='selected'; endif; $return.='>All</option>
                    <option value="2"'; if( $integration["w_type"] == 2 ): $return.='selected'; endif; $return.='>Public</option>
                    <option value="3"'; if( $integration["w_type"] == 3 ): $return.='selected'; endif; $return.='>Internal</option>
                </select>
            </div>
          </div>

          


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Button"]);


  elseif( $action == "payment_edit" ):
    $id = $_POST["id"];
    $payment  = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_id=:id");
    $payment  -> execute(array("id"=>$id));
    $payment  = $payment->fetch(PDO::FETCH_ASSOC);
    $methods  = $conn->prepare("SELECT * FROM payment_methods WHERE id!='4' ");
    $methods  -> execute();
    $methods  = $methods->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/payments/edit-online/".$id).'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Payment method</label>
              <select class="form-control" name="method">';
                foreach( $methods as $method ):
                  $return.= '<option value="'.$method["id"].'"'; if( $payment["payment_method"] == $method["id"] ): $return.='selected'; endif; $return.='>'.$method["method_name"].'</option>';
                endforeach;
                $return.='</select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Note</label>
            <input type="text" class="form-control" name="note" value="'.$payment["payment_note"].'">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Edit online payment (ID: ".$id.") "]);
elseif ($action == "reffered_users") :
  $ref_code = $_POST["id"];

  $clients  = $conn->prepare("SELECT * FROM clients WHERE ref_by=:ref_by");
  $clients->execute(array("ref_by" => $ref_code));
  $clients  = $clients->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form>
      <div class="modal-body">

        <div class="service-mode__block">
          <div class="form-group">

            <table  class="table" id="table1" style="overflow:auto;"> <thead>
            <th>Username</th><th>Balance</th><th>Spent</th><th>Actions </th>
            </thead>';
  foreach ($clients as $client) :
    // $return.=  $client['username'] .' , ';
    $return .= '<tr>
                <td>' . $client['username'] . '</td>
                <td>' . $client['balance'] . '</td>
                <td>' . $client['spent'] . '</td>
                <td><a href="admin/referrals?ref_code=' . $ref_code . '&remove=' . $client['client_id'] . '">Remove</a></td>
              </tr>';
  endforeach;

  // <textarea class="form-control" rows="8" readonly> Usernames :
  $return .= '</table>
          </div>
        </div>
      </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
        </form>';

  echo json_encode(["content" => $return, "title" => "Reffered Users by " . $ref_code . " Code"]);


  elseif( $action == "payment_new" ):
    $methods  = $conn->prepare("SELECT * FROM payment_methods WHERE id!='4' ");
    $methods  -> execute();
    $methods  = $methods->fetchAll(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/payments/new-online").'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Username</label>
            <input type="text" class="form-control" name="username" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Amount</label>
            <input type="text" class="form-control" name="amount" value="">
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Add/Remove</label>
              <select class="form-control" name="add-remove">
                <option value="add">Add</option>
                <option value="remove">Remove</option>
            </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Payment method</label>
              <select class="form-control" name="method">';
                foreach( $methods as $method ):
                  $return.= '<option value="'.$method["id"].'">'.$method["method_name"].'</option>';
                endforeach;
                $return.='</select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Note</label>
            <input type="text" class="form-control" name="note" value="">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add payment</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>" Add payment"]);
  elseif( $action == "payment_detail" ):
    $id     = $_POST["id"];
    $row    = $conn->prepare("SELECT * FROM payments WHERE payment_id=:id ");
    $row ->execute(array("id"=>$id));
    $row    = $row->fetch(PDO::FETCH_ASSOC);
    $detail = json_decode($row["payment_extra"]);
    $return = '<form>
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Payment info</label>
              <textarea class="form-control" rows="8" readonly>'; $return.=print_r($detail,true); $return.='</textarea>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Last update</label>
              <input class="form-control" value="'.$row["payment_update_date"].'" readonly>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Payment details (ID: ".$row["payment_id"].") "]);
elseif( $action == "add_currency" ):
    
    $return = '<form class="form" action="'.site_url("admin/settings/currency/add").'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Currency symbol</label>
            <input type="text" class="form-control" name="symbol" value="">
          </div>

          
          <div class="form-group">
            <label class="form-group__service-name">Currency Name</label>
            <input type="text" class="form-control" name="name" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">1 Usd = </label>
            <input type="text" class="form-control" name="value" value="">
          </div>
       
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Currency</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
    echo json_encode(["content"=>$return,"title"=>"Add Currency"]);
elseif( $action == "edit_currency" ):
    $id         = $_POST["id"];
    $provider   = $conn->prepare("SELECT * FROM currency WHERE id=:id ");
    $provider   ->execute(array("id"=>$id));
    $provider   = $provider->fetch(PDO::FETCH_ASSOC);
    $return = '<form class="form" action="'.site_url("admin/settings/currency/edit/".$id).'" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Currency Name</label>
            <input type="text" class="form-control" name="name" value="'.$provider["name"].'">
          </div>
          <div class="form-group">
            <label class="form-group__service-name">Currency Symbol</label>
            <input type="text" class="form-control" name="symbol" value="'.$provider["symbol"].'">
          </div>
<div class="form-group">
            <label class="form-group__service-name">Exchange Rates</label>
            <input type="text" class="form-control" name="currencyvalue" value="'.$provider["value"].'">
          </div> 


<div class="service-mode__block">
                <div class="form-group">
                <label>Currency Status</label>
                  <select class="form-control" name="status">
                      <option value="1"'; if( $provider["status"] == 1 ): $return.='selected'; endif; $return.='>Enabled</option>
                      <option value="2"'; if( $provider["status"] == 2 ): $return.='selected'; endif; $return.='>Disabled</option>
                  </select>
                </div>
              </div>


          </div>
          
          

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

         </form>';  
    echo json_encode(["content"=>$return,"title"=>"Edit currency (".$provider["name"].") "]);
   













elseif( $action == "new_news" ):
    $return = "<form class=\"form\" action=\"" . site_url("admin/appearance/news/new") . "\" method=\"post\" data-xhr=\"true\">\r\n\r\n <div class=\"modal-body\">\r\n \r\n <div class=\"form-group\">\r\n <label class=\"control-label\" for=\"createorderform-currency\">Announcement Icon</label>\r\n <select class=\"form-control\" name=\"icon\">\r\n <option value=\"General Announcement\">General Announcement</option>\r\n <option value=\"Star\">Star</option>\r\n <option value=\"instagram\">Instagram</option>\r\n <option value=\"facebook\">Facebook</option>\r\n <option value=\"youtube\">Youtube</option>\r\n <option value=\"twitter\">Twitter</option>\r\n <option value=\"tiktok\">TikTok</option> \r\n <option value=\"spotify\">Spotify</option>\r\n <option value=\"pinterest\">Pinterest</option>\r\n <option value=\"telegram\">Telegram</option>\r\n <option value=\"twitch\">Twitch</option>\r\n\r\n </select>\r\n </div>\r\n \r\n <div class=\"form-group\">\r\n <label class=\"form-group__service-name\">Announcement Title</label>\r\n <input type=\"text\" class=\"form-control\" name=\"title\"></textarea>\r\n </div>\r\n \r\n <div class=\"form-group\">\r\n <label class=\"form-group__service-name\">Announcement Content</label>\r\n <textarea class=\"form-control\" name=\"content\"></textarea>\r\n </div>\r\n</div>\r\n\r\n <div class=\"modal-footer\">\r\n <button type=\"submit\" class=\"btn btn-primary\">Add announcement</button>\r\n  <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cancel</button>\r\n </div>\r\n </form>";
 echo json_encode(["content" => $return, "title" => "Add new announcement"]);




  
  elseif( $action == "edit_news" ):
    $id = $_POST["id"];
 $news = $conn->prepare("SELECT * FROM news WHERE id=:id ");
 $news->execute(["id" => $id]);
 $news = $news->fetch(PDO::FETCH_ASSOC);
 $return = "<form class=\"form\" action=\"" . site_url("admin/appearance/news/edit/" . $id) . "\" method=\"post\" data-xhr=\"true\">\r\n\r\n <div class=\"modal-body\">\r\n\r\n <div class=\"form-group\">\r\n <label class=\"control-label\" for=\"createorderform-currency\">Announcement Icon</label>\r\n <select class=\"form-control\" name=\"icon\">\r\n <option value=\"" . $news["news_icon"] . "\">selected: " . $news["news_icon"] . "</option>\r\n <option value=\"General Announcement\">General Announcement</option>\r\n <option value=\"Star\">Star</option>\r\n <option value=\"instagram\">Instagram</option>\r\n <option value=\"facebook\">Facebook</option>\r\n <option value=\"youtube\">Youtube</option>\r\n <option value=\"twitter\">Twitter</option>\r\n <option value=\"tiktok\">TikTok</option> \r\n <option value=\"spotify\">Spotify</option>\r\n <option value=\"pinterest\">Pinterest</option>\r\n <option value=\"telegram\">Telegram</option>\r\n <option value=\"twitch\">Twitch</option>\r\n </select>\r\n </div>\r\n \r\n <div class=\"form-group\">\r\n <label class=\"form-group__service-name\">Announcement Title</label>\r\n <input type=\"text\" class=\"form-control\" name=\"title\" value=\"" . $news["news_title"] . "\"></textarea>\r\n </div>\r\n \r\n <div class=\"form-group\">\r\n <label class=\"form-group__service-name\">Announcement Content</label>\r\n <textarea class=\"form-control\" name=\"content\" rows=\"7\" >" . $news["news_content"] . "</textarea>\r\n </div>\r\n\r\n\r\n \r\n </div>\r\n\r\n <div class=\"modal-footer\">\r\n <button type=\"submit\" class=\"btn btn-primary\">Update</button>\r\n <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cancel</button>\r\n \r\n <a id=\"delete-row\" data-url=\"" . site_url("admin/appearance/news/delete/" . $news["id"]) . "\" class=\"btn btn-danger pull-right\">Delete Announcement</a>\r\n \r\n </div>\r\n </form>\r\n \r\n <script src=\"https://unpkg.com/sweetalert/dist/sweetalert.min.js\"></script>\r\n <script>\r\n \$(\"#delete-row\").click(function(){\r\n var action = \$(this).attr(\"data-url\");\r\n swal({\r\n title: \"Are you sure you want to delete?\",\r\n text: \"If you confirm, this content will be deleted, it may not be possible to restore it.\",\r\n icon: \"warning\",\r\n buttons: true,\r\n dangerMode: true,\r\n buttons: [\"Cancel\", \"Yes, I am sure!\"],\r\n })\r\n .then((willDelete) => {\r\n if (willDelete) {\r\n \$.ajax({\r\n url: action,\r\n type: \"GET\",\r\n dataType: \"json\",\r\n cache: false,\r\n contentType: false,\r\n processData: false\r\n })\r\n .done(function(result){\r\n if( result.s == \"error\" ){\r\n var heading = \"Unsuccessful\";\r\n }else{\r\n var heading = \"Successful\";\r\n }\r\n \$.toast({\r\n heading: heading,\r\n text: result.m,\r\n icon: result.s,\r\n loader: true,\r\n loaderBg: \"#9EC600\"\r\n });\r\n if (result.r!=null) {\r\n if( result.time ==null ){ result.time = 3; }\r\n setTimeout(function(){\r\n window.location.href = result.r;\r\n },result.time*1000);\r\n }\r\n })\r\n .fail(function(){\r\n \$.toast({\r\n heading: \"Unsuccessful\",\r\n text: \"İstek gerçekleştirilemedi\",\r\n icon: \"error\",\r\n loader: true,\r\n loaderBg: \"#9EC600\"\r\n });\r\n });\r\n /* Content deletion confirmed */\r\n } else {\r\n \$.toast({\r\n heading: \"Unsuccessful\",\r\n text: \"Deletion request denied\",\r\n icon: \"error\",\r\n loader: true,\r\n loaderBg: \"#9EC600\"\r\n });\r\n }\r\n });\r\n });\r\n </script>";
 echo json_encode(["content" => $return, "title" => "Edit Announcement (" . $provider["api_name"] . ") "]);











elseif ($action == "edit_code"):
                                                                                                                                                                                                                                                $id = $_POST["id"];
                                                                                                                                                                                                                                                $int = $conn->prepare("SELECT * FROM integrations WHERE id=:id");
                                                                                                                                                                                                                                                $int->execute(["id" => $id]);
                                                                                                                                                                                                                                                $int = $int->fetch(PDO::FETCH_ASSOC);
                                                                                                                                                                                                                                                $return = "<form class=\"form\" action=\"" . site_url("admin/settings/integrations/edit/" . $id) . "\" method=\"post\" data-xhr=\"true\">\r\n\r\n       <div class=\"modal-body\">\r\n                            <div id=\"editIntegrationError\" class=\"error-summary alert alert-danger hidden\"></div>                <div class=\"form edit-integration-modal-body\">\r\n                                <div class=\"form-group field-editintegrationform-code\">\r\n            <label class=\"control-label\" for=\"editintegrationform-code\">code area</label>\r\n            <textarea id=\"editintegrationform-code\" class=\"form-control\" name=\"code\" rows=\"7\" placeholder=\"\">" . $int["code"] . "</textarea>\r\n            </div>                    <div class=\"form-group field-editintegrationform-visibility\">\r\n            <label class=\"control-label\" for=\"editintegrationform-visibility\">Visibility</label>\r\n            <select class=\"form-control\" name=\"visibility\">\r\n            <option value=\"1\" ";
                                                                                                                                                                                                                                                if ($int["visibility"] == 1) {
                                                                                                                                                                                                                                                    $return .= "selected";
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                $return .= ">all pages</option>\r\n            <option value=\"2\" ";
                                                                                                                                                                                                                                                if ($int["visibility"] == 2) {
                                                                                                                                                                                                                                                    $return .= "selected";
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                $return .= ">Not logged in</option>\r\n            <option value=\"3\" ";
                                                                                                                                                                                                                                                if ($int["visibility"] == 3) {
                                                                                                                                                                                                                                                    $return .= "selected";
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                $return .= ">Signed in</option>\r\n            </select>\r\n            </div>                </div>\r\n                        </div>\r\n                        <div class=\"modal-footer\">\r\n                            <button type=\"submit\" class=\"btn btn-primary\">\r\n                                Update                </button>\r\n                            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">\r\n                                Close                </button>\r\n                            <a href=\"/admin/settings/integrations/disabled/" . $id . "\" class=\"btn btn-link pull-right deactivate-integration-btn\">\r\n                                deactivate\r\n                            </a>\r\n                        </div>\r\n                        </form>    ";
                                                                                                                                                                                                                                                echo json_encode(["content" => $return, "title" => "Edit integration (ID: " . $id . ")"]);
                                                                                                                                                                                                                                            elseif ($action == "edit_google"):
                                                                                                                                                                                                                                                    $return = "<form class=\"form\" action=\"" . site_url("admin/settings/integrations/google") . "\" method=\"post\" data-xhr=\"true\">\r\n            \r\n                    <div class=\"modal-body\">\r\n\r\n                 \r\n                 <div class=\"form-group\">\r\n                          <label class=\"control-label\">Site Key</label>\r\n                          <input type=\"text\" class=\"form-control\" name=\"pwd\" value=\"" . $settings["recaptcha_key"] . "\">\r\n                        </div>\r\n            \r\n                        <div class=\"form-group\">\r\n                        <label class=\"control-label\">Secret Key</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"secret\" value=\"" . $settings["recaptcha_secret"] . "\">\r\n                      </div>\r\n                    </div>\r\n            \r\n                      <div class=\"modal-footer\">            \r\n                      <button type=\"submit\" class=\"btn btn-primary\">Update Settings</button>\r\n                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n                      </div>\r\n                  </form>  ";
                                                                                                                                                                                                                                                    echo json_encode(["content" => $return, "title" => "Google reCAPTCHA v2"]);
                                                                                                                                                                                                                                                 elseif ($action == "edit_seo"):
                                                                                                                                                                                                                                                        $return = "<form class=\"form\" action=\"" . site_url("admin/settings/integrations/seo") . "\" method=\"post\" data-xhr=\"true\">\r\n            \r\n                    <div class=\"modal-body\">\r\n        <div class=\"form-group\">\r\n          <label for=\"\" class=\"control-label\">Title</label>\r\n          <input type=\"text\" class=\"form-control\" name=\"title\" value=\"" . $settings["site_title"] . "\">\r\n        </div>\r\n        <div class=\"form-group\">\r\n          <label for=\"\" class=\"control-label\">Keywords</label>\r\n          <input type=\"text\" class=\"form-control\" name=\"keywords\" value=\"" . $settings["site_keywords"] . "\">\r\n        </div>\r\n        <div class=\"form-group\">\r\n          <label class=\"control-label\">Description</label>\r\n          <textarea class=\"form-control\" rows=\"3\" name=\"description\">" . $settings["site_description"] . "</textarea>\r\n        </div>\r\n                      \r\n                      \r\n                    </div>\r\n            \r\n                      <div class=\"modal-footer\">            \r\n                      <button type=\"submit\" class=\"btn btn-primary\">Update Settings</button>\r\n                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n                      </div>\r\n                  </form>  ";
                                                                                                                                                                                                                                                        echo json_encode(["content" => $return, "title" => "SEO Adjustments"]);
                                                                                                                                                                                                                                                    elseif ($action == "edit_ticket"):
                                                                                                                                                                                                                                                            $id = $_POST["id"];
                                                                                                                                                                                                                                                            $tickets = $conn->prepare("SELECT * FROM ticket_reply WHERE id=:id");
                                                                                                                                                                                                                                                            $tickets->execute(["id" => $id]);
                                                                                                                                                                                                                                                            $tickets = $tickets->fetch(PDO::FETCH_ASSOC);
                                                                                                                                                                                                                                                            $return = "<form class=\"form\" action=\"" . site_url("admin/tickets/edit/" . $id) . "\" method=\"post\" data-xhr=\"true\">\r\n            \r\n                    <div class=\"modal-body\">\r\n        <div class=\"form-group\">\r\n          <label class=\"control-label\">Message Content</label>\r\n          <textarea class=\"form-control\" rows=\"5\" name=\"description\">" . $tickets["message"] . "</textarea>\r\n        </div>\r\n                    </div>\r\n                      <div class=\"modal-footer\">            \r\n                      <button type=\"submit\" class=\"btn btn-primary\">Update</button>\r\n                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n                      </div>\r\n                  </form>  ";
                                                                                                                                                                                                                                                            echo json_encode(["content" => $return, "title" => "Edit support message"]);
                                                                          















  
  endif;

