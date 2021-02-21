<!doctype html>
<html lang="en" >

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Koperasi Digital</title>
    <link rel="apple-touch-icon" sizes="120x120" href="<?php  echo base_url();?>img/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php  echo base_url();?>img/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php  echo base_url();?>img/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php  echo base_url();?>img/favicon.png">
    <!--BASIC css-->
    <!-- ========================================================= -->
    <link rel="stylesheet" href="<?php  echo base_url();?>theme/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php  echo base_url();?>theme/vendor/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="<?php  echo base_url();?>theme/vendor/animate.css/animate.css">
    <!--SECTION css-->
    <!-- ========================================================= -->
    <!--TEMPLATE css-->
    <!-- ========================================================= -->
    <link rel="stylesheet" href="<?php  echo base_url();?>theme/stylesheets/css/style.css"> 

	<style>
	
@import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

* {
	box-sizing: border-box;
}

body {
	background: #f6f5f7;
	display: flex;
	justify-content: center;
	align-items: center;
	flex-direction: column;
	font-family: 'Montserrat', sans-serif;
	height: 100vh;
	margin: -20px 0 50px;
}

h1 {
	font-weight: bold;
	margin: 0;
}

h2 {
	text-align: center;
}

p {
	font-size: 14px;
	font-weight: 100;
	line-height: 20px;
	letter-spacing: 0.5px;
	margin: 20px 0 30px;
}

span {
	font-size: 12px;
}

a {
	color: #333;
	font-size: 14px;
	text-decoration: none;
	margin: 15px 0;
}

button {
	border-radius: 20px;
	/* border: 1px solid #FF4B2B;
	background-color: #FF4B2B; */
	border: 1px solid #138254;
    background-color: #138254;
	color: #FFFFFF;
	font-size: 12px;
	font-weight: bold;
	padding: 12px 45px;
	letter-spacing: 1px;
	text-transform: uppercase;
	transition: transform 80ms ease-in;
}

button:active {
	transform: scale(0.95);
}

button:focus {
	outline: none;
}

button.ghost {
	background-color: transparent;
	border-color: #FFFFFF;
}

form {
	background-color: #FFFFFF;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 50px;
	height: 100%;
	text-align: center;
}

input {
	background-color: #eee;
	border: none;
	padding: 12px 15px;
	margin: 8px 0;
	width: 100%;
}

.container {
	margin-top: 110px;
	background-color: #fff;
	border-radius: 10px;
	box-shadow: 0 14px 28px rgba(0,0,0,0.25), 
	0 10px 10px rgba(0,0,0,0.22);
	position: relative;
	overflow: hidden;
	width: 768px;
	max-width: 100%;
	min-height: 480px;
}

.form-container {
	position: absolute;
	top: 0;
	height: 100%;
	transition: all 0.6s ease-in-out;
}

.sign-in-container {
	left: 0;
	width: 50%;
	z-index: 2;
}

.container.right-panel-active .sign-in-container {
	transform: translateX(100%);
}

.sign-up-container {
	left: 0;
	width: 50%;
	opacity: 0;
	z-index: 1;
}

.container.right-panel-active .sign-up-container {
	transform: translateX(100%);
	opacity: 1;
	z-index: 5;
	animation: show 0.6s;
}

@keyframes show {
	0%, 49.99% {
		opacity: 0;
		z-index: 1;
	}
	
	50%, 100% {
		opacity: 1;
		z-index: 5;
	}
}

.overlay-container {
	position: absolute;
	top: 0;
	left: 50%;
	width: 50%;
	height: 100%;
	overflow: hidden;
	transition: transform 0.6s ease-in-out;
	z-index: 100;
}

.container.right-panel-active .overlay-container{
	transform: translateX(-100%);
}

