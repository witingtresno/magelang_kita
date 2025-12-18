<?php

namespace App\Console\Commands;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SimpleXMLElement;


class SyncAntaraNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-antara-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ambil berita dari RSS Antara';

    protected array $feeds = [
        'terkini' => 'https://www.antaranews.com/rss/terkini.xml',
        'photo' => 'https://www.antaranews.com/rss/photo.xml',
        'video' => 'https://www.antaranews.com/rss/video.xml',
        'top' => 'https://www.antaranews.com/rss/top-news.xml',
        'politik' => 'https://www.antaranews.com/rss/politik.xml',
        'hukum' => 'https://www.antaranews.com/rss/hukum.xml',
        'ekonomi' => [
            'main' => 'https://www.antaranews.com/rss/ekonomi.xml',
            'finansial' => 'https://www.antaranews.com/rss/ekonomi-finansial.xml',
            'bisnis' => 'https://www.antaranews.com/rss/ekonomi-bisnis.xml',
            'bursa' => 'https://www.antaranews.com/rss/ekonomi-bursa.xml',
        ],
        'metro' => [
            'main' => 'https://www.antaranews.com/rss/metro.xml',
            'kriminalitas' => 'https://www.antaranews.com/rss/metro-kriminalitas.xml',
            'lintas_kota' => 'https://www.antaranews.com/rss/metro-lintas-kota.xml',
            'lenggang_jakarta' => 'https://www.antaranews.com/rss/metro-lenggang-jakarta.xml',
        ],
        'sepak_bola' => [
            'main' => 'https://www.antaranews.com/rss/sepakbola.xml',
            'indonesia' => 'https://www.antaranews.com/rss/sepakbola-liga-indonesia.xml',
            'international' => 'https://www.antaranews.com/rss/sepakbola-internasional.xml',
            'liga_inggris' => 'https://www.antaranews.com/rss/sepakbola-liga-inggris-premier.xml',
            'liga_spanyol' => 'https://www.antaranews.com/rss/sepakbola-liga-spanyol.xml',
            'liga_italia' => 'https://www.antaranews.com/rss/sepakbola-liga-italia-seri-a.xml',
            'liga_champions' => 'https://www.antaranews.com/rss/sepakbola-liga-champions.xml',
            'other' => 'https://www.antaranews.com/rss/sepakbola-liga-liga-dunia.xml',
        ],
        'olahraga' => [
            'main' => 'https://www.antaranews.com/rss/olahraga.xml',
            'bulutangkis' => 'https://www.antaranews.com/rss/olahraga-bulutangkis.xml',
            'bola_basket' => 'https://www.antaranews.com/rss/olahraga-bola-basket.xml',
            'tenis' => 'https://www.antaranews.com/rss/olahraga-tenis.xml',
            'balap' => 'https://www.antaranews.com/rss/olahraga-balap.xml',
            'all_sport' => 'https://www.antaranews.com/rss/olahraga-all-sport.xml',
        ],
        'humaniora' => 'https://www.antaranews.com/rss/humaniora.xml',
        'lifestyle' => 'https://www.antaranews.com/rss/lifestyle.xml',
        'hiburan' => 'https://www.antaranews.com/rss/hiburan.xml',
        'dunia' => [
            'main' => 'https://www.antaranews.com/rss/dunia.xml',
            'asean' => 'https://www.antaranews.com/rss/dunia-asean.xml',
            'internasional' => 'https://www.antaranews.com/rss/dunia-internasional.xml',
            'international_corner' => 'https://www.antaranews.com/rss/dunia-internasional-corner.xml',
        ],
        'infografik' => 'https://www.antaranews.com/rss/infografik.xml',
        'tekno' => 'https://www.antaranews.com/rss/tekno.xml',
        'otomotif' => [
            'main' => 'https://www.antaranews.com/rss/otomotif.xml',
            'umum' => 'https://www.antaranews.com/rss/otomotif-umum.xml',
            'go_green' => 'https://www.antaranews.com/rss/otomotif-go-green.xml',
            'prototype' => 'https://www.antaranews.com/rss/otomotif-prototype.xml',
            'review' => 'https://www.antaranews.com/rss/otomotif-review.xml',
        ],
        'warta_bumi' => 'https://www.antaranews.com/rss/warta-bumi.xml',
        'rilis_pers' => 'https://www.antaranews.com/rss/rilis-pers.xml',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->feeds as $category => $urls) {
            if (is_array($urls)) {
                foreach ($urls as $subCategory => $url) {
                    $categoryKey = $subCategory === 'main' ? $category : "{$category}_{$subCategory}";
                    $this->fetchFeed($categoryKey, $url);
                }
            } else {
                $this->fetchFeed($category, $urls);
            }
        }
    }

    private function fetchFeed(string $category, string $url): void
    {
        $response = Http::get($url);

        if (! $response->ok()) {
            $this->warn("Gagal fetch {$url}");
            return;
        }

        $xml = simplexml_load_string($response->body());

        if (! $xml instanceof SimpleXMLElement || empty($xml->channel->item)) {
            $this->warn("RSS invalid: {$url}");
            return;
        }

        foreach ($xml->channel->item as $item) {
            $title = (string) $item->title;
            $link = (string) $item->link;
            $slug = Str::slug($title);
            if (News::whereSlug($slug)->where('source_url', '!=', $link)->exists()) {
                $slug = Str::slug($title . '-' . Str::random(6));
            }
            $guid = (string) ($item->guid ?? $item->link);

            News::updateOrCreate(
                ['guid' => $guid],
                [
                    'source_url' => $link,
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => Str::limit(strip_tags((string) $item->description), 200),
                    'content' => (string) $item->description,
                    'category' => $category,
                    'source' => 'Antara',
                    'published_at' => Carbon::parse((string) $item->pubDate),
                    'image_url' => optional($item->enclosure)['url'] ?? null,
                    'is_published' => true,
                ]
            );
        }
        $this->info("Feed {$category} tersinkron.");
    }
}
