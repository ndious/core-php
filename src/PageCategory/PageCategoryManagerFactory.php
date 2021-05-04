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

namespace BackBeeCloud\PageCategory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Eric Chau <eric.chau@lp-digital.fr>
 */
class PageCategoryManagerFactory
{
    const PAGE_CATEGORY_PROVIDER_SERVICE_TAG = 'page_category.provider';

    public static function createPageCategoryManager(ContainerBuilder $dic, EntityManagerInterface $entityManager)
    {
        $categoryProviders = [];
        foreach ($dic->findTaggedServiceIds(self::PAGE_CATEGORY_PROVIDER_SERVICE_TAG) as $serviceId => $data) {
            if ($dic->has($serviceId)) {
                $categoryProviders[] = $dic->get($serviceId);
            }
        }

        return new PageCategoryManager($entityManager, $categoryProviders);
    }
}
