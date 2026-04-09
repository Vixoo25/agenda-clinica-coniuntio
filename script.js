document.addEventListener('DOMContentLoaded', function () {

    const mainContent = document.querySelector('main');

    // --- Lógica para la transición suave de páginas (fade-in) ---
    if (mainContent) mainContent.classList.add('fade-in');


    // --- Lógica para el menú móvil ---
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeMobileMenu = document.getElementById('close-mobile-menu');

    function openMobileMenu() {
        mobileMenu.classList.remove('hidden');
        mobileMenu.classList.add('show');
    }

    function closeMobileMenuFunc() {
        mobileMenu.classList.add('hide');
        setTimeout(() => {
            mobileMenu.classList.remove('show');
            mobileMenu.classList.remove('hide');
            mobileMenu.classList.add('hidden');
        }, 300);
    }

    if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
    if (closeMobileMenu) closeMobileMenu.addEventListener('click', closeMobileMenuFunc);

    // Cerrar menú móvil al hacer click en un link
    const mobileLinks = document.querySelectorAll('.mobile-nav-links a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenuFunc);
    });

    // --- Lógica de Revelado al Scroll (Intersection Observer) ---
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                revealObserver.unobserve(entry.target); // Solo se anima la primera vez
            }
        });
    }, { threshold: 0.1 });

    revealElements.forEach(el => {
        // La clase 'reveal' se añade directamente en el HTML para asegurar el estado inicial oculto y evitar FOUC
        revealObserver.observe(el);
    });

    // --- Lógica para el efecto de scroll en la barra de navegación ---
    const navbar = document.querySelector('.navbar-overlay');

    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }

    // --- Lógica para la transición suave de páginas (fade-out) ---
    const allLinks = document.querySelectorAll('a:not([href^="#"])');

    allLinks.forEach(link => {
        if (link.target === '_blank') return;

        link.addEventListener('click', function (e) {
            const url = this.href;
            if (url === window.location.href) {
                e.preventDefault();
                return;
            }

            e.preventDefault();           
            if (mainContent) mainContent.classList.remove('fade-in'); 
            setTimeout(() => {
                window.location.href = url;
            }, 200);
        });
    });

    // --- Lógica de Modales (Generalizada) ---
    function openModal(modal) {
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Evita scroll de fondo
        }
    }

    function closeModal(modal) {
        if (modal) {
            modal.classList.add('hide');
            setTimeout(() => {
                modal.classList.remove('show');
                modal.classList.remove('hide');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    }

    // Modal de Login
    const loginBtn = document.getElementById('login-btn');
    const mobileLoginBtn = document.getElementById('mobile-login-btn');
    const loginModal = document.getElementById('login-modal');
    const closeLogin = document.querySelector('#login-modal .close-modal');

    if (loginBtn) loginBtn.addEventListener('click', (e) => { e.preventDefault(); openModal(loginModal); });
    if (mobileLoginBtn) mobileLoginBtn.addEventListener('click', (e) => { e.preventDefault(); openModal(loginModal); });
    if (closeLogin) closeLogin.addEventListener('click', () => closeModal(loginModal));

    // Modal de Agenda
    const agendaBtn = document.getElementById('agenda-btn');
    const mobileAgendaBtn = document.getElementById('mobile-agenda-btn');
    const agendaModal = document.getElementById('agenda-modal');
    const closeAgenda = document.getElementById('close-agenda');
    const cancelAgendaBtn = document.getElementById('btn-cancelar-agenda');
    const agendaForm = document.getElementById('agenda-form');

    if (agendaBtn) agendaBtn.addEventListener('click', (e) => { e.preventDefault(); openModal(agendaModal); });
    if (mobileAgendaBtn) mobileAgendaBtn.addEventListener('click', (e) => { e.preventDefault(); openModal(agendaModal); });
    
    if (closeAgenda) closeAgenda.addEventListener('click', () => closeModal(agendaModal));

    // Detectar si venimos de otra página queriendo abrir la agenda
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('openAgenda') === 'true') {
        openModal(agendaModal);
    }

    if (cancelAgendaBtn) cancelAgendaBtn.addEventListener('click', () => {
        agendaForm.reset();
        closeModal(agendaModal);
    });

    // Cerrar modales al hacer clic fuera
    window.addEventListener('click', (e) => {
        if (e.target === loginModal) closeModal(loginModal);
        if (e.target === agendaModal) closeModal(agendaModal);
    });

    // Manejo del envío (Preventivo por ahora)
    if (agendaForm) {
        agendaForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert("Formulario listo para enviar. ¡Datos capturados!");
            // Aquí irá la lógica futura de envío
            agendaForm.reset();
            closeModal(agendaModal);
        });
    }
});
