{{-- <section class="sidebar  bg-white "> --}}
  <div class="p-4" style="height:100%;">
    <a href="" class="align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
      <span class=" fw-bolder fs-2" style="color:#435EBE">KLASIFIKASI C45</span>
    </a>

 
    <ul class="nav nav-pills flex-column mb-auto" style="color:#25396F">
      <li class="nav-item py-3 fw-bolder">Menu </li>
      <li class="nav-item">
        <a href="{{ route('dashboard.index') }}" class="nav-link link-dark" style="color:#25396F" aria-current="page">
          <i class="bi bi-menu-down"></i>
          Dashboard
      </a>
      </li>
      <li class="nav-item py-3 fw-bolder">Data & Tabel </li>
      <li>
        <a href="{{route('user.index')}}" class="nav-link link-dark " style="color:#25396F" aria-current="page">
          <i class="bi bi-people"></i>
          Data Users
        </a>
      </li>
      <li>
        <a href="{{route('dataset1.index')}}" class="nav-link link-dark" style="color:#25396F" aria-current="page">
          <i class="bi bi-bar-chart-line-fill"></i>
          Data 1
        </a>
      </li>
      <li>
        <a href="{{route('dataset2.index')}}"class="nav-link link-dark" style="color:#25396F" aria-current="page">
          <i class="bi bi-bar-chart-line-fill"></i>
          Data 2
        </a>
      </li>
      <li class="nav-item py-3 fw-bolder">Algoritma C45 </li>
      <li>
        <a href="{{route('datatrain1.index')}}" class="nav-link link-dark" style="color:#25396F" aria-current="page">
          <i class="bi bi-database-up"></i>
          Data Training 1
        </a>
      </li>
      <li>
        <a href="{{route('datatrain2.index')}}" class="nav-link link-dark" style="color:#25396F" aria-current="page">
          <i class="bi bi-database-up"></i>
          Data Training 2
        </a>
      </li>
      <li>
        <a href="{{ route('login') }}" class="nav-link link-dark" style="color:#25396F" aria-current="page">
          <i class="bi bi-box-arrow-left"></i>
          Logout
        </a>
      </li>
    </ul>

  </div>
  

{{-- </section> --}}