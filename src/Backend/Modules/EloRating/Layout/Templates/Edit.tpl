{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
  <h2>{$lblEloRating|ucfirst}: {$lblEditGame}</h2>
</div>

{form:edit}
  <div class="box">
    <div class="heading">
      <h3>
        <label>{$lblEditGame|ucfirst}</label>
      </h3>
    </div>

    <div class="options">
      <p>
        <label for="player1">{$lblPlayer1|ucfirst}<abbr title="required field">*</abbr></label>
        {$ddmPlayer1} {$ddmScore1} {$ddmPlayer1Error|sprintf:{$lblPlayer1}}
      </p>
      <p>
        <label for="player2">{$lblPlayer1|ucfirst}<abbr title="required field">*</abbr></label>
        {$ddmPlayer2} {$ddmScore2} {$ddmPlayer2Error|sprintf:{$lblPlayer2}} {$ddmScore2Error}
      </p>

      <p>
        <label for="date">{$lblDatePlayed|ucfirst}<abbr title="required field">*</abbr></label>
        {$txtDate} {$txtTime} {$txtDateError} {$txtTimeError}
      </p>
    </div>
  </div>
  <div class="box">
    <div class="heading">
      <h3>
        <label>{$lblActivate|ucfirst}</label>
      </h3>
    </div>

    <div class="options">
      <p>
        <label for="comment">{$lblInfo|ucfirst}</label>
        {$txtComment} {$txtCommentError}
      </p>
      <p>
        <label for="active">{$lblActive|ucfirst}</label>
        {$chkActive} {$chkActiveError}
        <br/>
        {$msgActiveGame}
      </p>
    </div>

    <div class="fullwidthOptions">

      <a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete"
         class="askConfirmation button linkButton icon iconDelete">
        <span>{$lblDelete|ucfirst}</span>
      </a>


      <div class="buttonHolderRight">
        <input id="editButton" class="inputButton button mainButton" type="submit" name="edit"
               value="{$lblEditGame|ucfirst}"/>
      </div>
    </div>
  </div>
  <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
    <p>
      {$msgConfirmDelete}
    </p>
  </div>
{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
