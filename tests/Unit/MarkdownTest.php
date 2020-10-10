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
        $this->assertSame("\n<h1>this is h1</h1>", Markdown::toHTML("[notoc]\n# this is h1"));
        $this->assertSame("\n<h2>this is h2</h2>", Markdown::toHTML("[notoc]\n## this is h2"));
        $this->assertSame("\n<h3>this is h3</h3>", Markdown::toHTML("[notoc]\n### this is h3"));
        $this->assertSame("\n<h4>this is h4</h4>", Markdown::toHTML("[notoc]\n#### this is h4"));
        $this->assertSame("\n<h5>this is h5</h5>", Markdown::toHTML("[notoc]\n##### this is h5"));
        $this->assertSame("\n<h6>this is h6</h6>", Markdown::toHTML("[notoc]\n###### this is h6"));
    }
}
