<?php

namespace App\Tests\Unit\Twig\Extension;

use App\Twig\Extension\TablePaginationExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\TwigFunction;

final class TablePaginationExtensionTest extends TestCase
{
    /**
     * @group unit
     */
    public function testGetFunctions(): void
    {
        $template = \Mockery::mock(Environment::class);
        $requestStack = \Mockery::mock(RequestStack::class);

        $extension = new TablePaginationExtension($template, $requestStack);

        $result = $extension->getFunctions();

        $this->assertIsArray($result);
        $this->assertInstanceOf(TwigFunction::class, $result[0]);
        $this->assertEquals('tablePagination', $result[0]->getName());
    }
}
