var offset = 10;
var currentPage = 0;
var maxPages = 15;
var order = 'date';
var cloudSettings = {
 "size" : { "grid" : 10, "factor" : 0, "normalize" : true },
            "color" : { "start" : "#777", "end" : "#333" },
            "options" : { "color" : "gradient", "rotationRatio" : 0, "printMultiplier" : 2, "sort" : "highest" },
            "font" : "Josefin Sans", "shape" : "square"

};


$(document).ready(function () {

  loadDestacados();
  loadCloud();
  loadMsjs(order);

  $("#destacados").gridalicious({
    width: 250
  });

  $("#timeline").gridalicious({
    width: 250,
    animate: true,
    animationOptions: {
      duration: 500
    },
  });

});


function getDocHeight() {
    var D = document;
    return Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
}


$(window).scroll(function() {
  if( $(window).scrollTop() + $(window).height()   == getDocHeight()) {
    if( currentPage < maxPages){
      loadMsjs(order);
    } else {
      $(".limit").html("<h2>No quedan mensajes por mostrar</h2>");
    }
  }
});


$(".send-msj").click( function(e) {
  e.preventDefault();
  var text = "&text=(Escribe aquí ideas, opiniones o propuestas sobre una Nueva Constitución)";
  var url = "https://twitter.com/share?";
  url += "&hashtags=ideasAC&url=ideas.wikiac.cl";
  url += text;
  console.log(url);
  window.open(url, "popupWindow", "width=600,height=600,scrollbars=yes");
});


$('#postForm').submit(function () {

  if(!loggedIn){
    if ($.trim($('#nombre').val())  === '') {
      alert('Olvidaste escribir tu nombre!');
      return false;
    }
    if ($.trim($('#email').val()) === '') {
      alert('Olvidaste escribir tu dirección de correo.');
      return false;
    }
  }
  // Check if empty
  if ($.trim($('#mensaje').val())  === '') {
    alert('Olvidaste escribir tu mensaje.');
    return false;
  }

  submitMensaje();
  return false;
});

$('#votados').click( function(e){
  e.preventDefault();
  $('#timeline').empty();
  order = 'important';
  currentPage = 0;
  $("body, html").animate({
            scrollTop: $( this ).offset().top
        }, 600);
  loadMsjs(order);
  $("#timeline").gridalicious({
    width: 250,
    animate: true,
    animationOptions: {
      duration: 500
    }
  });
  return false;
});

$('#recientes').click( function(e){
  e.preventDefault();
  $('#timeline').empty();
  $("#timeline").gridalicious({
    width: 250,
    animate: true,
    animationOptions: {
      duration: 500
    }
  });
  order = 'date';
  currentPage = 0;
  $("body, html").animate({
            scrollTop: $( this ).offset().top
        }, 600);

  loadMsjs(order);
  return false;
});
