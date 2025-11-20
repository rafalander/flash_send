<button id="darkModeToggle" class="btn btn-link text-white text-decoration-none p-2" title="Alternar modo escuro">
  <i id="darkModeIcon" class="bi bi-moon-fill"></i>
</button>

<style>
  /* Variáveis CSS para modo escuro */
  :root {
    --bg-primary: #ffffff;
    --bg-secondary: #f8f9fa;
    --text-primary: #000000;
    --text-secondary: #6c757d;
    --border-color: rgba(0,0,0,.08);
    --sidebar-bg: #f8f9fa;
    --footer-bg: #f8f9fa;
    --footer-text: hsl(60, 1%, 41%);
  }

  [data-theme="dark"] {
    --bg-primary: #1a1a1a;
    --bg-secondary: #2d2d2d;
    --text-primary: #ffffff;
    --text-secondary: #b0b0b0;
    --border-color: rgba(255,255,255,.1);
    --sidebar-bg: #252525;
    --footer-bg: #252525;
    --footer-text: #b0b0b0;
  }

  /* Aplicar cores do tema */
  body {
    background-color: var(--bg-primary);
    color: var(--text-primary);
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  #sidebar {
    background: var(--sidebar-bg) !important;
    border-right-color: var(--border-color) !important;
    transition: background-color 0.3s ease, border-color 0.3s ease;
  }

  #menuItems a {
    color: var(--text-primary) !important;
  }

  #menuItems a:hover {
    background: var(--bg-secondary) !important;
  }

  #toggleSidebar {
    background: var(--bg-primary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  #toggleSidebar:hover {
    background: var(--bg-secondary) !important;
  }

  main.content {
    background-color: var(--bg-primary);
    color: var(--text-primary);
  }

  footer {
    background-color: var(--footer-bg) !important;
    border-top-color: var(--border-color) !important;
  }

  footer .text-bg-light {
    background-color: var(--footer-bg) !important;
    color: var(--footer-text) !important;
  }

  /* Footer no modo escuro - estilos mais específicos */
  [data-theme="dark"] footer {
    background-color: var(--footer-bg) !important;
    border-top-color: var(--border-color) !important;
  }

  [data-theme="dark"] footer.text-bg-light {
    background-color: var(--footer-bg) !important;
  }

  [data-theme="dark"] footer .text-bg-light {
    background-color: var(--footer-bg) !important;
    color: var(--footer-text) !important;
  }

  [data-theme="dark"] footer div,
  [data-theme="dark"] footer p,
  [data-theme="dark"] footer span {
    color: var(--footer-text) !important;
  }

  /* Sobrescrever estilos inline do footer - regra mais específica */
  [data-theme="dark"] footer div[style],
  [data-theme="dark"] footer p[style],
  [data-theme="dark"] footer span[style],
  [data-theme="dark"] footer *[style*="color"] {
    color: var(--footer-text) !important;
  }

  /* Regra ainda mais específica para garantir */
  [data-theme="dark"] footer .p-2,
  [data-theme="dark"] footer .text-center {
    color: var(--footer-text) !important;
  }

  /* Header sempre escuro */
  header.text-bg-dark {
    background-color: #212529 !important;
  }

  /* Ajustes para componentes Bootstrap no modo escuro */
  [data-theme="dark"] .form-control {
    background-color: var(--bg-secondary);
    border-color: var(--border-color);
    color: var(--text-primary);
  }

  [data-theme="dark"] .form-control:focus {
    background-color: var(--bg-secondary);
    border-color: #0d6efd;
    color: var(--text-primary);
  }

  /* Cards e seus componentes */
  [data-theme="dark"] .card {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .card-header {
    background-color: var(--bg-secondary) !important;
    border-bottom-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .card-body {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .card-footer {
    background-color: var(--bg-secondary) !important;
    border-top-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .card-title {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .card-text {
    color: var(--text-primary) !important;
  }

  /* Cards customizados */
  [data-theme="dark"] .bloco-card {
    background-color: var(--bg-secondary) !important;
    box-shadow: 0 5px 8px rgba(0, 0, 0, 0.3) !important;
  }

  [data-theme="dark"] .bloco-card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4) !important;
  }

  /* List Groups */
  [data-theme="dark"] .list-group {
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .list-group-item {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .list-group-item:hover {
    background-color: var(--bg-primary) !important;
  }

  /* Items customizados */
  [data-theme="dark"] .apartamento-item {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
  }

  [data-theme="dark"] .apartamento-item:hover {
    background-color: var(--bg-primary) !important;
  }

  [data-theme="dark"] .encomenda-item {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
  }

  [data-theme="dark"] .encomenda-item:hover {
    background-color: var(--bg-primary) !important;
  }

  /* Textos e informações */
  [data-theme="dark"] .text-muted {
    color: var(--text-secondary) !important;
  }

  [data-theme="dark"] .info-destaque {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .info-secundaria {
    color: var(--text-secondary) !important;
  }

  [data-theme="dark"] h1,
  [data-theme="dark"] h2,
  [data-theme="dark"] h3,
  [data-theme="dark"] h4,
  [data-theme="dark"] h5,
  [data-theme="dark"] h6 {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] p {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] strong {
    color: var(--text-primary) !important;
  }

  /* Tabelas - Estilos mais específicos para garantir aplicação */
  [data-theme="dark"] table.table {
    color: var(--text-primary) !important;
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .table,
  [data-theme="dark"] table.table,
  [data-theme="dark"] .card-body .table,
  [data-theme="dark"] .card .table {
    color: var(--text-primary) !important;
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .table thead,
  [data-theme="dark"] table.table thead,
  [data-theme="dark"] .card-body .table thead {
    background-color: var(--bg-primary) !important;
  }

  [data-theme="dark"] .table thead th,
  [data-theme="dark"] table.table thead th,
  [data-theme="dark"] .card-body .table thead th {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
  }

  [data-theme="dark"] .table tbody,
  [data-theme="dark"] table.table tbody,
  [data-theme="dark"] .card-body .table tbody {
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .table tbody tr,
  [data-theme="dark"] table.table tbody tr,
  [data-theme="dark"] .card-body .table tbody tr {
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .table tbody td,
  [data-theme="dark"] table.table tbody td,
  [data-theme="dark"] .card-body .table tbody td {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
  }

  [data-theme="dark"] .table tbody tr:hover,
  [data-theme="dark"] table.table tbody tr:hover {
    background-color: var(--bg-primary) !important;
  }

  [data-theme="dark"] .table tbody tr:hover td,
  [data-theme="dark"] table.table tbody tr:hover td {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > td {
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(even) > td {
    background-color: var(--bg-primary) !important;
  }

  [data-theme="dark"] .table > :not(caption) > * > * {
    border-color: var(--border-color) !important;
  }

  [data-theme="dark"] .table-responsive {
    background-color: transparent !important;
  }

  [data-theme="dark"] .table-hover > tbody > tr:hover > td,
  [data-theme="dark"] .table-hover > tbody > tr:hover > th,
  [data-theme="dark"] .table-hover tbody tr:hover td,
  [data-theme="dark"] .table-hover tbody tr:hover th {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
  }

  /* Forçar todas as células da tabela a serem escuras - regra mais genérica */
  [data-theme="dark"] table td,
  [data-theme="dark"] table th {
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
  }

  /* Garantir que o thead tenha fundo escuro */
  [data-theme="dark"] table thead,
  [data-theme="dark"] .table thead {
    background-color: var(--bg-primary) !important;
  }

  [data-theme="dark"] table thead th,
  [data-theme="dark"] .table thead th {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
  }

  /* Garantir que o tbody tenha fundo escuro */
  [data-theme="dark"] table tbody,
  [data-theme="dark"] .table tbody {
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] table tbody td,
  [data-theme="dark"] .table tbody td {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] table tbody tr,
  [data-theme="dark"] .table tbody tr {
    background-color: var(--bg-secondary) !important;
  }

  /* Sobrescrever qualquer background branco do Bootstrap */
  [data-theme="dark"] table[style*="background"],
  [data-theme="dark"] .table[style*="background"],
  [data-theme="dark"] table[style*="background-color: white"],
  [data-theme="dark"] .table[style*="background-color: white"],
  [data-theme="dark"] table[style*="background-color:#fff"],
  [data-theme="dark"] .table[style*="background-color:#fff"] {
    background-color: var(--bg-secondary) !important;
  }

  /* Tabs (Nav Tabs) */
  [data-theme="dark"] .nav-tabs {
    border-bottom-color: var(--border-color) !important;
  }

  [data-theme="dark"] .nav-tabs .nav-link {
    color: var(--text-primary) !important;
    background-color: transparent !important;
    border-color: transparent !important;
  }

  [data-theme="dark"] .nav-tabs .nav-link:hover {
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .nav-tabs .nav-link.active {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) var(--border-color) var(--bg-secondary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .tab-content {
    color: var(--text-primary);
  }

  [data-theme="dark"] .tab-pane {
    color: var(--text-primary);
  }

  /* Modais */
  [data-theme="dark"] .modal-content {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .modal-header {
    background-color: var(--bg-secondary) !important;
    border-bottom-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .modal-title {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .modal-body {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .modal-footer {
    background-color: var(--bg-secondary) !important;
    border-top-color: var(--border-color) !important;
  }

  [data-theme="dark"] .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
  }

  /* Alerts */
  [data-theme="dark"] .alert {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .alert-info {
    background-color: rgba(13, 202, 240, 0.15) !important;
    border-color: rgba(13, 202, 240, 0.3) !important;
    color: #6edff6 !important;
  }

  [data-theme="dark"] .alert-success {
    background-color: rgba(25, 135, 84, 0.15) !important;
    border-color: rgba(25, 135, 84, 0.3) !important;
    color: #75b798 !important;
  }

  [data-theme="dark"] .alert-warning {
    background-color: rgba(255, 193, 7, 0.15) !important;
    border-color: rgba(255, 193, 7, 0.3) !important;
    color: #ffc107 !important;
  }

  [data-theme="dark"] .alert-danger {
    background-color: rgba(220, 53, 69, 0.15) !important;
    border-color: rgba(220, 53, 69, 0.3) !important;
    color: #f1aeb5 !important;
  }

  /* Labels e form labels */
  [data-theme="dark"] .form-label {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] label {
    color: var(--text-primary) !important;
  }

  /* Select e outros inputs */
  [data-theme="dark"] select.form-control,
  [data-theme="dark"] select.form-select {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] select.form-control option,
  [data-theme="dark"] select.form-select option {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] select.form-control:focus,
  [data-theme="dark"] select.form-select:focus {
    background-color: var(--bg-secondary) !important;
    border-color: #0d6efd !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] textarea.form-control {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  /* Select2 (se usado) */
  [data-theme="dark"] .select2-container--default .select2-selection--single {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .select2-dropdown {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
  }

  [data-theme="dark"] .select2-container--default .select2-results__option {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .select2-container--default .select2-results__option--highlighted {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .select2-search__field {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  /* Container e elementos gerais */
  [data-theme="dark"] .container,
  [data-theme="dark"] .container-fluid {
    color: var(--text-primary);
  }

  /* Links */
  [data-theme="dark"] a:not(.btn):not(.text-white) {
    color: #6ea8fe !important;
  }

  [data-theme="dark"] a:not(.btn):not(.text-white):hover {
    color: #8bb9fe !important;
  }

  /* Backgrounds específicos que devem ser sobrescritos */
  [data-theme="dark"] .bg-white {
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .bg-light {
    background-color: var(--bg-secondary) !important;
  }

  /* Input Groups */
  [data-theme="dark"] .input-group {
    color: var(--text-primary);
  }

  [data-theme="dark"] .input-group-text {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  /* Badges e contadores */
  [data-theme="dark"] .badge {
    color: var(--text-primary);
  }

  /* Contadores com bg-opacity (componente count) */
  [data-theme="dark"] [class*="bg-opacity-10"] {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
  }

  [data-theme="dark"] [class*="bg-primary"][class*="bg-opacity"] {
    background-color: rgba(13, 110, 253, 0.2) !important;
    border-color: rgba(13, 110, 253, 0.4) !important;
  }

  [data-theme="dark"] [class*="bg-success"][class*="bg-opacity"] {
    background-color: rgba(25, 135, 84, 0.2) !important;
    border-color: rgba(25, 135, 84, 0.4) !important;
  }

  [data-theme="dark"] [class*="bg-info"][class*="bg-opacity"] {
    background-color: rgba(13, 202, 240, 0.2) !important;
    border-color: rgba(13, 202, 240, 0.4) !important;
  }

  [data-theme="dark"] [class*="bg-warning"][class*="bg-opacity"] {
    background-color: rgba(255, 193, 7, 0.2) !important;
    border-color: rgba(255, 193, 7, 0.4) !important;
  }

  [data-theme="dark"] [class*="bg-danger"][class*="bg-opacity"] {
    background-color: rgba(220, 53, 69, 0.2) !important;
    border-color: rgba(220, 53, 69, 0.4) !important;
  }

  /* Textos dentro dos contadores */
  [data-theme="dark"] [class*="bg-opacity"] .text-primary,
  [data-theme="dark"] [class*="bg-opacity"] [class*="text-primary"] {
    color: #6ea8fe !important;
  }

  [data-theme="dark"] [class*="bg-opacity"] .text-success,
  [data-theme="dark"] [class*="bg-opacity"] [class*="text-success"] {
    color: #75b798 !important;
  }

  [data-theme="dark"] [class*="bg-opacity"] .text-info,
  [data-theme="dark"] [class*="bg-opacity"] [class*="text-info"] {
    color: #6edff6 !important;
  }

  [data-theme="dark"] [class*="bg-opacity"] .text-warning,
  [data-theme="dark"] [class*="bg-opacity"] [class*="text-warning"] {
    color: #ffc107 !important;
  }

  [data-theme="dark"] [class*="bg-opacity"] .text-danger,
  [data-theme="dark"] [class*="bg-opacity"] [class*="text-danger"] {
    color: #f1aeb5 !important;
  }

  /* Dropdowns */
  [data-theme="dark"] .dropdown-menu {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
  }

  [data-theme="dark"] .dropdown-item {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .dropdown-item:hover,
  [data-theme="dark"] .dropdown-item:focus {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
  }

  /* Paginação */
  [data-theme="dark"] .pagination .page-link {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .pagination .page-link:hover {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .pagination .page-item.active .page-link {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
  }

  /* Breadcrumbs */
  [data-theme="dark"] .breadcrumb {
    background-color: var(--bg-secondary) !important;
  }

  [data-theme="dark"] .breadcrumb-item a {
    color: #6ea8fe !important;
  }

  [data-theme="dark"] .breadcrumb-item.active {
    color: var(--text-secondary) !important;
  }

  /* Elementos de texto específicos */
  [data-theme="dark"] .text-dark {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .text-black {
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .text-black-50 {
    color: var(--text-secondary) !important;
  }

  [data-theme="dark"] .text-white-50 {
    color: var(--text-secondary) !important;
  }

  /* Spans e divs genéricos */
  [data-theme="dark"] span:not(.badge):not(.btn) {
    color: inherit;
  }

  [data-theme="dark"] div:not(.card):not(.modal):not(.alert):not(.dropdown) {
    color: inherit;
  }

  /* Overlay e backdrop */
  [data-theme="dark"] .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.7) !important;
  }

  /* Tooltips e popovers */
  [data-theme="dark"] .tooltip .tooltip-inner {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .popover {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .popover-header {
    background-color: var(--bg-primary) !important;
    border-bottom-color: var(--border-color) !important;
    color: var(--text-primary) !important;
  }

  [data-theme="dark"] .popover-body {
    color: var(--text-primary) !important;
  }

  /* Estilo do botão de toggle */
  #darkModeToggle {
    border: none;
    background: transparent;
    cursor: pointer;
    transition: transform 0.2s ease;
  }

  #darkModeToggle:hover {
    transform: scale(1.1);
  }

  #darkModeToggle:focus {
    outline: none;
    box-shadow: none;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const DARK_MODE_KEY = 'darkMode';
    const toggleBtn = document.getElementById("darkModeToggle");
    const darkModeIcon = document.getElementById("darkModeIcon");
    const htmlElement = document.documentElement;

    // Função para aplicar o tema
    function applyTheme(isDark, withTransition = true) {
      if (!withTransition) {
        htmlElement.style.transition = "none";
      }

      if (isDark) {
        htmlElement.setAttribute('data-theme', 'dark');
        darkModeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
      } else {
        htmlElement.removeAttribute('data-theme');
        darkModeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
      }

      if (!withTransition) {
        void htmlElement.offsetWidth;
        htmlElement.style.transition = "";
      }
    }

    // Sincronizar ícone com o tema já aplicado (evita flash do ícone)
    const currentTheme = htmlElement.getAttribute('data-theme');
    if (currentTheme === 'dark') {
      darkModeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
      // Aplicar cor do footer no modo escuro
      applyFooterDarkMode();
    }

    // Função para aplicar modo escuro no footer
    function applyFooterDarkMode() {
      const footer = document.querySelector('footer');
      if (footer) {
        const footerDiv = footer.querySelector('div');
        if (footerDiv && footerDiv.hasAttribute('style')) {
          // Remover ou atualizar o estilo inline de cor
          const currentStyle = footerDiv.getAttribute('style');
          const newStyle = currentStyle.replace(/color\s*:\s*[^;]+;?/gi, '');
          footerDiv.setAttribute('style', newStyle);
          footerDiv.style.color = 'var(--footer-text)';
        }
      }
    }

    // Alternar tema ao clicar
    toggleBtn.addEventListener("click", () => {
      const currentTheme = htmlElement.getAttribute('data-theme');
      const willBeDark = currentTheme !== 'dark';
      applyTheme(willBeDark, true);
      localStorage.setItem(DARK_MODE_KEY, willBeDark ? "1" : "0");
      
      // Aplicar/remover modo escuro no footer
      if (willBeDark) {
        applyFooterDarkMode();
      } else {
        // Restaurar estilo original se necessário
        const footer = document.querySelector('footer');
        if (footer) {
          const footerDiv = footer.querySelector('div');
          if (footerDiv) {
            footerDiv.style.color = '';
          }
        }
      }
    });
  });
</script>

