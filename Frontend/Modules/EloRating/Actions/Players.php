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
class Players extends FrontendBaseBlock
{

    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    
    private function parse()
    {
        $players = FrontendEloRatingModel::getPlayersWithGames();
        
        $this->tpl->assign('players', $players);
    }
}
