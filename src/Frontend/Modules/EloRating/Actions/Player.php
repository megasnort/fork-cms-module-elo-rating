<?php

namespace Frontend\Modules\EloRating\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;

/**
 * This is the player-action. Is shows some stats and graphs about a player
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Player extends FrontendBaseBlock
{
   
    /**
     * Record with the player that will be shown. 
     *
     * @var    array
     */
    private $record;


    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->getData();
        $this->parse();
    }

    private function getData()
    {
        // check if a player is not given, go to the index
        if ($this->URL->getParameter(1) === null) {
            $this->redirect(FrontendNavigation::getUrlForBlock('EloRating', 'Ranking'));
        } else if (!($this->record = FrontendEloRatingModel::getPlayer($this->URL->getParameter(1)))) {
            $this->redirect(FrontendNavigation::getURL(404));
        }
    }

    private function parse()
    {
        $this->header->addJS('/src/Frontend/Modules/EloRating/Js/d3.v3.min.js', false);
        $this->header->addCSS('/src/Frontend/Modules/EloRating/Css/Player.css', false);
        
        //add into breadcrumb
        $this->breadcrumb->addElement($this->record['name']);
    
        // set meta
        $this->header->setPageTitle($this->record['meta_title'], ($this->record['meta_title_overwrite'] == 'Y'));
        $this->header->addMetaDescription(
            $this->record['meta_description'],
            ($this->record['meta_description_overwrite'] == 'Y')
        );
        $this->header->addMetaKeywords(
            $this->record['meta_keywords'],
            ($this->record['meta_keywords_overwrite'] == 'Y')
        );
        
        $this->tpl->assign('playerUrl', FrontendNavigation::getURLForBlock('EloRating', 'Player'));
        
        $this->tpl->assign('player', $this->record);

        if (isset($this->record["history"])) {
            $this->addJSData('history', $this->record["history"]);
        }
    }
}
