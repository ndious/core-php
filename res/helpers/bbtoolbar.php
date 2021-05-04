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

namespace BackBee\Renderer\Helper;

use Exception;

/**
 * Class bbtoolbar
 *
 * @package BackBee\Renderer\Helper
 *
 * @author  Eric Chau <eric.chau@lp-digital.fr>
 */
class bbtoolbar extends AbstractHelper
{
    /**
     * Invoke.
     *
     * @return string
     * @throws Exception
     */
    public function __invoke(): string
    {
        $settings = $this->getRenderer()->getApplication()->getConfig()->getSection('cdn');

        return $this->getRenderer()->partial(
            'common/toolbar.html.twig',
            [
                'appJsUrl' => $settings['app_js_url'],
                'appCssUrl' => $settings['app_css_url'],
                'appRteUrl' => $settings['app_rte_url'],
                'imageDomain' => $settings['image_domain'],
            ]
        );
    }
}
