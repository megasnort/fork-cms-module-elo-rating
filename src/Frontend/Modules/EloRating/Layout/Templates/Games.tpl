<div id="eloRatingIndex">
  <section class="mod">
    <div class="inner">
      {option:!games}
        <div class="bd content">
          <p>{$msgNoGamesYet}</p>
        </div>
      {/option:!games}
      {option:games}
        <div class="bd content">

          {iteration:games}
            <header class="hd">
              <h3>{$games.date|date:{$dateFormatLong}:{$LANGUAGE}}</h3>
            </header>
            <table>
              <tr>
                <th width="40%">{$lblPlayer1}</th>
                <th width="40%">{$lblPlayer2}</th>
                <th width="20%" colspan="2">{$lblResult}</th>
              </tr>

              {iteration:games.games}
                <tr>
                  <td>
                    {option:games.games.player1active}
                    <a href="{$playerUrl}/{$games.games.player1url}">
                      {/option:games.games.player1active}
                      {$games.games.player1name}
                      {option:games.games.player1active}
                    </a>
                    {/option:games.games.player1active}
                  </td>
                  <td>
                    {option:games.games.player2active}
                    <a href="{$playerUrl}/{$games.games.player2url}">
                      {/option:games.games.player2active}
                      {$games.games.player2name}
                      {option:games.games.player2active}
                    </a>
                    {/option:games.games.player2active}
                  </td>
                  <td>{$games.games.score1}</td>
                  <td>{$games.games.score2}</td>
                </tr>
              {/iteration:games.games}

            </table>
          {/iteration:games}
        </div>
      {/option:games}
    </div>
  </section>
</div>
