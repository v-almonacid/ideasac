var base = './api';

var loadDestacados = function() {
  var url = base + "/mensajes?page=0&offset=12&order=important";
  url += "&text=html";
  console.log('Fetching ' + url);
  $.getJSON( url, function( data ) {
    if(data.length){
      var item;
      $.each( data, function( i ) {
        item = createItem( data[i], '' );
        $('#destacados').gridalicious('append', item);
      });
      console.log("loading of "+ url + " completed");
    } else {
      console.log("Error while loading featured messages");
    }
  });
};


var loadMsjs = function(order) {
  var url = base + "/mensajes?page=" + currentPage + "&offset="+ offset;
  url += "&order=" + order + "&text=html";
  console.log("Fetching " + url);
  $.getJSON( url, function( data ) {
    if(data.length){
      var item;
      $.each( data, function( i ) {
        item = createItem( data[i], 'tl' );
        $('#timeline').gridalicious('append', item).fadeIn("slow");
      });
      currentPage++;
      console.log("Loading of " + url + " completed");
    } else {
      $(".limit").html("<h2>No quedan mensajes por mostrar</h2>");
    }
  });
};

function submitMensaje(){
  if(!loggedIn){
    nombre = $.trim($('#nombre').val());
    mail = $.trim($('#email').val());
    datos = JSON.stringify({
        "nombre": nombre,
        "email": mail,
        "texto": $('#mensaje').val(),
        "is_tweet": 'false'
    });
  } else {
    datos = JSON.stringify({
        "texto": $('#mensaje').val(),
        "is_tweet": 'false'
    });
  }
  console.log(datos);
  $.ajax({
      type: 'POST',
      contentType: 'application/json',
      url: base + '/mensajes',
      dataType: 'json',
      data: datos,
      success: function(data){
        $('#resultados').html(data);
        data[0].msj_nfavor = 0;
        data[0].msj_ncontra = 0;
        item = createItem(data[0], 'tl');

        // finally add item into timeline
        $('#timeline').gridalicious('prepend', item);

        $("body, html").animate({
            scrollTop: $( item ).offset().top
        }, 600);

        $('#postForm').find("input[type=text], input[type=email], textarea").val("");
      }
    });
}


function createItem(data, type){

  id = data.id;
  mdate = data.msj_date;
  mtxt = data.msj_txt;
  mnombre = data.msj_nombre;
  mlikes = data.msj_nfavor;
  mdislikes = data.msj_ncontra;
  // create DOM elements following an outside-in approach

  // start with divs
  var item = $('<div></div>').addClass('item');
  var tweetWrapper =  $('<div></div>').addClass('tweet-wrapper');
  var tweetHeader = $('<div></div>').addClass('tweet-header');
  // var text = $('<div></div>').addClass('text');
  // var user = $('<div></div>').addClass('user');
  var text = $('<blockquote></blockquote>');
  var user = $('<cite></cite>');

  // define a parent-child relation between divs
  tweetWrapper.appendTo(item);
  tweetHeader.appendTo(tweetWrapper);
  text.appendTo(tweetWrapper);
  user.appendTo(tweetWrapper);

  // set content into 'tweet-header' div
  if (type == 'tl'){
    var time = $('<span></span>').addClass('time');
    time.appendTo(tweetHeader);
    moment.lang('es'); // added
    var myDate = moment(mdate).fromNow(); //added
    time.text( myDate );
  }

  // set votos
  var votos = $('<span></span>').addClass('votos');
  votos.appendTo(tweetHeader);
  var likeLink = $('<a>',{
    id: 'like-b-' + id,
    class: 'l-b l-' + id,
    href: '#',
    }).appendTo(votos);

  $(likeLink).click(function(event){
    var mid = ($(this).attr('id').split('-'))[2];
    var mtipo = ($(this).attr('id').split('-'))[0];
    vote(mid, mtipo);
    return false;
  });

  // add thumbs-up icon
  var thumbsUpIcon = $('<i>').addClass('fa fa-thumbs-up').appendTo(likeLink);
  var nLikesSpan = $('<span>',{ class: 'nfavor' }).appendTo(likeLink);
  // set the number of likes to 0
  nLikesSpan.text(mlikes);

  // a separator between likes and dislikes
  votos.append(' · ');

  // do the same for the dislike button
  var dislikeLink = $('<a>',{
    id: 'dislike-b-' + id,
    class: 'l-b d-' + id ,
    href: '#',
    }).appendTo(votos);

  $(dislikeLink).click(function(event){
    var mid = ($(this).attr('id').split('-'))[2];
    var mtipo = ($(this).attr('id').split('-'))[0];
    vote(mid, mtipo);
    return false;
  });

  var thumbsDownIcon = $('<i>').addClass('fa fa-thumbs-down').appendTo(dislikeLink);
  var nDislikesSpan = $('<span>',{ class: 'ncontra' }).appendTo(dislikeLink);
  nDislikesSpan.text(mdislikes);

  // set content
  //text.text(mtxt);
  text.html(mtxt);

  // set username
  user.text(mnombre);

  return item;
}

$(".l-b").click(function(event){
  var mid = ($(this).attr('id').split('-'))[2];
  var mtipo = ($(this).attr('id').split('-'))[0];
  vote(mid, mtipo);
  return false;
});

function vote (id, type){
  if(!loggedIn){
    alert("Debes iniciar sesión para poder votar.");
    return;
  }
  console.log("Checking id...", id);
  console.log("Checking type...", type);

  $.ajax({
    type: 'put',
    contentType: 'application/json',
    url: base + '/mensajes/' + id,
    dataType: 'json',
    data: JSON.stringify({
      "mid": id,
      "type": type
    }),
    success: function(data){
      console.log("success, the following item was updated: ", data[0].id);
      console.log("likes: ", data[0].msj_nfavor);
      console.log("dislikes: ", data[0].msj_ncontra);
      var id = data[0].id;
      $('.l-' + id.toString()).find(".nfavor").html(data[0].msj_nfavor);
      $('.d-' + id.toString()).find(".ncontra").html(data[0].msj_ncontra);
    }
  });
}

function loadCloud(){
  var minFontSize = 15;
  var maxFontSize = 40;
  var url = base + "/cloud?min_font_size=" + minFontSize + '&max_font_size=' + maxFontSize;
  console.log('Loading: '+ url);
  $('#wordcloud').load(url, function (responseTxt, statusTxt, xhr) {
    if (statusTxt == "success") {
      $( "#wordcloud" ).awesomeCloud( cloudSettings );
    } else if (statusTxt == 'error') {
      console.log('Error al cargar cloud desde API. Cargando contenido estático.');
      url = 'content/default_cloud.html';
      $('#wordcloud').load(url, function (responseTxt, statusTxt, xhr) {
        $( "#wordcloud" ).awesomeCloud( cloudSettings );
      });
    }
  });
}

function search(key){
  var url = base + "/mensajes/search/" + key + "?text=html";
  console.log("Searching :" + url); //debug
  $.getJSON( url, function( data ) {
    if(data.length){
      var item;
      $.each( data, function( i ) {
        item = createItem( data[i], 'tl' );
        $('#timeline').gridalicious('append', item).fadeIn("slow");
      });
      console.log("Search completed"); //debug
    } else {
    }
  });
}
