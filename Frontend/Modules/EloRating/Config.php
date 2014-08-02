<?php

namespace Frontend\Modules\EloRating;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Base\Config as FrontendBaseConfig;

/**
 * This is the configuration-object
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Config extends FrontendBaseConfig
{
    /**
     * The default action
     *
     * @var	string
     */
    protected $defaultAction = 'Ranking';

    /**
     * The disabled actions
     *
     * @var	array
     */
    protected $disabledActions = array();
}
