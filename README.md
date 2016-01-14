Fork CMS Module - Elo Rating
============================

Imagine you're playing some game (chess, pool, football ...) regularly at your company, school, neighbourhood ... and you want to keep score of who's the best without having to organise knock-out tournaments (which is a hassle).
 
Comes in: ***The Elo Rating Module for Fork CMS***

***Note, tested with Fork CMS 3.7.2. Should it be incompatible with the newest version of Fork, feel free to do a pull request or just send me a message. If I find the time, I will update***

## What's inside?
* Backend actions for organizing your players and games
* Automated calculation of the Elo ratings
* Three frontend actions
	* **Ranking**: List of players with some basic stats
	* **Games**: Overview of all the games ever played
	* **Player**: (Evolution) of his/her rating in a chart, current ranking, number of games played ...
* Four frontend widgets
 	* **Top Ranking**: List of the best players
	* **Number of Games played**: Some global stats
	* **Latest Games**: List of the most recent games
	* **Add a Game**: Form to let players submit their own games (with password protection)
* Translations for English and Dutch

You can see an example here: 

* [carcassonnecup.4fan.cz](http://carcassonnecup.4fan.cz) (in Czech)

##Changelog
**10th of January 2015**

* Added a select with opponents to the Player-action so you can compare better between the player an one of his/her opponents
* Modified a label ($lblPlayersGames) because it gets followed by a &lt;select&gt;
* Added the Elo-rating + gain or loss after a game to the table on the Player-action. If you installed the first version of this module, you need to manually add the 'game'-field (INT) to the elo_history-table, and re-save a game in the backend so the elo-history-table is re-filled correctly.
* Fixed a major bug in the Rating-generator
* Give a fixed width to the tables in Games.tpl

##License

The module is licensed under MIT. In short, this license allows you to do everything as long as the copyright statement stays present.
