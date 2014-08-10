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
						{$msgHasEloAndRanking|sprintf:{$player.name}:{$player.elo}:{$player.ranking}:{$player.won}:{$player.lost}:{$player.draws}|nl2br}
					{/option:player.ranking}
					{option:!player.ranking}
						{$msgNoRankingYet|sprintf:{$player.name}}
					{/option:!player.ranking}
					</p>


					{option:player.history}

						<h4>
							{$lblEloEvolved|sprintf:{$player.name}|ucfirst}
						</h4>
						<svg id="evolution" width="100%" height="310"></svg>
					{/option:player.history}

					{option:player.games}							

						<h4>
							{$lblPlayersGames|sprintf:{$player.name}|ucfirst}
						</h4>
						<table>
							<tr>
								<th>{$lblPlayer1}</th>
								<th>{$lblPlayer2}</th>
								<th></th>
								<th></th>
								<th>{$lblDate}</th>
							</tr>

						{iteration:player.games}

							<tr>
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

							</tr>


						{/iteration:player.games}

						</table>


					{/option:player.games}							


			</div>

		</div>
	</section>
</div>