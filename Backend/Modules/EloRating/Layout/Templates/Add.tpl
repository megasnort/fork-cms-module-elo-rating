{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$lblEloRating|ucfirst}: {$lblAddAGame}</h2>
</div>

{form:add}
	


<div class="box">
	<div class="heading">
		<h3>
			<label>{$lblAddAGame|ucfirst}</label>
		</h3>
	</div>
	
	<div class="options">
		<p>
			<label for="player1">{$p1_name|ucfirst}<abbr title="required field">*</abbr></label>
			{$ddmPlayer1} {$ddmScore1} {$ddmPlayer1Error}
		</p>
		<p>
			<label for="player2">{$p2_name|ucfirst}<abbr title="required field">*</abbr></label>
			{$ddmPlayer2} {$ddmScore2} {$ddmPlayer2Error} {$ddmScore2Error}
		</p>

		<p>
			<label for="date">{$lblDatePlayed|ucfirst}<abbr title="required field">*</abbr></label>
			{$txtDate} {$txtTime} {$txtDateError} {$txtTimeError} 
		</p>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblAddAGame|ucfirst}" />
		</div>
	</div>
</div>



	
{/form:add}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
