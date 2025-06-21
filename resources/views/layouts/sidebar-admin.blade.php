<!-- resources/views/components/sidebar.blade.php -->
<div id="sidebar" class="sidebar d-flex flex-column position-fixed top-0 start-0">
    <div class="d-flex align-items-center py-3 px-3">
        <button id="sidebar-toggle" class="btn text-white p-0 me-3">
            <i class="fas fa-bars"></i>
        </button>
        <h5 class="m-0 fw-bold fs-6 text-white">Si-Pemdora</h5>
    </div>

    <div class="menu-section">
        <div class="menu-wrap">
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i>
                <span>Home</span>
            </a>
        </div>
    </div>

    <div class="menu-header">
        <span>MANAGEMENT DATA PAGE</span>
    </div>
    
    <div class="menu-section">
        <div class="menu-wrap">
            <a href="{{ route('admin.projects.index') }}" class="menu-item {{ Request::routeIs(['admin.projects.index', 'admin.projects.create', 'admin.projects.edit']) ? 'active' : '' }}">
                <i class="fas fa-project-diagram me-2"></i>
                <span>Kelola Project</span>
            </a>
        </div>
        <div class="menu-wrap">
            <a href="{{ route('admin.perizinan.index') }}" class="menu-item {{ Request::routeIs('admin.perizinan.*') ? 'active' : '' }}">
                <i class="fas fa-radiation-alt me-2"></i>
                <span>Perizinan Sumber Radiasi</span>
            </a>
        </div>
        
        <div class="menu-wrap">
            <a href="{{ route('admin.sdm.index') }}" class="menu-item {{ Request::routeIs('admin.sdm.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i>
                <span>Ketersediaan SDM</span>
            </a>
        </div>
        <div class="menu-wrap">
            <a href="{{ route('admin.tld.search') }}" class="menu-item {{ Request::routeIs(['admin.tld.search', 'admin.pemantauan.tld.*', 'admin.pemantauan.tld', 'admin.tld.*']) ? 'active' : '' }}">
                <i class="fas fa-radiation me-2"></i>
                <span>Pemantauan TLD</span>
            </a>
        </div>
        <div class="menu-wrap">
            <a href="{{ route('admin.pendos.search') }}" class="menu-item {{ Request::routeIs(['admin.pendos.search', 'admin.pendos.*', 'admin.pemantauan.pendos.*', 'admin.pemantauan.pendos']) ? 'active' : '' }}">
                <i class="fas fa-radiation-alt me-2"></i>
                <span>Pemantauan Pendos</span>
            </a>
        </div>
    </div>

    <div class="menu-header">
        <span>ACCOUNT PAGE</span>
    </div>

    <div class="menu-section">
        <div class="menu-wrap">
            <a href="{{ route('admin.profile.index') }}" class="menu-item {{ Request::routeIs('admin.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-circle me-2"></i>
                <span>Profile</span>
            </a>
        </div>
        <div class="menu-wrap">
            <a href="{{ route('admin.kelola_akun') }}" class="menu-item {{ Request::routeIs('admin.kelola_akun*') ? 'active' : '' }}">
                <i class="fas fa-user me-2"></i>
                <span>Kelola Akun</span>
            </a>
        </div>
        <div class="menu-wrap">
            <a href="{{ route('admin.laporan') }}" class="menu-item {{ Request::routeIs(['admin.laporan', 'admin.laporan.*']) ? 'active' : '' }}">
                <i class="fas fa-chart-bar me-2"></i>
                <span>Laporan</span>
            </a>
        </div>
        <div class="menu-wrap">
            <a href="{{ route('admin.documents.index') }}" class="menu-item {{ Request::routeIs('admin.documents.*') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span>Dokumen</span>
            </a>
        </div>
        <div class="menu-wrap">
            <a href="#" class="menu-item" onclick="event.preventDefault(); showLogoutConfirmation();">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Sign Out</span>
            </a>
        </div>
    </div>
</div>

<style>
.sidebar {
    width: 250px;
    height: 100vh;
    background-color: #002B5B;
    z-index: 1030;
    overflow-x: hidden;
    overflow-y: auto;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
}

.sidebar.collapsed {
    width: 250px;
    height: 60px;
    flex-direction: row;
    border-radius: 0 0 30px 0;
    transition: all 0.3s;
}
.sidebar.collapsed > .d-flex.align-items-center {
    width: 100%;
    justify-content: flex-start;
    display: flex;
}
.sidebar.collapsed .menu-section,
.sidebar.collapsed .menu-header {
    display: none;
}
.sidebar.collapsed h5 {
    display: block;
    font-size: 1.3rem;
    margin-left: 10px;
}

.menu-header {
    padding: 1.5rem 1.5rem 0.5rem;
}

.menu-header span {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.menu-section {
    padding: 0.25rem 0;
}

.menu-wrap {
    position: relative;
    margin: 6px 0; /* Increased from 4px for better spacing */
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 0.8rem 1.5rem; /* Mengubah padding kanan-kiri dari 1rem menjadi 1.5rem */
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 0.68rem;
    position: relative;
    z-index: 1;
    white-space: nowrap;
    overflow: visible;
    text-overflow: unset;
}

.menu-item:hover:not(.active) {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
}

.menu-item i {
    width: 20px;
    text-align: center;
}

.menu-item span {
    display: inline-block;
    white-space: nowrap;
    overflow: visible;
    text-overflow: unset;
    font-size: 0.7rem;
}

.menu-item.active {
    background: white;
    color: #002B5B;
    font-weight: 500;
    border-radius: 30px 0 0 30px;
}

.menu-item.active i,
.menu-item.active span {
    color: #002B5B;
    position: relative;
    z-index: 3;
}

.menu-wrap:has(.active) {
    margin: 16px 0;
    padding-left: 24px; /* Mengubah padding kiri dari 15px menjadi 24px untuk tab aktif */
    border-radius: 30px 0 0 30px;
    background: white;
}

.menu-wrap:has(.active)::before,
.menu-wrap:has(.active)::after {
    content: '';
    position: absolute;
    right: 0;
    height: 25px;
    width: 25px;
    background-color: #002B5B;
}

.menu-wrap:has(.active)::before {
    top: -25px;
    border-bottom-right-radius: 25px;
    box-shadow: 5px 5px 0 5px white;
}

.menu-wrap:has(.active)::after {
    bottom: -25px;
    border-top-right-radius: 25px;
    box-shadow: 5px -5px 0 5px white;
}

.menu-wrap:has(.active) .menu-item::after {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    background: white;
    z-index: -1;
}

::-webkit-scrollbar {
    width: 0px;
}
</style>

<!-- Tambahkan Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@push('scripts')
<script>
    function showLogoutConfirmation() {
        Swal.fire({
            title: 'Yakin ingin logout?',
            text: "Anda akan keluar dari akun ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, logout!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Logout clicked');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.menu-item');
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const contentWrapper = document.querySelector('.content-wrapper');
        
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#') {
                    e.preventDefault();
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
        
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('collapsed');
                contentWrapper.classList.toggle('full-width');
            });
        }

        // Add click event listener to content wrapper
        if (contentWrapper) {
            contentWrapper.addEventListener('click', function(e) {
                // Only collapse if sidebar is not already collapsed
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('collapsed');
                    contentWrapper.classList.add('full-width');
                }
            });
        }
    });
</script>
@endpush
