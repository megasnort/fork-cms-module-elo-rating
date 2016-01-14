{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
  <h2>{$lblEloRating|ucfirst}: {$lblAddAPlayer}</h2>
</div>

{form:add}
  <div class="box">
    <div class="heading">
      <h3>
        <label>{$lblAddAPlayer|ucfirst}</label>
      </h3>
    </div>

    <div class="options">
      <p>
        <label for="name">{$lblName|ucfirst}<abbr title="required field">*</abbr></label>
        {$txtName} {$txtNameError}
      </p>

      <p>
        <label for="start_elo">{$lblStartElo|ucfirst}<abbr title="required field">*</abbr></label>
        {$txtStartElo} {$txtStartEloError}
        <br/>{$msgStartElo|sprintf:{$default_elo}}
      </p>

      <p>
        <label for="active">{$lblActive|ucfirst}</label>
        {$chkActive} {$chkActiveError}
        <br/>{$msgActive}
      </p>

    </div>

    <div class="fullwidthOptions">
      <div class="buttonHolderRight">
        <input id="addButton" class="inputButton button mainButton" type="submit" name="add"
               value="{$lblAddAPlayer|ucfirst}"/>
      </div>
    </div>
  </div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
