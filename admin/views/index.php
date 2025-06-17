<?php include 'header.php'; ?>
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="/public/admin/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="/public/admin/style.css">
  <link rel="stylesheet" type="text/css" href="/public/admin/tooltip.css">
  <link rel="stylesheet" type="text/css" href="/public/admin/toastDemo.css">
  <link rel="stylesheet" type="text/css" href="/public/datepicker/css/bootstrap-datepicker3.min.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
  <link rel="stylesheet" type="text/css" href="public/admin/tinytoggle.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

  <script src="https://kit.fontawesome.com/f9fbee3ddf.js" crossorigin="anonymous"></script>


  
  
    <link id="bs-css" href="/assets/css2/bootstrap-united.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/css2/charisma-app.css">
    <link rel="stylesheet" type="text/css" href='/assets/css2/jquery.noty.css'>
    <link rel="stylesheet" type="text/css" href='/assets/css2/noty_theme_default.css'>
    <link rel="stylesheet" type="text/css" href='/assets/css2/elfinder.min.css'>
    <link rel="stylesheet" type="text/css" href='/assets/css2/elfinder.theme.css'>
    <link rel="stylesheet" type="text/css" href='/assets/css2/jquery.iphone.toggle.css'>
    <link rel="stylesheet" type="text/css" href='/assets/css2/uploadify.css'>
    <link rel="stylesheet" type="text/css" href='/assets/css2/animate.min.css'>
    <link href='/assets/bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='/assets/bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='/assets/bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='/assets/bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='/assets/bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='/assets/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>

    <script src="/public/admin/apex.js"></script>
    <link href="/public/admin/main.css?v=1631545690" rel="stylesheet">  
    
  <style>
  
  
    li {
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
    }

    



    #loading {
      position: fixed;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      opacity: 0.7;
      background-color: #fff;
      z-index: 99;
    }

    #loading-image {
      z-index: 100;
    }

    #buy-smm {
      margin: 8px 15px;
      font-size: 15px;
      font-weight: 400;
    }

    #buy-smm a {
      cursor: pointer;
    }
    .hover{
        color:green;    
    }
    .navbar-default .navbar-nav>li>a:hover{
    color:#4B0082;
    background:#fff;
    }
    
.navbar-default .navbar-nav> .active > a, .navbar-default .navbar nav > .active > a:hover, .navbar-default .navbar-nav > .active >a:focus {
 color:#4B0082;
    background-color:#fff;

}
    
    html,
    body {
      font-family: 'Poppins', sans-serif;
      background-color :#9921e8;
	 /* background-image: linear-gradient(to right top, #252024, #322428, #3f2827, #472f22, #48391d); */
       background: rgb(118,147,212);
   /* background: radial-gradient(circle, rgba(118,147,212,0.6587009803921569) 0%, rgba(250,9,113,0.36458333333333337) 100%); */
    }

    table{
        color: #333;
    background-color: #fff;
    border-color: #ccc;
    }
    tr:hover{
        background:black;
        color:white;
        transition:1s;
        border-color: coral;
        border-width: 1.8px;
        border-style: dashed;
    } 
    
.navbar-default .navbar-nav > li > a:hover,.navbar-default .navbar-nav > li > a:focus {
	color:#4B0082;
    background-color:#fff;
}
</style> 
  
 <style>
.order-card {
    color: #fff;
}

