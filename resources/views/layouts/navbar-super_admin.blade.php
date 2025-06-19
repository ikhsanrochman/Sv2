<!-- resources/views/layouts/navbar-admin.blade.php -->
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-lg position-fixed rounded-4" style="height: 71px; right: 30px; left: 280px; z-index: 1000; top: 0;">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-end align-items-center w-100">
            <!-- Right Side Of Navbar - Always Visible -->
            <div class="d-flex align-items-center">
                <div style="padding-right: 40px;">
                    <a href="{{ route('super_admin.profile.index') }}" class="text-decoration-none text-dark">
                        <i class="bi bi-person me-1"></i>
                        {{ Auth::user()->nama }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav> 