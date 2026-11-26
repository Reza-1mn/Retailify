<?php

namespace App\Helpers;

class CategoryIconHelper
{
    /**
     * Daftar ikon standar yang didukung helper.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            'tag'      => 'Label',
            'rice'     => 'Sembako',
            'home'     => 'Rumah',
            'sparkles' => 'Kebersihan',
            'flame'    => 'Bumbu & Pedas',
            'milk'     => 'Susu & Olahan',
            'bread'    => 'Roti & Bakery',
            'coffee'   => 'Minuman',
            'cookie'   => 'Makanan Ringan',
            'pencil'   => 'Alat Tulis',
            'skincare' => 'Perawatan Wajah',
            'hair'     => 'Perawatan Rambut',
            'health'   => 'Kesehatan',
            'baby'     => 'Bayi & Anak',
        ];
    }

    /**
     * Menentukan key ikon secara otomatis berdasarkan nama kategori.
     */
    public static function resolve(?string $icon, ?string $categoryName = ''): string
    {
        $validKeys = array_keys(self::options());

        if (!empty($icon) && $icon !== 'auto' && in_array($icon, $validKeys, true)) {
            return $icon;
        }

        $lower = mb_strtolower(trim((string) $categoryName));

        if ($lower === '') {
            return 'tag';
        }

        $rules = [
            'skincare' => ['wajah', 'muka', 'kulit', 'skincare', 'serum', 'toner', 'moisturizer', 'sunscreen', 'facial', 'lip', 'kosmetik', 'kecantikan'],
            'hair'     => ['rambut', 'hair', 'shampo', 'shampoo', 'conditioner', 'kondisioner', 'hair tonic', 'hair vitamin', 'pomade', 'sisir'],
            'health'   => ['sehat', 'kesehatan', 'obat', 'apotek', 'vitamin', 'suplemen', 'masker', 'plester', 'minyak kayu putih', 'p3k', 'herbal'],
            'baby'     => ['bayi', 'balita', 'anak', 'popok', 'diaper', 'dot', 'telon'],
            'coffee'   => ['minum', 'kopi', 'teh', 'air', 'jus', 'soda', 'es '],
            'milk'     => ['susu', 'keju', 'yogurt', 'mentega', 'olahan'],
            'bread'    => ['roti', 'kue', 'bakery', 'pastry', 'donat', 'bolu'],
            'cookie'   => ['snack', 'ringan', 'keripik', 'biskuit', 'coklat', 'wafer', 'permen'],
            'rice'     => ['sembako', 'beras', 'pokok', 'gula', 'minyak', 'tepung', 'telur', 'garam'],
            'flame'    => ['bumbu', 'masak', 'sambal', 'pedas', 'cabai', 'cabe', 'rokok', 'korek'],
            'sparkles' => ['mandi', 'bersih', 'sabun', 'deterjen', 'pewangi', 'cuci', 'tisu', 'lantai'],
            'home'     => ['rumah', 'tangga', 'perabot', 'dapur', 'lampu'],
            'pencil'   => ['tulis', 'atk', 'buku', 'kertas', 'pulpen', 'pensil'],
        ];

        foreach ($rules as $key => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($lower, $keyword)) {
                    return $key;
                }
            }
        }

        return 'tag';
    }
}