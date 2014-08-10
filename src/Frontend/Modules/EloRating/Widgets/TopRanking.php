<?php

namespace Frontend\Modules\EloRating\Widgets;


use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;

/**
 * This is a widget that shows the top of the ranking.
 * How big the top is can be set in a setting.
 *
 * @author Stef Bastiaansen <stef@megasnort.be>
 */
class TopRanking extends FrontendBaseWidget
{

    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }


    private function parse()
    {
        
        $topRanking = FrontendEloRatingModel::getTopRanking();

        $this->tpl->assign('minimum_played_games', FrontendModel::getModuleSetting('EloRating', 'minimum_played_games', 5));
        $this->tpl->assign('topRankingCount', FrontendModel::getModuleSetting('EloRating', 'top_ranking_count', 10));
        $this->tpl->assign('widgetTopRanking', $topRanking);
        $this->tpl->assign('playerUrl', FrontendNavigation::getUrlForBlock('EloRating', 'Player'));
    }
}
