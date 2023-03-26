function fonctionRecupInput(){

  var titreEnter = document.getElementByName("titreEnter");
  alert(titreEnter);

  $.ajax({
    type: "POST",
    url: "{{path('add')}}",
    data: titreEnter
});
}

