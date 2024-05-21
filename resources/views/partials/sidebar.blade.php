<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img class="img-fluid" src="{{ asset('img/logo.png') }}" alt="logo"></img>
        </div>
        <div class="sidebar-brand-text mx-3">Inventory</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Interface
    </div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('surat_masuk.index') }}">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Surat Masuk</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="{{ route('barang.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Barang</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="{{ route('detail_barang.index') }}">
            <i class="fas fa-fw fa-boxes-packing"></i>
            <span>Detail Barang</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('penempatan_barang.index') }}">
            <i class="fas fa-fw fa-computer"></i>
            <span>Penempatan Barang</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('ruangan.index') }}">
            <i class="fas fa-fw fa-store-alt"></i>
            <span>Ruangan</span>
        </a>
    </li>
    
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
