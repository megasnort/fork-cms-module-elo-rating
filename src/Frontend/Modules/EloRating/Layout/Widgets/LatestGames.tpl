<section id="eloRatingLatestGamesWidget" class="mod">
	<div class="inner">
		<header class="hd">
			<h3>{$lblLatestGames|ucfirst}</h3>
		</header>

		{option:!widgetLatestGames}
			<div class="bd content">
				<p>{$msgNoGamesYet}</p>
			</div>
		{/option:!widgetLatestGames}
	
		
		{option:widgetLatestGames}

			<table>
				<tr>
					<th>{$lblPlayer1}</th>
					<th>{$lblPlayer2}</th>
					<th colspan="2"></th>
					<th>{$lblDate}</th>
				</tr>

		
			{iteration:widgetLatestGames}

				<tr>
					<td>
						{option:widgetLatestGames.player1active}
							<a href="{$playerUrl}/{$widgetLatestGames.player1url}">
						{/option:widgetLatestGames.player1active}
						{$widgetLatestGames.player1name}
						{option:widgetLatestGames.player1active}
							</a>
						{/option:widgetLatestGames.player1active}
					</td>
					<td>
						{option:widgetLatestGames.player2active}
							<a href="{$playerUrl}/{$widgetLatestGames.player2url}">
						{/option:widgetLatestGames.player2active}
						{$widgetLatestGames.player2name}
						{option:widgetLatestGames.player2active}
							</a>
						{/option:widgetLatestGames.player2active}
					</td>
					<td>{$widgetLatestGames.score1}</td>
					<td>{$widgetLatestGames.score2}</td>
					<td>
						{option:widgetLatestGames.date}
							{$widgetLatestGames.date|date:{$dateFormatShort}:{$LANGUAGE}}
						{/option:widgetLatestGames.date}
					</td>

				</tr>

			{/iteration:widgetLatestGames}
			

			</table>
		{/option:widgetLatestGames}
	</div>
</section>
