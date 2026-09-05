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
            syncAccentRgb();
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

    const galleryTabs = Array.from(document.querySelectorAll('[data-gallery-tab]'));
    const galleryPanels = Array.from(document.querySelectorAll('[data-gallery-panel]'));
    if (galleryTabs.length && galleryPanels.length) {
        function activateGalleryTab(activeTab, updateHash = true) {
            const activeKey = activeTab.dataset.galleryTab;
            galleryTabs.forEach(function (tab) {
                const selected = tab === activeTab;
                tab.classList.toggle('active', selected);
                tab.setAttribute('aria-selected', selected ? 'true' : 'false');
                tab.tabIndex = selected ? 0 : -1;
            });
            galleryPanels.forEach(function (panel) {
                panel.hidden = panel.dataset.galleryPanel !== activeKey;
            });
            if (updateHash) history.replaceState(null, '', `#gallery-${activeKey}`);
        }

        galleryTabs.forEach(function (tab, index) {
            tab.addEventListener('click', function () { activateGalleryTab(tab); });
            tab.addEventListener('keydown', function (event) {
                if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
                event.preventDefault();
                let nextIndex = index;
                if (event.key === 'ArrowLeft') nextIndex = (index - 1 + galleryTabs.length) % galleryTabs.length;
                if (event.key === 'ArrowRight') nextIndex = (index + 1) % galleryTabs.length;
                if (event.key === 'Home') nextIndex = 0;
                if (event.key === 'End') nextIndex = galleryTabs.length - 1;
                activateGalleryTab(galleryTabs[nextIndex]);
                galleryTabs[nextIndex].focus();
            });
        });

        const initialTab = galleryTabs.find(function (tab) {
            return window.location.hash === `#gallery-${tab.dataset.galleryTab}`;
        }) || galleryTabs[0];
        activateGalleryTab(initialTab, false);
    }

    const galleryLightbox = document.querySelector('[data-gallery-lightbox]');
    const allGalleryLinks = Array.from(document.querySelectorAll('.personal-gallery-card > a'));
    if (galleryLightbox && allGalleryLinks.length) {
        const lightboxStage = galleryLightbox.querySelector('.gallery-lightbox-stage');
        const lightboxImageFrame = galleryLightbox.querySelector('.gallery-lightbox-image');
        const lightboxImage = galleryLightbox.querySelector('[data-gallery-lightbox-image]');
        const lightboxLabel = galleryLightbox.querySelector('[data-gallery-lightbox-label]');
        const lightboxTitle = galleryLightbox.querySelector('[data-gallery-lightbox-title]');
        const lightboxDescription = galleryLightbox.querySelector('[data-gallery-lightbox-description]');
        const lightboxPosition = galleryLightbox.querySelector('[data-gallery-lightbox-position]');
        const lightboxProgress = galleryLightbox.querySelector('[data-gallery-lightbox-progress]');
        const lightboxThumbnails = galleryLightbox.querySelector('[data-gallery-lightbox-thumbnails]');
        const closeLightbox = galleryLightbox.querySelector('[data-gallery-lightbox-close]');
        const previousPhoto = galleryLightbox.querySelector('[data-gallery-lightbox-prev]');
        const nextPhoto = galleryLightbox.querySelector('[data-gallery-lightbox-next]');
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        let activePhoto = 0;
        let lastGalleryTrigger = null;
        let photoTransitionTimer;
        let closeTimer;
        let touchStartX = 0;
        let touchStartY = 0;
        let galleryLinks = allGalleryLinks;
        let thumbnailButtons = [];

        function buildLightboxThumbnails() {
            lightboxThumbnails.replaceChildren();
            thumbnailButtons = galleryLinks.map(function (link, index) {
                const sourceImage = link.querySelector('img');
                const cardTitle = link.closest('.personal-gallery-card').querySelector('h4').textContent;
                const button = document.createElement('button');
                const thumbnail = document.createElement('img');
                button.type = 'button';
                button.className = 'gallery-lightbox-thumbnail';
                button.setAttribute('aria-label', `View photo ${index + 1}: ${cardTitle}`);
                thumbnail.src = link.dataset.thumbnail || sourceImage.currentSrc || sourceImage.src;
                thumbnail.alt = '';
                thumbnail.loading = 'lazy';
                thumbnail.width = 72;
                thumbnail.height = 54;
                button.appendChild(thumbnail);
                button.addEventListener('click', function () {
                    const direction = index > activePhoto ? 1 : -1;
                    showGalleryPhoto(index, direction);
                });
                lightboxThumbnails.appendChild(button);
                return button;
            });
        }

        function preloadNearbyPhotos() {
            [activePhoto - 1, activePhoto + 1].forEach(function (index) {
                const wrappedIndex = (index + galleryLinks.length) % galleryLinks.length;
                const preloadImage = new Image();
                preloadImage.src = galleryLinks[wrappedIndex].href;
            });
        }

        function renderGalleryPhoto() {
            const link = galleryLinks[activePhoto];
            const card = link.closest('.personal-gallery-card');
            const sourceImage = link.querySelector('img');
            lightboxImageFrame.classList.add('is-loading');
            lightboxImageFrame.setAttribute('aria-busy', 'true');
            lightboxImage.src = link.href;
            lightboxImage.alt = sourceImage.alt;
            lightboxImage.width = Number(sourceImage.getAttribute('width'));
            lightboxImage.height = Number(sourceImage.getAttribute('height'));
            lightboxLabel.textContent = card.querySelector('.eyebrow').textContent;
            lightboxTitle.textContent = card.querySelector('h4').textContent;
            lightboxDescription.textContent = card.querySelector('figcaption > p:last-child').textContent;
            lightboxPosition.textContent = `Photo ${activePhoto + 1} of ${galleryLinks.length}`;
            lightboxProgress.style.width = `${((activePhoto + 1) / galleryLinks.length) * 100}%`;
            thumbnailButtons.forEach(function (button, index) {
                const active = index === activePhoto;
                button.classList.toggle('active', active);
                if (active) button.setAttribute('aria-current', 'true');
                else button.removeAttribute('aria-current');
            });
            thumbnailButtons[activePhoto].scrollIntoView({
                behavior: reducedMotion.matches ? 'auto' : 'smooth',
                block: 'nearest',
                inline: 'center'
            });
            preloadNearbyPhotos();
        }

        function showGalleryPhoto(index, direction = 0, animate = true) {
            activePhoto = (index + galleryLinks.length) % galleryLinks.length;
            window.clearTimeout(photoTransitionTimer);
            lightboxStage.classList.remove('move-previous', 'move-next');

            if (!animate || reducedMotion.matches) {
                renderGalleryPhoto();
                return;
            }

            lightboxStage.classList.add(direction < 0 ? 'move-previous' : 'move-next', 'is-switching');
            photoTransitionTimer = window.setTimeout(function () {
                renderGalleryPhoto();
                requestAnimationFrame(function () {
                    lightboxStage.classList.remove('is-switching');
                });
            }, 130);
        }

        function requestLightboxClose() {
            if (galleryLightbox.classList.contains('is-closing')) return;
            if (reducedMotion.matches) {
                galleryLightbox.close();
                return;
            }
            galleryLightbox.classList.add('is-closing');
            closeTimer = window.setTimeout(function () { galleryLightbox.close(); }, 180);
        }

        allGalleryLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                lastGalleryTrigger = link;
                const activePanel = link.closest('[data-gallery-panel]');
                galleryLinks = activePanel
                    ? Array.from(activePanel.querySelectorAll('.personal-gallery-card > a'))
                    : allGalleryLinks;
                activePhoto = galleryLinks.indexOf(link);
                buildLightboxThumbnails();
                galleryLightbox.showModal();
                renderGalleryPhoto();
                document.body.classList.add('lightbox-open');
                closeLightbox.focus();
            });
        });

        lightboxImage.addEventListener('load', function () {
            lightboxImageFrame.classList.remove('is-loading');
            lightboxImageFrame.setAttribute('aria-busy', 'false');
        });

        lightboxImage.addEventListener('error', function () {
            lightboxImageFrame.classList.remove('is-loading');
            lightboxImageFrame.setAttribute('aria-busy', 'false');
        });

        closeLightbox.addEventListener('click', requestLightboxClose);
        previousPhoto.addEventListener('click', function () { showGalleryPhoto(activePhoto - 1, -1); });
        nextPhoto.addEventListener('click', function () { showGalleryPhoto(activePhoto + 1, 1); });

        galleryLightbox.addEventListener('click', function (event) {
            if (event.target === galleryLightbox) requestLightboxClose();
        });

        galleryLightbox.addEventListener('cancel', function (event) {
            event.preventDefault();
            requestLightboxClose();
        });

        galleryLightbox.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft') showGalleryPhoto(activePhoto - 1, -1);
            if (event.key === 'ArrowRight') showGalleryPhoto(activePhoto + 1, 1);
            if (event.key === 'Escape') {
                event.preventDefault();
                requestLightboxClose();
            }
        });

        lightboxStage.addEventListener('touchstart', function (event) {
            touchStartX = event.changedTouches[0].clientX;
            touchStartY = event.changedTouches[0].clientY;
        }, { passive: true });

        lightboxStage.addEventListener('touchend', function (event) {
            const horizontalDistance = event.changedTouches[0].clientX - touchStartX;
            const verticalDistance = event.changedTouches[0].clientY - touchStartY;
            if (Math.abs(horizontalDistance) < 45 || Math.abs(horizontalDistance) < Math.abs(verticalDistance) * 1.2) return;
            if (horizontalDistance < 0) showGalleryPhoto(activePhoto + 1, 1);
            else showGalleryPhoto(activePhoto - 1, -1);
        }, { passive: true });

        galleryLightbox.addEventListener('close', function () {
            window.clearTimeout(closeTimer);
            window.clearTimeout(photoTransitionTimer);
            document.body.classList.remove('lightbox-open');
            galleryLightbox.classList.remove('is-closing');
            lightboxStage.classList.remove('is-switching', 'move-previous', 'move-next');
            lightboxImage.removeAttribute('src');
            if (lastGalleryTrigger) lastGalleryTrigger.focus();
        });
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


// Static-build contact form: validate locally, then compose an email in the visitor's mail app.
(function () {
    'use strict';
    const form = document.querySelector('[data-contact-form]');
    if (!form || !form.getAttribute('action')?.startsWith('mailto:')) return;

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!form.checkValidity()) return;

        const data = new FormData(form);
        const subject = String(data.get('subject') || 'Portfolio enquiry');
        const body = [
            `Name: ${data.get('name') || ''}`,
            `Email: ${data.get('email') || ''}`,
            '',
            String(data.get('message') || '')
        ].join('\n');
        const recipient = form.getAttribute('action').replace(/^mailto:/, '');
        window.location.href = `mailto:${recipient}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    });
}());