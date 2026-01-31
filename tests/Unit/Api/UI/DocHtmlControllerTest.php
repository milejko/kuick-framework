<?php

namespace Tests\Unit\Kuick\Framework\Api\UI;

use Kuick\Framework\Api\UI\DocHtmlController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DocHtmlController::class)]
class DocHtmlControllerTest extends TestCase
{
    public function testIfAllValuesAreReturned(): void
    {
        $doc = new DocHtmlController();
        $response = $doc();
        $this->assertEquals(200, $response->getStatusCode());
        //checking body size
        $this->assertEquals(1056, $response->getBody()->getSize());
    }
}
