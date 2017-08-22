<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.css">
        <script src="./js/jquery-3.2.1.js"></script>
        <script src="./js/bootstrap.js"></script>
		<script src="./js/jquery.bxslider.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="./css/custom.css">
  		<link rel="icon" type="image/png" href="https://icons.better-idea.org/icon?url=twitter.com&size=80..120..200">
  		
    </head>
    <body>
    	<nav class="navbar navbar-dark bg-primary">
		    <div class="navbar-header">
		      <a class="navbar-brand"><img src="./images/twitterlogo.png" style="height: 48px;width:48px" /></a>
		    </div>
		    <ul class="nav navbar-nav" style="float: right">
		    	<li class="dropdown">
    				<a class="dropdown-toggle" data-toggle="dropdown" href="#" style="margin-top:15px">DOWNLOAD <i class="fa fa-download" aria-hidden="true"></i> </a>
				    <ul class="dropdown-menu">
	          			<li><a class="download" data-value='csv' target="_blank" href="./controller.php?download=true&type=csv">csv</a></li>
	          			<li><a class="download" data-value='xls' target="_blank" href="./controller.php?download=true&type=xls">xls</a></li>
	          			<li><a class="download" data-value='google-spreadhseet' href="./controller.php?download=true&type=google-spread-sheet">google-spreadhseet</a></li>
	          			<li><a class="download" data-value='json' target="_blank" href="./controller.php?download=true&type=json">json</a></li>
	        		</ul>
	        	</li>
		      	<li class="active dropdown">
		      		<a class="dropdown-toggle" data-toggle="dropdown"> <b id="name_user"></b> <img id="user_pic" src="" style="border-radius: 50%" /> </a>
		      		<ul class="dropdown-menu">
		      			<li><a id='followers-names' class='followers-name' data-value=''>My Tweets</a></li>
	          			<li><a href="controller.php?logout=true"> <i class="fa fa-sign-out" aria-hidden="true"></i> SignOut </a></li>		
	        		</ul>
		      	</li>
		    </ul>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-3 left">
					<h1>My Followers</h1>
					<br />
					<div class="col-md-12">
						<form>
							<input type="text" class="form-control" placeholder="Search Here..." id="searchbox" name="followers_search" autocomplete="off" />
						</form>
					</div>
					<div class="col-md-12" id="search"></div>
					<div class="col-md-12">
						<div id="hr_line"></div>
						<br />
					</div>
					<div id="followers"></div>
				</div>

				<div class="col-md-9">
					<center>
						<h1><img id="user_pic_mid" style="border-radius: 50%" /> &nbsp;&nbsp;&nbsp;<p id="name_user_mid"></p></h1>
						<div id="myCarousel" class="carousel slide" data-ride="carousel">
							<!-- Wrapper for slides -->
							<div class="carousel-inner">
								<div class="item active" style="height:100px"> Loading </div>
							</div>
							<!-- Left and right controls -->
							<a class="left carousel-control" href="#myCarousel" data-slide="prev">
								<span class="glyphicon glyphicon-chevron-left"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="right carousel-control" href="#myCarousel" data-slide="next">
								<span class="glyphicon glyphicon-chevron-right"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>
					</center>

					<br />
					<br />
					
					<div id="col-md-12">
						<center><h1> Search And Download public User's Tweets </h1></center>
					</div>

					<br />

					<div class="col-md-4"></div>
					<div class="col-md-4">
						<form method="post" action="./controller.php">
							<input type="text" name="key" class="form-control" placeholder="Search Public User" />
							<br />
							<input type="submit" name="search_public_user" class="btn btn-primary form-control"  />
						</form>
					</div>
					<div class="col-md-4"></div>

				</div> 
			</div>
		</div>
    </body>
    <script src="./js/script.js"></script>
</html>