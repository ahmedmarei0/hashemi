$(document).ready( function (){
    $('#internal-trip').on('click', function(){
        $('#internal-trip i').toggleClass('fa-chevron-up');
        $('#internal-trip i').toggleClass('fa-chevron-down');
    });
    $('#city-trip').on('click', function(){
        $('#city-trip i').toggleClass('fa-chevron-up');
        $('#city-trip i').toggleClass('fa-chevron-down');
    });
    $('#booking-trip').on('click', function(){
        $('#booking-trip i').toggleClass('fa-chevron-up');
        $('#booking-trip i').toggleClass('fa-chevron-down');
    });
});