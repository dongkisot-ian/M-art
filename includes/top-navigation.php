 <!-- Navbar -->



 <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg  blur border-radius-xl top-0 z-index-fixed shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                    <div class="container-fluid px-0">
                        <a class="navbar-brand font-weight-bolder ms-sm-3" href="./" rel="tooltip"  data-placement="bottom">
                        <?= $_settings->info('M-Art') ?>
                        </a>
                            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon mt-2">
                                    <span class="navbar-toggler-bar bar1"></span>
                                    <span class="navbar-toggler-bar bar2"></span>
                                    <span class="navbar-toggler-bar bar3"></span>
                                </span>
                            </button>
                        <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
                            <ul class="navbar-nav navbar-nav-hover ms-auto">
                                <li class="nav-item dropdown dropdown-hover mx-2">
                                    <a class="nav-link ps-2 d-flex cursor-pointer align-items-center <?= $page == "home" ? "text-primary" : "" ?>" href="./" aria-expanded="false">
                                        <i class="material-icons opacity-6 me-2 text-md">dashboard</i> Home
                                    </a>
                                </li>
                                <li class="nav-item dropdown dropdown-hover mx-2">
                                    <a class="nav-link ps-2 d-flex cursor-pointer align-items-center <?= $page == "products" ? "text-primary" : "" ?>" href="./?page=products" aria-expanded="false">
                                        <i class="material-icons opacity-6 me-2 text-md">widgets</i> Gallery
                                    </a>
                                </li>
                                <li class="nav-item dropdown dropdown-hover mx-2">
                                    <a class="nav-link ps-2 d-flex cursor-pointer align-items-center <?= $page == "cart" ? "text-primary" : "" ?>" href="./?page=cart" aria-expanded="false">
                                    <i class="material-icons opacity-6 me-2 text-md">shopping_cart</i> Cart
                                    </a>
                                </li>
                                <li class="nav-item dropdown dropdown-hover mx-2">
                                    <a class="nav-link ps-2 d-flex cursor-pointer align-items-center <?= $page == "about" ? "text-primary" : "" ?>" href="./?page=about" aria-expanded="false">
                                        <i class="material-icons opacity-6 me-2 text-md">info</i> About Us
                                    </a>
                                </li>
                                <!-- <li class="nav-item dropdown dropdown-hover mx-2">
                                    <a class="nav-link ps-2 d-flex cursor-pointer align-items-center" href="./admin" aria-expanded="false">
                                        <i class="material-icons opacity-6 me-2 text-md">admin_panel_settings</i> Admin Login
                                    </a>
                                </li> -->
                                <li class="nav-item dropdown dropdown-hover mx-2">
                                    <a class="nav-link ps-2 d-flex cursor-pointer align-items-center" href="./seller" aria-expanded="false">
                                        <i class="material-icons opacity-6 me-2 text-md">person</i> Seller
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
        </div>
    </div>