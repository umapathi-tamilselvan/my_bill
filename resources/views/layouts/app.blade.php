<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Store Billing System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @auth
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center text-white mb-4">
                        <h4><i class="bi bi-shop"></i> Billing System</h4>
                    </div>
                    <ul class="nav flex-column">
                        @can('access-billing')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('billing.*') ? 'active bg-primary' : '' }}" href="{{ route('billing.create') }}">
                                <i class="bi bi-cart-plus"></i> POS / Billing
                            </a>
                        </li>
                        @endcan
                        @can('access-products')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('products.*') ? 'active bg-primary' : '' }}" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam"></i> Products
                            </a>
                        </li>
                        @endcan
                        @can('access-customers')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('customers.*') ? 'active bg-primary' : '' }}" href="{{ route('customers.index') }}">
                                <i class="bi bi-people"></i> Customers
                            </a>
                        </li>
                        @endcan
                        @can('access-billing')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('billing.index') ? 'active bg-primary' : '' }}" href="{{ route('billing.index') }}">
                                <i class="bi bi-receipt"></i> Bills
                            </a>
                        </li>
                        @endcan
                        @can('access-reports')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('reports.*') ? 'active bg-primary' : '' }}" href="{{ route('reports.sales') }}">
                                <i class="bi bi-graph-up"></i> Reports
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 d-flex align-items-center gap-2">
                        @auth
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                    @if(Auth::user()->role)
                                        <span class="badge bg-primary ms-1">{{ Auth::user()->role->name }}</span>
                                    @endif
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                    <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-box-arrow-right"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                        @yield('header-actions')
                    </div>
                </div>

                <!-- Alerts -->
                <x-alert type="success" />
                <x-alert type="error" />
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Content -->
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    @else
    <script>window.location.href = '{{ route('login') }}';</script>
    @endauth
</body>
</html>

<style>
    .sidebar {
        min-height: 100vh;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    .nav-link.active {
        font-weight: bold;
    }
    .nav-link:hover {
        background-color: rgba(255,255,255,0.1);
    }
</style>

