
// import '../../node_modules/bootstrap/dist/css/bootstrap.min.css';
import '../../node_modules/bootstrap/dist/js/bootstrap.min.js';
// import '../../node_modules/bxslider/dist/jquery.bxslider.min.js';
// import '../../node_modules/bxslider/dist/jquery.bxslider.css';
import '../../node_modules/animate.css/animate.min.css';
import '../scss/app.scss';


$(document).ready(function(){
    // $('.slider').bxSlider(
    //     {
    //         captions: true
    //     }
    // );

    $("a.btn-primary").hover(
        function(){
            $(this).find('i.fa-heart').addClass('animated pulse');
        },
        function(){
            $(this).find('i.fa-heart').removeClass('animated pulse');
        }
    );
});