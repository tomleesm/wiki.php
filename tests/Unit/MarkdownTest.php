<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Markdown\Markdown;

class MarkdownTest extends TestCase
{
    /**
     * h1 to h6
     *
     * @dataProvider headingsProvider
     * @return void
     */
    public function testHeadings($html, $markdown) {
        $this->assertSame($html, Markdown::toHTML($markdown));
    }

    public function headingsProvider() {
        return [
            ["\n<h1>h1 標題</h1>", "[notoc]\n# h1 標題"],
            ["\n<h2>h2 標題</h2>", "[notoc]\n## h2 標題"],
            ["\n<h3>h3 標題</h3>", "[notoc]\n### h3 標題"],
            ["\n<h4>h4 標題</h4>", "[notoc]\n#### h4 標題"],
            ["\n<h5>h5 標題</h5>", "[notoc]\n##### h5 標題"],
            ["\n<h6>h6 標題</h6>", "[notoc]\n###### h6 標題"],
        ];
    }
}
