<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Flash Send')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      min-height: 100vh;
      overflow-x: hidden;
    }

    .page-body { display: flex; }

    /* Sidebar base */
    #sidebar {
      width: 60px;
      transition: width 0.3s;
      height: calc(100vh - 68px);
      border-right: 1px solid rgba(0,0,0,.08);
      position: relative;
      background: #f8f9fa;
      padding-top: 0.25rem; /* reduzir espaço no topo */
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Sidebar expandida */
    #sidebar.expanded { width: 200px; }

    /* Botão fora do sidebar */
    #toggleSidebar {
      position: absolute;
      top: 1rem;
      right: -15px; /* deixa fora */
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
    }

    #menuItems a:hover { 
      background: rgba(0,0,0,.05); 
      border-radius: 4px; 
    }

    #menuItems { 
      margin-top: 0.5rem; /* diminuir espaço antes das opções */
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center; /* centraliza verticalmente e horizontalmente */
      gap: 0.5rem;
      padding-bottom: 1rem;
    }
    #menuItems.d-none {
      display: none; 
    }

    /* Conteúdo principal */
    main.content {
      flex-grow: 1;
      padding: 1.25rem;
    }

  </style>
</head>
<body>

  @cache('navbar', 60)
  <!-- Navbar -->
  <header class="p-1 text-bg-dark">
    <div class="container-fluid d-flex align-items-center gap-3">
      <a href="/" class="d-flex align-items-center text-white text-decoration-none">
        <i class="bi bi-envelope fs-3 text-primary me-1"></i>
        <strong class="d-none d-md-inline">Flash Send</strong>
      </a>

      <div class="ms-auto d-flex align-items-center gap-2">
        <div class="text-end">
          <button type="button" class="btn btn-outline-light btn-sm me-2">Login</button>
          <button type="button" class="btn btn-warning btn-sm">Sign-up</button>
        </div>
      </div>
    </div>
  </header>
@endcache
<!-- Corpo da página -->
<div class="page-body">

  @cache('sidebar', 60)  
    <!-- Sidebar -->
    <div id="sidebar" class="vh-100 shadow-sm">
        <!-- Botão da setinha -->
        <button id="toggleSidebar">
            <i id="toggleIcon" class="bi bi-chevron-right"></i>
        </button>

        <!-- Menu -->
        <div id="menuItems" class="d-none">
            <a href="#" title="Menu1"><i class="bi bi-house"></i>Menu1</a>
            <a href="#" title="Menu2"><i class="bi bi-speedometer2"></i>Menu2</a>
            <a href="#" title="Menu3"><i class="bi bi-table"></i>Menu3</a>
            <a href="#" title="Menu4"><i class="bi bi-grid"></i>Menu4</a>
            <a href="#" title="Menu5"><i class="bi bi-people"></i>Menu5</a>
        </div>
    </div>
  @endcache
    <!-- Conteúdo principal -->
    <main class="content">
        @yield('content')
    </main>
</div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const toggleBtn = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const menuItems = document.getElementById("menuItems");
    const toggleIcon = document.getElementById("toggleIcon");

    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("expanded");
      menuItems.classList.toggle("d-none");

      if (sidebar.classList.contains("expanded")) {
        toggleIcon.classList.replace("bi-chevron-right", "bi-chevron-left");
      } else {
        toggleIcon.classList.replace("bi-chevron-left", "bi-chevron-right");
      }
    });
  </script>
</body>
</html>
