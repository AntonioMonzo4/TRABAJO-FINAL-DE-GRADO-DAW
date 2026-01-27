(function () {
  function init() {
    const header = document.querySelector('.header');
    const highHeader = document.querySelector('.highHeader');
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.getElementById('mainNav');
    const navList = nav ? nav.querySelector('.navbar-enlaces') : null;
    const overlay = document.querySelector('.mobile-menu-overlay');

    if (!header || !highHeader || !toggle || !nav || !navList) return;

    let injected = false;

    function setMobileHeaderHeight() {
      const h = highHeader.offsetHeight || 72;
      document.documentElement.style.setProperty('--mobileHeaderH', h + 'px');
    }

    function setOpen(isOpen) {
      header.classList.toggle('nav-open', isOpen);
      toggle.setAttribute('aria-expanded', String(isOpen));

      // Bloquear scroll del body cuando está abierto (opcional pero recomendable)
      document.body.style.overflow = isOpen ? 'hidden' : '';
    }

    function isMobile() {
      return window.innerWidth <= 1024;
    }

    function clearInjected() {
      navList.querySelectorAll('.js-mobile-injected').forEach(el => el.remove());
      injected = false;
    }

    function injectItem(liContentNode) {
      const li = document.createElement('li');
      li.className = 'js-mobile-injected';
      li.appendChild(liContentNode);
      navList.appendChild(li);
    }

    function buildDivider() {
      const div = document.createElement('div');
      div.className = 'js-mobile-injected';
      div.style.height = '1px';
      div.style.background = 'rgba(0,0,0,0.08)';
      div.style.margin = '8px 0';
      navList.appendChild(div);
    }

    function ensureInjected() {
      if (!isMobile()) {
        clearInjected();
        return;
      }
      if (injected) return;

      // --- Admin (clonar si existe) ---
      const adminDesktop = document.querySelector('.admin-link');
      if (adminDesktop) {
        const a = adminDesktop.cloneNode(true);
        injectItem(a);
      }

      // Separador si hay admin
      if (adminDesktop) buildDivider();

      // --- Usuario: login/registro o menú de usuario ---
      const loginBtn = document.querySelector('.login-btn');
      const registerBtn = document.querySelector('.register-btn');
      const userWelcomeBtn = document.querySelector('.user-welcome');
      const userMenu = document.querySelector('.user-menu');

      if (loginBtn && registerBtn) {
        injectItem(loginBtn.cloneNode(true));
        injectItem(registerBtn.cloneNode(true));
      } else if (userWelcomeBtn && userMenu) {
        // “Hola, X” como texto (no botón)
        const hello = document.createElement('div');
        hello.className = 'js-mobile-injected';
        hello.style.padding = '12px';
        hello.style.fontWeight = '600';
        hello.textContent = userWelcomeBtn.textContent.replace(/\s+/g, ' ').trim();
        navList.appendChild(hello);

        // Clonar links del user-menu
        userMenu.querySelectorAll('a').forEach(a0 => {
          const a = a0.cloneNode(true);
          injectItem(a);
        });
      }

      buildDivider();

      // --- Carrito (clonar link + contador) ---
      const carrito = document.querySelector('.carrito');
      if (carrito) {
        // Clonamos todo el bloque para mantener el contador
        const carritoClone = carrito.cloneNode(true);
        carritoClone.style.display = 'block';
        carritoClone.style.padding = '12px';
        carritoClone.style.background = 'transparent';
        carritoClone.style.borderRadius = '12px';

        // Asegurar que el count no queda absolute raro dentro del menú
        const count = carritoClone.querySelector('.carrito-count');
        if (count) {
          count.style.position = 'static';
          count.style.marginLeft = '8px';
          count.style.width = 'auto';
          count.style.height = 'auto';
          count.style.display = 'inline-flex';
          count.style.padding = '2px 8px';
          count.style.borderRadius = '999px';
        }

        injectItem(carritoClone);
      }

      injected = true;
    }

    // Eventos
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      ensureInjected();
      const isOpen = header.classList.contains('nav-open');
      setOpen(!isOpen);
    });

    nav.addEventListener('click', function (e) {
      const link = e.target.closest('a');
      if (link) setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setOpen(false);
    });

    if (overlay) {
      overlay.addEventListener('click', function () {
        setOpen(false);
      });
    }

    window.addEventListener('resize', function () {
      setMobileHeaderHeight();
      ensureInjected();
      if (!isMobile()) setOpen(false);
    });

    // Init
    setMobileHeaderHeight();
    ensureInjected();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
