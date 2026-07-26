(function () {
    'use strict';

    const root = document.documentElement;
    const header = document.querySelector('[data-site-header]');
    const navToggle = document.querySelector('[data-nav-toggle]');
    const navigation = document.querySelector('[data-navigation]');
    const themeToggle = document.querySelector('[data-theme-toggle]');

    function syncAccentRgb() {
        const accent = getComputedStyle(root).getPropertyValue('--accent').trim();
        const match = /^#([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(accent);
        if (match) {
            root.style.setProperty('--accent-rgb', `${parseInt(match[1], 16)}, ${parseInt(match[2], 16)}, ${parseInt(match[3], 16)}`);
        }
    }

    function setHeaderState() {
        if (header) header.classList.toggle('scrolled', window.scrollY > 16);
    }

    function closeNavigation() {
        if (!navigation || !navToggle) return;
        navigation.classList.remove('open');
        navToggle.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
        navToggle.setAttribute('aria-label', 'Open navigation');
    }

    if (navToggle && navigation) {
        navToggle.addEventListener('click', function () {
            const isOpen = navigation.classList.toggle('open');
            navToggle.classList.toggle('open', isOpen);
            navToggle.setAttribute('aria-expanded', String(isOpen));
            navToggle.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
        });

        navigation.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeNavigation);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeNavigation();
                navToggle.focus();
            }
        });

        document.addEventListener('click', function (event) {
            if (!navigation.classList.contains('open')) return;
            if (!navigation.contains(event.target) && !navToggle.contains(event.target)) closeNavigation();
        });
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
            root.dataset.theme = nextTheme;
            themeToggle.setAttribute('aria-label', `Switch to ${nextTheme === 'dark' ? 'light' : 'dark'} theme`);
            try {
                localStorage.setItem('portfolio-theme', nextTheme);
            } catch (error) {}
        });
    }

    syncAccentRgb();
    setHeaderState();
    window.addEventListener('scroll', setHeaderState, { passive: true });

    const revealElements = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.09, rootMargin: '0px 0px -24px' });
        revealElements.forEach(function (element) { revealObserver.observe(element); });
    } else {
        revealElements.forEach(function (element) { element.classList.add('visible'); });
    }

    const homeSections = document.querySelectorAll('.home-page main section[id]');
    const navLinks = document.querySelectorAll('.primary-nav a[href*="#"]');
    if ('IntersectionObserver' in window && homeSections.length && navLinks.length) {
        const sectionObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                navLinks.forEach(function (link) {
                    const matches = link.hash === `#${entry.target.id}`;
                    if (matches) link.setAttribute('aria-current', 'page');
                    else link.removeAttribute('aria-current');
                });
            });
        }, { rootMargin: '-35% 0px -58% 0px', threshold: 0 });
        homeSections.forEach(function (section) { sectionObserver.observe(section); });
    }

    const projectCards = Array.from(document.querySelectorAll('[data-project-card]'));
    const projectSearch = document.querySelector('[data-project-search]');
    const filterButtons = Array.from(document.querySelectorAll('[data-project-filter]'));
    const emptyProjects = document.querySelector('[data-project-empty]');
    let activeFilter = 'all';

    function filterProjects() {
        if (!projectCards.length) return;
        const query = projectSearch ? projectSearch.value.trim().toLowerCase() : '';
        let visibleCount = 0;

        projectCards.forEach(function (card) {
            const categories = (card.dataset.categories || '').split('|');
            const matchesCategory = activeFilter === 'all'
                || (activeFilter === 'featured' && card.querySelector('.featured-badge'))
                || categories.includes(activeFilter);
            const matchesSearch = !query || (card.dataset.search || '').includes(query);
            const isVisible = Boolean(matchesCategory && matchesSearch);
            card.hidden = !isVisible;
            if (isVisible) visibleCount += 1;
        });

        if (emptyProjects) emptyProjects.hidden = visibleCount > 0;
    }

    if (projectSearch) {
        projectSearch.addEventListener('input', filterProjects);
    }

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            activeFilter = button.dataset.projectFilter || 'all';
            filterButtons.forEach(function (item) {
                const active = item === button;
                item.classList.toggle('active', active);
                item.setAttribute('aria-pressed', String(active));
            });
            filterProjects();
        });
    });

    const slider = document.querySelector('[data-testimonial-slider]');
    if (slider) {
        const slides = Array.from(slider.querySelectorAll('[data-testimonial]'));
        const previous = slider.querySelector('[data-testimonial-prev]');
        const next = slider.querySelector('[data-testimonial-next]');
        const dotsContainer = slider.querySelector('[data-testimonial-dots]');
        let currentSlide = 0;
        let rotationTimer;

        function showSlide(index) {
            currentSlide = (index + slides.length) % slides.length;
            slides.forEach(function (slide, slideIndex) {
                const active = slideIndex === currentSlide;
                slide.classList.toggle('active', active);
                slide.setAttribute('aria-hidden', String(!active));
            });
            if (dotsContainer) {
                dotsContainer.querySelectorAll('button').forEach(function (dot, dotIndex) {
                    dot.classList.toggle('active', dotIndex === currentSlide);
                    dot.setAttribute('aria-current', dotIndex === currentSlide ? 'true' : 'false');
                });
            }
        }

        function resetRotation() {
            window.clearInterval(rotationTimer);
            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                rotationTimer = window.setInterval(function () { showSlide(currentSlide + 1); }, 7000);
            }
        }

        slides.forEach(function (_, index) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = `slider-dot${index === 0 ? ' active' : ''}`;
            dot.setAttribute('aria-label', `Show testimonial ${index + 1}`);
            dot.addEventListener('click', function () { showSlide(index); resetRotation(); });
            dotsContainer.appendChild(dot);
        });

        previous.addEventListener('click', function () { showSlide(currentSlide - 1); resetRotation(); });
        next.addEventListener('click', function () { showSlide(currentSlide + 1); resetRotation(); });
        showSlide(0);
        resetRotation();
    }

    const contactForm = document.querySelector('[data-contact-form]');
    if (contactForm) {
        const fields = contactForm.querySelectorAll('input[required], textarea[required]');
        fields.forEach(function (field) {
            field.addEventListener('blur', function () {
                field.classList.toggle('user-invalid', !field.checkValidity());
            });
            field.addEventListener('input', function () {
                if (field.checkValidity()) field.classList.remove('user-invalid');
            });
        });

        contactForm.addEventListener('submit', function (event) {
            if (contactForm.checkValidity()) return;
            event.preventDefault();
            fields.forEach(function (field) {
                field.classList.toggle('user-invalid', !field.checkValidity());
            });
            const firstInvalid = contactForm.querySelector(':invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.reportValidity();
            }
        });
    }
}());
