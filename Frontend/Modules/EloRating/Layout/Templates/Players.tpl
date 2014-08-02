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
							
							<h3><a name="p{$players.id}"></a>{$players.name}</h3>
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
									<td>{$players.games.player1name}</td>
									<td>{$players.games.player2name}</td>
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