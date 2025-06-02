<!-- resources/views/components/sidebar.blade.php -->
<div class="d-flex flex-column bg-white shadow position-fixed top-0 start-0" style="width: 280px; height: 100vh; overflow-y: auto; z-index: 1030;">
    
    <div class="text-center py-4 border-bottom">
        <h5 class="m-0 fw-bold fs-6">Si-Pemdora</h5>
    </div>
    <div class="px-3 mb-4 mt-3">
        <a href="{{ route('super_admin.dashboard') }}" class="btn btn-primary w-100 text-start fs-6">
            <img src="{{ asset('img/home.png') }}" alt="Dashboard" class="me-2" width="18">
            Dashboard
        </a>
    </div>

    <div class="px-3">
        <strong class="text-muted" style="font-size: 0.7rem;">MANAGEMENT DATA PAGE</strong>
    </div>
    
    <ul class="nav flex-column mb-2 mt-2 px-1">
        <li class="nav-item">
            <a href="{{ route('super_admin.perizinan_sumber_radiasi_pengion') }}" class="nav-link text-dark small">
                <img src="{{ asset('img/atom.png') }}" alt="Perizinan" class="me-2" width="16">
                Perizinan Sumber Radiasi
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('super_admin.ketersediaan_sdm') }}" class="nav-link text-dark small">
                <img src="{{ asset('img/user.png') }}" alt="SDM" class="me-2" width="16">
                Ketersediaan SDM
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-dark small d-flex justify-content-between align-items-center" href="#collapseDosisPemantauan" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseDosisPemantauan">
                <span>
                <img src="{{ asset('img/radiation.png') }}" alt="Dosis" class="me-2" width="16">
                Pemantauan Dosis Radiasi
                </span>
                <i class="fas fa-chevron-down collapse-icon"></i>
            </a>
            <div class="collapse" id="collapseDosisPemantauan">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">
                    <li><a href="{{ route('super_admin.pemantauan_tld') }}" class="nav-link text-dark small">Pemantauan TLD (Thermoluminescent Dosimeter)</a></li>
                    <li><a href="{{ route('super_admin.pemantauan_dosis_pendose') }}" class="nav-link text-dark small">Pemantauan Dosis Pendose</a></li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('super_admin.pengangkutan_sumber_radioaktif') }}" class="nav-link text-dark small">
                <img src="{{ asset('img/atom.png') }}" alt="Pengangkutan" class="me-2" width="16">
                Pengangkutan Sumber Radioaktif
            </a>
        </li>
    </ul>
    
    <div class="px-3 mt-2">
        <strong class="text-muted" style="font-size: 0.7rem;">ACCOUNT PAGES</strong>
    </div>
    
    <ul class="nav flex-column mt-2 px-1">
        <li class="nav-item">
            <a href="{{ route('super_admin.kelola_akun') }}" class="nav-link text-dark small">
                <img src="{{ asset('img/profile.png') }}" alt="Profile" class="me-2" width="16">
                Kelola Akun
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('super_admin.laporan') }}" class="nav-link text-dark small">
                <img src="{{ asset('img/chart.png') }}" alt="Laporan" class="me-2" width="16">
                Laporan
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark small">
                <img src="{{ asset('img/setting.png') }}" alt="Setting" class="me-2" width="16">
                Setting
            </a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a href="#" 
                   onclick="event.preventDefault(); showLogoutConfirmation();" 
                   class="nav-link text-dark small">
                    <img src="{{ asset('img/rocket.png') }}" alt="Logout" class="me-2" width="16">
                    Log out
                </a>
            </form>
        </li>
    </ul>
</div>

<style>
/* Hover effect pada menu */
.nav-link:hover {
    background-color: rgba(0, 123, 255, 0.1);
    border-radius: 5px;
}

/* Mengatur ukuran font menu */
.small {
    font-size: 0.8rem !important;
}

.collapse-icon {
    transition: transform 0.3s ease-in-out;
}

.nav-link[aria-expanded="true"] .collapse-icon {
    transform: rotate(180deg);
}
</style>

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
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endpush
