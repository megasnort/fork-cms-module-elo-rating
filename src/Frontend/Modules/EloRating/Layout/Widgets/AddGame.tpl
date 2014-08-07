
<section id="eloRatingAddGameWidget" class="mod">
    <div class="inner">
        <header class="hd">
            <h3>{$lblAddGame}</h3>
        </header>
    
        <div class="bd">


        {form:addGameForm}
            <p>
                <label for="player1">{$lblPlayer1|ucfirst}<abbr title="required field">*</abbr></label>
                {$ddmPlayer1} {$ddmScore1}
            </p>
            <p>
                <label for="player2">{$lblPlayer2|ucfirst}<abbr title="required field">*</abbr></label>
                {$ddmPlayer2} {$ddmScore2}
            </p>

            <p>
                <label for="date">{$lblDate|ucfirst}<abbr title="required field">*</abbr></label>
                {$txtDate}
                {$txtTime}

            </p>

            <p>
                <label for="date">{$lblInfo|ucfirst}</label>
                {$txtComment}
            </p>

            <p>
                <label for="password">{$lblPassword|ucfirst}</label>
                {$txtPassword}
            </p>

            <p>
                <input class="inputSubmit" type="submit" name="submit" value="{$lblAddTheGame|ucfirst}" />
            </p>
            <div class="response"></div>
            
        {/form:addGameForm}

        </div>
    </div>
</section>