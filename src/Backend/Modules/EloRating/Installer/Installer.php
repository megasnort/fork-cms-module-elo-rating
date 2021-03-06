<?php

namespace Backend\Modules\EloRating\Installer;


use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the Elo-Rating module
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Installer extends ModuleInstaller
{
    public function install()
    {
        $this->importSQL(dirname(__FILE__) . '/Data/install.sql');

        $this->addModule('EloRating');

        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setSetting('EloRating', 'minimum_played_games', 5);
        $this->setSetting('EloRating', 'top_ranking_count', 5);
        $this->setSetting('EloRating', 'top_latest_games', 5);

        $this->makeSearchable('EloRating');

        $this->setModuleRights(1, 'EloRating');

        $this->setActionRights(1, 'EloRating', 'Index');
        $this->setActionRights(1, 'EloRating', 'Add');
        $this->setActionRights(1, 'EloRating', 'Edit');
        $this->setActionRights(1, 'EloRating', 'Delete');
        $this->setActionRights(1, 'EloRating', 'IndexPlayers');
        $this->setActionRights(1, 'EloRating', 'AddPlayer');
        $this->setActionRights(1, 'EloRating', 'EditPlayer');
        $this->setActionRights(1, 'EloRating', 'DeletePlayer');
        $this->setActionRights(1, 'EloRating', 'Settings');

        $navigationModulesId = $this->setNavigation(null, 'Modules');

        $navigationEloRatingId = $this->setNavigation($navigationModulesId, 'EloRating');

        $this->setNavigation(
            $navigationEloRatingId,
            'Games',
            'elo_rating/index',
            array('elo_rating/add', 'elo_rating/edit')
        );

        $this->setNavigation(
            $navigationEloRatingId,
            'Players',
            'elo_rating/index_players',
            array('elo_rating/add_player', 'elo_rating/edit_player')
        );

        $navigationSettingsId = $this->setNavigation(null, 'Settings');

        $navigationEloRatingId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationEloRatingId, 'EloRating', 'elo_rating/settings');

        $this->insertExtra('EloRating', 'block', 'Ranking', null, null, 'N', 1701);
        $this->insertExtra('EloRating', 'block', 'Games', 'Games', null, 'N', 1702);
        $this->insertExtra('EloRating', 'widget', 'GamesPlayed', 'GamesPlayed', null, 'N', 1704);
        $this->insertExtra('EloRating', 'widget', 'TopRanking', 'TopRanking', null, 'N', 1705);
        $this->insertExtra('EloRating', 'widget', 'LatestGames', 'LatestGames', null, 'N', 1706);
        $this->insertExtra('EloRating', 'widget', 'AddGame', 'AddGame', null, 'N', 1707);
    }
}
