<?php
	session_start();
	set_include_path("../src/" . PATH_SEPARATOR . get_include_path());
	require_once '/var/www/stirplate/vendor/google-api-php-client/autoload.php';
    require_once 'functions.php';
	$token = googleConnect();
	$instances = getAllInstances($token);

?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Stirplate: Instance Tool</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ladda-bootstrap/0.9.4/ladda-themeless.min.css" />
<link href="css/theme.css" rel="stylesheet" /> 


<style type="text/css" title="currentStyle">
	@import "css/demo_page.css";
	@import "css/demo_table.css";
</style>
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ladda-bootstrap/0.9.4/spin.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ladda-bootstrap/0.9.4/ladda.min.js"></script>

<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#example').dataTable( {
         "sDom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>'
        });
        
        $('.link').tooltip();
        $('#start-instances').one("click", function(){
  		    $.ajax({
			 	async: false,
	   			type: 'GET',
	    		url: 'launch-all.php',
	    		success: function(responseData) {
	    			alert("All Instances should be up and running shortly...");
	    			location.reload();
	    		},
	    		error: function(XMLHttpRequest, textStatus, errorThrown) {
		      		  // TODO: log errorThrown to php log or other logger
			   	}
		    }); 
     	 });     
     	 
     	 $('#stop-instances').one("click", function(){
  		    $.ajax({
			 	async: false,
	   			type: 'GET',
	    		url: 'stop-all.php',
	    		success: function(responseData) {
	    			alert("Shutting down all instances refresh page for status...");
	    			location.reload();
	    		},
	    		error: function(XMLHttpRequest, textStatus, errorThrown) {
		      		  // TODO: log errorThrown to php log or other logger
			   	}
		    }); 
     	 });   
     $(function(){
	    $('a, button').click(function() {
	        $(this).toggleClass('active');
	    });
	});
	
	$body = $("body");
	
	$(document).on({
	    ajaxStart: function() { $body.addClass("loading");    },
	    ajaxStop: function() { $body.removeClass("loading"); }    
	});    	  	
	});
	
	
	
</script>

  
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="ex_highlight_row">
 <!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Stirplate.io</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="">Manage Instances</a></li>
            <li><a href="">Admin Console</a></li>
            <!-- <li><a href="#contact">Nav 3</a></li> -->
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
<div class="container">
<div class="page-header">
    <h1>Stirplate.io - Instance Manager Tool</h1>
</div>
<div class="well">
<p>The Instance Manager Tool provides Google App Engine Administration using the Google Compute Engine API.  </p>
<p><button id="start-instances" class="btn btn-lg btn-success ladda-button" href="#" role="button" 
	data-style="expand-right" data-color="green" data-size="medium" data-spinner-size="40" data-spinner-color="#ff0000">
	<span class="ladda-label">Launch All Instances &raquo;</span> 
</button>
		&nbsp;&nbsp;
	<a id="stop-instances" class="btn btn-lg btn-danger" href="" role="button">Shut down All Instances</a>	
</p>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Instance ID</th> 
			<th>Instance Name</th>
			<th>Creation Time Stamp</th>
			<th>Status</th>
            <th>Machine Type</th>
            <th>Actions</th>
		</tr>
	</thead>
	<tbody>	
		<?php foreach($instances as $instance) { ?>	
    	<tr class="gradeC">
            <td class="center"><?php echo $instance->id; ?></td>
            <td class="center"><?php echo $instance->name; ?></td>                    
            <td class="center"><?php echo $instance->creationTimestamp; ?></td>
            <td class="center"><?php echo $instance->status; ?></td>
            <td class="center"><?php echo end(explode("/", $instance->machineType)); ?></td>
            <td class="center">
            <a href="" class="link" data-toggle="tooltip" title="Start Instance">
            	<span class="glyphicon glyphicon-flash"></a>
            <a href="" class="link" data-toggle="tooltip" title="Stop Instance">
            	<span class="glyphicon glyphicon-stop"></span></a>
            <a href="" class="link" data-toggle="tooltip" title="Delete Instance">
            	<span class="glyphicon glyphicon-trash"></span></a>
            </td>
        </tr>   
        <?php } ?>              
		</tbody>
	<tfoot>
		<tr>
			<th>Instance ID</th> 
			<th>Instance Name</th>
			<th>Creation Time Stamp</th>
			<th>Status</th>
            <th>Machine Type</th>
            <th>Actions</th>
		</tr>
	</tfoot>
</table>
		</div>
	</body>
</html>
