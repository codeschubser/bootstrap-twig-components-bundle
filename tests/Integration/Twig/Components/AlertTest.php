<?php

declare(strict_types=1);

namespace Codeschubser\Bundle\BootstrapTwigComponentsBundle\Tests\Integration\Twig\Components;

use Codeschubser\Bundle\BootstrapTwigComponentsBundle\BootstrapTwigComponentsBundle;
use Codeschubser\Bundle\BootstrapTwigComponentsBundle\DependencyInjection\BootstrapTwigComponentsExtension;
use Codeschubser\Bundle\BootstrapTwigComponentsBundle\Tests\Common\AbstractComponentsTestCase;
use Codeschubser\Bundle\BootstrapTwigComponentsBundle\Twig\Component\Alert;
use Codeschubser\Bundle\BootstrapTwigComponentsBundle\Twig\Component\Button;
use Codeschubser\Bundle\BootstrapTwigComponentsBundle\Twig\Component\Icon;
use Codeschubser\Bundle\BootstrapTwigComponentsBundle\Twig\Component\Option\Variant;
use Codeschubser\Bundle\BootstrapTwigComponentsBundle\Twig\Component\Option\VariantInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Alert::class)]
#[UsesClass(Button::class)]
#[UsesClass(Variant::class)]
#[UsesClass(Icon::class)]
#[UsesClass(BootstrapTwigComponentsBundle::class)]
#[UsesClass(BootstrapTwigComponentsExtension::class)]
#[Group('alert')]
final class AlertTest extends AbstractComponentsTestCase
{
    public function testComponentMount(): void
    {
        /** @var Alert $component */
        $component = $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'danger',
            ],
        );

        $this->assertInstanceOf(Alert::class, $component);
        $this->assertInstanceOf(VariantInterface::class, $component);
        $this->assertSame(Variant::from('danger'), $component->variant);
        $this->assertFalse($component->dismissible);
    }

    public function testComponentMountFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'foobar',
            ],
        );
    }

    public function testComponentWithDismissibleMount(): void
    {
        $component = $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'success',
                'dismissible' => true,
            ],
        );

        $this->assertInstanceOf(Alert::class, $component);
        $this->assertSame(Variant::from('success'), $component->variant);
        $this->assertTrue($component->dismissible);
    }

    public function testComponentWithTitleMount(): void
    {
        $component = $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'danger',
                'title' => 'Lorem ipsum',
            ],
        );

        $this->assertInstanceOf(Alert::class, $component);
        $this->assertSame(Variant::from('danger'), $component->variant);
        $this->assertStringContainsString('d-flex align-items-start', $component->getDefaultCssClass());
    }

    public function testComponentWithIconMount(): void
    {
        $component = $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'danger',
                'icon' => 'bi bi-info-circle',
            ],
        );

        $this->assertInstanceOf(Alert::class, $component);
        $this->assertSame(Variant::from('danger'), $component->variant);
        $this->assertStringContainsString('d-flex align-items-start', $component->getDefaultCssClass());
    }

    public function testComponentRenders(): void
    {
        $rendered = $this->renderTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'success',
            ],
        );

        $this->assertCount(1, $component = $rendered->crawler()->filter('div.alert-success'));
        $this->assertCount(1, $component->filter('[role="alert"]'));
    }

    public function testComponentWithDismissibleRenders(): void
    {
        $rendered = $this->renderTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'warning',
                'dismissible' => true,
            ],
        );

        $this->assertCount(1, $component = $rendered->crawler()->filter('div.alert-dismissible'));
        $this->assertCount(1, $component->filter('button[data-bs-dismiss="alert"]'));
    }

    public function testComponentWithTitleRenders(): void
    {
        $rendered = $this->renderTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'warning',
                'title' => 'Lorem ipsum',
            ],
        );

        $this->assertCount(1, $rendered->crawler()->filter('.alert-heading'));
    }

    public function testComponentWithIconRenders(): void
    {
        $rendered = $this->renderTwigComponent(
            name: Alert::class,
            data: [
                'variant' => 'warning',
                'icon' => 'bi-exclamation-triangle-fill',
            ],
        );

        $this->assertSame('bi-exclamation-triangle-fill', $rendered->crawler()->filter('span.icon.me-2>i')->attr('class'));
    }
}
