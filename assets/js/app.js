import '../../node_modules/bootstrap/dist/css/bootstrap.min.css';
import '../../node_modules/bootstrap/dist/js/bootstrap.min.js';
import '../../node_modules/bxslider/dist/jquery.bxslider.min.js';
import '../../node_modules/bxslider/dist/jquery.bxslider.css';
import '../scss/app.scss';


$(document).ready(function(){
    $('.slider').bxSlider(
        {
            captions: true
        }
    );
});