<?php

namespace Frontend\Modules\EloRating\Widgets;


use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;

/**
 * This is a widget that shows the most recent games. 
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */

class LatestGames extends FrontendBaseWidget
{

    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    
    private function parse()
    {
        $widgetLatestGames = FrontendEloRatingModel::getLatestGames();
        
        $this->tpl->assign('widgetLatestGames', $widgetLatestGames);

        $playerUrl = FrontendNavigation::getUrlForBlock('EloRating', 'Player');

        // If the Players page is not found, no link should be displayed
        if (!strpos($playerUrl, '404')) {
            $this->tpl->assign('playerUrl', $playerUrl);
        }
    }
}