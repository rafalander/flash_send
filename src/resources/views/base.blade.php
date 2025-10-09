<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="{{ asset('images/icons/logo.png') }}" type="image/png" />
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
    }
    body {
      min-height: 100vh;
      overflow-x: hidden;
    }

    .page-body { display: flex; }

    /* Sidebar base */
    #sidebar {
      width: 60px;
      transition: width 0.35s ease-in-out;
      height: calc(100vh - 68px);
      border-right: 1px solid rgba(0,0,0,.08);
      position: relative;
      background: #f8f9fa;
      padding-top: 0.25rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Sidebar expandida */
    #sidebar.expanded { width: 200px; }

    /* Botão da setinha */
    #toggleSidebar {
      position: absolute;
      top: 1rem;
      right: -15px; /* fica fora do sidebar */
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

    /* Links do menu */
    #menuItems a {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      text-decoration: none;
      color: #000;
      width: 100%;
      justify-content: flex-start;
      transition: gap 0.25s ease, padding 0.25s ease;
    }

    #menuItems a:hover {
      background: rgba(0,0,0,.05);
      border-radius: 4px;
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

    /* Tamanho/alinhamento dos ícones */
    #menuItems a i {
      font-size: 1.15rem;
      width: 1.5rem;
      text-align: center;
    }

    /* Texto do menu com animação suave */
    #menuItems a .label {
      display: inline-block;
      white-space: nowrap;
      overflow: hidden;
      max-width: 140px; /* espaço para o texto quando expandido */
      opacity: 1;
      transition: max-width 0.35s ease-in-out, opacity 0.25s ease-in-out 0.05s;
    }

    /* Sidebar colapsada: mostra só ícones */
    #sidebar:not(.expanded) #menuItems a {
      justify-content: center;
      gap: 0;
    }
    #sidebar:not(.expanded) #menuItems a .label {
      max-width: 0;
      opacity: 0;
    }

    /* Sidebar expandida: centraliza itens também */
    #sidebar.expanded #menuItems a {
      justify-content: center;
    }

    /* Conteúdo principal */
    main.content {
      flex-grow: 1;
      padding: 1.25rem;
    }
    .logo {
      height: 25px;
      object-fit: contain;
      margin-right: 0.5rem;
    }

    .nameLogo {
      font-size: 1.25rem;
      font-weight: bold;
    }

    footer {
      border-top: 1px solid rgba(0,0,0,.08);
      position: relative;
    }
  .form-control:focus {
    box-shadow: 0 0 0 0.1rem rgba(13, 110, 253, 0.25);
  }
  </style>
</head>
<body>

  <!-- Navbar -->
  <header class="p-1 text-bg-dark">
    <div class="container-fluid d-flex align-items-center gap-3">
      <a href="/" class="d-flex align-items-center text-white text-decoration-none">
        <img src="{{ asset('images/icons/logo.png') }}" alt="Logo" class="logo">
        <strong class="d-none d-md-inline nameLogo">Flash Send</strong>
      </a>

      <div class="ms-auto d-flex align-items-center gap-2">
        <div class="text-end">
       {{-- Mantendo a div porque provavelmente vou colocar alguma feature no lugar --}}
        </div>
      </div>
    </div>
  </header>
  <!-- Corpo da página -->
  <div class="page-body">

    <!-- Sidebar -->
    <div id="sidebar" class="vh-100 shadow-sm">
      <!-- Botão da setinha -->
      <button id="toggleSidebar">
        <i id="toggleIcon" class="bi bi-chevron-right"></i>
      </button>

      <!-- Menu -->
      <div id="menuItems">
        <a href="{{ route('home') }}" title="Home">
          <i class="bi bi-house"></i><span class="label">Home</span>
        </a>
        <a href="{{ route('encomendas') }}" title="Encomendas">
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
        <a href="{{ route('moradores') }}" title="Moradores">
          <i class="bi bi-people"></i><span class="label">Moradores</span>
        </a>
      </div>
    </div>

    <!-- Conteúdo -->
    <main class="content">
      @yield('content')
    </main>
  </div>

  <footer class="p-1 text-bg-light mt-auto">
    <div class="p-2 text-center" style="font-size: 0.75rem; color: hsl(60, 1%, 41%);">
      &copy; {{ date('Y') }} Flash Send
      </a>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
          void sidebar.offsetWidth; // força reflow
          sidebar.style.transition = "";
        }
      }

      // pega o último estado salvo
      const saved = localStorage.getItem(KEY);
      const expanded = saved === "1";

      // aplica sem animação no load
      applySidebarState(expanded, false);

      // clique no botão
      toggleBtn.addEventListener("click", () => {
        const willExpand = !sidebar.classList.contains("expanded");
        applySidebarState(willExpand, true);
        localStorage.setItem(KEY, willExpand ? "1" : "0");
      });
    });
  </script>
</body>
</html>
