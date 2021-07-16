var contador=0;
var total_cost = 0;
var item_quantity=document.getElementById('item_quantity');
item_quantity.value=contador;
function add(button)
{
    var row=button.parentNode.parentNode;
    var cells=row.querySelectorAll('td:not(:last-of-type)');
    var id_item=button.id;
    if(document.getElementById('cantidad_'+id_item))
    {
        var cantidad_existente=document.getElementById('cantidad_'+id_item);
        cantidad_existente.focus();
    }
    else
    {
        addToCartTotable(cells,id_item);
        contador++;
        var item_quantity=document.getElementById('item_quantity');
        item_quantity.value=contador;

    }
}

function addToCartTotable(cells,id_item)
{
    var name = cells[0].innerText;
    var stock = cells[1].innerText;
    var cost = cells[2].innerText;
    max_quantity=stock;
    total_cost += (+cost);
    var newRow = document.createElement('tr');
    newRow.appendChild(createCell(name));
    var cellInputQty=createCell();
    cellInputQty.appendChild(createInputQty(max_quantity,id_item, +cost));
    var cellRemoveBtn=createCell();
    cellRemoveBtn.appendChild(createRemoveBtn(id_item));
    var cellHidden=createCell();
    cellHidden.appendChild(createInputHidden(id_item));
    var cellcost=createCell();
    cellcost.appendChild(createInputCost(cost));
    newRow.appendChild(cellInputQty);
    newRow.appendChild(cellRemoveBtn);
    newRow.appendChild(cellHidden);
    newRow.appendChild(cellcost);
    //newRow.appendChild(createCell(stock));
    document.querySelector('#target tbody').appendChild(newRow);
}

function createCell(text)
{
    var td = document.createElement('td');
    if(text)
    {
        td.innerText = text;
    }
    return td;
}

function updateCost(){
    if (total_cost<0){
        total_cost = 0;
    }
    $('#total_cost_lbl').text('');
    $('#total_cost_lbl').text("Q "+parseFloat(total_cost).toFixed(2));
    $('#totalCost').val(parseFloat(total_cost).toFixed(2));
}


function createInputQty(max_quantity,id_item,cost)
{
    var inputQty = document.createElement('input');
    inputQty.type = 'number';
    inputQty.id='cantidad_'+id_item;
    inputQty.name='cantidad_'+contador;
    inputQty.required = 'true';
    inputQty.style.cssText='text-align:center;';
    inputQty.setAttribute('cost', cost);
    inputQty.min = 1;
    inputQty.addEventListener('change',function(){
        var id=this.id;
        var cantidad_cambio=document.getElementById(id);
        if(cantidad_cambio.value==""||(parseFloat(cantidad_cambio.value)<0))
        {
            cantidad_cambio.value=cantidad_cambio.defaultValue;
        }
        else{
            if (parseFloat(cantidad_cambio.value)>parseFloat(this.max)) {
                this.value=this.defaultValue;
                toastr.error("Existencias insuficientes.", "Error");
            }
            else{
                total_cost -= (+cantidad_cambio.getAttribute('cost')*cantidad_cambio.defaultValue);
                total_cost += (+cantidad_cambio.getAttribute('cost')*cantidad_cambio.value);
                cantidad_cambio.defaultValue = cantidad_cambio.value;
                updateCost();
            }
        }
    });
    inputQty.value=1;
    inputQty.defaultValue=1;
    inputQty.max=max_quantity;
    inputQty.className = 'form-control';
    inputQty.style.width='50%';
    updateCost();
    return inputQty;
}

function createInputHidden(id_item) {
    var inputHidden = document.createElement('input');

    inputHidden.type = 'hidden';
    inputHidden.name='id_product_'+contador;
    inputHidden.required = 'true';
    inputHidden.style.cssText='text-align:center;';
    inputHidden.min = 1;
    inputHidden.value=id_item;
    inputHidden.className = 'form-control';
    return  inputHidden;
}

function createInputCost(cost) {
    var inputHidden = document.createElement('input');

    inputHidden.type = 'hidden';
    inputHidden.name='id_productcost_'+contador;
    inputHidden.required = 'true';
    inputHidden.style.cssText='text-align:center;';
    inputHidden.min = 1;
    inputHidden.value=cost;
    inputHidden.className = 'form-control';
    return  inputHidden;
}

function createRemoveBtn(item_id)
{
    var btnRemove=document.createElement('button');
    btnRemove.className='btn btn-xs btn-danger';
    btnRemove.onclick=remove;
    btnRemove.setAttribute('id_item', item_id);
    btnRemove.innerText='Eliminar';
    return btnRemove;
}

function remove()
{
    /*
    DESCONTAR CANTIDAD
    * */
    var input = document.getElementById('cantidad_'+$(this).attr('id_item'));
    var qty = input.value;
    var cost = input.getAttribute('cost');
    var total = qty*cost;
    total_cost -= parseFloat(total).toFixed(2);
    updateCost();
    var row=this.parentNode.parentNode
    document.querySelector('#target tbody')
        .removeChild(row);
    contador--;
    var item_quantity=document.getElementById('item_quantity');
    item_quantity.value=contador;
}

function sendForm(){
    showLoading("Guardando traslado de bodega...");
    document.getElementById('addCustomerPayment').submit();
}

btn_save_transfer=document.getElementById('btn_save_transfer');
btn_save_transfer.addEventListener('click',function()
{
    var id_storage_destination=document.getElementById('id_storage_destination');
    var id_document=document.getElementById('id_document');
    var contador=document.getElementById('item_quantity').value;
    // alert(contador);
    selected=id_storage_destination.value;
    selected2=id_document.value;
    if(selected2==0)
    {
        id_document.focus();
    }
    else if(selected==0)
    {
        id_storage_destination.focus();
    }
    else if ((contador==0) || (contador<0))
    {
        toastr.error("Debe agregar productos al traslado...");
        //  document.getElementById("message_div").style.display = 'inline';
        //  var mensaje_error = document.getElementById("error_message");
        //  mensaje_error.innerHTML = "Debe agregar productos para continuar!";
        //  setTimeout(function(){
        //  document.getElementById("message_div").style.display = "none";
        // }, 3000);
        // alert('no hay articulos');
        // window.setTimeout(document.getElementById("message_div").style.display = 'none', 3000);
    }
    else
    {
        $('#confirmSave').modal('show');
        // document.getElementById('id_form_save').submit();
    }
});


$('#id_document').change(function () {
    $('#documento').val($(this).val());
});

$('#id_storage_destination').change(function () {
    $('#bodega_destino').val($(this).val());
});

$('#comment').change(function () {
    $('#tsComment').val($(this).val());
});
