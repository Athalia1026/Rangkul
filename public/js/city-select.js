(() => {
    const dropdown = document.getElementById('cityDropdown');
    if (!dropdown) return;

    const cityInput = document.getElementById('cityInput');
    const citySearch = document.getElementById('citySearch');
    const cityList = document.getElementById('cityList');
    const cityEmpty = document.getElementById('cityEmpty');
    const cityOptionTemplate = document.getElementById('cityOptionTemplate');
    const cityResultLimit = 50;
    let cities = null;
    let citiesRequest = null;
    let activeCityIndex = -1;

    function normalizeCity(text) {
        return text.toLowerCase().replace(/\s+/g, ' ').trim();
    }

    function loadCities() {
        citiesRequest ??= fetch(dropdown.dataset.citiesUrl)
            .then(response => {
                if (!response.ok) throw new Error('Daftar kota gagal dimuat.');
                return response.json();
            })
            .then(names => {
                cities = names.map(name => ({
                    name,
                    full: normalizeCity(name),
                    base: normalizeCity(name.replace(/^(Kabupaten|Kota)( Administrasi)? /, '')),
                }));
            })
            .catch(() => {
                citiesRequest = null;
            });

        return citiesRequest;
    }

    function cityMatchRank(city, query) {
        if (!query) return 0;
        if (city.base.startsWith(query) || city.full.startsWith(query)) return 0;
        if (city.base.includes(' ' + query)) return 1;
        if (city.full.includes(query)) return 2;

        let position = 0;
        for (const char of query.replace(/ /g, '')) {
            position = city.base.indexOf(char, position) + 1;
            if (!position) return null;
        }
        return 3;
    }

    function renderedCityOptions() {
        return [...cityList.querySelectorAll('.city-option')];
    }

    function setActiveCity(index, scroll = true) {
        const options = renderedCityOptions();
        activeCityIndex = options.length ? (index + options.length) % options.length : -1;

        options.forEach((option, i) => {
            option.dataset.active = i === activeCityIndex ? 'true' : 'false';
        });
        if (scroll) options[activeCityIndex]?.scrollIntoView({ block: 'nearest' });
    }

    function showCityMessage(message) {
        cityEmpty.textContent = message;
        cityEmpty.classList.toggle('hidden', !message);
    }

    function renderCities(query) {
        renderedCityOptions().forEach(option => option.remove());

        if (!cities) {
            showCityMessage(citiesRequest ? 'Memuat daftar kota...' : 'Gagal memuat daftar kota, coba lagi.');
            return;
        }

        const matches = cities
            .map(city => ({ city, rank: cityMatchRank(city, query) }))
            .filter(match => match.rank !== null)
            .sort((a, b) => a.rank - b.rank);
        const selected = cityInput.value;
        const fragment = document.createDocumentFragment();

        matches.slice(0, cityResultLimit).forEach(({ city }) => {
            const option = cityOptionTemplate.content.firstElementChild.cloneNode(true);
            option.dataset.value = city.name;
            option.textContent = city.name;
            option.setAttribute('aria-selected', city.name === selected ? 'true' : 'false');
            fragment.appendChild(option);
        });
        cityList.insertBefore(fragment, cityEmpty);

        if (!matches.length) showCityMessage('Kota tidak ditemukan');
        else if (matches.length > cityResultLimit) showCityMessage('Ketik nama kota untuk melihat hasil lainnya');
        else showCityMessage('');

        setActiveCity(query ? 0 : renderedCityOptions().findIndex(option => option.dataset.value === selected));
    }

    window.toggleCityList = async function (open) {
        const shouldOpen = open ?? cityList.classList.contains('hidden');

        cityList.classList.toggle('hidden', !shouldOpen);
        citySearch.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');

        if (!shouldOpen) {
            citySearch.value = cityInput.value;
            return;
        }

        renderCities('');
        if (!cities) {
            await loadCities();
            if (!cityList.classList.contains('hidden')) window.filterCities();
        }
    };

    window.filterCities = function () {
        cityList.classList.remove('hidden');
        citySearch.setAttribute('aria-expanded', 'true');

        const query = normalizeCity(citySearch.value);
        const selected = cityInput.value;
        renderCities(query === normalizeCity(selected) ? '' : query);
    };

    window.selectCity = function (name) {
        cityInput.value = name;
        window.toggleCityList(false);
    };

    citySearch.addEventListener('keydown', event => {
        const isOpen = !cityList.classList.contains('hidden');

        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            if (!isOpen) window.toggleCityList(true);
            setActiveCity(activeCityIndex + (event.key === 'ArrowDown' ? 1 : -1));
        } else if (event.key === 'Enter' && isOpen) {
            event.preventDefault();
            const active = renderedCityOptions()[activeCityIndex];
            if (active) window.selectCity(active.dataset.value);
        } else if (event.key === 'Escape' && isOpen) {
            window.toggleCityList(false);
        }
    });

    cityList.addEventListener('mousemove', event => {
        const option = event.target.closest('.city-option');
        if (option) setActiveCity(renderedCityOptions().indexOf(option), false);
    });

    citySearch.addEventListener('blur', () => window.toggleCityList(false));

    document.addEventListener('click', event => {
        if (!dropdown.contains(event.target) && !cityList.classList.contains('hidden')) {
            window.toggleCityList(false);
        }
    });
})();
