<?php

namespace Frontend\Modules\EloRating\Engine;


use Frontend\Core\Engine\Model as FrontendModel;

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
                p.draws,
                m.url
            FROM
                elo_rating_players AS p
            INNER JOIN
                `meta` AS m ON `p`.meta_id = m.id
            WHERE
                `active` = ?
                AND
                `games_played` > ?
   
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
                m.url,
                @pos := IFNULL(@pos,0) + 1,
                IF(@prevElo = p.current_elo, '-', @pos) AS position,
                @prevElo := p.current_elo
            FROM
                elo_rating_players AS p
            INNER JOIN meta as m
                ON m.id = p.meta_id
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

    const QRY_GAMES = "SELECT
                g.id,
                g.player1,
                g.player2,
                p1.name as player1name,
                p2.name as player2name,
                IF(p1.active = 'Y', 1, null) as player1active,
                IF(p2.active = 'Y', 1, null) as player2active,
                g.score1,
                g.score2,
                m1.url as player1url,
                m2.url as player2url,
                UNIX_TIMESTAMP(g.`date`) AS `date`,
                SUBSTRING(g.`date`,1, 10) AS compareDate
            FROM
                elo_rating_games AS g
            INNER JOIN elo_rating_players AS p1
                ON p1.id = g.player1
            INNER JOIN elo_rating_players AS p2
                ON p2.id = g.player2
            INNER JOIN meta AS m1
                ON m1.id = p1.meta_id
            INNER JOIN meta AS m2
                ON m2.id = p1.meta_id
            ORDER BY
                `date` DESC";

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

            if ($previousDate != $game["compareDate"]) {
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
     * Get the player with the given name
     *
     * @return array
     */
    public static function getPlayer($url)
    {
        $db = FrontendModel::getContainer()->get('database');

        // Set the vars to 0 because the session stays open.

        if( $player = (array) $db->getRecord(
            "SELECT
                p.id,
                p.name,
                p.current_elo as elo,
                p.games_played,
                p.won,
                p.lost,
                p.draws,
                m.keywords AS meta_keywords, m.keywords_overwrite AS meta_keywords_overwrite,
                m.description AS meta_description, m.description_overwrite AS meta_description_overwrite,
                m.title AS meta_title, m.title_overwrite AS meta_title_overwrite,
                m.url,
                m.data AS meta_data
            FROM
                elo_rating_players AS p
            INNER JOIN
                meta AS m ON p.meta_id = m.id
            WHERE
                `active` = ?
                AND
                `games_played` > ?
                AND
                `m`.`url` = ?
            LIMIT 1",
            array(
                (string) 'Y',
                (int) 0,
                (string) $url
            )
        )
        ) {

            $minimum_played_games = FrontendModel::getModuleSetting('EloRating', 'minimum_played_games', 5);

            $games = (array) $db->getRecords(
                "SELECT
                    g.id,
                    g.player1,
                    g.player2,
                    IF(g.player1 = ?,1,null) AS isplayer1,
                    score1,
                    score2,
                    UNIX_TIMESTAMP(`date`) AS `date`,
                    p1.name AS player1name,
                    p2.name AS player2name,
                    m1.url as player1url,
                    m2.url as player2url,
                    IF(p1.active = 'Y', 1, null) as player1active,
                    IF(p2.active = 'Y', 1, null) as player2active
                FROM
                    elo_rating_games AS g
                INNER JOIN
                    `elo_rating_players` AS p1 ON `p1`.id = g.player1
                INNER JOIN
                    `elo_rating_players` AS p2 ON `p2`.id = g.player2
                INNER JOIN
                    `meta` AS m1 ON `p1`.meta_id = m1.id
                INNER JOIN
                    `meta` AS m2 ON `p2`.meta_id = m2.id
                WHERE player1 = ? OR player2 = ?
                ORDER BY date DESC",
                array(
                    (int) $player['id'],
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
        

            return $player;
        } else {
            return false;
        }
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
                (string) 'Y',
                (int) 0
            )
        );

        $minimum_played_games = FrontendModel::getModuleSetting('EloRating', 'minimum_played_games', 5);

        foreach ($players as &$player) {

            $games = (array) $db->getRecords(
                "SELECT
                    g.id, g.player1, g.player2,
                    IF(g.player1 = ?,1,null) AS isplayer1,
                    score1,
                    score2,
                    UNIX_TIMESTAMP(`date`) AS `date`,
                    p1.name AS player1name,
                    p2.name AS player2name,
                    m1.url as player1url,
                    m2.url as player2url,
                    IF(p1.active = 'Y', 1, null) as player1active,
                    IF(p2.active = 'Y', 1, null) as player2active
                FROM
                    elo_rating_games AS g
                INNER JOIN
                    `elo_rating_players` AS p1 ON `p1`.id = g.player1
                INNER JOIN
                    `elo_rating_players` AS p2 ON `p2`.id = g.player2
                INNER JOIN
                    `meta` AS m1 ON `p1`.meta_id = m1.id
                INNER JOIN
                    `meta` AS m2 ON `p2`.meta_id = m2.id

                WHERE player1 = ? OR player2 = ?
                ORDER BY date DESC",
                array(
                    (int) $player['id'],
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
