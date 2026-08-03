

function addtt(){
    
    var addList = document.getElementById('nuevos');

    var text = document.createElement('div');
    
    text.innerHTML = '<label> Titulo</label>'+
    '<input type="text" name="titulo[]" id="titulo[]" class="form-control" required> ' +
    '<label>Descripcion</label>'+
    '<textarea class="summernote" name="descripcion[]"></textarea>';
   
    addList.appendChild(text);
     $('.summernote').summernote();
    // $('.summernoteen').summernote();

}

