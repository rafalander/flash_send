<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" class="favicon" href="{{ asset('images/icons/favicon.png') }}" type="image/png" />
  <title>@yield('title', 'Flash Send')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    header {
      height: 45px;
      display: flex;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1020;
    }

    body {
      min-height: 100vh;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
    }

    .page-body {
      display: flex;
      flex: 1; /* ocupa o restante da tela */
      flex-wrap: nowrap;
    }

    /* Sidebar base */
    #sidebar {
      width: 60px;
      transition: width 0.35s ease-in-out;
      border-right: 1px solid rgba(0,0,0,.08);
      background: #f8f9fa;
      padding-top: 0.25rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      flex-shrink: 0;
    }

    #sidebar.expanded { width: 200px; }

    #toggleSidebar {
      position: absolute;
      top: 1rem;
      right: -15px;
      border-radius: 50%;
      background: white;
      border: 1px solid #ccc;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1050;
      cursor: pointer;
      transition: all 0.3s;
    }

    #toggleSidebar:hover {
      background: #f8f9fa;
      border-color: #999;
    }

    #menuItems {
      margin-top: 0.5rem;
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
      padding-bottom: 1rem;
    }

    #menuItems a {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      text-decoration: none;
      color: #000;
      width: 100%;
      justify-content: flex-start;
    }

    #menuItems a:hover {
      background: rgba(0,0,0,.05);
      border-radius: 4px;
    }

    #menuItems a i {
      font-size: 1.15rem;
      width: 1.5rem;
      text-align: center;
    }

    #menuItems a .label {
      display: inline-block;
      white-space: nowrap;
      overflow: hidden;
      max-width: 140px;
      opacity: 1;
    }

    #sidebar:not(.expanded) #menuItems a {
      justify-content: center;
      gap: 0;
    }
    #sidebar:not(.expanded) #menuItems a .label {
      max-width: 0;
      opacity: 0;
    }

    #sidebar.expanded #menuItems a {
      justify-content: center;
    }

    main.content {
      flex-grow: 1;
      padding: 1.25rem;
      min-width: 0;
    }

    .logo {
      height: 80px;
      object-fit: contain;
    }
    footer {
      border-top: 1px solid rgba(0,0,0,.08);
      background-color: #f8f9fa;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.1rem rgba(13, 110, 253, 0.25);
    }

    /* Responsividade */
    @media (max-width: 768px) {
      #sidebar {
        position: fixed;
        top: 45px;
        left: 0;
        height: calc(100vh - 45px);
        z-index: 1040;
        transform: translateX(-100%);
      }

      #sidebar.expanded {
        transform: translateX(0);
      }

      .page-body {
        flex-direction: column;
      }

      main.content {
        padding-top: 1rem;
      }

      #toggleSidebar {
        right: auto;
        left: 70px;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <header class="text-bg-dark w-100">
    <div class="d-flex align-items-center justify-content-between w-100 px-2">
      <a href="/" class="d-flex align-items-center text-white text-decoration-none">
        <img src="{{ asset('images/icons/newlogo.png') }}" alt="Logo" class="logo">
      </a>
      <a href="{{ route('config.index') }}" class="text-white text-decoration-none">
        <i class="bi bi-gear-fill me-1 ms-1"></i>Configurações
      </a>
    </div>
  </header>

  <!-- Corpo da página -->
  <div class="page-body">

    <!-- Sidebar -->
    <div id="sidebar" class="shadow-sm position-relative">
      <button id="toggleSidebar">
        <i id="toggleIcon" class="bi bi-chevron-right"></i>
      </button>

      <div id="menuItems">
        <a href="{{ route('home') }}" title="Home">
          <i class="bi bi-house"></i><span class="label">Home</span>
        </a>
        <a href="{{ route('encomendas.index') }}" title="Encomendas">
          <i class="bi bi-box2-heart"></i><span class="label">Encomendas</span>
        </a>
        <a href="{{ route('blocos.index') }}" title="Blocos">
          <i class="bi bi-columns"></i><span class="label">Blocos</span>
        </a>
        <a href="{{ route('torres.index') }}" title="Torres">
          <i class="bi bi-building"></i><span class="label">Torres</span>
        </a>
        <a href="{{ route('apartamentos.index') }}" title="Apartamentos">
          <i class="bi bi-door-open"></i><span class="label">Apartamentos</span>
        </a>
        <a href="{{ route('moradores.index') }}" title="Moradores">
          <i class="bi bi-people"></i><span class="label">Moradores</span>
        </a>
      </div>
    </div>

    <!-- Conteúdo -->
    <main class="content">
      @yield('content')
    </main>
  </div>

  <footer class="p-1 text-bg-light">
    <div class="p-2 text-center" style="font-size: 0.75rem; color: hsl(60, 1%, 41%);">
      &copy; {{ date('Y') }} Flash Send
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Utilitários de Máscara -->
  <script src="{{ asset('js/masks.js') }}"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const KEY = 'sidebarExpanded';
      const toggleBtn = document.getElementById("toggleSidebar");
      const sidebar = document.getElementById("sidebar");
      const toggleIcon = document.getElementById("toggleIcon");

      function applySidebarState(expanded, withTransition = true) {
        if (!withTransition) {
          sidebar.style.transition = "none";
        }

        if (expanded) {
          sidebar.classList.add('expanded');
          toggleIcon.classList.replace('bi-chevron-right', 'bi-chevron-left');
        } else {
          sidebar.classList.remove('expanded');
          toggleIcon.classList.replace('bi-chevron-left', 'bi-chevron-right');
        }

        if (!withTransition) {
          void sidebar.offsetWidth;
          sidebar.style.transition = "";
        }
      }

      const saved = localStorage.getItem(KEY);
      const expanded = saved === "1";
      applySidebarState(expanded, false);

      toggleBtn.addEventListener("click", () => {
        const willExpand = !sidebar.classList.contains("expanded");
        applySidebarState(willExpand, true);
        localStorage.setItem(KEY, willExpand ? "1" : "0");
      });
    });
  </script>
</body>
</html>
