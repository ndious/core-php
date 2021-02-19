<?php

namespace BackBee\KnowledgeGraph\Schema;

use BackBee\Renderer\Renderer;
use Datetime;
use Exception;

/**
 * Class SchemaArticle
 *
 * @package BackBee\KnowledgeGraph\Schema
 *
 * @author Michel Baptista <michel.baptista@lp-digital.fr>
 */
class SchemaArticle implements SchemaInterface
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var SchemaContext
     */
    private $context;

    /**
     * SchemaArticle constructor.
     *
     * @param SchemaContext $context
     */
    public function __construct(SchemaContext $context)
    {
        $this->context = $context;
        $this->renderer = $context->getApplication()->getRenderer();
    }

    /**
     * Returns the Article  Schema data.
     *
     * @return array $data The Article schema.
     * @throws Exception
     * @throws Exception
     * @throws Exception
     */
    public function generate(): array
    {
        $cxData = $this->context->getData();

        $data = [
            '@type' => 'Article',
            '@id' => $this->renderer->getUri($cxData['url']) . SchemaIds::ARTICLE_HASH,
            'name' => $cxData['title'],
            'description' => $cxData['abstract'],
            'articleBody' => $cxData['contents'],
            'headline' => substr($cxData['title'], 0, 110),
            'url' => $this->renderer->getUri($cxData['url']),
            'isPartOf' => [
                '@id' => $this->renderer->getUri($cxData['url']) . SchemaIds::WEBPAGE_HASH,
            ],
            'mainEntityOfPage' => $this->renderer->getUri($cxData['url']) . SchemaIds::WEBPAGE_HASH,
            'publisher' => [
                '@id' => $this->renderer->getUri('/') . SchemaIds::ORGANIZATION_HASH,
            ],
            'author' => [
                '@id' => $this->renderer->getUri('/') . SchemaIds::ORGANIZATION_HASH,
            ],
            'dateCreated' => (new Datetime($cxData['created_at']))->format('c'),
            'dateModified' => (new Datetime($cxData['modified_at']))->format('c'),
            'datePublished' => (new Datetime($cxData['published_at']))->format('c'),
        ];

        $data = $this->addImage($data);

        return $data;
    }

    /**
     * Adds a article's image.
     *
     * @param array $data The Article schema.
     *
     * @return array $data The Article schema.
     */
    private function addImage(array $data): array
    {
        $cxData = $this->context->getData();

        $schemaId = $this->renderer->getUri($cxData['url']) . SchemaIds::PRIMARY_IMAGE_HASH;

        if (null === $cxData['image']) {
            $data['image'] = new SchemaImage($schemaId);

            return $data;
        }

        $schemaImage = new SchemaImage($schemaId);
        $data['image'] = $schemaImage->generate($this->renderer->getUri($cxData['image']['url']));

        return $data;
    }
}
