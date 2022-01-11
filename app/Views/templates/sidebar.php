    <?php if (in_groups('admin')) : ?>
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark toggled" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/user'); ?>">
                <div class="sidebar-brand-icon">
                    <img src="<?= base_url('/favicon.png') ?>" alt="horas poin" style="max-width: 5rem">
                </div>
                <div class="sidebar-brand-text mx-3">Horas Poin</sup></div>
            </a>

            <!-- Heading -->
            <div class="sidebar-heading">
                Site Management
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - User List -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin'); ?>">
                    <i class="fas fa-fw fa-user"></i>
                    <span>User List</span></a>
            </li>
            
            <!-- Nav Item - Unit List -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/units'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>List Unit Cabang</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Akuisisi User
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Product List -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/listAcquisitions'); ?>">
                    <i class="fas fa-fw fa-th-list"></i>
                    <span>List Akuisisi</span></a>
            </li>

            <!-- Nav Item - Product List -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/productList'); ?>">
                    <i class="fas fa-fw fa-list-alt"></i>
                    <span>Product List</span></a>
            </li>

            <!-- Nav Item - Product List -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/projectList'); ?>">
                    <i class="fas fa-fw fa-th-list"></i>
                    <span>Project List</span></a>
            </li>

            <!-- Nav Item - Product Rules -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/productRule'); ?>">
                    <i class="fas fa-fw fa-gavel"></i>
                    <span>Product Rules</span></a>
            </li>

            <!-- Nav Item - Product Rules -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/PatriotDay'); ?>">
                    <i class="fas fa-fw fa-flag"></i>
                    <span>Patriot Day</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                User Section
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - My Profile -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('user'); ?>">
                    <i class="fas fa-fw fa-user"></i>
                    <span>My Profile</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        
    <?php else : ?>
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="width: 5px !important;"></ul>
    <?php endif; ?>