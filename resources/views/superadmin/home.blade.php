<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <span id="count-staff" class="text-2xl font-bold text-gray-900 leading-none mt-1.5 block">2</span>
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
                        <span id="count-manager" class="text-2xl font-bold text-gray-900 leading-none mt-1.5 block">1</span>
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
                    <p class="text-gray-500 font-medium">Total Per Minggu</p>
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
                                    <div id="pantiBulananBar" class="bg-rangkul-green h-full w-0 flex items-center px-4">
                                        <span id="pantiBulanan" class="text-sm font-bold text-white whitespace-nowrap">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Per Tahun</p>
                                <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">
                                    <div id="pantiTahunanBar" class="bg-rangkul-green h-full w-0 flex items-center px-4">
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
                                    <div id="sekolahBulananBar" class="bg-rangkul-green h-full w-0 flex items-center px-4">
                                        <span id="sekolahBulanan" class="text-sm font-bold text-white whitespace-nowrap">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Per Tahun</p>
                                <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">
                                    <div id="sekolahTahunanBar" class="bg-rangkul-green h-full w-0 flex items-center px-4">
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
                        <!-- Data dirender oleh JavaScript -->
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
                        <!-- Data dirender oleh JavaScript -->
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
                        <option value="Staff">Staff</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
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
                <input type="hidden" id="reset-email-target">
                
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

                <p class="text-xs text-gray-400">User akan menerima notifikasi email setelah password berhasil diubah.</p>
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
                <textarea id="alasan-nonaktif" required rows="4" placeholder="Tuliskan alasan penolakan disini......" class="w-full px-4 py-2.5 border border-gray-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8b1814]"></textarea>
                <p class="text-xs text-gray-500 mt-1 mb-6">Kolom ini wajib diisi untuk melanjutkan proses.</p>

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
        // Data Akun Awal
        let dataAkun = [
            { id: 1, nama: 'Manager', email: 'manager@rangkul.com', telepon: '081234567890', role: 'Manager', status: 'Aktif' },
            { id: 2, nama: 'Staff 1', email: 'staff123@rangkul.com', telepon: '081298765432', role: 'Staff', status: 'Aktif' },
            { id: 3, nama: 'Staff 2', email: 'staff456@rangkul.com', telepon: '', role: 'Staff', status: 'Aktif' },
            { id: 4, nama: 'Staff 3', email: 'staff789@rangkul.com', telepon: '', role: 'Staff', status: 'Nonaktif' }
        ];

        // Format Angka ke Rupiah
        const formatRupiah = (value) => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value || 0);

        // Buka & Tutup Modal Pop-up
        function openModal(modalId) {
            const el = document.getElementById(modalId);
            if (el) {
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

        // Render Tabel Akun Aktif & Nonaktif
        function renderTabel() {
            const tbodyStaff = document.getElementById('table-staff-body');
            const tbodyNonaktif = document.getElementById('table-nonaktif-body');

            const totalStaff = dataAkun.filter(a => a.role === 'Staff' && a.status === 'Aktif').length;
            const totalManager = dataAkun.filter(a => a.role === 'Manager' && a.status === 'Aktif').length;

            document.getElementById('count-staff').textContent = totalStaff;
            document.getElementById('count-manager').textContent = totalManager;

            // Render Tabel 1
            tbodyStaff.innerHTML = dataAkun.map((item, index) => {
                const isNonaktif = item.status === 'Nonaktif';
                return `
                    <tr class="hover:bg-gray-50/70">
                        <td class="py-3 px-6 text-gray-800">${index + 1}.</td>
                        <td class="py-3 px-6 font-medium text-gray-900">${item.nama}</td>
                        <td class="py-3 px-6 text-gray-800">${item.email}</td>
                        <td class="py-3 px-6 text-gray-800">${item.role}</td>
                        <td class="py-3 px-6 text-gray-800">${item.status}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    ${isNonaktif ? 'disabled' : `onclick="aksiEdit(${item.id})"`}
                                    class="px-4 py-1 rounded-full text-xs font-medium ${isNonaktif ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-[#fef3c7] text-[#92400e] hover:bg-[#fde68a]'}">
                                    Edit
                                </button>
                                <button
                                    ${isNonaktif ? 'disabled' : `onclick="aksiReset('${item.email}')"`}
                                    class="px-4 py-1 rounded-full text-xs font-medium ${isNonaktif ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-[#e0f2fe] text-[#0369a1] hover:bg-[#bae6fd]'}">
                                    Reset
                                </button>
                                <button
                                    ${isNonaktif ? 'disabled' : `onclick="aksiNonaktifkan(${item.id})"`}
                                    class="px-3 py-1 rounded-full text-xs font-medium ${isNonaktif ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-[#fee2e2] text-[#b91c1c] hover:bg-[#fecaca]'}">
                                    Nonaktifkan
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            // Render Tabel 2
            const nonaktifList = dataAkun.filter(a => a.status === 'Nonaktif');
            if (nonaktifList.length === 0) {
                tbodyNonaktif.innerHTML = `<tr><td colspan="6" class="py-6 text-center text-gray-500">Tidak ada akun nonaktif.</td></tr>`;
            } else {
                tbodyNonaktif.innerHTML = nonaktifList.map((item, index) => `
                    <tr class="hover:bg-gray-50/70">
                        <td class="py-3 px-6 text-gray-800">${index + 1}.</td>
                        <td class="py-3 px-6 font-medium text-gray-900">${item.nama}</td>
                        <td class="py-3 px-6 text-gray-800">${item.email}</td>
                        <td class="py-3 px-6 text-gray-800">${item.role}</td>
                        <td class="py-3 px-6 text-gray-800">${item.status}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="aksiAktifkan(${item.id})" class="px-4 py-1 rounded-full bg-[#e0f2fe] text-[#0284c7] hover:bg-[#bae6fd] text-xs font-medium">Aktifkan</button>
                                <button onclick="aksiHapus(${item.id})" class="px-3.5 py-1 rounded-full bg-[#fee2e2] text-[#b91c1c] hover:bg-[#fecaca] text-xs font-medium">Hapus Permanen</button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }

        // ==========================================
        // HANDLER SETIAP AKSI POP UP
        // ==========================================
        function openModalTambah() {
            document.getElementById('input-nama').value = '';
            document.getElementById('input-email').value = '';
            document.getElementById('input-role').value = 'Staff';
            openModal('modal-tambah');
        }

        function handleSimpanTambah(e) {
            e.preventDefault();
            const nama = document.getElementById('input-nama').value;
            const email = document.getElementById('input-email').value;
            const role = document.getElementById('input-role').value;

            dataAkun.push({ id: Date.now(), nama, email, telepon: '', role, status: 'Aktif' });
            closeModal('modal-tambah');
            renderTabel();
        }

        function aksiEdit(id) {
            const item = dataAkun.find(a => a.id === id);
            if (!item) return;
            document.getElementById('edit-id').value = item.id;
            document.getElementById('edit-nama').value = item.nama;
            document.getElementById('edit-email').value = item.email;
            document.getElementById('edit-telepon').value = item.telepon || '';
            openModal('modal-edit');
        }

        function handleSimpanEdit(e) {
            e.preventDefault();
            const id = Number(document.getElementById('edit-id').value);
            const item = dataAkun.find(a => a.id === id);
            if (item) {
                item.nama = document.getElementById('edit-nama').value;
                item.email = document.getElementById('edit-email').value;
                item.telepon = document.getElementById('edit-telepon').value;
            }
            closeModal('modal-edit');
            renderTabel();
        }

        function aksiReset(email) {
            document.getElementById('reset-email-target').value = email;
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
                alert('Password baru dan konfirmasi password tidak sesuai!');
                return;
            }
            closeModal('modal-reset');
            alert('Password berhasil diubah!');
        }

        function aksiAktifkan(id) {
            document.getElementById('aktifkan-id-target').value = id;
            openModal('modal-aktifkan');
        }

        function handleSimpanAktifkan() {
            const id = Number(document.getElementById('aktifkan-id-target').value);
            const item = dataAkun.find(a => a.id === id);
            if (item) {
                item.status = 'Aktif';
            }
            closeModal('modal-aktifkan');
            renderTabel();
        }

        function aksiNonaktifkan(id) {
            document.getElementById('nonaktifkan-id-target').value = id;
            document.getElementById('alasan-nonaktif').value = '';
            openModal('modal-nonaktifkan');
        }

        function handleSimpanNonaktifkan(e) {
            e.preventDefault();
            const id = Number(document.getElementById('nonaktifkan-id-target').value);
            const item = dataAkun.find(a => a.id === id);
            if (item) {
                item.status = 'Nonaktif';
            }
            closeModal('modal-nonaktifkan');
            renderTabel();
        }

        function aksiHapus(id) {
            document.getElementById('hapus-id-target').value = id;
            document.getElementById('alasan-hapus').value = '';
            openModal('modal-hapus');
        }

        function handleSimpanHapus(e) {
            e.preventDefault();
            const id = Number(document.getElementById('hapus-id-target').value);
            dataAkun = dataAkun.filter(a => a.id !== id);
            closeModal('modal-hapus');
            renderTabel();
        }

        // =====================================================
        // DASHBOARD & CHARTS
        // =====================================================
        let trendChart = null;
        let gaugeChart = null;

        const dashboardDummy = {
            period: {
                tanggal_mulai: '2026-10-01',
                tanggal_selesai: '2026-10-07'
            },
            summary: {
                total_donasi: 2130000,
                donasi_tertinggi: 600000,
                persentase_tersalurkan: 72
            },
            organization_totals: {
                panti: { monthly: 1000000, yearly: 2350000 },
                sekolah: { monthly: 50000000, yearly: 65000000 }
            },
            trend: [
                { label: 'Mon', total: 190000 },
                { label: 'Tue', total: 350000 },
                { label: 'Wed', total: 140000 },
                { label: 'Thu', total: 240000 },
                { label: 'Fri', total: 600000 },
                { label: 'Sat', total: 440000 },
                { label: 'Sun', total: 380000 }
            ]
        };

        function renderDashboard(data) {
            const summary = data.summary;
            const organizationTotals = data.organization_totals;

            document.getElementById('tanggalMulai').value = data.period.tanggal_mulai;
            document.getElementById('tanggalSelesai').value = data.period.tanggal_selesai;

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
                        tooltip: { callbacks: { label: (c) => `${c.dataset.label}: ${formatRupiah(c.raw)}` } }
                    },
                    scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
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

        async function loadDashboard() {
            const authToken = localStorage.getItem('rangkul_access_token') || localStorage.getItem('auth_token');
            if (!authToken) {
                renderDashboard(dashboardDummy);
                return;
            }

            try {
                const tanggalMulai = document.getElementById('tanggalMulai').value;
                const tanggalSelesai = document.getElementById('tanggalSelesai').value;
                const params = new URLSearchParams({ tanggal_mulai: tanggalMulai, tanggal_selesai: tanggalSelesai });
                const response = await fetch(`/api/admin/dashboard?${params.toString()}`, {
                    headers: { Authorization: `Bearer ${authToken}`, Accept: 'application/json' }
                });

                if (response.status === 401) {
                    localStorage.removeItem('rangkul_access_token');
                    localStorage.removeItem('rangkul_user');
                    renderDashboard(dashboardDummy);
                    return;
                }

                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Gagal memuat.');
                renderDashboard(result.data);
            } catch (err) {
                console.error(err);
                renderDashboard(dashboardDummy);
            }
        }

        document.getElementById('tanggalMulai').addEventListener('change', loadDashboard);
        document.getElementById('tanggalSelesai').addEventListener('change', loadDashboard);

        // Jalankan saat halaman pertama kali dimuat
        renderTabel();
        loadDashboard();
    </script>
</body>
</html>