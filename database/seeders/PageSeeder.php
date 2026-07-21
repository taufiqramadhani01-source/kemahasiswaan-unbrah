<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Visi & Misi',
                'slug' => 'visi-misi',
                'content' => [
                    [
                        'id' => uniqid(),
                        'type' => 'hero',
                        'data' => [
                            'label' => 'Profil Kemahasiswaan',
                            'title' => 'Visi & Misi',
                            'highlight' => 'Lembaga',
                            'highlight_color' => '#c8b282',
                            'subtitle' => 'Arah kebijakan dan tujuan utama pengembangan kemahasiswaan Universitas Baiturrahmah.',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'two_col',
                        'data' => [
                            'ratio' => '50/50',
                            'left_heading' => 'Visi Kemahasiswaan',
                            'left_content' => '<p>Menjadi lembaga kemahasiswaan yang unggul dalam membina karakter, mengembangkan potensi, dan meningkatkan kesejahteraan mahasiswa berbasis nilai-nilai Islami pada tahun 2030.</p>',
                            'right_heading' => 'Misi Kemahasiswaan',
                            'right_content' => '<ol><li>Menyelenggarakan pembinaan karakter dan mental spiritual mahasiswa.</li><li>Memfasilitasi pengembangan minat, bakat, dan penalaran melalui organisasi kemahasiswaan.</li><li>Meningkatkan layanan kesejahteraan mahasiswa melalui beasiswa dan bimbingan konseling.</li></ol>'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'content' => [
                    [
                        'id' => uniqid(),
                        'type' => 'hero',
                        'data' => [
                            'label' => 'Profil',
                            'title' => 'Struktur',
                            'highlight' => 'Organisasi',
                            'highlight_color' => '#c8b282',
                            'subtitle' => 'Jajaran pimpinan dan staf pengelola Bidang Kemahasiswaan.',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'text',
                        'data' => [
                            'html' => '<p style="text-align:center;"><em>Gunakan blok tipe Gambar (Image) untuk mengunggah bagan struktur organisasi di sini.</em></p>'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Tracer Study',
                'slug' => 'tracer-study',
                'content' => [
                    [
                        'id' => uniqid(),
                        'type' => 'hero',
                        'data' => [
                            'label' => 'Alumni',
                            'title' => 'Tracer',
                            'highlight' => 'Study',
                            'highlight_color' => '#c8b282',
                            'subtitle' => 'Pemantauan jejak alumni dan evaluasi lulusan Universitas Baiturrahmah.',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'callout',
                        'data' => [
                            'color' => 'blue',
                            'text' => 'Bagi seluruh alumni Universitas Baiturrahmah yang lulus pada tahun lalu, mohon ketersediaannya untuk mengisi form Tracer Study pada link di bawah ini.'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Pusat Unduhan & Dokumen',
                'slug' => 'download',
                'content' => [
                    [
                        'id' => uniqid(),
                        'type' => 'hero',
                        'data' => [
                            'label' => 'Layanan',
                            'title' => 'Pusat',
                            'highlight' => 'Unduhan',
                            'highlight_color' => '#c8b282',
                            'subtitle' => 'Kumpulan pedoman, peraturan, SOP, dan dokumen resmi kemahasiswaan.',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'downloads',
                        'data' => [
                            'items' => [
                                ['label' => 'Pedoman Hasimaru', 'url' => '#', 'type' => 'pdf'],
                                ['label' => 'Pedoman SAPS', 'url' => '#', 'type' => 'pdf'],
                                ['label' => 'Peraturan Ormawa', 'url' => '#', 'type' => 'pdf'],
                                ['label' => 'SOP Layanan Mahasiswa', 'url' => '#', 'type' => 'pdf'],
                                ['label' => 'SK Ormawa 2024/2025', 'url' => '#', 'type' => 'pdf'],
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Program Kreativitas Mahasiswa (PKM)',
                'slug' => 'pkm',
                'content' => [
                    [
                        'id' => uniqid(),
                        'type' => 'hero',
                        'data' => [
                            'label' => 'Lomba & Kompetensi',
                            'title' => 'Program Kreativitas',
                            'highlight' => 'Mahasiswa (PKM)',
                            'highlight_color' => '#c8b282',
                            'subtitle' => 'Wadah mahasiswa untuk berinovasi dan berkreativitas di kancah nasional.',
                            'align' => 'center'
                        ]
                    ]
                ]
            ]
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'content' => $p['content'],
                    'is_published' => true
                ]
            );
        }
    }
}
