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
 * This is the Player Action. It shows all the (active) players with all the games they played. 
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Games extends FrontendBaseBlock
{

    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    
    private function parse()
    {
        $games = FrontendEloRatingModel::getAllGames();
        
        //@todo: make edit settings page
        $p1Name = FrontendModel::getModuleSetting('EloRating', 'p1_name', 'white');
        $p2Name = FrontendModel::getModuleSetting('EloRating', 'p2_name', 'black');

        $this->tpl->assign('p1_name', $p1Name);
        $this->tpl->assign('p2_name', $p2Name);
        
        $this->tpl->assign('games', $games);

        $playerUrl = FrontendNavigation::getUrlForBlock('EloRating', 'Players');

        // If the Players page is not found, no link should be displayed
        if (!strpos($playerUrl, '404')) {
            $this->tpl->assign('playerUrl', $playerUrl);
        }
    }
}
