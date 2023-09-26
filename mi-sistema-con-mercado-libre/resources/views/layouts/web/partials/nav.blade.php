<div class="container">
    <ul class="nav navbar-nav">
        <li class="nav-item dropdown active">
            <a class="nav-link" href="/" role="button" aria-haspopup="true" aria-expanded="false">INICIO <span class="sr-only">(current)</span></a>
            <!-- <ul class="dropdown-menu">
                <li class="dropdown-item">
                <a tabindex="-1" href="#"> HOME 1 </a>
                </li>
                <li class="dropdown-item">
                <a tabindex="-1" href="#"> HOME 2 </a>
                </li>
                <li class="dropdown-item">
                <a tabindex="-1" href="#"> HOME 3 </a>
                </li>
                <li class="dropdown-item">
                <a tabindex="-1" href="#"> HOME 4 </a>
                </li>
                <li class="dropdown-item">
                <a tabindex="-1" href="#"> HOME 5 </a>
                </li>
                <li class="dropdown-item">
                <a tabindex="-1" href="#"> HOME 6 </a>
                </li>
                <li class="dropdown-item">
                <a tabindex="-1" href="#"> HOME 7 </a>
                </li>
            </ul> -->
        </li>
        @if (url()->current() != 'https://www.outletceramicas.com.ar/contacto')
            <li class="nav-item dropdown mega-fw">
                <a class="nav-link" role="button" aria-haspopup="true" aria-expanded="false" href="#">PRODUCTOS</a>
                <div class="dropdown-menu">
                    <div class="row">
                        <categories />
                    </div>
                </div>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="/contacto">CONTÁCTENOS</a>
        </li>
    </ul>
    <!-- <div class="cart pull-xs-right">
        <div class="dropdown-toggle" role="menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-shopping-cart"></i> (0)
        </div>
        <ul class="dropdown-menu">
        <li>
            <p class="text-center">Your shopping cart is empty!</p>
        </li>
        </ul>
    </div> -->
</div>