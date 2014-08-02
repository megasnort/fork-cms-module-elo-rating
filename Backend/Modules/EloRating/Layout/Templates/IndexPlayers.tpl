{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$lblEloRating|ucfirst}: {$lblPlayers|ucfirst}</h2>

	<div class="buttonHolderRight">
		<a href="{$var|geturl:'addPlayer'}" class="button icon iconAdd" title="{$lblAddPlayer|ucfirst}">
			<span>{$lblAddAPlayer|ucfirst}</span>
		</a>
	</div>
</div>

{option:dgPlayers}
	<div class="dataGridHolder">
		<div class="tableHeading">
			<h3>{$lblPlayers|ucfirst}</h3>
		</div>
		{$dgPlayers}
	</div>
{/option:dgPlayers}

{option:!dgPlayers}<p>{$msgNoPlayers}</p>{/option:!dgPlayers}


{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
