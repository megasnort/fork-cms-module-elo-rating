{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
  <h2>{$lblEloRating|ucfirst}: {$lblGames|ucfirst}</h2>

  <div class="buttonHolderRight">
    <a href="{$var|geturl:'add'}" class="button icon iconAdd" title="{$lblAdd|ucfirst}">
      <span>{$lblAddAGame|ucfirst}</span>
    </a>
  </div>
</div>

{option:dgGames}
  <div class="dataGridHolder">
    <div class="tableHeading">
      <h3>{$lblGames|ucfirst}</h3>
    </div>
    {$dgGames}
  </div>
{/option:dgGames}

{option:!dgGames}<p>{$msgNoGames}</p>{/option:!dgGames}


{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
