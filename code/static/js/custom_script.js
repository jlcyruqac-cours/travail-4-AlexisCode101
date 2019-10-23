// Used for datepicker birth date input
$( "#birthday_date_input" ).datepicker();

// Ajax function to process input
$(document).ready(function(){
    $("#request_horoscope").click(function(){
        last_name_input = $('#last_name_input').val();
        first_name_input = $('#first_name_input').val();
        birthday_date_input = $('#birthday_date_input').val();
        $.ajax({
            url : '/horoscope',
            type : 'POST',
            data : {
                'last_name_input': last_name_input,
                'first_name_input': first_name_input,
                'birthday_date': birthday_date_input
            },
            success: function(result){
				$('#horoscope_display').html(result)
                // console.log(result)
			},
			error: function(error){
				console.log(error);
			}
        });
    });
});

// Erase input value when button reset is clicked
$(document).ready(function () {
    $("#reset_input").click(function(){
        $('#last_name_input').val('');
        $('#first_name_input').val('');
        $('#birthday_date_input').val('');
    })
})