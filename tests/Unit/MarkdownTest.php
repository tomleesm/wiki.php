<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Markdown\Markdown;

class MarkdownTest extends TestCase
{
    /**
     * h1 to h6
     *
     * @return void
     */
    public function testHeadings()
    {
        $this->assertSame("\n<h1>h1 標題</h1>", Markdown::toHTML("[notoc]\n# h1 標題"));
        $this->assertSame("\n<h2>h2 標題</h2>", Markdown::toHTML("[notoc]\n## h2 標題"));
        $this->assertSame("\n<h3>h3 標題</h3>", Markdown::toHTML("[notoc]\n### h3 標題"));
        $this->assertSame("\n<h4>h4 標題</h4>", Markdown::toHTML("[notoc]\n#### h4 標題"));
        $this->assertSame("\n<h5>h5 標題</h5>", Markdown::toHTML("[notoc]\n##### h5 標題"));
        $this->assertSame("\n<h6>h6 標題</h6>", Markdown::toHTML("[notoc]\n###### h6 標題"));
    }
}
