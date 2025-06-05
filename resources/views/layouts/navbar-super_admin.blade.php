<!-- resources/views/layouts/navbar-admin.blade.php -->
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm position-fixed" style="height: 66px; right: 0; left: 280px; z-index: 1000; top: 0;">
    <div class="container">
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-person me-1"></i>
                        {{ Auth::user()->nama}}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav> 