<div id="eloRatingPlayer">
  <section class="mod">
    <div class="inner">
      <div class="bd content">
        <header class="hd">
          <h3>
            {$player.name}
          </h3>
        </header>
        <p>
          {option:player.ranking}
          {$msgHasEloAndRanking|sprintf:{$player.name}:{$player.elo}:{$player.ranking}:{$player.games_played}:{$player.won}:{$player.lost}:{$player.draws}:{$player.winrate}:{$player.lossrate}:{$player.drawrate}|nl2br}
          {/option:player.ranking}

          {option:!player.ranking}
          {$msgNoRankingYet|sprintf:{$player.name}}
          {/option:!player.ranking}
        </p>
        {option:player.history}
          <h4>
            {$lblEloEvolved|sprintf:{$player.name}}
          </h4>
          <svg id="evolution" width="100%" height="310"></svg>
        {/option:player.history}

        {option:player.games}
          <h4>
            {$lblPlayersGames|sprintf:{$player.name}}
            <select id="opponents">
              <option value="0">-</option>
              {iteration:player.opponents}
                <option value="{$player.opponents.id}">{$player.opponents.name}</option>
              {/iteration:player.opponents}
            </select>
          </h4>
          <table id="games">
            <tr>
              <th>{$lblPlayer1}</th>
              <th>{$lblPlayer2}</th>
              <th colspan="2">{$lblResult}</th>
              <th>{$lblDate}</th>
              <th>{$lblEloRating}</th>
            </tr>
            {iteration:player.games}
              <tr
                data-id="{option:player.games.isplayer1}{$player.games.player2}{/option:player.games.isplayer1}{option:!player.games.isplayer1}{$player.games.player1}{/option:!player.games.isplayer1}">
                <td>
                  {option:!player.games.isplayer1}
                {option:player.games.player1active}
                  <a href="{$playerUrl}/{$player.games.player1url}">
                    {/option:player.games.player1active}
                    {/option:!player.games.isplayer1}

                    {$player.games.player1name}

                    {option:!player.games.isplayer1}
                  </a>
                  {/option:!player.games.isplayer1}
                </td>
                <td>
                  {option:player.games.isplayer1}
                {option:player.games.player2active}
                  <a href="{$playerUrl}/{$player.games.player2url}">
                    {/option:player.games.player2active}
                    {/option:player.games.isplayer1}

                    {$player.games.player2name}
                    {option:player.games.isplayer1}
                  </a>
                  {/option:player.games.isplayer1}
                </td>
                <td>{$player.games.score1}</td>
                <td>{$player.games.score2}</td>
                <td>{$player.games.date|date:{$dateFormatShort}}</td>
                <td>{$player.games.elo} (<span
                    class="{option:player.games.won}positive{/option:player.games.won}{option:player.games.lost}negative{/option:player.games.lost}">{$player.games.gainLoss}</span>)
                </td>
              </tr>
            {/iteration:player.games}
          </table>
        {/option:player.games}
      </div>
    </div>
  </section>
</div>
