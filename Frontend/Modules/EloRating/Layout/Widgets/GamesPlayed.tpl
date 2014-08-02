{option:widgetGamesPlayed}
	<section id="eloRatingTopRankingWidget" class="mod">
		<div class="inner">
			<header class="hd">
				<h3>{$lblEloGamesPlayed|ucfirst}</h3>
			</header>
			<p>{$msgEloGamesPlayed|nl2br|sprintf:{$widgetGamesPlayed.total}:{$widgetGamesPlayed.p1won}:{$lblPlayer1}:{$widgetGamesPlayed.p2won}:{$lblPlayer2}:{$widgetGamesPlayed.draw}}</p>
		</div>
	</section>
{/option:widgetGamesPlayed}