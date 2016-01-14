<?php

namespace Frontend\Modules\EloRating\Widgets;

use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;

/**
 * This is a widget that shows a simple overview about how many games were played etc.
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
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
