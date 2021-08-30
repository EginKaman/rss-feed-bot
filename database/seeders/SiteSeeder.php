<?php

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
            'title' => 's&box News',
            'link' => 'https://sbox.facepunch.com/rss',
            'description' => 'The latest news posts from s&box',
            'fed_at' => now()->startOfMonth()
        ]);
        Site::create([
            'title' => 'Репаки от Кролика: программы, софт - REPACK.ME',
            'link' => 'https://repack.me/rss.xml',
            'description' => 'Репаки от Кролика: программы, софт - REPACK.ME',
            'fed_at' => now()->startOfMonth()
        ]);
        Site::create([
            'title' => 'Авторские репаки от ELCHUPACABRA - REPACK скачать',
            'link' => 'https://lrepacks.net/rss.xml',
            'description' => 'Авторские репаки от ELCHUPACABRA - REPACK скачать',
            'fed_at' => now()->startOfMonth()
        ]);
        Site::create([
            'title' => 'Сообщество администраторов игровых серверов HLmod.ru',
            'link' => 'https://hlmod.ru/forums/-/index.rss',
            'description' => "Сообщество администраторов игровых серверов по играм на движке Source (Counter-Strike, Team Fortress 2, Half-Life 2, Garry's Mod, Day of Defeat), GoldSource (CS 1.6)",
            'fed_at' => now()->startOfMonth()
        ]);
        Site::create([
            'title' => "GMod-Fan Inc. - Всё для Garry's Mod",
            'link' => 'https://www.gmod-fan.ru/rss.xml',
            'description' => "GMod-Fan Inc. - Всё для Garry's Mod",
            'fed_at' => now()->startOfMonth()
        ]);
        Site::create([
            'title' => 'LostFilm.TV',
            'link' => 'https://www.lostfilm.tv/rss.xml',
            'description' => 'Свежачок от LostFilm.TV',
            'fed_at' => now()->startOfMonth()
        ]);
        Site::create([
            'title' => 'Гуртом - торрент-толока',
            'link' => 'https://toloka.to/rss.php?t=1&lite=1&cat=8&toronly=1&thumbs=1',
            'description' => "Об'єднаймо все українське гуртом! Завантажити або скачати фільми і мультфільми українською, HD, українську музику, літературу, ігри та українізації",
            'fed_at' => now()->startOfMonth()
        ]);
    }
}
