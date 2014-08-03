<?php


namespace Backend\Modules\EloRating\Engine;


use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

use Frontend\Core\Engine\Language as FL;

/**
 * In this file we store all generic functions that we will be using in the Backend EloRating module
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Model
{

    
    const K = 16;               // Indicates the importance of games. (Note: if a game has players with really high ratings, this should rise. Should ...)
    const F = 400;              // K-factor. A standard in Elo-ratings calculatings.

    const MIN_ELO = 1000;       // Starting with an Elo-rating lower then 1000 is nonsense
    const MAX_ELO = 3000;       // Starting with an Elo-rating higher then 3000 is nonsense

    const DEFAULT_ELO = 1450;   // 1450 is the standard Elo-rating for a new player

    const QRY_GAMES =
        'SELECT
            g.id,
            p1.name as player1,
            p2.name as player2,
            g.score1,
            g.score2,
            UNIX_TIMESTAMP(g.`date`) AS `date`
        FROM
            elo_rating_games AS g
        INNER JOIN
            elo_rating_players AS p1
            ON p1.id = g.player1
        INNER JOIN elo_rating_players AS p2
            ON p2.id = g.player2';


    const QRY_PLAYERS =
        'SELECT
            p.id,
            p.name,
            p.current_elo,
            p.active,
            p.games_played,
            p.won,
            p.lost,
            p.draws
        FROM
            elo_rating_players AS p';


    /**
     * Generate the new Elo-ratings of two players after they compete in a game
     *
     * @return array                 array with two elements, p1 and p2, with the new Elo-ratings of two players
     * @param int $p1rating         the current rating of Player 1
     * @param int $p2rating         the current rating of Player 2
     * @param int $p1result         the result of the game for Player 1 (1: win, 0.5: draw, 0: lost)
     * @param int $p2return         the result of the game for Player 2 (1: win, 0.5: draw, 0: lost)
     */
    public static function calculateEloRating($p1rating, $p2rating, $p1result, $p2result)
    {

        $elo1 = self::K * ($p1result - (1 / pow(10, (- ($p1rating - $p2rating) / self::F) + 1)));
        $elo2 = self::K * ($p2result - (1 / pow(10, (- ($p2rating - $p1rating) / self::F) + 1)));

        return array('p1' => $p1rating + $elo1, 'p2' => $p2rating + $elo2);
    }

   
    /**
     * Delete a game
     *
     * @param int $id
     */
    public static function delete($id)
    {
        BackendModel::getContainer()->get('database')->delete('elo_rating_games', 'id = ?', array((int) $id));
        self::generateEloRatings();
    }


    /**
     * Delete a player
     *
     * @param int $id
     */
    public static function deletePlayer($id)
    {
        BackendModel::getContainer()->get('database')->delete('elo_rating_players', 'id = ?', array((int) $id));
        BackendModel::getContainer()->get('database')->delete('elo_rating_games', 'player1 = ? OR player2 = ?', array((int) $id, (int) $id));

        self::generateEloRatings();
    }


    /**
     * Walk all games and calculate the new Elo-ratings for every player
     * 
     */
    public static function generateEloRatings()
    {
 
        $db = BackendModel::getContainer()->get('database');

        // get all players, the inactive ones too
        // use pairs so we can alter the array-elements later by using the Id of the player
        $players = (array) $db->getPairs(
            'SELECT
                p.id, p.start_elo as elo
            FROM
                elo_rating_players as p'
        );

        //walk all players, and convert the player (elo) value to an array, with room to store the games played
        foreach ($players as &$player) {
            $player = array(
                'current_elo' => $player,
                'games_played' => 0,
                'won' => 0,
                'lost' => 0,
                'draws' => 0
            );
        }

        // get all games, ordered by date (important for the ratings)
        $games = (array) $db->getRecords(
            'SELECT
                g.id, g.player1, g.player2, g.score1, g.score2
            FROM
                elo_rating_games AS g
            ORDER BY `date`'
        );

        // walk all games and step by step, recalculate every rating throughout the time
        // because it's possible a game played earlier in the history is added
        foreach ($games as $game) {

            $newRatings = self::calculateEloRating($players[$game["player1"]]['current_elo'], $players[$game["player2"]]['current_elo'], $game["score1"], $game["score2"]);
            
            $players[$game["player1"]]['current_elo'] = $newRatings["p1"];
            $players[$game["player2"]]['current_elo'] = $newRatings["p2"];

            //totals
            $players[$game["player1"]]['games_played']++;
            $players[$game["player2"]]['games_played']++;

            if ($game["score1"] == 1) {
                $players[$game["player1"]]['won']++;
                $players[$game["player2"]]['lost']++;
            } else if ($game["score1"] == 0) {
                $players[$game["player1"]]['lost']++;
                $players[$game["player2"]]['won']++;
            } else {
                $players[$game["player1"]]['draws']++;
                $players[$game["player2"]]['draws']++;
            }

        }

        // when the ratings are recaclulated, update every player
        foreach ($players as $playerId => $values) {
            $db->update('elo_rating_players', $values, 'id = ?', array((int) $playerId));
        }



        // Because it's possible that changes to one player or game changes everybody's rating.
        // there is just one trigger.
        BackendModel::triggerEvent('EloRating', 'generate_ratings', $players);

      
    }


    /**
     * Get all data for a game.
     *
     * @param int $id       The id of the game to get.
     * @return array
     */
    public static function get($id)
    {
        $return = (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT g.id, g.player1, g.player2, g.score1, g.score2, UNIX_TIMESTAMP(g.`date`) AS `date`
             FROM elo_rating_games AS g WHERE g.id = ?',
            (int) $id
        );

        return $return;
    }


    /**
     * Get a list of pairs for each player that is still in competition
     *
     * @return array
     */
    public static function getActivePlayers()
    {
        return (array) BackendModel::getContainer()->get('database')->getPairs(
            'SELECT id, name FROM elo_rating_players WHERE active = ? ORDER BY name',
            (string) 'Y'
        );
    }


    /**
     * Get all data for a player.
     *
     * @param int $id           The id of the player to get.
     * @return array
     */
    public static function getPlayer($id)
    {
        $return = (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT p.id, p.name, p.start_elo, p.current_elo, p.active FROM elo_rating_players AS p WHERE p.id = ?',
            (int) $id
        );

        return $return;
    }


    /**
     * Insert a game
     *
     * @return int              the id of the newly added game
     * @param array $item       record with the fields of the game to be added
     */
    public static function insert(array $item)
    {
        $insertId = BackendModel::getContainer()->get('database')->insert('elo_rating_games', $item);
        
        self::generateEloRatings();


        //BackendModel::invalidateFrontendCache('Faq', BL::getWorkingLanguage());

        return $insertId;
    }


    /**
     * Insert a player
     *
     * @return int              the id of the newly added player
     * @param array $item       record with the fields of the player to be added
     */
    public static function insertPlayer(array $item)
    {
        $insertId = BackendModel::getContainer()->get('database')->insert('elo_rating_players', $item);

        return $insertId;
    }
  

   /**
     * Update a game
     *
     * @param array $item     record with the fields of the game to be altered
     */
    public static function update(array $item)
    {
        BackendModel::getContainer()->get('database')->update('elo_rating_games', $item, 'id = ?', array((int) $item['id']));
        self::generateEloRatings();
    }


    /**
     * Update a player
     *
     * @param array $item     record with the fields of the player to be altered
     */
    public static function updatePlayer(array $item)
    {
        BackendModel::getContainer()->get('database')->update('elo_rating_players', $item, 'id = ?', array((int) $item['id']));
        self::generateEloRatings();
    }
}
