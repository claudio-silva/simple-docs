<?php
require 'markdown.php'
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>My Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php
    $path = dirname ($_SERVER['SCRIPT_NAME']);
    echo $path == '/' ? '' : $path;
    ?>/">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,400italic'
          rel='stylesheet'
          type='text/css'>

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
          crossorigin="anonymous">
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/syntax.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  </head>

  <body>
    <?php $path =
      isset($_SERVER['REDIRECT_PATH'])
        ? $_SERVER['REDIRECT_PATH']
        :
        (isset($_SERVER['SCRIPT_URL'])
          ? $_SERVER['SCRIPT_URL']
          : // PHP FCGI
          (isset($_SERVER['PATH_INFO'])
            ? $_SERVER['PATH_INFO']
            : // MOD_PHP
            '/index'));
    if ($path == '/')
      $path = '/index';
    $split   = explode ('/', $path);
    $menuDir = $split[1];
    ?>

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header col-md-2">
          <button type="button"
                  class="navbar-toggle collapsed"
                  data-toggle="collapse"
                  data-target="#navbar-collapsing"
                  aria-expanded="false">
            <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="."><i class="fa fa-pencil"></i><span>doc-kit</span></a>
        </div>

        <div class="collapse navbar-collapse col-md-10" id="navbar-collapsing">
          <ul class="nav navbar-nav">
            <li<?= $menuDir == 'index' ? ' class="active"' : '' ?>>
              <a href=".">Home</a>
            </li>
            <li<?= $menuDir == 'docs' ? ' class="active"' : '' ?>>
              <a href="docs">Documentation</a>
            </li>
            <li<?= $menuDir == 'download' ? ' class="active"' : '' ?>>
              <a href="download">Downloads</a>
            </li>
            <li<?= $menuDir == 'community' ? ' class="active"' : '' ?>>
              <a href="community">Community</a>
            </li>
            <li<?= $menuDir == 'support' ? ' class="active"' : '' ?>>
              <a href="support">Support</a>
            </li>
            <li<?= $menuDir == 'about' ? ' class="active"' : '' ?>>
              <a href="about">About</a>
            </li>
          </ul>
        </div>

      </div>
    </nav>

    <div class="main container">
      <?php
      $context = [
        'my' => substr ($path, 1),
      ];

      function compileMD ($src)
      {
        $text = Markdown ($src);
        $text = preg_replace_callback ('/\{\$(\w+)\}/', function ($m) {
          global $context;
          return $context[$m[1]];
        }, $text);
        return $text;
      }

      function navMenu ($src)
      {
        return preg_replace ([
          '%<ul>%',
        ], [
          '<ul class="nav nav-list">',
        ], $src);
      }

      if ($menuDir == 'docs') {
        $subPath = substr ($path, 6);
        if ($subPath == '')
          $subPath = 'index';
        $file    = __DIR__ . "/src/docs/$subPath.md";
        $content = file_exists ($file) ? navMenu (compileMD (file_get_contents ($file))) : "<code>$path</code> was not found.";
        ?>
        <div class="row">
          <div class="col-md-2">
            <div class="sidebar-nav">
              <?= navMenu (compileMD (file_get_contents ('src/docs/menu.md', FILE_USE_INCLUDE_PATH))) ?>
            </div>
            <!--/.well -->
          </div>
          <!--/span-->
          <div class="page col-md-10">
            <div class="container-fluid">
              <div class="content col-md-offset0 col-lg-offset-1 col-md-12 col-lg-10">
                <?= $content ?>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      else {
        $file = __DIR__ . "/src$path.md";
        echo file_exists ($file) ? compileMD (file_get_contents ($file)) : "<code>$path</code> was not found.";
      }
      ?>
      <div class="footer pull-right">
        &copy; 2016 <a href="#">Someone</a>
      </div>
    </div>
    <!-- /container -->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
    <script src="assets/js/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad ();</script>
  </body>
</html>
