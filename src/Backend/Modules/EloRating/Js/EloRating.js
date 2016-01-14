/**
 * Interaction for adding / editing a game
 *
 * @author    Stef Bastiaansen <stef@megasnort.com>
 */
jsBackend.elo_rating =
{
    init: function () {

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
        // (this is dubbelchecked server side, no worries, this is just for a better UX)
        $('#score1').change(function () {
            $('#score2').val(1 - $('#score1').val());
        });

        $('#score2').change(function () {
            $('#score1').val(1 - $('#score2').val());
        });
    }
};

$(jsBackend.elo_rating.init);
