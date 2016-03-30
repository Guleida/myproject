<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panna Daily</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-theme.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/site.css"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url(); ?>assets/js/jquery-2.2.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="<?php echo site_url('home/timeline'); ?>">Panna Daily</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="<?php echo site_url('home/search'); ?>"><i class="glyphicon glyphicon-search"></i> Search</a>
                    </li>
                    <li>
                    	<a href="<?php echo site_url('home/leagues'); ?>"> Leagues</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('home/timeline'); ?>"><i class="glyphicon glyphicon-list"></i> Timeline</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('home/new_article'); ?>"><i class="glyphicon glyphicon-pencil"></i> New Article</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="glyphicon glyphicon-user"></i> <?php echo $user->username; ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('home/profile'); ?>"><i class="glyphicon glyphicon-user"></i> View Profile</a></li>
                            <li><a href="<?php echo site_url('home/edit_profile'); ?>"><i class="glyphicon glyphicon-edit"></i> Manage Account</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo site_url('account/logout'); ?>"><i class="glyphicon glyphicon-log-out"></i> Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.navbar-collapse -->
        </div>
    </nav>
    <br/>
    <div class="container">