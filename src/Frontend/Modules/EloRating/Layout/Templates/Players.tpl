<div id="eloRatingIndex">
	<section class="mod">
		<div class="inner">

			{option:!players}
				<div class="bd content">
					<p>{$msgNoPlayersYet}</p>
				</div>	
			{/option:!players}
			{option:players}
				<div class="bd content">
					{iteration:players}
						<header class="hd">
							<h3><a href="{$playerUrl}/{$players.url}">{$players.name}</a></h3>
						</header>

						<p>
						{option:players.ranking}
							{$msgHasEloAndRanking|sprintf:{$players.name}:{$players.elo}:{$players.ranking}}
						{/option:players.ranking}
						{option:!players.ranking}
							{$msgNoRankingYet|sprintf:{$players.name}}
						{/option:!players.ranking}
						</p>

						{option:players.games}							

							<table>
								<tr>
									<th>{$lblPlayer1}</th>
									<th>{$lblPlayer2}</th>
									<th></th>
									<th></th>
									<th>{$lblDate}</th>
								</tr>

							{iteration:players.games}

								<tr>
									<td>
										{option:!players.games.isplayer1}
											{option:players.games.player1active}
												<a href="{$playerUrl}/{$players.games.player1url}">
											{/option:players.games.player1active}
										{/option:!players.games.isplayer1}

											{$players.games.player1name}

										{option:!players.games.isplayer1}
										</a>
										{/option:!players.games.isplayer1}
									</td>
									<td>
										{option:players.games.isplayer1}
											{option:players.games.player2active}
												<a href="{$playerUrl}/{$players.games.player2url}">
											{/option:players.games.player2active}
										{/option:players.games.isplayer1}

											{$players.games.player2name}
										{option:players.games.isplayer1}
										</a>
										{/option:players.games.isplayer1}
									</td>
									<td>{$players.games.score1}</td>
									<td>{$players.games.score2}</td>
									<td>{$players.games.date|date:{$dateFormatShort}}</td>

								</tr>

							{/iteration:players.games}

							</table>

						{/option:players.games}							

						
					{/iteration:players}
				</div>
			{/option:players}
		</div>
	</section>
</div>