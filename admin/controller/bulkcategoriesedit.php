<?php


  if( $admin["access"]["services"] != 1  ):
    header("Location:".site_url("admin"));
    exit();
  endif;

  if( $_SESSION["client"]["data"] ):
    $data = $_SESSION["client"]["data"];
    foreach ($data as $key => $value) {
      $$key = $value;
    }
    unset($_SESSION["client"]);
  endif;


    $categories       = $conn->prepare("SELECT * FROM category ") ;
    $categories       -> execute(array());
    $categories       = $categories->fetchAll(PDO::FETCH_ASSOC);
    
    require admin_view('bulkcategoriesedit');


  if( $_POST) :

        
    $categories = $_POST["service"];

        foreach ($categories as $id => $value):


            $update = $conn->prepare("UPDATE services SET category_name=:name   ,  WHERE category_id=:id ");
            $update->execute(array("name" => $_POST["name-$id"], "id" => $id ));

echo  $_POST["name-$id"] ;
if( $update ):
                header("Location:" . site_url("admin/bulkcategoriesedit"));
                    $_SESSION["client"]["data"]["success"] = 1;
                    $_SESSION["client"]["data"]["successText"] = "Successful";
              else:
                $errorText  = "Failed";
                $error      = 1;
	header("Location:" . site_url("admin/bulkcategoriesedit"));	

	            endif;


endforeach;
        
endif;






$name = "$name-$id";