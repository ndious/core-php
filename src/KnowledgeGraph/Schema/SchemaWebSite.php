<?php

/*
 * Copyright (c) 2011-2021 Lp Digital
 *
 * This file is part of BackBee Standalone.
 *
 * BackBee is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BackBee Standalone. If not, see <https://www.gnu.org/licenses/>.
 */

namespace BackBee\KnowledgeGraph\Schema;

use BackBee\Renderer\Renderer;

/**
 * Class SchemaWebSite
 *
 * @package BackBee\KnowledgeGraph\Schema
 *
 * @author Michel Baptista <michel.baptista@lp-digital.fr>
 */
class SchemaWebSite implements SchemaInterface
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var array
     */
    private $config;

    /**
     * SchemaWebSite constructor.
     *
     * @param SchemaContext $context
     */
    public function __construct(SchemaContext $context)
    {
        $this->config = $context->getConfig();
        $this->renderer = $context->getApplication()->getRenderer();
    }

    /**
     * Returns the WebSite  Schema data.
     *
     * @return array $data The WebSite schema.
     */
    public function generate(): array
    {
        $data = [
            '@type' => 'WebSite',
            '@id' => $this->renderer->getUri('/') . SchemaIds::WEBSITE_HASH,
            'name' => $this->config['website_name'],
            'url' => $this->renderer->getUri('/'),
            'publisher' => [
                '@id' => $this->renderer->getUri('/') . SchemaIds::ORGANIZATION_HASH,
            ],
        ];

        if (null !== $this->config['website_description']) {
            $data['description'] = $this->config['website_description'];
        }

        $data = $this->processSearchSection($data);

        return $data;
    }

    /**
     * Adds the internal search JSON LD code to the homepage if it's not disabled.
     *
     * @param array $data The website data array.
     *
     * @return array $data
     */
    private function processSearchSection(array $data): array
    {
        if ((null === $this->config['website_search']) || (null === $this->config['website_search_term_string'])) {
            return $data;
        }

        $data['potentialAction'] = [
            '@type' => 'SearchAction',
            'target' => $this->renderer->getUri($this->config['website_search']) .
                $this->config['website_search_term_string'] . '{search_term_string}',
            'query-input' => 'required name=search_term_string',
        ];

        return $data;
    }
}
