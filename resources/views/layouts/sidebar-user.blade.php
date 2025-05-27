<!-- resources/views/components/user-sidebar.blade.php -->
<div class="d-flex flex-column bg-white shadow position-fixed top-0 start-0" style="width: 280px; height: 100vh; overflow-y: auto; z-index: 1030;">
    <div class="text-center py-4 border-bottom">
        <h5 class="m-0 fw-bold fs-6">Si-Pemdora</h5>
    </div>
    <div class="px-3 mb-4 mt-3">
        <a href="{{ route('user.dashboard') }}" class="btn btn-primary w-100 text-start fs-6">
            <i class="bi bi-house-door me-2"></i>
            Dashboard
        </a>
    </div>

    <div class="px-3">
        <strong class="text-muted" style="font-size: 0.7rem;">MANAGEMENT DATA PAGE</strong>
    </div>
    
    <ul class="nav flex-column mb-2 mt-2 px-1">
        <li class="nav-item">
            <a href="#" class="nav-link text-dark small">
            <img src="{{ asset('img/radiation.png') }}" alt="Profile" class="me-2" width="16">
            Pemantauan Dosis Radiasi
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark small">
            <img src="{{ asset('img/atom.png') }}" alt="Profile" class="me-2" width="16">
            Pengangkutan Dosis Radiasi
            </a>
        </li>
        
    </ul>
    
    <div class="px-3 mt-2">
        <strong class="text-muted" style="font-size: 0.7rem;">ACCOUNT PAGES</strong>
    </div>
    
    <ul class="nav flex-column mt-2 px-1">
        <li class="nav-item">
            <a href="#" class="nav-link text-dark small">
            <img src="{{ asset('img/profile.png') }}" alt="Profile" class="me-2" width="16">
            Profile
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark small">
            <img src="{{ asset('img/setting.png') }}" alt="Profile" class="me-2" width="16">
            Setting
            </a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a href="#" onclick="event.preventDefault(); showLogoutConfirmation();" class="nav-link text-dark small">
                <img src="{{ asset('img/rocket.png') }}" alt="Profile" class="me-2" width="16">
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
</style>

@push('scripts')
<script>
    function showLogoutConfirmation() {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Anda akan keluar dari akun ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, keluar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endpush 