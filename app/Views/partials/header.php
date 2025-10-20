 <!-- Navbar -->
  
  
<body class="layout-2">
    
    <div class="tap-top"><i class="fa-solid fa-angles-up"></i></div>
    <main>
        <!-- Header start -->
        <header class="header-absolute">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="custom-container container">
                    <a class="navbar-brand m-0" href="<?= base_url()?>">
                        <img src="<?= base_url()?>assets/images/logo/logo.webp" alt="logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav navigation">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="<?= base_url()?>" role="button"
                                    aria-expanded="false">
                                    Home
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="<?= base_url()?>admin" class="btn btn-primary rounded-pill">Admin</a>
                </div>
            </nav>
        </header>


  