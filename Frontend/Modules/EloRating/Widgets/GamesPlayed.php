<?php

namespace Frontend\Modules\EloRating\Widgets;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;

/**
 * This is a widget that shows a simple overview about how many games were played etc.
 *
 * @author Stef Bastiaansen <stef@megasnort.be>
 */
class GamesPlayed extends FrontendBaseWidget
{

    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    private function parse()
    {
        $gamesPlayed = FrontendEloRatingModel::getGamesPlayed();
        
        $this->tpl->assign('widgetGamesPlayed', $gamesPlayed);
        
    }
}