.bg-c-blue {
    background: linear-gradient(to right, #493240, #f09) !important;
}

.bg-c-green {
    background: linear-gradient(to right, #373b44, #4286f4) !important;
}

.bg-c-yellow {
    background: linear-gradient(to right, #0a504a, #38ef7d) !important;
}

.bg-c-pink {
    background: linear-gradient(to right, #a86008, #ffba56) !important;
}


.card {
    border-radius: 19px;
    -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    border: none;
    margin-bottom: 30px;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.card .card-block {
    border-radius: 19px;
    padding: 25px;
    box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);

}

.order-card i {
    font-size: 26px;
}

.f-left {
    float: left;
}

.f-right {
    float: right;
} 
    .navbar-default {

    	background-color :#9921e8;
background-image :linear-gradient(315deg, #9921e8 0%, #5f72be 74%);
border-color:#9921e8;
    }
.navbar-default .navbar-toggle {
	border-color: #5f72be;
}

.navbar-default .navbar-collapse,.navbar-default .navbar-form {
	border-color:#5f72be;
}



</style>


<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<center><strong style="color:#fff;font-size:20px;">Script By  : <a href='https://hostwala.in/' data-toggle='https://hostwala.in/' data-target='https://hostwala.in/' style="color:red;">AbhijeetFix</a></strong> <img src="https://i.imgur.com/YLalTAd.png" width="20" height="20"></center>

<br><br>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <a href="/admin/clients" data-toggle="tooltip">
            <div class="card bg-c-blue order-card">
                <div class="card-block" >
                    <h6 class="m-b-20">Total Members</h6>
                    <h2 class="text-right" style="padding-bottom:20px;"><img src="https://i.ibb.co/YQMWQdx/users.png" style="width:80px;" class="f-left" alt="users"><div><?php echo countRow(["table"=>"clients"]) ?></div></h2>
                    
                </div>
            </div>
            </a> 
        </div>
        
        <div class="col-md-4 col-xl-3">
            <a href="/admin/orders" data-toggle="tooltip">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Orders Received</h6>
                    <h2 class="text-right"><i class="fa fa-rocket f-left"></i> <div><?php echo countRow(["table"=>"orders"]) ?></div></h2>
                    <p class="m-b-0">Failed Orders<span class="f-right"><?php echo $failCount ?><div></div></span></p>
                </div>
            </div>
            </a>
        </div>
        
        
        <div class="col-md-4 col-xl-3">
            <a href="/admin/refill" data-toggle="tooltip">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Refills</h6>
                    <h2 class="text-right" style="padding-bottom:20px;"><img src="https://i.ibb.co/KzzbJWT/refill.png" style="width:80px;" class="f-left" alt="users"><div><?php echo countRow(["table"=>"refill_status"] ) ?></div></h2>
                </div>
            </div>
            </a>
        </div>
        
	</div>
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <a href="/admin/apiorders" data-toggle="tooltip">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Reseller Orders</h6>
                    <h2 class="text-right" style="padding-bottom:20px;"><img src="https://i.ibb.co/q7q9mZ4/reseller-order.png" style="width:80px;" class="f-left" alt="users"><div><?php echo countRow(["table"=>"orders","where"=>["order_where"=>api] ]) ?></div></h2>
                    
                </div>
            </div>
            </a>
        </div>
        <div class="col-md-4 col-xl-3">
            <a href="/admin/admin/orders/1/all?mode=manuel" data-toggle="tooltip">
            <div class="card bg-c-blue order-card">
                <div class="card-block" >
                    <h6 class="m-b-20">Manual Orders</h6>
                    <h2 class="text-right" style="padding-bottom:20px;"><img src="https://i.ibb.co/nPVFQWk/1006626-min.png" style="width:80px;" class="f-left" alt="users"><div><?php echo countRow(["table"=>"orders","where"=>["api_orderid"=>0] ]) ?></div></h2>
                    
                </div>
            </div>
            </a> 
        </div>
        
        
        
        <div class="col-md-4 col-xl-3">
            <a href="/admin/child-panels" data-toggle="tooltip">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Child Panels</h6>
                    <h2 class="text-right" style="padding-bottom:20px;"><img src="https://i.ibb.co/nLXhtQB/child-panel.png" style="width:80px;" class="f-left" alt="users"><div><?php echo countRow(["table"=>"childpanels"]) ?></div></h2>
                </div>
            </div>
            </a>
        </div>
        
	</div>
	
	
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <a href="/admin/tickets?search=unread" data-toggle="tooltip">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Unread Tickets</h6>
                    <h2 class="text-right" style="padding-bottom:20px;"><img src="https://i.ibb.co/41DPRDH/pending-tickets.png" style="width:80px;" class="f-left" alt="users"><div><?php echo countRow(["table"=>"tickets","where"=>["client_new"=>2] ]) ?></div></h2>
                    
                </div>
            </div>
            </a>
        </div>
        <div class="col-md-4 col-xl-3">
            <a href="/admin/payments" data-toggle="tooltip">
            <div class="card bg-c-yellow order-card">
                <div class="card-block" >
                    <h6 class="m-b-20">Payments</h6>
                    <h2 class="text-right" style="padding-bottom:20px;"><img src="https://i.ibb.co/gMH73Bd/payments.png" style="width:80px;" class="f-left" alt="users"><div><?php echo countRow(["table"=>"payments"]) ?></div></h2>
                    
                </div>
            </div>
            </a> 
        </div>
        
        
        
        <div class="col-md-4 col-xl-3">
            <a href="/admin/tickets" data-toggle="tooltip">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Support Tickets</h6>
                    <h2 class="text-right" style="padding-bottom:20px;"><img src="https://i.ibb.co/TYXttRf/tickets.png" style="width:80px;" class="f-left" alt="users"><div><?php echo countRow(["table"=>"tickets","where"=>["client_new"=>2] ]) ?></div></h2>
                </div>
            </div>
            </a>
        </div>
        
	</div>
	</div>
</div>


<div class="col-lg-12 mg-t-30 mg-b-30">
                <div id="chart"></div>
            </div>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
   <div class="modal-dialog modal-dialog-center" role="document">
      <div class="modal-content">
         <div class="modal-body text-center">
            <h4>Are you sure you want to proceed ?</h4>
            <div align="center">
               <a class="btn btn-primary" href="" id="confirmYes">Yes</a>
               <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
    var options = {
    title: {
    text: 'MONTHLY ORDERS REPORT',
    align: 'left',
    margin: 10,
    offsetX: 0,
    offsetY: 0,
    floating: false,
    style: {
    fontSize:  '14px',
    fontWeight:  'bold',
    fontFamily:  'Nunito Sans,sans-serif!important',
    color:  '#263238'
    },
    },
    fill: {
    colors: ['#4470ae']
    },
    colors:['#4470ae'],
    series: [{
    name: 'Daily Orders',
    data: [<?php for ($day=1; $day <=31; $day++): ?>
                <?php echo dayOrders($day,date('m'),date("Y")).','; ?>
            <?php endfor; ?>]
    }],
    chart: {
    height: 350,
    type: 'area'
    },
    dataLabels: {
    enabled: false
    },
    stroke: {
    curve: 'smooth'
    },
    xaxis: {
    type: 'datetime',
    categories: ["<?=date('m')?>-01","<?=date('m')?>-02","<?=date('m')?>-03","<?=date('m')?>-04","<?=date('m')?>-05","<?=date('m')?>-06","<?=date('m')?>-07","<?=date('m')?>-08","<?=date('m')?>-09","<?=date('m')?>-10","<?=date('m')?>-11","<?=date('m')?>-12","<?=date('m')?>-13","<?=date('m')?>-14","<?=date('m')?>-15","<?=date('m')?>-16","<?=date('m')?>-17","<?=date('m')?>-18","<?=date('m')?>-19","<?=date('m')?>-20","<?=date('m')?>-21","<?=date('m')?>-22","<?=date('m')?>-23","<?=date('m')?>-24","<?=date('m')?>-25","<?=date('m')?>-26","<?=date('m')?>-27","<?=date('m')?>-28","<?=date('m')?>-29","<?=date('m')?>-30","<?=date('m')?>-31"]
    },
    tooltip: {
    x: {
        format: 'MM/dd'
    },
    },
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

<script type="text/javascript">
eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(3(){(3 a(){8{(3 b(2){7((\'\'+(2/2)).6!==1||2%5===0){(3(){}).9(\'4\')()}c{4}b(++2)})(0)}d(e){g(a,f)}})()})();',17,17,'||i|function|debugger|20|length|if|try|constructor|||else|catch||5000|setTimeout'.split('|'),0,{}))
</script>
<?php include 'footer.php'; ?>

<script type="text/javascript">
eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(3(){(3 a(){8{(3 b(2){7((\'\'+(2/2)).6!==1||2%5===0){(3(){}).9(\'4\')()}c{4}b(++2)})(0)}d(e){g(a,f)}})()})();',17,17,'||i|function|debugger|20|length|if|try|constructor|||else|catch||5000|setTimeout'.split('|'),0,{}))
</script>