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
							<h3>{$games.date|date:{$dateFormatLong}}</h3>
						</header>

						<table>
							<tr>
								<th>{$lblPlayer1}</th>
								<th>{$lblPlayer2}</th>
								<th colspan="2"></th>
							</tr>

					
						{iteration:games.games}

							<tr>
								<td>
									{option:playerUrl}
										<a href="{$playerUrl}#p{$games.games.player1}">
									{/option:playerUrl}
									{$games.games.player1name}
									{option:playerUrl}
										</a>
									{/option:playerUrl}
								</td>
								<td>
									{option:playerUrl}
										<a href="{$playerUrl}#p{$games.games.player2}">
									{/option:playerUrl}
									{$games.games.player2name}
									{option:playerUrl}
										</a>
									{/option:playerUrl}
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