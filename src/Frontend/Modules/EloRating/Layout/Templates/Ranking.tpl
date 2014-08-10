<div id="eloRatingIndex">
	<section class="mod">
		<div class="inner">
			{option:!ranking}
				<div class="bd content">
					<p>{$msgNoPlayersYetRanking|sprintf:{$minimum_played_games}}</p>
				</div>	
			{/option:!ranking}
			{option:ranking}
				
				<table class="bd content">
					<tr>
						<th>#</th>
						<th>{$lblPlayer}</th>
						<th>{$lblEloRating}</th>
						<th>{$lblGamesPlayed}</th>
						<th>{$lblWon}</th>
						<th>{$lblLost}</th>
						<th>{$lblDraws}</th>
					</tr>
					{iteration:ranking}
						<tr>
							<td>{$ranking.position}</td>
							<td>								
								<a href="{$playerUrl}/{$ranking.url}">
								{$ranking.name}
								</a>
							</td>
							<td>{$ranking.elo}</td>
							<td>{$ranking.games_played}</td>
							<td>{$ranking.won}</td>
							<td>{$ranking.lost}</td>
							<td>{$ranking.draws}</td>
						</tr>
					{/iteration:ranking}
				</table>
			{/option:ranking}
		</div>
	</section>
</div>