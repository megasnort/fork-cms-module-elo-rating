<?php

namespace Frontend\Modules\EloRating\Actions;


use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;

/**
 * This is the Games Action. It shows all the games games played. 
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
        
        $this->tpl->assign('games', $games);

        $this->tpl->assign('playerUrl', FrontendNavigation::getUrlForBlock('EloRating', 'Player'));
    }
}
