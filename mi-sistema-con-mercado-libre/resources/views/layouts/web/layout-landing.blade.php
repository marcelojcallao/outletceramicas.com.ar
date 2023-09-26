<!doctype html>
<html lang="" class="page-shop">
<head>
    @include('layouts.web.partials.head')
</head>
<body>

<div id="page" class="hfeed site">
    <!-- @include('layouts.web.partials.mobile-menu') -->

    <header class="site-header style-1">
        <div class="container">
        @include('layouts.web.partials.header')
        </div><!-- container -->
    </header>

    <div id="search">
        <button type="button" class="close">×</button>
        <form>
            <input type="search" value="" placeholder="type keyword(s) here" />
            <button type="submit" class="btn btn-primary">SEARCH</button>
        </form>
    </div>

    <nav class="navbar mega navbar-dark bg-dark hidden-lg-down">
        @include('layouts.web.partials.nav')
    </nav>

    <div class="page-title" style="background-image: url('images/title/bg01.jpg')">
        @include('layouts.web.partials.page-title')
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-3 sidebar">
                <aside class="widget widget_search">
                    @include('layouts.web.partials.side-bar-search')
                </aside>
            </div>
            <div id="content" class="col-lg-9" >
                @include('layouts.web.partials.sort-products')
            </div>
        </div>
        <div class="row">
            <aside class="col-lg-3 sidebar">
                @include('layouts.web.partials.side-bar-categories')
            </aside>

            <main id="content" class="col-lg-12" role="main">
                @include('layouts.web.partials.products')
            </main>

        </div>
    </div>
    <!-- ADVISORY
    ================================================== -->
    @include('layouts.web.partials.advisory')
    @include('layouts.web.partials.footer')
    @include('layouts.web.partials.copyright')
</div>

  <script src="components/owl.carousel/dist/owl.carousel.js"></script>
  <script src="components/countUp.js/dist/countUp.min.js"></script>
  <script src="components/jQuery.mmenu/dist/core/js/jquery.mmenu.min.all.js"></script>
  <script src="components/tether/tether.min.js"></script><!-- Tether for Bootstrap -->
  <script src="components/bootstrap/dist/js/bootstrap.js"></script>
  <script src="components/parallax.js/parallax.min.js"></script>
  <script src="components/sliphover/src/jquery.sliphover.js"></script>
  <script src="components/lightslider/dist/js/lightslider.min.js"></script>
  <script src="components/lightgallery/dist/js/lightgallery.min.js"></script>
  <script src="components/lightgallery/dist/js/lightgallery-all.min.js"></script>

  <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>
  <script src="js/vendor/gmap3.min.js"></script>
  <script src="js/vendor/jquery.elevateZoom-3.0.8.min.js"></script>

  <script src="js/main.js"></script>
  <script src="js/client.js"></script>
</body>
</html>
