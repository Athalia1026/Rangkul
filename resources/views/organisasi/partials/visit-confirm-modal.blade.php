{{--
    Pop up konfirmasi terima/tolak kunjungan.

    Tombol pemicu cukup diberi atribut:
      data-visit-status="dikonfirmasi|ditolak"
      data-respond-url="{{ route('organisasi.kunjungan.respond', $visit->id) }}"
      data-visitor="Nama donatur"
      data-visit-date="24 Oktober 2026"

    Jika halaman punya textarea #pesanOrganisasi, isinya dipakai sebagai pesan (pratinjau saja).
    Jika tidak ada, pesan dapat diketik langsung di dalam pop up.
--}}
<div
    id="visitConfirmModal"
    class="hidden
           fixed inset-0 z-50
           items-center justify-center
           bg-black/50
           px-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="visitConfirmTitle"
>

    <div
        class="w-full
               max-w-[720px]
               bg-white
               rounded-[28px]
               shadow-2xl
               px-12 py-12
               text-center"
    >

        <div
            id="visitConfirmIcon"
            class="w-[110px]
                   h-[110px]
                   mx-auto
                   rounded-full
                   flex items-center justify-center
                   text-[50px]"
        ></div>


        <h2
            id="visitConfirmTitle"
            class="mt-8
                   text-[38px]
                   font-bold
                   text-gray-950"
        ></h2>


        <p
            id="visitConfirmText"
            class="mt-4
                   text-[23px]
                   text-gray-600
                   leading-relaxed"
        ></p>


        {{-- PRATINJAU PESAN (halaman yang sudah punya kolom catatan) --}}
        <div
            id="visitConfirmPreview"
            class="mt-8
                   bg-[#F3F5F4]
                   border border-gray-200
                   rounded-[16px]
                   px-7 py-5
                   text-left"
        >

            <p class="text-[20px] font-semibold text-gray-500">
                Pesan untuk pengunjung
            </p>

            <p
                id="visitConfirmMessage"
                class="mt-2
                       text-[22px]
                       text-gray-900
                       font-medium
                       break-words"
            ></p>

        </div>


        {{-- INPUT PESAN (halaman tanpa kolom catatan) --}}
        <div id="visitConfirmInputWrapper" class="hidden mt-8 text-left">

            <label
                for="visitConfirmInput"
                class="text-[20px] font-semibold text-gray-500"
            >
                Pesan untuk pengunjung
            </label>

            <textarea
                id="visitConfirmInput"
                maxlength="255"
                rows="3"
                class="mt-3
                       w-full
                       bg-white
                       border border-gray-300
                       rounded-[16px]
                       px-6 py-4
                       text-[22px]
                       text-gray-900
                       leading-relaxed
                       outline-none
                       resize-none
                       focus:border-[#08703F]
                       focus:ring-2
                       focus:ring-[#08703F]/10"
            ></textarea>

        </div>


        <div
            class="mt-10
                   flex flex-col
                   sm:flex-row
                   justify-center
                   gap-5"
        >

            <button
                type="button"
                data-modal-cancel
                class="min-w-[200px]
                       h-[72px]
                       bg-white
                       border-2 border-gray-300
                       text-gray-700
                       rounded-[18px]
                       text-[23px]
                       font-semibold
                       hover:bg-gray-100
                       transition"
            >
                Batal
            </button>


            <button
                type="button"
                id="visitConfirmSubmit"
                class="min-w-[240px]
                       h-[72px]
                       inline-flex
                       items-center
                       justify-center
                       gap-3
                       text-white
                       rounded-[18px]
                       text-[23px]
                       font-semibold
                       disabled:opacity-60
                       transition"
            ></button>

        </div>

    </div>

</div>


@push('scripts')
<script>
    (function () {
        const modal = document.getElementById('visitConfirmModal');
        const icon = document.getElementById('visitConfirmIcon');
        const title = document.getElementById('visitConfirmTitle');
        const text = document.getElementById('visitConfirmText');
        const preview = document.getElementById('visitConfirmPreview');
        const messagePreview = document.getElementById('visitConfirmMessage');
        const inputWrapper = document.getElementById('visitConfirmInputWrapper');
        const messageInput = document.getElementById('visitConfirmInput');
        const submitButton = document.getElementById('visitConfirmSubmit');
        const pageMessageField = document.getElementById('pesanOrganisasi');
        const iconBaseClass = icon.className;

        const variants = {
            dikonfirmasi: {
                icon: '<i class="fa-solid fa-check"></i>',
                iconClass: 'bg-[#DDF0E9] text-[#08703F]',
                title: 'Terima Kunjungan?',
                action: 'akan dikonfirmasi',
                button: 'Ya, Terima',
                buttonClass: 'bg-[#08703F] hover:bg-[#065D35]',
                defaultMessage: 'Kunjungan Anda telah kami terima.',
            },
            ditolak: {
                icon: '<i class="fa-solid fa-xmark"></i>',
                iconClass: 'bg-[#FBE7E7] text-[#D94A4A]',
                title: 'Tolak Kunjungan?',
                action: 'akan ditolak',
                button: 'Ya, Tolak',
                buttonClass: 'bg-red-500 hover:bg-red-600',
                defaultMessage: 'Mohon maaf, kunjungan belum dapat kami terima.',
            },
        };

        let selected = null;

        function currentMessage() {
            const typed = pageMessageField ? pageMessageField.value : messageInput.value;
            return typed.trim() || variants[selected.status].defaultMessage;
        }

        function openModal(trigger) {
            const variant = variants[trigger.dataset.visitStatus];
            selected = {
                status: trigger.dataset.visitStatus,
                url: trigger.dataset.respondUrl,
            };

            icon.className = iconBaseClass + ' ' + variant.iconClass;
            icon.innerHTML = variant.icon;
            title.textContent = variant.title;
            text.textContent = 'Kunjungan dari ' + (trigger.dataset.visitor || 'donatur')
                + (trigger.dataset.visitDate ? ' pada ' + trigger.dataset.visitDate : '')
                + ' ' + variant.action + '.';

            if (pageMessageField) {
                preview.classList.remove('hidden');
                inputWrapper.classList.add('hidden');
                messagePreview.textContent = currentMessage();
            } else {
                preview.classList.add('hidden');
                inputWrapper.classList.remove('hidden');
                messageInput.value = '';
                messageInput.placeholder = variant.defaultMessage;
            }

            submitButton.textContent = variant.button;
            submitButton.classList.remove('bg-[#08703F]', 'hover:bg-[#065D35]', 'bg-red-500', 'hover:bg-red-600');
            submitButton.classList.add(...variant.buttonClass.split(' '));
            submitButton.disabled = false;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            (pageMessageField ? submitButton : messageInput).focus();
        }

        function closeModal() {
            if (submitButton.disabled) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.querySelectorAll('[data-visit-status][data-respond-url]').forEach(function (button) {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        modal.querySelector('[data-modal-cancel]').addEventListener('click', closeModal);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        submitButton.addEventListener('click', async function () {
            const message = currentMessage();

            submitButton.disabled = true;
            submitButton.textContent = 'Memproses...';

            try {
                await orgRequest(selected.url, {
                    method: 'PATCH',
                    body: { status: selected.status, pesan_organisasi: message },
                });
                window.location.reload();
            } catch (error) {
                submitButton.disabled = false;
                closeModal();
                orgFlash(error.message, 'error');
            }
        });
    })();
</script>
@endpush
