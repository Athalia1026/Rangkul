const donorPage = document.querySelector('[data-donor-page]');

if (donorPage) {
    const panel = document.getElementById('donor-panel');
    const panelTitle = document.getElementById('donor-panel-title');
    const panelContent = document.getElementById('donor-panel-content');
    const dropdown = document.getElementById('donor-profile-dropdown');
    const avatarButton = document.getElementById('donor-avatar-button');
    let currentUser = null;
    let panelRequest = 0;
    let previousOverflow = '';

    const goToLogin = () => window.location.replace(donorPage.dataset.loginUrl);
    const clearAuth = () => {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
    };
    async function api(path, method = 'GET') {
        const token = localStorage.getItem('auth_token');
        if (!token) { goToLogin(); throw new Error('Silakan masuk sebagai donatur.'); }
        const response = await fetch(path, {
            method,
            headers: { Accept: 'application/json', Authorization: `Bearer ${token}` },
        });
        if (response.status === 401) { clearAuth(); goToLogin(); throw new Error('Sesi berakhir. Silakan masuk kembali.'); }
        if (!response.ok) throw new Error('Data belum dapat dimuat. Silakan coba kembali.');
        return response.status === 204 ? {} : response.json();
    }
    const accountReady = api('/api/me').then(result => {
        const user = result.user;
        if (user?.account_type !== 'donatur') {
            window.location.replace(user?.account_type === 'organisasi' ? '/organisasi/dashboard' : '/manager/home');
            return null;
        }
        currentUser = user;
        document.getElementById('donor-name').textContent = user.nama;
        document.getElementById('donor-initials').textContent = (user.nama || 'D').split(/\s+/).slice(0, 2).map(part => part[0]).join('').toUpperCase();
        if (user.profile_photo) {
            const image = document.getElementById('donor-avatar-image');
            const url = new URL(user.profile_photo.startsWith('http') ? user.profile_photo : `/storage/${user.profile_photo.replace(/^\//, '')}`, window.location.origin);
            if (['http:', 'https:'].includes(url.protocol)) {
                image.onload = () => { image.hidden = false; document.getElementById('donor-initials').hidden = true; };
                image.src = url.href;
            }
        }
        return user;
    }).catch(error => {
        document.getElementById('donor-name').textContent = 'Akun donatur';
        document.getElementById('donor-account-error').textContent = error.message;
        return null;
    });

    function closeDropdown() { dropdown.hidden = true; avatarButton.setAttribute('aria-expanded', 'false'); }
    avatarButton.addEventListener('click', () => { dropdown.hidden = !dropdown.hidden; avatarButton.setAttribute('aria-expanded', String(!dropdown.hidden)); });
    document.addEventListener('click', event => { if (!event.target.closest('.donor-profile-menu')) closeDropdown(); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') closeDropdown(); });
    document.getElementById('donor-logout').addEventListener('click', async event => {
        const button = event.currentTarget;
        button.disabled = true;
        try { await api('/api/logout', 'POST'); clearAuth(); goToLogin(); }
        catch (error) { document.getElementById('donor-account-error').textContent = error.message; button.disabled = false; }
    });

    function paragraph(text, target = panelContent) {
        const element = document.createElement('p');
        element.textContent = text;
        target.append(element);
    }
    document.querySelectorAll('[data-donor-panel]').forEach(button => button.addEventListener('click', async () => {
        closeDropdown();
        const type = button.dataset.donorPanel;
        const request = ++panelRequest;
        panelTitle.textContent = { history: 'Riwayat Donasi', profile: 'Profil Donatur', notifications: 'Notifikasi', contact: 'Kontak', privacy: 'Kebijakan Privasi', terms: 'Syarat dan Ketentuan' }[type];
        panelContent.replaceChildren();
        previousOverflow = document.documentElement.style.overflow;
        panel.showModal();
        document.documentElement.style.overflow = 'hidden';
        if (!['profile', 'history'].includes(type)) {
            paragraph({ notifications: 'Notifikasi akun belum tersedia.', contact: 'Informasi kontak akan tersedia di halaman ini.', privacy: 'Kebijakan privasi akan tersedia di halaman ini.', terms: 'Syarat dan ketentuan akan tersedia di halaman ini.' }[type]);
            return;
        }
        paragraph('Memuat...');
        try {
            await accountReady;
            if (!currentUser) throw new Error('Akun belum dapat diverifikasi. Muat ulang halaman untuk mencoba kembali.');
            const result = type === 'history' ? await api('/api/donors/activities') : null;
            if (request !== panelRequest || !panel.open) return;
            panelContent.replaceChildren();
            if (type === 'profile') {
                paragraph(currentUser.nama);
                paragraph(currentUser.email);
                paragraph(`Donatur ${currentUser.donor?.tipe || 'individu'}`);
            } else {
                const donations = result.data?.riwayat_donasi || [];
                if (!donations.length) paragraph('Belum ada riwayat donasi. Yuk, mulai berbagi kebaikan!');
                donations.forEach(donation => {
                    const item = document.createElement('div');
                    item.className = 'donor-history-item';
                    paragraph(donation.campaign_name, item);
                    paragraph(`${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(donation.amount)} · ${donation.display_status.replaceAll('_', ' ')}`, item);
                    panelContent.append(item);
                });
            }
        } catch (error) { if (request === panelRequest && panel.open) { panelContent.replaceChildren(); paragraph(error.message); } }
    }));
    document.querySelector('[data-panel-close]').addEventListener('click', () => panel.close());
    panel.addEventListener('close', () => { panelRequest++; document.documentElement.style.overflow = previousOverflow; });
    panel.addEventListener('click', event => {
        const bounds = panel.getBoundingClientRect();
        if (event.target === panel && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) panel.close();
    });

    const slides = [...document.querySelectorAll('[data-hero-slide]')];
    let activeIndex = 0;
    function showSlide(index) {
        activeIndex = (index + slides.length) % slides.length;
        slides.forEach((slide, position) => {
            const active = position === activeIndex;
            slide.classList.toggle('is-active', active);
            slide.classList.toggle('is-next', !active && position === (activeIndex + 1) % slides.length);
            slide.classList.toggle('is-previous', !active && slides.length > 2 && position === (activeIndex - 1 + slides.length) % slides.length);
            slide.inert = !active;
            slide.setAttribute('aria-hidden', String(!active));
        });
    }
    document.querySelectorAll('[data-hero-step]').forEach(button => button.addEventListener('click', () => showSlide(activeIndex + Number(button.dataset.heroStep))));
    document.querySelectorAll('.donor-track').forEach(track => {
        const controls = [...document.querySelectorAll(`[data-track-step][aria-controls="${track.id}"]`)];
        const moveTrack = direction => {
            const card = track.querySelector('.donor-card');
            const step = card ? card.getBoundingClientRect().width + parseFloat(getComputedStyle(track).columnGap) : track.clientWidth;
            track.scrollBy({ left: direction * step, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' });
        };
        const updateControls = () => controls.forEach(button => {
            button.disabled = Number(button.dataset.trackStep) < 0
                ? track.scrollLeft <= 2
                : track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
        });
        controls.forEach(button => button.addEventListener('click', () => moveTrack(Number(button.dataset.trackStep))));
        track.addEventListener('scroll', updateControls, { passive: true });
        new ResizeObserver(updateControls).observe(track);
        updateControls();
        track.addEventListener('keydown', event => {
            if (event.target !== track || !['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
            event.preventDefault();
            moveTrack(event.key === 'ArrowRight' ? 1 : -1);
        });
    });
}
