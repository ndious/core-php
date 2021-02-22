<?php

namespace BackBeePlanet\Sitemap\Query;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Interface CollectorInterface
 *
 * A collector builds an array of pages regarding a provided set of discriminators.
 * The array items are indexed by a site name URL generated by the provided
 * URL pattern.
 *
 * @package BackBeePlanet\Sitemap\Query
 */
interface CollectorInterface extends ContainerAwareInterface
{
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null);

    /**
     * Sets the URL pattern for this collector.
     *
     * @param string $urlPattern The URL pattern to set.
     */
    public function setUrlPattern(string $urlPattern);

    /**
     * Sets the selection limits.
     *
     * @param array $limits An array of limits.
     */
    public function setLimits(array $limits);

    /**
     * Collects objects and organizes them regarding discriminators.
     *
     * @param array $preset Optional, an array of preset values for discriminators.
     *
     * @return Paginator[]         An array of matching objects indexed by their sitemap URLs.
     */
    public function collect(array $preset = []): array;

    /**
     * Gets an array of discriminators accepted by this collector.
     *
     * @return string[] An array of accepted discriminators.
     */
    public function getAcceptedDiscriminators(): array;
}
