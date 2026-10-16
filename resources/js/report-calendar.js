const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const isoDate = date => `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
const parseDate = value => value ? new Date(`${value}T12:00:00`) : new Date();

function initializeReportCalendar() {
    const inputs = document.querySelectorAll('[data-report-date]');
    if (!inputs.length || typeof HTMLDialogElement === 'undefined') return;

    const dialog = document.createElement('dialog');
    dialog.className = 'report-calendar';
    dialog.setAttribute('aria-labelledby', 'report-calendar-title');
    dialog.innerHTML = `
        <h2 id="report-calendar-title"></h2>
        <div class="report-calendar-nav">
            <button type="button" data-prev aria-label="Bulan sebelumnya">&#8249;</button>
            <select aria-label="Bulan">${months.map((month, index) => `<option value="${index}">${month}</option>`).join('')}</select>
            <input type="number" min="1" max="9999" aria-label="Tahun">
            <button type="button" data-next aria-label="Bulan berikutnya">&#8250;</button>
        </div>
        <div class="report-calendar-week" aria-hidden="true"><span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span></div>
        <div class="report-calendar-grid" role="group" aria-label="Pilih tanggal"></div>
        <div class="report-calendar-footer">
            <button type="button" data-clear>Hapus</button>
            <button type="button" data-today>Hari ini</button>
            <button type="button" data-close>Tutup</button>
        </div>`;
    document.body.append(dialog);

    const monthSelect = dialog.querySelector('select');
    const yearInput = dialog.querySelector('input');
    const grid = dialog.querySelector('.report-calendar-grid');
    let activeInput;
    let activeTrigger;
    let visibleDate;

    function render(focusDate) {
        const year = visibleDate.getFullYear();
        const month = visibleDate.getMonth();
        monthSelect.value = month;
        yearInput.value = year;
        grid.replaceChildren();
        const offset = new Date(year, month, 1).getDay();
        for (let i = 0; i < offset; i++) grid.append(document.createElement('span'));
        for (let day = 1; day <= new Date(year, month + 1, 0).getDate(); day++) {
            const date = new Date(year, month, day);
            const value = isoDate(date);
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = day;
            button.dataset.date = value;
            button.setAttribute('aria-label', `${day} ${months[month]} ${year}`);
            button.setAttribute('aria-pressed', String(value === activeInput.value));
            if (value === isoDate(new Date())) button.setAttribute('aria-current', 'date');
            button.addEventListener('click', () => choose(value));
            grid.append(button);
        }
        if (focusDate) grid.querySelector(`[data-date="${isoDate(focusDate)}"]`)?.focus();
    }

    function choose(value) {
        activeInput.value = value;
        activeInput.dispatchEvent(new Event('change', { bubbles: true }));
        dialog.close();
    }

    function changeMonth(delta) {
        const next = new Date(visibleDate.getFullYear(), visibleDate.getMonth() + delta, 1);
        if (next.getFullYear() < 100 || next.getFullYear() > 9999) return;
        visibleDate = next;
        render();
    }

    dialog.querySelector('[data-prev]').addEventListener('click', () => changeMonth(-1));
    dialog.querySelector('[data-next]').addEventListener('click', () => changeMonth(1));
    monthSelect.addEventListener('change', () => { visibleDate.setMonth(Number(monthSelect.value)); render(); });
    yearInput.addEventListener('change', () => {
        if (yearInput.checkValidity() && Number(yearInput.value) >= 100) {
            visibleDate.setFullYear(Number(yearInput.value));
            render();
        }
    });
    dialog.querySelector('[data-clear]').addEventListener('click', () => choose(''));
    dialog.querySelector('[data-today]').addEventListener('click', () => choose(isoDate(new Date())));
    dialog.querySelector('[data-close]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('close', () => {
        activeTrigger?.setAttribute('aria-expanded', 'false');
        activeTrigger?.focus();
    });
    grid.addEventListener('keydown', event => {
        if (!event.target.dataset.date) return;
        const steps = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 };
        if (!(event.key in steps)) return;
        event.preventDefault();
        const date = parseDate(event.target.dataset.date);
        date.setDate(date.getDate() + steps[event.key]);
        if (date.getFullYear() < 100 || date.getFullYear() > 9999) return;
        visibleDate = new Date(date.getFullYear(), date.getMonth(), 1);
        render(date);
    });

    inputs.forEach(input => {
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.id = `${input.id}-trigger`;
        trigger.className = `${input.className} text-left`;
        trigger.setAttribute('aria-haspopup', 'dialog');
        trigger.setAttribute('aria-expanded', 'false');
        const label = document.querySelector(`label[for="${input.id}"]`);
        const title = label.textContent.trim();
        label.htmlFor = trigger.id;
        function updateLabel() {
            trigger.textContent = input.value ? input.value.split('-').reverse().join('/') : 'Pilih tanggal';
            trigger.setAttribute('aria-label', `${title}: ${trigger.textContent}`);
        }
        input.addEventListener('change', updateLabel);
        updateLabel();
        trigger.addEventListener('click', () => {
            activeInput = input;
            activeTrigger = trigger;
            const selected = parseDate(input.value);
            visibleDate = new Date(selected.getFullYear(), selected.getMonth(), 1);
            dialog.querySelector('h2').textContent = title;
            render();
            trigger.setAttribute('aria-expanded', 'true');
            dialog.showModal();
            grid.querySelector(`[data-date="${isoDate(selected)}"]`)?.focus();
        });
        input.type = 'hidden';
        input.after(trigger);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeReportCalendar);
} else {
    initializeReportCalendar();
}
