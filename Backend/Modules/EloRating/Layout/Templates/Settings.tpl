{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$lblModuleSettings|ucfirst}: {$lblEloRating}</h2>
</div>

{form:settings}
	<div class="box">
	<div class="heading">
			<h3>{$lblEloRating|ucfirst}</h3>
		</div>
		<div class="options">
			<label for="minimumPlayedGames">{$lblMinimumPlayedGames|ucfirst}</label>
			{$ddmMinimumPlayedGames} {$ddmMinimumPlayedGamesError}
			<p>{$msgMinimumPlayedGames} </p>
		</div>
		<div class="options">
			<label for="topRankingCount">{$lblTopRankingCount|ucfirst}</label>
			{$ddmTopRankingCount} {$ddmTopRankingCountError}
			<p>{$msgTopRankingCount} </p>
		</div>
	</div>

	

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="save" class="inputButton button mainButton" type="submit" name="save" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:settings}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
