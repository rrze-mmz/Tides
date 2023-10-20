<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('article')->insert([
            'title_en' => 'Contact',
            'content_en' => 'Contact page',
            'title_de' => 'Kontakt',
            'content_de' => 'Kontakt Seite',
            'slug' => 'contact',
            'is_published' => true,
        ]);

        DB::table('article')->insert([
            'title_en' => 'FAQ',
            'content_en' => 'FAQ page',
            'title_de' => 'FAQ',
            'content_de' => 'FAQ Seite',
            'slug' => 'faq',
            'is_published' => true,
        ]);
    }
}
