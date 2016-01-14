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

    {option:hasPlayers}
      <div class="options">
        <p>
          <label for="player1">{$lblPlayer1|ucfirst}<abbr title="required field">*</abbr></label>
          {$ddmPlayer1} {$ddmScore1} {$ddmPlayer1Error|sprintf:{$lblPlayer1}}
        </p>
        <p>
          <label for="player2">{$lblPlayer2|ucfirst}<abbr title="required field">*</abbr></label>
          {$ddmPlayer2} {$ddmScore2} {$ddmPlayer2Error|sprintf:{$lblPlayer2}} {$ddmScore2Error}
        </p>

        <p>
          <label for="date">{$lblDatePlayed|ucfirst}<abbr title="required field">*</abbr></label>
          {$txtDate} {$txtTime} {$txtDateError} {$txtTimeError}
        </p>
      </div>
      <div class="fullwidthOptions">
        <div class="buttonHolderRight">
          <input id="addButton" class="inputButton button mainButton" type="submit" name="add"
                 value="{$lblAddAGame|ucfirst}"/>
        </div>
      </div>
    {/option:hasPlayers}
    {option:!hasPlayers}
      <div class="options">
        <p>{$msgNoPlayers}</p>
      </div>
    {/option:!hasPlayers}

  </div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
