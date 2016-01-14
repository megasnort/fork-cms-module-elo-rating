<?php

namespace Frontend\Modules\EloRating;

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
     * @var    string
     */
    protected $defaultAction = 'Ranking';

    /**
     * The disabled actions
     *
     * @var    array
     */
    protected $disabledActions = array();
}
