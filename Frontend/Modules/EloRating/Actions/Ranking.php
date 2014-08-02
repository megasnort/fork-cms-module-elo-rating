<?php

namespace Frontend\Modules\EloRating\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;

/**
 * This is the Ranking Action.
 * It shows all the active players with enough games played
 * ordered by Elo-rating (and games played)
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Ranking extends FrontendBaseBlock
{

    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    
    private function parse()
    {
        $ranking = FrontendEloRatingModel::getTotalRanking();
        
        
        $this->tpl->assign('minimum_played_games', FrontendModel::getModuleSetting('EloRating', 'minimum_played_games', 5));
        $this->tpl->assign('ranking', $ranking);

        $playerUrl = FrontendNavigation::getUrlForBlock('EloRating', 'Players');

        // If the Players page is not found, no link should be displayed
        if (!strpos($playerUrl, '404')) {
            $this->tpl->assign('playerUrl', $playerUrl);
        }
    }
}
