{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$lblEloRating|ucfirst}: {$lblEditPlayerName|ucfirst|sprintf:{$item.name}}</h2>
</div>

{form:edit}
	


<div class="box">
	<div class="heading">
		<h3>
			<label>{$lblEditPlayer|ucfirst}</label>
		</h3>
	</div>
	
	<div class="options">
		<p>
			<label for="name">{$lblName|ucfirst}<abbr title="required field">*</abbr></label>
			{$txtName} {$txtNameError}
		</p>

		<p>
			<label>{$lblCurrentElo|ucfirst}</label>
			<br />{$msgCurrentElo|sprintf:{$item.name}:{$item.current_elo}}
		</p>


		<p>
			<label for="startElo">{$lblStartElo|ucfirst}<abbr title="required field">*</abbr></label>
			{$txtStartElo} {$txtStartEloError|sprintf:{$min_elo}:{$max_elo}}
			<br />{$msgStartElo|sprintf:{$default_elo}}
		</p>


		<p>
			<label for="active">{$lblActive|ucfirst}</label>
			{$chkActive} {$chkActiveError}
			<br />
			{$msgActive}
		</p>

	</div>

	<div class="fullwidthOptions">

		<a href="{$var|geturl:'deletePlayer'}&amp;id={$item.id}" data-message-id="confirmDeletePlayer" class="askConfirmation button linkButton icon iconDelete">
			<span>{$lblDelete|ucfirst}</span>
		</a>

		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblEditPlayer|ucfirst}" />
		</div>
	</div>
</div>

<div id="confirmDeletePlayer" title="{$lblDeletePlayer|ucfirst|sprintf:{$item.name}}?" style="display: none;">
	<p>
		{$msgConfirmDeletePlayer|sprintf:{$item.name}|nl2br}
	</p>
</div>

	
{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
