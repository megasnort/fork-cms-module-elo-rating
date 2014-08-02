
<section id="eloRatingTopRankingWidget" class="mod">
	<div class="inner">
		<header class="hd">
			<h3>{$lblEloTopRanking|ucfirst|sprintf:{$topRankingCount}}</h3>
		</header>

		{option:!widgetTopRanking}
			<div class="bd content">
				<p>{$msgNoPlayersYetRanking|sprintf:{$minimum_played_games}}</p>
			</div>
		{/option:!widgetTopRanking}
		{option:widgetTopRanking}
	
			<table class="bd content">
				<tr>
					<th>#</th>
					<th>{$lblPlayer}</th>
					<th>{$lblEloRating}</th>
				</tr>
				{iteration:widgetTopRanking}
					<tr>
						<td>{$widgetTopRanking.position}</td>
						<td>
							{option:playerUrl}
							<a href="{$playerUrl}#p{$widgetTopRanking.id}">
							{/option:playerUrl}
							{$widgetTopRanking.name}
							{option:playerUrl}
							</a>
							{/option:playerUrl}
						</td>
						<td>{$widgetTopRanking.elo}</td>
					</tr>
				{/iteration:widgetTopRanking}
			</table>
		{/option:widgetTopRanking}
	</div>
</section>