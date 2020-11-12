<?php

use Illuminate\Database\Seeder;
use App\Article;

class MarkdownSyntaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = __DIR__ . '/markdown-syntax.md';

        $article          = new Article();
        $article->title   = 'Markdown Syntax';
        $article->content = file_get_contents($path);
        $article->save();
    }
}
