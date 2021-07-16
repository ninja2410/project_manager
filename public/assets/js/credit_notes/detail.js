/**
 * DETALLE DE DOCUMENTO
 */
class Detail {
    /**
     * CONSTRUCTOR CLASE
     * @param quantity: Cantidad de producto
     * @param detail: Descripción del producto
     * @param id: ID del detalle
     * @param code: Código de producto
     * @param price: Precio unitario de producto
     */
    constructor(quantity, detail, id, code, price) {
        this.quantity = quantity;
        this.detail = detail;
        this.id = id;
        this.price = price;
        this.code = code;
    }

    /**
     * Calcula el total de cada detalle de factura
     * @returns {number}
     */
    get total(){
        return this.quantity*this.price;
    }

    /**
     * funcion que retorna html configurado para imprimir tabla de detalles
     * @returns {string}
     */
    get render(){
        return  '<tr id="'+this.id+'" class="standar_row">'+
                    '<td>'+this.code+'</td>'+
                    '<td>'+this.detail+'</td>'+
                    '<td>Q'+number_format(this.price, 2)+'</td>'+
                    '<td><input type="number" id_detail="'+this.id+'" readonly value="'+
                        this.quantity+'" class="form-control">'+
                    '</td>'+
                    '<td>Q'+ number_format(this.total, 2)+'</td>'+
                    '<td class="hide  detControl devolucion"><input type="text" min="0" id_detail="'+
                        this.id+'" maxQty="'+this.quantity+'" costItem="'+this.price+'" oldValue="0" value="0"  onchange="change_devolucion(this)"' +
                        ' class="form-control input_custom devolucion_input number">'+
                    '</td>'+
                    '<td class="hide  detControl descuento"><input type="text" min="0" onchange="change_descuento(this)"  id_detail="'+
                        this.id+'" maxAmount="'+this.total+'" oldValue="0" value="0" class="form-control input_custom descuento_input number">'+
                    '</td>'+
                '</tr>';
    }

    /**
     * Funcion que retorna html configurado para imprimir el detalle unico de descuento general para el documento
     * @returns {string}
     */
    get renderGd(){
        return  '<tr id="'+this.id+'" class="hide dsc_row">'+
            '<td>'+this.code+'</td>'+
            '<td>'+this.detail+'</td>'+
            '<td>Q'+number_format(this.price, 2)+'</td>'+
            '<td><input type="number" id_detail="'+this.id+'" readonly value="'+
            this.quantity+'" class="form-control">'+
            '</td>'+
            '<td>Q'+ number_format(this.total, 2)+'</td>'+
            '<td class="hide  detControl devolucion"><input type="text" min="0" id_detail="'+
            this.id+'" maxQty="'+this.quantity+'" costItem="'+this.price+'" oldValue="0" value="0" onchange="change_devolucion(this)" ' +
            ' class="form-control input_custom devolucion_input number">'+
            '</td>'+
            '<td class="detControl descuento"><input type="text" min="0" onchange="change_descuento(this)"  id_detail="'+
            this.id+'" maxAmount="'+this.total+'" oldValue="0" value="0" class="form-control input_custom descuento_input number">'+
            '</td>'+
            '</tr>';
    }
}

/**
 * Listener inputs de devolución
 * @param control: Input modificado
 */
function change_devolucion(control){
    let newVal = cleanNumber(control.value);
    let oldVal = cleanNumber(control.getAttribute("oldValue"));
    let price = cleanNumber(control.getAttribute("costItem"));
    let max = cleanNumber(control.getAttribute("maxQty"));

    if (max<newVal){
        toastr.error("No puede devolver una más producto que la cantidad de factura.");
        control.value = number_format(oldVal, 2);
        return;
    }
    if (newVal<0){
        toastr.error("Debe ingresar un valor mayor o igual que 0.");
        control.value = number_format(oldVal, 2);
        return;
    }
    if (!isNaN(newVal)){
        hVenta.total_desc((oldVal*-1)*price);
        hVenta.total_desc((newVal*price));
        control.setAttribute("oldValue", number_format(newVal, 2));
    }
    else{
        toastr.error("Debe ingresar un valor válido");
        control.value = number_format(oldVal, 2);
        return;
    }
}

/**
 * LISTENER QUE CAMBIA EL VALUDA Y CAMBIA EL BALOR DEL DESCUENTO A APLICAR EN CADA LINEA DE LA FACTURA
 * @param control
 */
function change_descuento(control){
    let newVal = cleanNumber(control.value);
    let oldVal = cleanNumber(control.getAttribute("oldValue"));
    let maxCost = cleanNumber(control.getAttribute("maxAmount"));
    if (isNaN(newVal)){
        toastr.error("Debe ingresar un valor válido");
        control.value = number_format(oldVal, 2);
        return;
    }
    if (newVal<0){
        toastr.error("Debe ingresar un valor mayor o igual que 0.");
        control.value = number_format(oldVal, 2);
        return;
    }
    if (newVal>maxCost){
        toastr.error("No puede realizar un descuento mayor al valor total del artículo.");
        control.value = number_format(oldVal, 2);
        return;
    }
    hVenta.total_desc((oldVal*-1));
    hVenta.total_desc(number_format(newVal, 2));
    control.setAttribute("oldValue", cleanNumber(newVal));
}
