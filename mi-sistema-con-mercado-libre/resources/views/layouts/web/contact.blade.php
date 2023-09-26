<!doctype html>
<html lang="" class="page-shop">
<head>
    @include('layouts.web.partials.head')
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css' rel='stylesheet' />
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
    <div class="container">
        <h1 class="entry-title">CONTÁCTENOS</h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li class="active">Shop</li>
        </ol> -->
        
    </div>
    </div>

    <div>
        
    </div>
    <section class="info-contact">
  <div class="container">
    <div class="row">

      <div class="col-lg-3">

        <div class="custom-heading part-heading three-slashes">
          <h2>CONTACTO</h2>
        </div>
        <div class="office">
          <p><i class="fa fa-map-marker"></i>  Los Andes 1659 - Carlos Spegazzini
          </p>
          <p><i class="fa fa-phone"></i> +542236682788 </p>
          <p><i class="fa fa-envelope"></i> patriciabeltrami2@gmail.com </p>
          <p><i class="fa fa-clock-o"></i> Lunes a Viernes de 08:00 a 16:00 Hs.
Sábados de 08:00 a 13:00 Hs.</p>
        </div>
      </div>
      <div class="contact-content col-lg-9">

        <div class="custom-heading part-heading three-slashes">
          <h2>COMPLETAR FORMULARIO DE CONTACTO</h2>
        </div>

        <p>Estamos para atender tus necesidades, tú consulta ayuda a mejorar nuestar atención al cliente.</p>

        <div class="contact-form">
            <contact_form />
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- ADVISORY
    ================================================== -->
    <!-- @include('layouts.web.partials.advisory') -->
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