.overlay {
	background: #FF416C;
	background: -webkit-linear-gradient(to right, #FF4B2B, #FF416C);
	background: linear-gradient(to right, #FF4B2B, #FF416C);
	background-repeat: no-repeat;
	background-size: cover;
	background-position: 0 0;
	color: #FFFFFF;
	position: relative;
	left: -100%;
	height: 100%;
	width: 200%;
  	transform: translateX(0);
	transition: transform 0.6s ease-in-out;
}

.container.right-panel-active .overlay {
  	transform: translateX(50%);
}

.overlay-panel {
	position: absolute;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 40px;
	text-align: center;
	top: 0;
	height: 100%;
	width: 50%;
	transform: translateX(0);
	transition: transform 0.6s ease-in-out;
}

.overlay-left {
	transform: translateX(-20%);
}

.container.right-panel-active .overlay-left {
	transform: translateX(0);
}

.overlay-right {
	right: 0;
	transform: translateX(0);
}

.container.right-panel-active .overlay-right {
	transform: translateX(20%);
}

.social-container {
	margin: 20px 0;
}

.social-container a {
	border: 1px solid #DDDDDD;
	border-radius: 50%;
	display: inline-flex;
	justify-content: center;
	align-items: center;
	margin: 0 5px;
	height: 40px;
	width: 40px;
}
 .input-with-icon i {
    display: inline-block;
    position: absolute;
    bottom: 0;
    left: 5px;
    z-index: 10;
    padding: 13px 5px;
    line-height: 34px;
    color: #999999;
}
input {
    background-color: #eee;
    border: none;
    padding: 12px 15px;
    margin: 8px 0;
    width: 100%;
}
	</style>
	<style>
	/* ---- reset ---- */

body {
	/* background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); */
	    background: linear-gradient(-45deg, #ee7752, #e73c7e, #022f3f, #033d2f);
	background-size: 400% 400%;
	animation: gradient 15s ease infinite;
}

canvas {
  display: block;
  vertical-align: bottom;
}

/* ---- particles.js container ---- */

#particles-js {
  position: absolute;
  width: 100%;
  height: 100%;
  /* background-color: #b61924; */
  background-image: url("");
  background-repeat: no-repeat;
  background-size: cover;
  background-position: 50% 50%;
}

/* ---- stats.js ---- */

.count-particles{
  background: #000022;
  position: absolute;
  top: 48px;
  left: 0;
  width: 80px;
  color: #13E8E9;
  font-size: .8em;
  text-align: left;
  text-indent: 4px;
  line-height: 14px;
  padding-bottom: 2px;
  font-family: Helvetica, Arial, sans-serif;
  font-weight: bold;
}

.js-count-particles{
  font-size: 1.1em;
}

#stats,
.count-particles{
  -webkit-user-select: none;
}

#stats{
  border-radius: 3px 3px 0 0;
  overflow: hidden;
}

.count-particles{
  border-radius: 0 0 3px 3px;
}
	</style>
</head>

<body>
<div id="particles-js"></div>
<div class="wrap">
    <!-- page BODY -->
    <!-- ========================================================= -->
    <div class="page-body">
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        <!--LOGO-->
        <!--div class="logo">
            <div class="avatar">
                <img alt="User photo" src="<?php  echo base_url();?>img/logokoprasi.png" />
            </div>
        </div--> 
		 <!--END LOGO-->
		<div class="container" id="container">
			 
			<div class="form-container sign-in-container">
				<!--form action="#">
					<h1>Sign in</h1>
					 
					<span>or use your account</span>
					<input type="email" placeholder="Email" />
					<input type="password" placeholder="Password" />
					<a href="#">Forgot your password?</a>
					<button>Sign In</button>
				</form-->
				<form action="" method="post">
					<h1>Sign in</h1>
					<span>use your account</span>
					<input type="text" id="username" placeholder="Username" name="username">
					<input type="password" id="password" placeholder="Password" name="password">
					<div class="form-group">
						<button class="">Sign in</button>
						<?php  if($response !=""){ ?>
							<hr>
							<div class="alert alert-danger fade in">
								<a href="#" class="close" data-dismiss="alert">×</a>
								<strong>Error</strong> Login Gagal,.. Periksa username/password anda
							</div>
						<?php  } ?>
					</div>
				</form>
			</div>
			<div class="overlay-container">
				<div class="overlay">
					 
					<div class="overlay-panel overlay-right">
						 <img src="https://pict-a.sindonews.net/dyn/620/pena/news/2020/10/06/34/186870/digitalisasi-kunci-koperasi-tidak-mati-ditelan-pandemi-quq.png" class="card-img login-img h-100" alt="..." style ="height: 480px;">
					</div>
				</div>
			</div>
		</div>
 
        <!--div class="box"> 
            <div class="panel mb-none">
                <div class="panel-content bg-scale-0">
                    <form action="" method="post">
                        <div class="form-group mt-md">
                            <h4 class="text-center"><span class="highlight">LOGIN FORM</span></h4>
                        </div>
                        <div class="form-group mt-md">
                            <span class="input-with-icon">
                                <input type="text" class="form-control" id="username" placeholder="Username" name="username">
                                <i class="fa fa-user color-darker-2"></i>
                            </span>
                        </div>
                        <div class="form-group">
                            <span class="input-with-icon">
                                <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                                <i class="fa fa-lock color-darker-2"></i>
                            </span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block">Log me in</button>
                            <?php  if($response !=""){ ?>
                                <hr>
                                <div class="alert alert-danger fade in">
                                    <a href="#" class="close" data-dismiss="alert">×</a>
                                    <strong>Error</strong> Login Gagal,.. Periksa username/password anda
                                </div>
                            <?php  } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div-->
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    </div>
</div>
<!--BASIC scripts-->
<!-- ========================================================= -->
<script src="<?php  echo base_url();?>theme/vendor/jquery/jquery-1.12.3.min.js"></script>
<script src="<?php  echo base_url();?>theme/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php  echo base_url();?>theme/vendor/nano-scroller/nano-scroller.js"></script>
<!--TEMPLATE scripts-->
<!-- ========================================================= -->
<script src="<?php  echo base_url();?>theme/javascripts/template-script.min.js"></script>
<script src="<?php  echo base_url();?>theme/javascripts/template-init.min.js"></script>
<script src="<?php  echo base_url();?>assets/js/particles.js"></script>

<script>
/* ---- particles.js config ---- */

particlesJS("particles-js", {
  "particles": {
    "number": {
      "value": 40,
      "density": {
        "enable": true,
        "value_area": 700
      }
    },
    "color": {
      "value": "#ffffff"
    },
    "shape": {
      "type": "circle",
      "stroke": {
        "width": 0,
        "color": "#000000"
      },
      "polygon": {
        "nb_sides": 5
      },
      "image": {
        "src": "img/github.svg",
        "width": 100,
        "height": 100
      }
    },
    "opacity": {
      "value": 0.5,
      "random": false,
      "anim": {
        "enable": false,
        "speed": 1,
        "opacity_min": 0.1,
        "sync": false
      }
    },
    "size": {
      "value": 3,
      "random": true,
      "anim": {
        "enable": false,
        "speed": 40,
        "size_min": 0.1,
        "sync": false
      }
    },
    "line_linked": {
      "enable": true,
      "distance": 150,
      "color": "#ffffff",
      "opacity": 0.4,
      "width": 1
    },
    "move": {
      "enable": true,
      "speed": 6,
      "direction": "none",
      "random": false,
      "straight": false,
      "out_mode": "out",
      "bounce": false,
      "attract": {
        "enable": false,
        "rotateX": 600,
        "rotateY": 1200
      }
    }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": {
        "enable": true,
        "mode": "grab"
      },
      "onclick": {
        "enable": true,
        "mode": "push"
      },
      "resize": true
    },
    "modes": {
      "grab": {
        "distance": 140,
        "line_linked": {
          "opacity": 1
        }
      },
      "bubble": {
        "distance": 400,
        "size": 40,
        "duration": 2,
        "opacity": 8,
        "speed": 3
      },
      "repulse": {
        "distance": 200,
        "duration": 0.4
      },
      "push": {
        "particles_nb": 4
      },
      "remove": {
        "particles_nb": 2
      }
    }
  },
  "retina_detect": true
});


/* ---- stats.js config ---- */

var count_particles, stats, update;
stats = new Stats;
stats.setMode(0);
stats.domElement.style.position = 'absolute';
stats.domElement.style.left = '0px';
stats.domElement.style.top = '0px';
document.body.appendChild(stats.domElement);
count_particles = document.querySelector('.js-count-particles');
update = function() {
  stats.begin();
  stats.end();
  if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) {
    count_particles.innerText = window.pJSDom[0].pJS.particles.array.length;
  }
  requestAnimationFrame(update);
};
requestAnimationFrame(update);

</script>
<!-- SECTION script and examples-->
<!-- ========================================================= -->
</body>

</html>
