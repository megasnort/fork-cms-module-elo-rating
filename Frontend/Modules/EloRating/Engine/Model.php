<?php

namespace Frontend\Modules\EloRating\Engine;


use Frontend\Core\Engine\Language as FL;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Core\Engine\Url as FrontendURL;

/**
 * In this file we store all generic functions that we will be using in the Elo Rating module
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Model
{
   
    const QRY_PLAYERS = "SELECT
                p.id,
                p.name,
                p.current_elo as elo,
                p.games_played,
                p.won,
                p.lost,
                p.draws
            FROM
                elo_rating_players AS p
            WHERE
                `active` = ?
   
            ORDER BY 
                p.name";


    const QRY_RANKING = "SELECT
                p.id,
                p.name,
                p.current_elo as elo,
                p.games_played,
                p.won,
                p.lost,
                p.draws,
                @pos := IFNULL(@pos,0) + 1,
                IF(@prevElo = p.current_elo, '-', @pos) AS position,
                @prevElo := p.current_elo
            FROM
                elo_rating_players AS p
            WHERE
                `active` = ?
                 AND
                `games_played` >= ?
            ORDER BY 
                p.current_elo DESC,
                p.games_played DESC,
                p.won DESC,
                p.draws DESC,
                p.lost ASC";

    const QRY_GAMES = 'SELECT
                g.id,
                g.player1,
                g.player2,
                p1.name as player1name,
                p2.name as player2name,
                g.score1,
                g.score2,
                UNIX_TIMESTAMP(g.`date`) AS `date`,
                SUBSTRING(g.`date`,1, 10) AS compareDate
            FROM
                elo_rating_games AS g
            INNER JOIN
                elo_rating_players AS p1
                ON p1.id = g.player1
            INNER JOIN elo_rating_players AS p2
                ON p2.id = g.player2
            ORDER BY
                `date` DESC';

    const QRY_VARS_RANKING = "SET @pos = 0, @prevElo = 0";


    /**
     * Get all the games, grouped by date
     *
     * @return array
     */
    public static function getAllGames()
    {
        $db = FrontendModel::getContainer()->get('database');

        $dates = array();

        $games = (array) $db->getRecords(self::QRY_GAMES);

        $previousDate = '';

        foreach ($games as $game) {
            // echo '<pre>'. print_r($previousDate . ' ----- ' . $game["compareDate"], 1) .'</pre>';

            if ($previousDate != $game["compareDate"]) {
              //  echo '<b>niet hetzelfde</b>';
                $dates[] = array('date' => $game["date"], 'games' => (array) array());
            }

            $dates[count($dates)-1]['games'][] = $game;

            $previousDate = $game["compareDate"];

        }

        return $dates;
    }


    /**
     * Generate some stats about how many games were played, won ...
     *
     * @return array
     */
    public static function getGamesPlayed()
    {
        $games = (array) FrontendModel::getContainer()->get('database')->getRecords(
            'SELECT score1 FROM elo_rating_games'
        );

        $data = array(
            'total' => count($games),
            'p1won' => 0,
            'p2won' => 0,
            'draw' => 0,
        );

        foreach ($games as $game) {
            if ($game["score1"] == 1) {
                $data["p1won"]++;
            } else if ($game["score1"] == 0) {
                $data["p2won"]++;
            } else {
                $data["draw"]++;
            }
        }

        return $data;
    }
 

    /**
     * Get all the active players with their played games
     *
     * @return array
     */
    public static function getPlayersWithGames()
    {
        $db = FrontendModel::getContainer()->get('database');

        // Set the vars to 0 because the session stays open.

        $players = (array) $db->getRecords(
            self::QRY_PLAYERS,
            array(
                (string) 'Y'
            )
        );

        $minimum_played_games = FrontendModel::getModuleSetting('EloRating', 'minimum_played_games', 5);

        foreach ($players as &$player) {

            $games = (array) $db->getRecords(
                "SELECT
                    g.id, g.player1, g.player2, score1, score2, UNIX_TIMESTAMP(`date`) AS `date`, p1.name AS player1name, p2.name AS player2name
                FROM
                    elo_rating_games AS g
                INNER JOIN
                    `elo_rating_players` AS p1 ON `p1`.id = g.player1
                INNER JOIN
                    `elo_rating_players` AS p2 ON `p2`.id = g.player2
                WHERE player1 = ? OR player2 = ?
                ORDER BY date DESC",
                array(
                    (int) $player['id'],
                    (int) $player['id']
                )
            );

            $player["games"] = $games;

            if ($player["games_played"] < $minimum_played_games) {
                $player["ranking"] = false;
            } else {

                $player["ranking"] = $db->getVar(
                    "SELECT COUNT(*)+1 FROM elo_rating_players WHERE current_elo > ? AND active = ? AND games_played >= ?",
                    array(
                        (string) $player["elo"],
                        (string) 'Y',
                        (int) $minimum_played_games
                    )
                );
            }
        }

        return $players;
    }


    /**
     * Get the top X of players ordered by Elo-rating.
     * Players with the same rating get the same position, but are ordered by number of games played
     *
     * @return array
     */
    public static function getTopRanking()
    {
        $db = FrontendModel::getContainer()->get('database');

        // Set the vars to 0 because the session stays open.
        $db->execute(self::QRY_VARS_RANKING);

        $return = (array) $db->getRecords(
            self::QRY_RANKING . " LIMIT ?",
            array(
                (string) 'Y',
                (int) FrontendModel::getModuleSetting('EloRating', 'minimum_played_games', 5),
                (int) FrontendModel::getModuleSetting('EloRating', 'top_ranking_count', 10)
            )
        );

        return $return;
    }


    /**
     * Get the top X of players ordered by Elo-rating.
     * Players with the same rating get the same position, but are ordered by number of games played
     *
     * @return array
     */
    public static function getTotalRanking()
    {
        $db = FrontendModel::getContainer()->get('database');

        // Set the vars to 0 because the session stays open.
        $db->execute(self::QRY_VARS_RANKING);

        $return = (array) $db->getRecords(
            self::QRY_RANKING,
            array(
                (string) 'Y',
                (int) FrontendModel::getModuleSetting('EloRating', 'minimum_played_games', 5)
            )
        );

        return $return;
    }
}
