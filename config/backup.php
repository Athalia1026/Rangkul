<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tujuan backup
    |--------------------------------------------------------------------------
    |
    | Disk (config/filesystems.php) tempat arsip backup disimpan, dipisah koma.
    | Production: "backups,backups_offsite" agar salinan tetap ada walau server
    | rusak. Lokal cukup "backups".
    |
    */

    'disks' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('BACKUP_DISKS', 'backups'))
    ))),

    // Backup yang lebih tua dari jumlah hari ini dihapus, kecuali backup terbaru.
    'keep_days' => (int) env('BACKUP_KEEP_DAYS', 14),

    // Lokasi mysqldump bila tidak ada di PATH, mis. D:/laragon/bin/mysql/mysql-8.0.30-winx64/bin
    'dump_binary_path' => env('DB_DUMP_BINARY_PATH'),

    // Folder di dalam storage/app yang ikut diarsipkan (dokumen organisasi, bukti, foto).
    'storage_root' => storage_path('app'),

    'filename_prefix' => 'rangkul-backup-',

];
