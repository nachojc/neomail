(function($){
    // Ordenacion 
    var headers =$('THEAD TH'), footers =$('TFOOT TH'), sortable=[];
    $.each( headers, function(i,ele){  ele.classList.contains('sortable') && sortable.push(i); });

    sortable.length && $('th.manage-column.sortable').on('click', toggleSortFn);
    
    function toggleSortFn(obj) {
        var pos = obj.currentTarget.cellIndex;
        var column = $([headers[pos],footers[pos]]);
        sortable.forEach(function(i){
            i!==pos && $([headers[i],footers[i]]).removeClass('desc').addClass('asc');
        });

        if (hasClass(obj,'asc')){
            column.removeClass('asc').addClass('desc');
        }else{
            column.removeClass('desc').addClass('asc');
        }
    }
    


    function hasClass( obj,name){
        return obj.currentTarget.classList.contains(name);
    }

    $('.page-title-action').on('click', addNewList);
    
    function addNewList(){
        var modal = `<form>
        <div><p><label for="name">Name</label></p><input type="text" name="name" id="name"/></div>
        <div><p><label for="description">Description</label></p><input type="text" name="description" id="description"/></div>
        <div><p class="btn"><button class="button button-primary button-large modal-action">Add</button><p><div>
        </form>`;
        openModal('Add new list',modal, 'short');
        return false;
        
    }

    var content = $('#wpcontent');
    function openModal(tittle, modal, style){
        var width = style || '';
        content.append('<div class="modal-back"></div><div class="modal-main '+ width +'"><div class="content"><div class="header"><span>'+(tittle||'')+'</span><button>X</button></div><div class="main">'+ (modal||'')+'</div></div>')
        $('.modal-back,.modal-main div.header button').on('click', closeModal);
        $('.modal-action').on('click',sendData);
    }
    function closeModal(){
        $('.modal-back,.modal-main div.header button').off('click', closeModal);
        content.find('.modal-back, .modal-main').remove();
    }
    function sendData(e){
        var val=$(e.currentTarget).closest('form').serializeArray();

        
        $.ajax({
            url:  '/wp-json/neomail/v1/list/add/',
            method: 'POST',
            beforeSend: function(xhr) {
                // xhr.setRequestHeader( 'X-WP-Nonce', votaciones_data.nonce);
            },
            data: getJson(val)
        })
        .done(function(data) {console.log(data);})
        .fail(function(data) {console.log(data);})

        // $.post("/wp-json/neomail/v1/list/add/", obj,
        //     function(data, status){
        //         console.log(data);
                
        //         // alert("Data: " + data + "\nStatus: " + status);
        //     });
        return false;
    }
    function getJson(array){
        var result={};
        array.forEach(function(ele){
            result[ele.name]=ele.value;
        })
        return result;
    };
}(jQuery));