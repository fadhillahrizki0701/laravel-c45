<nav id="c45-sidebar" :class="{ 'sidebar-open': open }">
  <a href="" class="align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
    <h2 class="fw-bolder fs-2" style="color:#435EBE;">KLASIFIKASI C4.5</h2>
  </a>
  <ul class="nav nav-pills flex-column mb-auto" style="color:#25396F;">
    <li class="nav-item py-3 fw-bolder">menu</li>
    <li class="nav-item">
      <a href="{{ route('dashboard.index') }}" class="nav-link link-dark" style="color:#25396F;" aria-current="page">
        <i class="bi bi-menu-down"></i>
        Dashboard
      </a>
    </li>
    <li class="nav-item py-3 fw-bolder">Data & Tabel</li>
    <li>
      <a href="{{ route('user.index') }}" class="nav-link link-dark " style="color:#25396F;">
        <i class="bi bi-people"></i>
        Data Pengguna
      </a>
    </li>
    <li>
      <a href="{{ route('dataset1.index') }}" class="nav-link link-dark" style="color:#25396F;">
        <i class="bi bi-bar-chart-line-fill"></i>
        Data 1
      </a>
    </li>
    <li>
      <a href="{{ route('dataset2.index') }}"class="nav-link link-dark" style="color:#25396F;">
        <i class="bi bi-bar-chart-line-fill"></i>
        Data 2
      </a>
    </li>
    <li class="nav-item py-3 fw-bolder">Algoritma C4.5</li>
    <li>
      <a href="{{ route('datatrain1.index') }}" class="nav-link link-dark" style="color:#25396F;">
        <i class="bi bi-database-up"></i>
        Data Training 1
      </a>
    </li>
    <li>
      <a href="{{ route('datatrain2.index') }}" class="nav-link link-dark" style="color:#25396F;">
        <i class="bi bi-database-up"></i>
        Data Training 2
      </a>
    </li>
    <li class="nav-item py-3 fw-bolder">Profil</li>
    <li>
      <button class="nav-link link-dark mb-4" type="button" data-bs-toggle="collapse" data-bs-target="#profileDetail" aria-expanded="false" aria-controls="profileDetail" style="color:#25396F;">
        <i class="bi bi-person-circle"></i>
        Profil
      </button>
      <div class="collapse mb-5" id="profileDetail">
        <div class="card card-body">
          <a href="{{ route('profile.index', auth()->user()->id) }}" class="btn btn-light mb-3 shadow-sm border border-primary-subtle">
            <i class="bi bi-gear"></i>
            Pengaturan
          </a>
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-left"></i>
            Keluar
          </button>
        </div>
      </div>
    </li>
  </ul>
</nav>

<button id="sidebar-button" @click="open = !open" x-data="{
  openIcon: '<i class=\'bi bi-list fs-2\'></i>',
  closeIcon: '<i class=\'bi bi-x fs-2\'></i>',
}">
  <span x-html="open ? closeIcon : openIcon"></span>
</button>

<div class="modal fade" id="logoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Keluar Aplikasi</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <p>Apakah Anda yakin ingin keluar dari Aplikasi Klasifikasi C4.5?</p>
                  <section class="d-flex gap-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Keluar</button>
                  </section>
              </form>
          </div>
      </div>
  </div>
</div>
