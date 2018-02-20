<?php
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=Config::get('site_name') ?></title>
    <link rel="stylesheet" href="../web/css/style.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>-->

</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/"><?=Config::get('site_name')?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?php if( App::getRouter()->getController() == 'pages' ) {?>class="active"<?php } ?>><a href="/pages/">Pages</a></li>
                <li><a <?php if( App::getRouter()->getController() == 'contacts' ) {?>class="active"<?php } ?> href="/contacts/">Contact Us</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">

    <div class="starter-template">

        <!--<?php if( Session::hasFlash() ){ ?>-->
        <!--<div class="alert alert-info" role="alert">-->
        <!--<?php Session::flash(); ?>-->
        <!--</div>-->
        <!--<?php } ?>-->

        <?=$data['content']?>
    </div>

</div>



<!--<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">-->
<!--<a class="navbar-brand" href="#">Navbar</a>-->
<!--&lt;!&ndash;<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">&ndash;&gt;-->
<!--&lt;!&ndash;<span class="navbar-toggler-icon"></span>&ndash;&gt;-->
<!--&lt;!&ndash;</button>&ndash;&gt;-->

<!--<div class="collapse navbar-collapse" id="navbarsExampleDefault">-->
<!--<ul class="navbar-nav mr-auto">-->
<!--<li class="nav-item active">-->
<!--<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>-->
<!--</li>-->
<!--<li class="nav-item">-->
<!--<a class="nav-link" href="#">Link</a>-->
<!--</li>-->
<!--<li class="nav-item">-->
<!--<a class="nav-link disabled" href="#">Disabled</a>-->
<!--</li>-->
<!--<li class="nav-item dropdown">-->
<!--<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>-->
<!--<div class="dropdown-menu" aria-labelledby="dropdown01">-->
<!--<a class="dropdown-item" href="#">Action</a>-->
<!--<a class="dropdown-item" href="#">Another action</a>-->
<!--<a class="dropdown-item" href="#">Something else here</a>-->
<!--</div>-->
<!--</li>-->
<!--</ul>-->
<!--<form class="form-inline my-2 my-lg-0">-->
<!--<input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">-->
<!--&lt;!&ndash;<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>&ndash;&gt;-->
<!--</form>-->
<!--</div>-->
<!--</nav>-->

<!--<main role="main" class="container">-->

<!--<div class="starter-template">-->
<!--<?=$data['content']?>\\\-->

<!--&lt;!&ndash;<h1>Bootstrap starter template</h1>&ndash;&gt;-->
<!--&lt;!&ndash;<p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>&ndash;&gt;-->
<!--</div>-->

<!--</main>-->
<!-- /.container -->

<!--<h3>HEADER WILL BE HERE</h3>-->


<!--<?=$data['content']?>-->


<!--<h3>FOOTER WILL BE HERE</h3>-->
</body>
</html>
