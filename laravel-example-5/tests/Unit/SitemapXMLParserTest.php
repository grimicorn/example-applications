<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Parsers\SitemapXML;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class SitemapXMLParserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->copySitemapStubs();
    }


    public function tearDown()
    {
        $this->clearSitemapStubs();

        parent::tearDown();
    }

    /**
    * @test
    */
    public function it_parses_a_sitemap_xml_index_file()
    {
        $pages = (new SitemapXML(
            $path = url('stubs/sitemaps/sitemap-index.xml')
        ))->parse();

        $this->assertEquals(
            $this->sitemapPageUrls()->sort()->toArray(),
            $pages->sort()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_parses_a_sitemap_xml_file()
    {
        $pages1 = (new SitemapXML(
            $path1 = url('stubs/sitemaps/sitemap1.xml')
        ))->parse();

        $this->assertEquals(
            $this->sitemap1PageUrls()->sort()->toArray(),
            $pages1->sort()->toArray()
        );

        $pages2 = (new SitemapXML(
            $path2 = url('stubs/sitemaps/sitemap2.xml')
        ))->parse();

        $this->assertEquals(
            $this->sitemap2PageUrls()->sort()->toArray(),
            $pages2->sort()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_parses_a_sitemap_xml_gz_index_file()
    {
        $pages = (new SitemapXML(
            $path = url('stubs/sitemaps/sitemap-index.xml.gz')
        ))->parse();

        $this->assertEquals(
            $this->sitemapPageUrls()->sort()->toArray(),
            $pages->sort()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_parses_a_sitemap_xml_gz_file()
    {
        $pages1 = (new SitemapXML(
            $path1 = url('stubs/sitemaps/sitemap1.xml.gz')
        ))->parse();

        $this->assertEquals(
            $this->sitemap1PageUrls()->sort()->toArray(),
            $pages1->sort()->toArray()
        );

        $pages2 = (new SitemapXML(
            $path2 = url('stubs/sitemaps/sitemap2.xml.gz')
        ))->parse();

        $this->assertEquals(
            $this->sitemap2PageUrls()->sort()->toArray(),
            $pages2->sort()->toArray()
        );
    }
}
