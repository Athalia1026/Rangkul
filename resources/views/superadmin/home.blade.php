<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />
    <title>Dashboard Super Admin</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome Icons CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Font Plus Jakarta Sans -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F5F7F4; }
        .text-rangkul-green { color: #086538; }
        .bg-rangkul-green { background-color: #086538; }
        .bg-light-green { background-color: #F5F7F4; }
    </style>
</head>
<body class="text-black-800">

    @include('superadmin.layouts.navbar')

    <!-- ========================================================= -->
    <!-- MAIN CONTENT -->
    <!-- ========================================================= -->
    <main class="w-full max-w-[1200px] mx-auto px-8 py-6 space-y-6">

        <div id="dashboard-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>

        <!-- Header Dashboard & Counter Cards -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard Super Admin</h1>
                <p class="text-sm text-gray-500 mt-1">Monitoring aktivitas dan kinerja sistem</p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Total Staff -->
                <div class="bg-white rounded-xl border border-gray-200/90 shadow-sm px-5 py-3.5 w-60 flex items-center justify-between">
                    <div>
                        <span class="text-sm font-semibold text-gray-800 block">Total Staff</span>
                        <span id="count-staff" class="text-2xl font-bold text-gray-900 leading-none mt-1.5 block">0</span>
                    </div>
                    <div class="text-[#0d4a32] pl-2">
                        <svg class="w-9 h-9 fill-[#0d4a32]" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </div>
                </div>

                <!-- Total Manager -->
                <div class="bg-white rounded-xl border border-gray-200/90 shadow-sm px-5 py-3.5 w-60 flex items-center justify-between">
                    <div>
                        <span class="text-sm font-semibold text-gray-800 block">Total Manager</span>
                        <span id="count-manager" class="text-2xl font-bold text-gray-900 leading-none mt-1.5 block">0</span>
                    </div>
                    <div class="text-[#0d4a32] pl-2">
                        <svg class="w-9 h-9 fill-[#0d4a32]" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- TREN DONASI SECTION -->
        <section class="w-full bg-white p-6 rounded-xl shadow-sm border border-gray-100">            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[25px] font-bold">Tren Donasi</h2>
                <div class="flex items-center gap-2">
                    <input type="date" id="tanggalMulai" class="border rounded-lg px-4 py-2 bg-white text-gray-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                    <span class="text-gray-500">-</span>
                    <input type="date" id="tanggalSelesai" class="border rounded-lg px-4 py-2 bg-white text-gray-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                </div>
            </div>
            
            <div class="h-[300px]">
                <canvas id="trenDonasiChart"></canvas>
            </div>

            <div class="flex justify-between mt-8 px-12">
                <div class="text-center">
                    <p id="labelTotalPeriode" class="text-gray-500 font-medium">Total Per Minggu</p>
                    <p id="totalPeriode" class="text-2xl font-bold">Rp 0</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-500 font-medium">Donasi Tertinggi</p>
                    <p id="donasiTertinggi" class="text-2xl font-bold">Rp 0</p>
                </div>
            </div>
        </section>

        <!-- TWO COLUMNS STATS -->
        <div class="w-full grid grid-cols-2 gap-6">
            <!-- TOTAL DONASI -->
            <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-center text-[22px] font-bold mb-6">Total Donasi</h2>
                <div class="space-y-4">
                    <!-- PANTI -->
                    <div>
                        <h3 class="text-base font-bold mb-3">Panti</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Per Bulan</p>
                                <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">
                                    <div id="pantiBulananBar" class="bg-rangkul-green h-full w-0 min-w-max flex items-center px-4">
                                        <span id="pantiBulanan" class="text-sm font-bold text-white whitespace-nowrap">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Per Tahun</p>
                                <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">
                                    <div id="pantiTahunanBar" class="bg-rangkul-green h-full w-0 min-w-max flex items-center px-4">
                                        <span id="pantiTahunan" class="text-sm font-bold text-white whitespace-nowrap">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEKOLAH -->
                    <div>
                        <h3 class="text-base font-bold mb-3">Sekolah</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Per Bulan</p>
                                <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">
                                    <div id="sekolahBulananBar" class="bg-rangkul-green h-full w-0 min-w-max flex items-center px-4">
                                        <span id="sekolahBulanan" class="text-sm font-bold text-white whitespace-nowrap">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Per Tahun</p>
                                <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">
                                    <div id="sekolahTahunanBar" class="bg-rangkul-green h-full w-0 min-w-max flex items-center px-4">
                                        <span id="sekolahTahunan" class="text-sm font-bold text-white whitespace-nowrap">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TOTAL TERSALURKAN (GAUGE) -->
            <section class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                <h2 class="text-center text-[22px] font-bold mb-6">Total Tersalurkan</h2>
                <div class="relative w-full max-w-[300px]">
                    <canvas id="gaugeChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-end pb-8">
                        <span id="persentaseTersalurkan" class="text-4xl font-bold">0%</span>
                    </div>
                </div>
                <div class="flex justify-between w-full text-gray-400 font-semibold px-12 mt-2">
                    <span>0%</span>
                    <span>100%</span>
                </div>
            </section>
        </div>

        <div id="account-notice" class="hidden rounded-lg border px-4 py-3 text-center text-sm"></div>

        <!-- TABEL 1: DAFTAR AKUN STAFF & MANAGER -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200/80">
            <div class="bg-[#086538] px-6 py-3.5 flex items-center justify-between text-white">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">📁</span>
                    <h2 class="font-bold text-base tracking-wide">Daftar Akun Staff & Manager</h2>
                </div>
                <button onclick="openModalTambah()" class="px-4 py-1.5 rounded-full bg-[#faecc8] text-[#086538] hover:bg-[#f6e4b2] text-xs font-semibold transition-all shadow-sm">
                    Tambahkan Staff/Manager
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs font-semibold text-gray-800 bg-white">
                            <th class="py-3 px-6 w-16">No.</th>
                            <th class="py-3 px-6 w-48">Nama</th>
                            <th class="py-3 px-6">Email</th>
                            <th class="py-3 px-6 w-36">Role</th>
                            <th class="py-3 px-6 w-36">Status</th>
                            <th class="py-3 px-6 w-64 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-staff-body" class="divide-y divide-gray-200/80 text-sm">
                        <tr><td colspan="6" class="py-6 text-center text-gray-500">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABEL 2: DAFTAR NONAKTIF AKUN -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200/80">
            <div class="bg-[#086538] px-6 py-3.5 flex items-center gap-2.5 text-white">
                <span class="text-xl">📁</span>
                <h2 class="font-bold text-base tracking-wide">Daftar Nonaktif Akun</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs font-semibold text-gray-800 bg-white">
                            <th class="py-3 px-6 w-16">No.</th>
                            <th class="py-3 px-6 w-48">Nama</th>
                            <th class="py-3 px-6">Email</th>
                            <th class="py-3 px-6 w-36">Role</th>
                            <th class="py-3 px-6 w-36">Status</th>
                            <th class="py-3 px-6 w-64 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-nonaktif-body" class="divide-y divide-gray-200/80 text-sm">
                        <tr><td colspan="6" class="py-6 text-center text-gray-500">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- ========================================================= -->
    <!-- 1. POP UP: TAMBAHKAN STAFF/MANAGER -->
    <!-- ========================================================= -->
    <div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Tambahkan Staff / Manager</h2>
            <form onsubmit="handleSimpanTambah(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="input-nama" required placeholder="Contoh: Staff 4 atau Budi Santoso" class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Email</label>
                    <input type="email" id="input-email" required placeholder="Contoh: staff@rangkul.com" class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Role</label>
                    <select id="input-role" class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#086538]">
                        <option value="staff">Staff</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
                <p data-modal-error class="hidden text-sm text-red-600 mb-3"></p>
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('modal-tambah')" class="px-8 py-2.5 bg-[#d8dbdf] text-gray-800 text-sm font-semibold rounded-xl hover:bg-[#cbd0d6] transition-all shadow-sm">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-[#086538] text-white text-sm font-semibold rounded-xl hover:bg-[#064e2b] transition-all shadow-sm">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 2. POP UP: UBAH DATA AKUN (Frame 319) -->
    <!-- ========================================================= -->
    <div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Ubah Data Akun</h2>
            <form onsubmit="handleSimpanEdit(event)" class="space-y-4">
                <input type="hidden" id="edit-id">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="edit-nama" required placeholder="Masukkan nama lengkap...." class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Email</label>
                    <input type="email" id="edit-email" required placeholder="Masukkan email...." class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Nomor Telepon</label>
                    <input type="text" id="edit-telepon" placeholder="Masukkan nomor telepon (opsional)" class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]">
                </div>
                <p data-modal-error class="hidden text-sm text-red-600 mb-3"></p>
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('modal-edit')" class="px-8 py-2.5 bg-[#d8dbdf] text-gray-800 text-sm font-semibold rounded-xl hover:bg-[#cbd0d6] transition-all shadow-sm">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-[#086538] text-white text-sm font-semibold rounded-xl hover:bg-[#064e2b] transition-all shadow-sm">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 3. POP UP: RESET PASSWORD DENGAN SEEN / UNSEEN (Frame 320) -->
    <!-- ========================================================= -->
    <div id="modal-reset" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Reset Password</h2>
            <form onsubmit="handleSimpanReset(event)" class="space-y-4">
                <input type="hidden" id="reset-id-target">
                
                <!-- Password Baru dengan Toggle Mata -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="reset-pass-baru" required placeholder="••••••••" class="w-full pl-4 pr-11 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]">
                        <button type="button" onclick="togglePasswordVisibility('reset-pass-baru', 'icon-pass-baru')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 cursor-pointer" title="Lihat/Sembunyikan password">
                            <i id="icon-pass-baru" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password dengan Toggle Mata -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" id="reset-pass-confirm" required placeholder="••••••••" class="w-full pl-4 pr-11 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]">
                        <button type="button" onclick="togglePasswordVisibility('reset-pass-confirm', 'icon-pass-confirm')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 cursor-pointer" title="Lihat/Sembunyikan password">
                            <i id="icon-pass-confirm" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <p class="text-xs text-gray-400">Password minimal 8 karakter, berisi huruf dan angka. User akan menerima notifikasi email setelah password berhasil diubah.</p>
                <p data-modal-error class="hidden text-sm text-red-600 mb-3"></p>
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('modal-reset')" class="px-8 py-2.5 bg-[#d8dbdf] text-gray-800 text-sm font-semibold rounded-xl hover:bg-[#cbd0d6] transition-all shadow-sm">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-[#086538] text-white text-sm font-semibold rounded-xl hover:bg-[#064e2b] transition-all shadow-sm">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 4. POP UP: KONFIRMASI PENGAKTIFAN (Frame 320-1) -->
    <!-- ========================================================= -->
    <div id="modal-aktifkan" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Konfirmasi Pengaktifan</h2>
            <p class="text-sm text-gray-700 mb-8">Apakah Anda yakin ingin mengaktifkan kembali akun ini?</p>
            <input type="hidden" id="aktifkan-id-target">
            <p data-modal-error class="hidden text-sm text-red-600 mb-3"></p>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeModal('modal-aktifkan')" class="px-8 py-2.5 bg-[#d8dbdf] text-gray-800 text-sm font-semibold rounded-xl hover:bg-[#cbd0d6] transition-all shadow-sm">Batal</button>
                <button type="button" onclick="handleSimpanAktifkan()" class="px-8 py-2.5 bg-[#086538] text-white text-sm font-semibold rounded-xl hover:bg-[#064e2b] transition-all shadow-sm">Aktifkan</button>
            </div>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 5. POP UP: NONAKTIFKAN AKUN (Frame 321-1) -->
    <!-- ========================================================= -->
    <div id="modal-nonaktifkan" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8 text-center">
            <div class="flex justify-center mb-3">
                <svg class="w-14 h-14 text-[#8b1814]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Nonaktifkan Akun</h2>
            <div class="text-sm text-gray-700 space-y-1 mb-6">
                <p>Apa anda yakin ingin menonaktifkan akun ini?</p>
                <p>Pengguna tidak akan dapat masuk ke sistem setelah dinonaktifkan, dan hanya Super Admin yang dapat mengaktifkannya kembali.</p>
            </div>

            <form onsubmit="handleSimpanNonaktifkan(event)" class="text-left">
                <input type="hidden" id="nonaktifkan-id-target">
                <label class="block text-sm font-bold text-gray-900 mb-1.5">Alasan Penonaktifan</label>
                <textarea id="alasan-nonaktif" required rows="4" placeholder="Tuliskan alasan penonaktifan disini......" class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8b1814]"></textarea>
                <p class="text-xs text-gray-500 mt-1 mb-6">Kolom ini wajib diisi untuk melanjutkan proses.</p>

                <p data-modal-error class="hidden text-sm text-red-600 mb-3"></p>
                <div class="flex items-center justify-center gap-4">
                    <button type="button" onclick="closeModal('modal-nonaktifkan')" class="px-8 py-2.5 bg-[#d8dbdf] text-gray-800 text-sm font-semibold rounded-xl hover:bg-[#cbd0d6] transition-all shadow-sm">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-[#8b1814] text-white text-sm font-semibold rounded-xl hover:bg-[#70120f] transition-all shadow-sm">Nonaktifkan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 6. POP UP: HAPUS AKUN PERMANEN (Frame 321) -->
    <!-- ========================================================= -->
    <div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8 text-center">
            <div class="flex justify-center mb-3">
                <svg class="w-14 h-14 text-[#8b1814]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Hapus Akun</h2>
            <div class="text-sm text-gray-700 space-y-1 mb-6">
                <p>Apa anda yakin ingin menghapus akun ini?</p>
                <p>Pengguna tidak akan dapat masuk ke sistem setelah dihapus.</p>
            </div>

            <form onsubmit="handleSimpanHapus(event)" class="text-left">
                <input type="hidden" id="hapus-id-target">
                <label class="block text-sm font-bold text-gray-900 mb-1.5">Alasan Penghapusan</label>
                <textarea id="alasan-hapus" required rows="4" placeholder="Tuliskan alasan penghapusan disini......" class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8b1814]"></textarea>
                <p class="text-xs text-gray-500 mt-1 mb-6">Kolom ini wajib diisi untuk melanjutkan proses.</p>

                <p data-modal-error class="hidden text-sm text-red-600 mb-3"></p>
                <div class="flex items-center justify-center gap-4">
                    <button type="button" onclick="closeModal('modal-hapus')" class="px-8 py-2.5 bg-[#d8dbdf] text-gray-800 text-sm font-semibold rounded-xl hover:bg-[#cbd0d6] transition-all shadow-sm">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-[#8b1814] text-white text-sm font-semibold rounded-xl hover:bg-[#70120f] transition-all shadow-sm">Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- JAVASCRIPT LOGIC -->
    <!-- ========================================================= -->
    <script>
        const { request, formatRupiah, escapeHtml } = window.RangkulAdmin;

        // Akun staf & manajer dari /api/admin/accounts
        let dataAkun = [];

        // Buka & Tutup Modal Pop-up
        function openModal(modalId) {
            const el = document.getElementById(modalId);
            if (el) {
                setModalError(el, '');
                el.classList.remove('hidden');
                el.classList.add('flex');
            }
        }

        function closeModal(modalId) {
            const el = document.getElementById(modalId);
            if (el) {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        function setModalError(modal, message) {
            const error = modal.querySelector('[data-modal-error]');
            if (!error) return;
            error.textContent = message;
            error.classList.toggle('hidden', !message);
        }

        function showNotice(message, isError = false) {
            const notice = document.getElementById('account-notice');
            notice.textContent = message;
            notice.className = 'rounded-lg border px-4 py-3 text-center text-sm ' + (isError
                ? 'border-red-200 bg-red-50 text-red-700'
                : 'border-green-200 bg-green-50 text-green-800');
            notice.classList.toggle('hidden', !message);
        }

        // Kirim aksi akun dari dalam modal: tombol dikunci selama proses, galat tampil di modal.
        async function submitAkun(modalId, path, method, body, button) {
            const modal = document.getElementById(modalId);
            const submitButton = button || modal.querySelector('button[type="submit"]');

            setModalError(modal, '');
            submitButton.disabled = true;
            submitButton.classList.add('opacity-60', 'cursor-wait');

            try {
                const result = await request(path, { method, body });
                closeModal(modalId);
                showNotice(result.message);
                await loadAkun();
            } catch (error) {
                setModalError(modal, error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-60', 'cursor-wait');
            }
        }

        // Fitur Toggle Lihat / Sembunyikan Password (Eye / Eye-Slash)
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function barisKosong(pesan) {
            return `<tr><td colspan="6" class="py-6 text-center text-gray-500">${escapeHtml(pesan)}</td></tr>`;
        }

        function tombolAksi(aksi, id, label, className, disabled) {
            return `
                <button type="button" data-aksi="${aksi}" data-id="${escapeHtml(id)}" ${disabled ? 'disabled' : ''}
                    class="${className} rounded-full text-xs font-medium ${disabled ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : ''}">
                    ${label}
                </button>
            `;
        }

        function barisAkun(item, index, aksi) {
            return `
                <tr class="hover:bg-gray-50/70">
                    <td class="py-3 px-6 text-gray-800">${index + 1}.</td>
                    <td class="py-3 px-6 font-medium text-gray-900">${escapeHtml(item.name)}</td>
                    <td class="py-3 px-6 text-gray-800">${escapeHtml(item.email)}</td>
                    <td class="py-3 px-6 text-gray-800">${escapeHtml(item.role_label)}</td>
                    <td class="py-3 px-6 text-gray-800">${escapeHtml(item.status_label)}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">${aksi}</div>
                    </td>
                </tr>
            `;
        }

        // Render Tabel Akun Aktif & Nonaktif
        function renderTabel() {
            const tbodyStaff = document.getElementById('table-staff-body');
            const tbodyNonaktif = document.getElementById('table-nonaktif-body');

            // Render Tabel 1
            tbodyStaff.innerHTML = dataAkun.length ? dataAkun.map((item, index) => {
                const isNonaktif = item.status === 'nonaktif';
                return barisAkun(item, index, [
                    tombolAksi('edit', item.id, 'Edit', isNonaktif ? 'px-4 py-1' : 'px-4 py-1 bg-[#fef3c7] text-[#92400e] hover:bg-[#fde68a]', isNonaktif),
                    tombolAksi('reset', item.id, 'Reset', isNonaktif ? 'px-4 py-1' : 'px-4 py-1 bg-[#e0f2fe] text-[#0369a1] hover:bg-[#bae6fd]', isNonaktif),
                    tombolAksi('nonaktifkan', item.id, 'Nonaktifkan', isNonaktif ? 'px-3 py-1' : 'px-3 py-1 bg-[#fee2e2] text-[#b91c1c] hover:bg-[#fecaca]', isNonaktif)
                ].join(''));
            }).join('') : barisKosong('Belum ada akun staff atau manager.');

            // Render Tabel 2
            const nonaktifList = dataAkun.filter(a => a.status === 'nonaktif');
            tbodyNonaktif.innerHTML = nonaktifList.length ? nonaktifList.map((item, index) => barisAkun(item, index, [
                tombolAksi('aktifkan', item.id, 'Aktifkan', 'px-4 py-1 bg-[#e0f2fe] text-[#0284c7] hover:bg-[#bae6fd]', false),
                tombolAksi('hapus', item.id, 'Hapus Permanen', 'px-3.5 py-1 bg-[#fee2e2] text-[#b91c1c] hover:bg-[#fecaca]', false)
            ].join(''))).join('') : barisKosong('Tidak ada akun nonaktif.');
        }

        async function loadAkun() {
            try {
                const { data } = await request('/api/admin/accounts');

                dataAkun = data.accounts;
                document.getElementById('count-staff').textContent = data.total_staff;
                document.getElementById('count-manager').textContent = data.total_manager;
                renderTabel();
            } catch (error) {
                showNotice(error.message, true);
                document.getElementById('table-staff-body').innerHTML = barisKosong('Data akun gagal dimuat.');
                document.getElementById('table-nonaktif-body').innerHTML = barisKosong('Data akun gagal dimuat.');
            }
        }

        // ==========================================
        // HANDLER SETIAP AKSI POP UP
        // ==========================================
        function openModalTambah() {
            document.getElementById('input-nama').value = '';
            document.getElementById('input-email').value = '';
            document.getElementById('input-role').value = 'staff';
            openModal('modal-tambah');
        }

        function handleSimpanTambah(e) {
            e.preventDefault();
            submitAkun('modal-tambah', '/api/admin/accounts', 'POST', {
                nama: document.getElementById('input-nama').value,
                email: document.getElementById('input-email').value,
                role: document.getElementById('input-role').value
            });
        }

        function aksiEdit(id) {
            const item = dataAkun.find(a => a.id === id);
            if (!item) return;
            document.getElementById('edit-id').value = item.id;
            document.getElementById('edit-nama').value = item.name;
            document.getElementById('edit-email').value = item.email;
            document.getElementById('edit-telepon').value = item.phone || '';
            openModal('modal-edit');
        }

        function handleSimpanEdit(e) {
            e.preventDefault();
            const id = document.getElementById('edit-id').value;
            submitAkun('modal-edit', '/api/admin/accounts/' + encodeURIComponent(id), 'PUT', {
                nama: document.getElementById('edit-nama').value,
                email: document.getElementById('edit-email').value,
                no_telp: document.getElementById('edit-telepon').value || null
            });
        }

        function aksiReset(id) {
            document.getElementById('reset-id-target').value = id;
            const passBaru = document.getElementById('reset-pass-baru');
            const passConfirm = document.getElementById('reset-pass-confirm');
            passBaru.value = '';
            passBaru.type = 'password';
            passConfirm.value = '';
            passConfirm.type = 'password';

            document.getElementById('icon-pass-baru').className = 'fa-solid fa-eye';
            document.getElementById('icon-pass-confirm').className = 'fa-solid fa-eye';

            openModal('modal-reset');
        }

        function handleSimpanReset(e) {
            e.preventDefault();
            const passBaru = document.getElementById('reset-pass-baru').value;
            const passConfirm = document.getElementById('reset-pass-confirm').value;
            if (passBaru !== passConfirm) {
                setModalError(document.getElementById('modal-reset'), 'Password baru dan konfirmasi password tidak sesuai!');
                return;
            }

            const id = document.getElementById('reset-id-target').value;
            submitAkun('modal-reset', '/api/admin/accounts/' + encodeURIComponent(id) + '/password', 'PUT', {
                password: passBaru,
                password_confirmation: passConfirm
            });
        }

        function aksiAktifkan(id) {
            document.getElementById('aktifkan-id-target').value = id;
            openModal('modal-aktifkan');
        }

        function handleSimpanAktifkan() {
            const id = document.getElementById('aktifkan-id-target').value;
            const button = document.querySelector('#modal-aktifkan button[onclick="handleSimpanAktifkan()"]');
            submitAkun('modal-aktifkan', '/api/admin/accounts/' + encodeURIComponent(id) + '/activate', 'PATCH', null, button);
        }

        function aksiNonaktifkan(id) {
            document.getElementById('nonaktifkan-id-target').value = id;
            document.getElementById('alasan-nonaktif').value = '';
            openModal('modal-nonaktifkan');
        }

        function handleSimpanNonaktifkan(e) {
            e.preventDefault();
            const id = document.getElementById('nonaktifkan-id-target').value;
            submitAkun('modal-nonaktifkan', '/api/admin/accounts/' + encodeURIComponent(id) + '/deactivate', 'PATCH', {
                alasan: document.getElementById('alasan-nonaktif').value
            });
        }

        function aksiHapus(id) {
            document.getElementById('hapus-id-target').value = id;
            document.getElementById('alasan-hapus').value = '';
            openModal('modal-hapus');
        }

        function handleSimpanHapus(e) {
            e.preventDefault();
            const id = document.getElementById('hapus-id-target').value;
            submitAkun('modal-hapus', '/api/admin/accounts/' + encodeURIComponent(id), 'DELETE', {
                alasan: document.getElementById('alasan-hapus').value
            });
        }

        // Tombol aksi di tabel memakai data-aksi agar ID akun (UUID) tidak disisipkan ke onclick.
        const AKSI_AKUN = {
            edit: aksiEdit,
            reset: aksiReset,
            nonaktifkan: aksiNonaktifkan,
            aktifkan: aksiAktifkan,
            hapus: aksiHapus
        };

        document.addEventListener('click', (event) => {
            const button = event.target.closest('button[data-aksi]');
            if (button && !button.disabled) {
                AKSI_AKUN[button.dataset.aksi]?.(button.dataset.id);
            }
        });

        // =====================================================
        // DASHBOARD & CHARTS
        // =====================================================
        const tanggalMulai = document.getElementById('tanggalMulai');
        const tanggalSelesai = document.getElementById('tanggalSelesai');
        let trendChart = null;
        let gaugeChart = null;

        function renderDashboard(data) {
            const summary = data.summary;
            const organizationTotals = data.organization_totals;
            const periodDays = Math.round(
                (new Date(data.period.tanggal_selesai) - new Date(data.period.tanggal_mulai)) / 86400000
            ) + 1;

            tanggalMulai.value = data.period.tanggal_mulai;
            tanggalSelesai.value = data.period.tanggal_selesai;

            document.getElementById('labelTotalPeriode').textContent = periodDays === 7 ? 'Total Per Minggu' : 'Total Periode';
            document.getElementById('totalPeriode').textContent = formatRupiah(summary.total_donasi);
            document.getElementById('donasiTertinggi').textContent = formatRupiah(summary.donasi_tertinggi);

            const values = [
                organizationTotals.panti.monthly,
                organizationTotals.panti.yearly,
                organizationTotals.sekolah.monthly,
                organizationTotals.sekolah.yearly
            ];
            const maximum = Math.max(...values, 1);

            [
                ['pantiBulanan', 'pantiBulananBar', organizationTotals.panti.monthly],
                ['pantiTahunan', 'pantiTahunanBar', organizationTotals.panti.yearly],
                ['sekolahBulanan', 'sekolahBulananBar', organizationTotals.sekolah.monthly],
                ['sekolahTahunan', 'sekolahTahunanBar', organizationTotals.sekolah.yearly]
            ].forEach(([valueId, barId, value]) => {
                document.getElementById(valueId).textContent = formatRupiah(value);
                document.getElementById(barId).style.width = `${Math.round((value / maximum) * 100)}%`;
            });

            const persentase = Number(summary.persentase_tersalurkan || 0);
            document.getElementById('persentaseTersalurkan').textContent = `${persentase.toLocaleString('id-ID', { maximumFractionDigits: 2 })}%`;

            if (trendChart) trendChart.destroy();
            trendChart = new Chart(document.getElementById('trenDonasiChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.trend.map(item => item.label),
                    datasets: [{
                        label: 'Tren Donasi',
                        data: data.trend.map(item => item.total),
                        borderColor: '#4ade80',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4ade80',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        tension: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (c) => formatRupiah(c.raw) } }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { maxTicksLimit: 4, callback: (value) => Number(value).toLocaleString('id-ID') }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            if (gaugeChart) gaugeChart.destroy();
            gaugeChart = new Chart(document.getElementById('gaugeChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [persentase, 100 - persentase],
                        backgroundColor: ['#4ade80', '#f3f4f6'],
                        borderWidth: 0,
                        circumference: 180,
                        rotation: 270,
                        cutout: '85%',
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { tooltip: { enabled: false }, legend: { display: false } }
                }
            });
        }

        function showDashboardError(message) {
            const error = document.getElementById('dashboard-error');
            error.textContent = message;
            error.classList.toggle('hidden', !message);
        }

        async function loadDashboard() {
            const params = new URLSearchParams();

            // Tanpa rentang tanggal, server memakai minggu berjalan.
            if (tanggalMulai.value && tanggalSelesai.value) {
                params.set('tanggal_mulai', tanggalMulai.value);
                params.set('tanggal_selesai', tanggalSelesai.value);
            }

            try {
                const { data } = await request('/api/admin/dashboard?' + params);
                showDashboardError('');
                renderDashboard(data);
            } catch (error) {
                showDashboardError(error.message);
            }
        }

        tanggalMulai.addEventListener('change', loadDashboard);
        tanggalSelesai.addEventListener('change', loadDashboard);

        // Jalankan saat halaman pertama kali dimuat
        loadAkun();
        loadDashboard();
    </script>
</body>
</html>