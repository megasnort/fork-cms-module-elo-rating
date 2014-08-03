<?php

namespace Frontend\Modules\EloRating\Actions;


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
        
        $this->tpl->assign('games', $games);

        $playerUrl = FrontendNavigation::getUrlForBlock('EloRating', 'Players');

        // If the Players page is not found, no link should be displayed
        if (!strpos($playerUrl, '404')) {
            $this->tpl->assign('playerUrl', $playerUrl);
        }
    }
}


/*
  // add caching later ...
        $this->tpl->cache(FRONTEND_LANGUAGE . '_blogWidgetArchiveCache', (24 * 60 * 60));

        // if the widget isn't cached, assign the variables
        if (!$this->tpl->isCached(FRONTEND_LANGUAGE . '_blogWidgetArchiveCache')) {
            // get the numbers
            $this->tpl->assign('widgetBlogArchive', FrontendBlogModel::getArchiveNumbers());
        }
 */