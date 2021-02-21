<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="wadsystem">
    <meta name="author" content="wadsystem">

    <title>Wadsystem</title>

    <!-- vendor css -->
    <link href="<?php  echo base_url();?>themes/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?php  echo base_url();?>themes/lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="<?php  echo base_url();?>themes/css/bracket.css">
  </head>

  <body>

    <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">
	  <form method="post" action="">
      <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">
        <div class="signin-logo tx-center tx-28 tx-bold tx-inverse"><span class="tx-normal">[</span> Wad <span class="tx-info">System</span> <span class="tx-normal">]</span></div>
        <div class="tx-center mg-b-60">Administrator Dashboard</div>
		<?php  echo $response;?>
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Enter your username" name="ppobuser">
        </div><!-- form-group -->
        <div class="form-group">
          <input type="password" class="form-control" placeholder="Enter your password" name="ppobpass">
        </div><!-- form-group -->
        <button type="submit" class="btn btn-info btn-block">Sign In</button>

      </div><!-- login-wrapper -->
	  </form>
    </div><!-- d-flex -->

    <script src="<?php  echo base_url();?>themes/lib/jquery/jquery.min.js"></script>
    <script src="<?php  echo base_url();?>themes/lib/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="<?php  echo base_url();?>themes/lib/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
