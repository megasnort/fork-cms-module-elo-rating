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
 * This is a widget with the blog-categories
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

        $topRankingCount = FrontendModel::getModuleSetting('EloRating', 'top_ranking_count', 10);

        $this->tpl->assign('minimum_played_games', FrontendModel::getModuleSetting('EloRating', 'minimum_played_games', 5));
        $this->tpl->assign('widgetTopRanking', $topRanking);
        $this->tpl->assign('topRankingCount', $topRankingCount);


        $playerUrl = FrontendNavigation::getUrlForBlock('EloRating', 'Players');

        // If the Players page is not found, no link should be displayed
        if (!strpos($playerUrl, '404')) {
            $this->tpl->assign('playerUrl', $playerUrl);
        }

        
    }
}
