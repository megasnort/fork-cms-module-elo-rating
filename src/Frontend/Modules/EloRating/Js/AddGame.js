
/**
 * This JS-file is loaded in every page of the mini_blog module
 * 
 * @author Stef Bastiaansen <stef@megasnort.com>
 */




jsFrontend.eloRating = 
{
    // init, something like a constructor
    init: function() 
    {       
        //init ajax action
        jsFrontend.eloRating.addGame();
    },

    addGame: function()
    {
        $('#comment').removeAttr('cols').css('width', '95%');

        //make sure that the same player can't be selected at the same time
        $('#player1').change(function(){

            if ($(this).val() == $('#player2').val() ) {
                $('#player2').val('');
            }
        });

        $('#player2').change(function(){

            if ($(this).val() == $('#player1').val() ) {
                $('#player1').val('');
            }
        });

        // make sure that the total score is always 1.
        // (this is dubbelchecked serverside, no worries, this is just for a better UX)
        $('#score1').change(function(){
            $('#score2').val(1 - $('#score1').val());
        });

        $('#score2').change(function(){
            $('#score1').val(1 - $('#score2').val());
        });

//console.log();

        // for all buttons with the awesome class (on the detailpage, just one, but on the index page, many
        $('#addGameForm').submit(function(e)
        {
            e.preventDefault();

      
            // split the url in pieces, so we can easily get the working language
            // We need to add the language attribute to every
            // ajax call because our site is multilangual.
            //var chunks = document.location.pathname.split('/');

            //var data = $(this).serialize();

            $.ajax(
            { 
                data: {
                    fork: { module: 'EloRating', action: 'AddGame'},
                    player1: $('#player1').val(),
                    player2: $('#player2').val(),
                    score1: $('#score1').val(),
                    score2: $('#score2').val(),
                    date: $('#date').val(),
                    time: $('#time').val(),
                    comment: $('#comment').val(),
                    password: $('#password').val()
                },
                error: function(data, textStatus){
                    //Does this really have be solved like this?
                    //- the label?
                    //- the parsing of the JSON data?
                    //
                    //If not, send an e-mail: stef@megasnort.com
                    var response = $.parseJSON(data.responseText)

                    $('#addGameForm .response').html(jsFrontend.locale.err(response.message)).addClass('error');
                },
                success: function(data, textStatus) 
                { 
                    $('#addGameForm .response').html(jsFrontend.locale.lbl(data.message)).removeClass('error');
                    $('#addGameForm')[0].reset();
                }
            });     
        });
    }
}

$(jsFrontend.eloRating.init);

                   
                    // success: function(data, textStatus)
                    // {
                    //     // init var
                    //     var realData = [];

                    //     // alert the user
                    //     if(data.code != 200 && jsFrontend.debug) { alert(data.message); }

                    //     if(data.code == 200)
                    //     {
                    //         for(var i in data.data) realData.push({ label: data.data[i].term, value: data.data[i].term, url: data.data[i].url });
                    //     }

                    //     // set response
                    //     response(realData);
                    // }