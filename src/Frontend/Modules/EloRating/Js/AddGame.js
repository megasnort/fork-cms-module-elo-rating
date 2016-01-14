/**
 * @author Stef Bastiaansen <stef@megasnort.com>
 */

jsFrontend.eloRating =
{
    init: function () {
        jsFrontend.eloRating.addGame();
    },
    addGame: function () {
        $('#comment').removeAttr('cols').css('width', '95%');

        //make sure that the same player can't be selected at the same time
        $('#player1').change(function () {

            if ($(this).val() == $('#player2').val()) {
                $('#player2').val('');
            }
        });

        $('#player2').change(function () {

            if ($(this).val() == $('#player1').val()) {
                $('#player1').val('');
            }
        });

        // make sure that the total score is always 1.
        // (this is dubbelchecked serverside, no worries, this is just for a better UX)
        $('#score1').change(function () {
            $('#score2').val(1 - $('#score1').val());
        });

        $('#score2').change(function () {
            $('#score1').val(1 - $('#score2').val());
        });

        // for all buttons with the awesome class (on the detailpage, just one, but on the index page, many
        $('#addGameForm').submit(function (e) {
            e.preventDefault();

            $.ajax(
                {
                    data: {
                        fork: {module: 'EloRating', action: 'AddGame'},
                        player1: $('#player1').val(),
                        player2: $('#player2').val(),
                        score1: $('#score1').val(),
                        score2: $('#score2').val(),
                        date: $('#date').val(),
                        time: $('#time').val(),
                        comment: $('#comment').val(),
                        password: $('#password').val()
                    },
                    error: function (data, textStatus) {
                        //Does this really have be solved like this?
                        //- the label?
                        //- the parsing of the JSON data?
                        //
                        //If not, send an e-mail: stef@megasnort.com
                        var response = $.parseJSON(data.responseText)

                        $('#addGameForm .response').html(jsFrontend.locale.err(response.message)).addClass('error');
                    },
                    success: function (data, textStatus) {
                        $('#addGameForm .response').html(jsFrontend.locale.lbl(data.message)).removeClass('error');
                        $('#addGameForm')[0].reset();
                    }
                });
        });
    }
};

$(jsFrontend.eloRating.init);
