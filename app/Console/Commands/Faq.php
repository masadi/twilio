<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;

class Faq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:faq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            [
                'title' => 0,
                'body' => "Balas pesan ini Dengan memilih 1 opsi:",
                'MediaUrl' => NULL,
                'sub' => [
                    [
                        'title' => 'Informasi Umum',
                        'body' => 'Informasi Seputar Aplikasi, Panduan dan Pedoman. Balas pesan ini Dengan memilih 1 opsi:',
                        'MediaUrl' => NULL,
                        'sub' => [
                            [
                                'title' => 'Unduh Aplikasi',
                                'body' => 'Untuk mengunduh aplikasi, bagi pengguna windows silahkan kunjungi https://erapor.ditpsmk.net/pusat-unduhan, dan untuk pengguna linux silahkan kunjungi https://github.com/eraporsmk/erapor7',
                                'MediaUrl' => NULL,
                                'sub' => [],
                            ],
                            [
                                'title' => 'Panduan Penggunaan Aplikasi',
                                'body' => 'Untuk mempelajari tata cara penggunaan aplikasi, silahkan dibaca melalui tautan https://drive.google.com/file/d/1zT0r9SVwJhyRle6JnydoN2wu-p6nnC12/view?usp=sharing',
                                'MediaUrl' => NULL,
                                'sub' => [],
                            ],
                            [
                                'title' => 'Panduan Pembelajaran dan Asesmen Kurikulum Merdeka',
                                'body' => 'Untuk mempelajari tentang Panduan Pembelajaran dan Asesmen Kurikulum Merdeka, silahkan dibaca melalui tautan https://drive.google.com/file/d/1VWJ3MGrc9q12z3VOAfTO8WwxbGuaw_WN/view?usp=share_link',
                                'MediaUrl' => NULL,
                                'sub' => [],
                            ],
                            [
                                'title' => 'Panduan Penguatan Projek Profil Pelajar Pancasila',
                                'body' => 'Untuk mempelajari tentang Panduan Penguatan Projek Profil Pelajar Pancasila, silahkan dibaca melalui tautan https://drive.google.com/file/d/104vUWWHkAaXit-nxiTXDLP4ktM7zJX6K/view?usp=sharing',
                                'MediaUrl' => NULL,
                                'sub' => [],
                            ],
                            [
                                'title' => 'Dimensi Elemen Subelemen Profil Pelajar Pancasila pada Kurikulum Merdeka',
                                'body' => 'Untuk mempelajari tentang Dimensi Elemen Subelemen Profil Pelajar Pancasila pada Kurikulum Merdeka, silahkan dibaca melalui tautan https://drive.google.com/file/d/1uCrllxH1uPdGp1A0AenWiGkdvA0f1isV/view?usp=share_link',
                                'MediaUrl' => NULL,
                                'sub' => [],
                            ],
                            [
                                'title' => 'Pedoman Pembelajaran dan Asesmen Kurikulum 2013',
                                'body' => 'Untuk mempelajari tentang Pedoman Pembelajaran dan Asesmen Kurikulum 2013, silahkan dibaca melalui tautan https://drive.google.com/file/d/19tP2LIclhq0a5phMsBnlVQuzdZynPeRV/view?usp=share_link',
                                'MediaUrl' => NULL,
                                'sub' => [],
                            ]
                        ]
                    ],
                    [
                        'title' => 'Troubleshooting',
                        'body' => 'Informasi seputar kendala pengguna aplikasi',
                        'MediaUrl' => NULL,
                        'sub' => [
                            [
                                'title' => 'Database',
                                'body' => 'Pertanyaan seputar pengelolaan database',
                                'MediaUrl' => NULL,
                                'sub' => [
                                    [
                                        'title' => 'Cara menggunakan database versi 6.x di versi 7.x',
                                        'body' => "- Matikan services eRaporSMKDB\n- copy folder eRaporSMK di drive C, amankan di drive lain\n- Uninstall e-Rapor SMK versi 6\n- Restart Komputer/Laptop\n- Install eRapor SMK versi 7\n- Matikan services eRaporSMKDB\n- Hapus folder database di folder C:\eRaporSMK\n- Copy folder database hasil backup di atas dan paste di folder C:\eRaporSMK\n- Jalankan services eRaporSMKDB\n- Buka folder C:\eRaporSMK\updater\n- Klik kanan file update-erapor.bat dan pilih Run as Administrator\n- Klik kanan file symlink.bat dan pilih Run as Administrator\n- Selesai",
                                        'MediaUrl' => NULL,
                                        'sub' => [],
                                    ],
                                    [
                                        'title' => 'Services eRaporSMKDB tidak bisa running',
                                        'body' => "Masuk ke folder C:\eRaporSMK\webserver\bin, cari file pg_ctl.bat.\nKlik kanan file tersebut dan klik Run as Administrator",
                                        'MediaUrl' => NULL,
                                        'sub' => [],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Update Aplikasi',
                                'body' => 'Permasalahan saat update aplikasi',
                                'MediaUrl' => NULL,
                                'sub' => [
                                    [
                                        'title' => 'composer update gagal',
                                        'body' => 'Jika saat menjalankan perintah ```composer update``` tampil seperti gambar ini, silahkan download aplikasi composer melalui tautan https://getcomposer.org/download/ kemudian tutup CMD nya kemudian buka kembali dan ulangi dari awal',
                                        'MediaUrl' => 'https://erapor.ditpsmk.net/img/faq/composer-update.png',
                                        'sub' => [],
                                    ],
                                    [
                                        'title' => 'git pull gagal (part 1)',
                                        'body' => 'Jika saat menjalankan perintah ```git pull``` tampil seperti gambar ini, silahkan ketik git ```config --global --add safe.directory C:/eRaporSMK/dataweb``` [enter]'."\n".'Kemudian ketik lagi ```git pull``` [enter]',
                                        'MediaUrl' => 'https://erapor.ditpsmk.net/img/faq/git-pull-part-1.jpeg',
                                        'sub' => [],
                                    ],
                                    [
                                        'title' => 'git pull gagal (part 2)',
                                        'body' => 'Jika saat menjalankan perintah git pull tampil seperti gambar ini, silahkan ketik ```git stash``` [enter]'."\n".'Kemudian ketik lagi ```git pull``` [enter]',
                                        'MediaUrl' => 'https://erapor.ditpsmk.net/img/faq/git-pull-part-2.jpeg',
                                        'sub' => [],
                                    ],
                                    [
                                        'title' => 'git pull gagal (part 3)',
                                        'body' => 'Jika saat menjalankan perintah git pull tampil seperti gambar ini, silahkan ketik ```git clean -df``` [enter]'."\n".'Kemudian ketik lagi ```git pull``` [enter]',
                                        'MediaUrl' => 'https://erapor.ditpsmk.net/img/faq/git-pull-part-3.jpeg',
                                        'sub' => [],
                                    ],
                                    [
                                        'title' => 'git pull gagal (part 4)',
                                        'body' => 'Jika saat menjalankan perintah git pull tampil seperti gambar ini, silahkan silahkan download dulu aplikasi git melalui tautan https://git-scm.com/download/win'."\n".'Tutup CMD nya kemudian buka kembali dan ulangi dari awal',
                                        'MediaUrl' => 'https://erapor.ditpsmk.net/img/faq/git-pull-part-4.png',
                                        'sub' => [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        /*
        [
            'title' => '',
            'body' => '',
            'MediaUrl' => NULL,
            'sub' => [
                [],
            ],
        */
        foreach($data as $d){
            $a = $this->insertData($d);
            foreach($d['sub'] as $s){
                $b = $this->insertData($s, $a->id);
                foreach($s['sub'] as $ss){
                    $c = $this->insertData($ss, $b->id);
                    foreach($ss['sub'] as $sss){
                        $d = $this->insertData($sss, $c->id);
                    }
                }
            }
        }
    }
    private function insertData($data, $message_id = NULL){
        return Message::updateOrCreate(
            [
                'title' => $data['title'],
            ],
            [
                'message_id' => $message_id,
                'body' => $data['body'],
                'MediaUrl' => $data['MediaUrl'],
            ]
        );
    }
}
