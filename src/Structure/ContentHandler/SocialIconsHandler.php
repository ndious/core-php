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

namespace BackBeeCloud\Structure\ContentHandler;

use BackBee\ClassContent\AbstractClassContent;
use BackBee\ClassContent\Social\Icons;
use BackBeeCloud\Structure\ContentHandlerInterface;

/**
 * @author Eric Chau <eric.chau@lp-digital.fr>
 */
class SocialIconsHandler implements ContentHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(AbstractClassContent $content, array $data)
    {
         if (!$this->supports($content)) {
            return;
        }

        $result = $content->getParamValue('social');
        foreach ($data as $id => $url) {
            if (isset($result[$id])) {
                $result[$id]['url'] = (string) $url;
                $result[$id]['enable'] = true;
            }
        }

        $content->setParam('social', $result);
    }

    /**
     * {@inheritdoc}
     */
    public function handleReverse(AbstractClassContent $content, array $data = [])
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractClassContent $content)
    {
        return $content instanceof Icons;
    }
}
