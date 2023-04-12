<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Site::create([
            'title'       => 's&box News',
            'home_link'   => 'https://sbox.facepunch.com/',
            'link'        => 'https://sbox.facepunch.com/rss',
            'description' => 'The latest news posts from s&box',
            'fed_at'      => now()->startOfMonth(),
        ]);
        Site::create([
            'title'       => 'Репаки от Кролика: программы, софт - REPACK.ME',
            'home_link'   => 'https://repack.me/',
            'link'        => 'https://repack.me/rss.xml',
            'description' => 'Репаки от Кролика: программы, софт - REPACK.ME',
            'fed_at'      => now()->startOfMonth(),
        ]);
        Site::create([
            'title'       => 'Авторские репаки от ELCHUPACABRA - REPACK скачать',
            'home_link'   => 'https://lrepacks.net/',
            'link'        => 'https://lrepacks.net/rss.xml',
            'description' => 'Авторские репаки от ELCHUPACABRA - REPACK скачать',
            'fed_at'      => now()->startOfMonth(),
        ]);
        Site::create([
            'title'       => 'Сообщество администраторов игровых серверов HLmod.ru',
            'home_link'   => 'https://hlmod.ru/forums/',
            'link'        => 'https://hlmod.ru/forums/-/index.rss',
            'description' => "Сообщество администраторов игровых серверов по играм на движке Source (Counter-Strike, Team Fortress 2, Half-Life 2, Garry's Mod, Day of Defeat), GoldSource (CS 1.6)",
            'fed_at'      => now()->startOfMonth(),
        ]);
        Site::create([
            'title'       => "GMod-Fan Inc. - Всё для Garry's Mod",
            'home_link'   => 'https://www.gmod-fan.ru/',
            'link'        => 'https://www.gmod-fan.ru/rss.xml',
            'description' => "GMod-Fan Inc. - Всё для Garry's Mod",
            'fed_at'      => now()->startOfMonth(),
        ]);
        Site::create([
            'title'       => 'LostFilm.TV',
            'home_link'   => 'https://www.lostfilm.tv/',
            'link'        => 'https://www.lostfilm.tv/rss.xml',
            'description' => 'Свежачок от LostFilm.TV',
            'fed_at'      => now()->startOfMonth(),
        ]);
        Site::create([
            'title'       => 'Гуртом - торрент-толока',
            'home_link'   => 'https://toloka.to/',
            'link'        => 'https://toloka.to/rss.php?t=1&lite=1&cat=8&toronly=1&thumbs=1',
            'description' => "Об'єднаймо все українське гуртом! Завантажити або скачати фільми і мультфільми українською, HD, українську музику, літературу, ігри та українізації",
            'fed_at'      => now()->startOfMonth(),
        ]);
        Site::create([
            'title'       => 'Хабр / Новости ИТ',
            'home_link'   => 'https://habr.com/ru/news/',
            'link'        => 'https://habr.com/ru/rss/news/?fl=ru',
            'description' => 'Самое важное из ИТ мира на сегодня: новости науки и высоких технологий, разработки, гаджетов, игр, бизнеса и другие.',
            'fed_at'      => now()->startOfMonth(),
        ]);
    }
}
