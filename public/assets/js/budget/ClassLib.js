class Item {
    constructor(row) {
        this.container = row;

        //clases y atributos
        this.attId = 'item_id';
        this.cnm = 'itm_name';
        this.cs = 'itm_size';
        this.cuc = 'unit_cost';
        this.qc = 'quantity_item';
        //ATRIBUTES
        this.item_id = null;
        this.name = '';
        this.size = '';
        this.quantity = 0;
        this.cost = 0;
    }
    map(){
        this.item_id = this.container.getAttribute(this.attId);
        this.name = this.container.getElementsByClassName(this.cnm)[0].textContent;
        this.size = this.container.getElementsByClassName(this.cs)[0].textContent;
        this.quantity = cleanNumber(this.container.getElementsByClassName(this.qc)[0].textContent);
        this.cost = cleanNumber(this.container.getElementsByClassName(this.cuc)[0].textContent);
        return this;
    }
}
class LineTemplate {
    constructor(element) {
        //CLASES
        this.nc = 'lt_name';
        this.sc = 'lt_size';
        this.qc = 'Quantity_line';
        this.cuc = 'Total_line';
        this.tl = 'Subtotal_line';
        this.row = '_item_row';

        //ATRIBUTOS
        this.attrId ='line_template_id';
        this.line_id = null;
        this.name = '';
        this.quantity = 0;
        this.size = '';
        this.totalCost = 0;
        this.unitCost = 0;
        this.element = element;
        this.items = [];
    }

    getItems(){
        this.items = [];
        let rows = this.element.getElementsByClassName(this.row);
        for(let row of rows){
            if (row){
                let _ntmp = new Item(row);
                _ntmp.map();
                this.items.push(_ntmp);
            }
        }
    }

    buildJson(){
        this.getItems();
    }

    build(){
        this.line_id = this.element.getAttribute(this.attrId);
        this.name = this.element.getElementsByClassName(this.nc)[0].textContent;
        this.size = this.element.getElementsByClassName(this.sc)[0].textContent;
        this.quantity = cleanNumber(this.element.getElementsByClassName(this.qc)[0].textContent);
        this.totalCost = cleanNumber(this.element.getElementsByClassName(this.tl)[0].textContent);
        this.unitCost = cleanNumber(this.element.getElementsByClassName(this.cuc)[0].textContent);
        return this;
    }
    renderRow(){
        return '<tr>'
            +'<td>'+this.name+'</td>'
            +'<td>'+this.size+'</td>'
            +'<td>'+this.quantity+'</td>'
            +'<td>'+current+this.unitCost.format(2)+'</td>'
            +'<td>'+current+this.totalCost.format(2)+'</td>'
            +'</tr>';
    }
}
class Header {
    constructor(header_id) {
        this.header_id = header_id;

        //clases
        this.headerClass = 'header_container';
        this.detailClass = 'detail';
        this.subTotalClass = 'Subtotal_line';
        this.subTotalTableClass = 'subtotal_table';
        this.hteatleClass = 'header_title';
        this.ltClass = 'lt_detail';

        this.totalCost = 0;
        this.totalItems = 0;
        this.totalCostItems = 0;
        this.totalCostServices = 0;
        this.name='';

        //ATRIBUTES
        this.attrType = 'type_item';

        this.details = [];
        this.jsonDetails ='';
        this.element = document.getElementsByClassName(this.headerClass+this.header_id)[0];
    }

    /**
     * Mapea los renglones agregados al header
     */
    mapDetails(){
        this.details = [];
        let details = this.element.getElementsByClassName(this.detailClass);
        this.name = this.element.getElementsByClassName(this.hteatleClass)[0].textContent;
        //OBTENER DETALLES DE CADA RENGLON AGREGADO AL HEADER
        for (let det of details){
            let nd = new LineTemplate(det);
            nd.buildJson();
            this.details.push(nd.build());
        }
    }

    buildJson(){
        this.mapDetails();
        this.jsonDetails = JSON.stringify(this.jsonDetails);
    }

    renderRow(){
        this.mapDetails();
        let html = '<tr>'
            +'<td colspan="6" style="background-color: #928d8d">'+this.name+'<td>'
            +'</tr>';
        this.details.forEach(function (detail) {
            html+=detail.renderRow();
        });
        return html;
    }
    getTotal(){
        this.totalCost = 0;
        this.totalCostItems = 0;
        this.totalCostServices = 0;
        let stl = this.element.getElementsByClassName(this.subTotalClass);
        for (let st of stl){
            this.totalCost += cleanNumber(st.textContent);
        }

        let stTbl = this.element.getElementsByClassName(this.subTotalTableClass);
        for (let stt of stTbl){
            let ta = stt.getAttribute(this.attrType);
            if (ta == 1){
                this.totalCostItems += cleanNumber(stt.textContent);
            }
            else{
                this.totalCostServices += cleanNumber(stt.textContent);
            }
        }
        return cleanNumber(this.totalCost);
    }
}

class Budget {
    constructor() {
        this.headers = [];
        this.totalCost = 0;
        this.totalCostItems = 0;
        this.totalCostServices = 0;
        this.idTotalF = 'bdTotal';
        this.idTotalIF = 'bdITotal';
        this.idTotalSF = 'bdSTotal';

        //CLASES
        this.tblIntegrated = 'tblBudgetIntegrated';
        this.ftrIntegrated = 'fTotalInt';
        this.hasError = false;
    }
    addHeader(header){
        this.headers.push(header);
        return 1;
    }
    getTotal(){
        let ac = 0;
        let tcItems = 0;
        let tcServices = 0;
        this.headers.forEach(function (header) {
            ac += header.getTotal();
            tcItems += header.totalCostItems;
            tcServices += header.totalCostServices;
        });
        this.totalCost = cleanNumber(ac);
        this.totalCostItems = cleanNumber(tcItems);
        this.totalCostServices = cleanNumber(tcServices);
        return ac;
    }
    deleteHeader(header_id){
        let head = null;
        for (let i =0; i < this.headers.length; i++){
            if (this.headers[i].header_id == header_id){
                this.headers.splice(i, 1);
            }
        }
    }
    updateFooter(){
        this.getTotal();
        document.getElementById(this.idTotalF).textContent = current+this.totalCost.format(2);
        document.getElementById(this.idTotalIF).textContent = current+this.totalCostItems.format(2);
        document.getElementById(this.idTotalSF).textContent = current+this.totalCostServices.format(2);
    }

    renderIntegratedDetail(){
        let html = '';
        this.headers.forEach(function (header) {
            html += header.renderRow();
        });
        $('#'+this.tblIntegrated).find('tbody').empty();
        $('#'+this.tblIntegrated).find('tbody').append(html);
        document.getElementById(this.ftrIntegrated).textContent = current+this.totalCost.format(2);
    }

    renderSummarys(){
        this.renderIntegratedDetail();
    }

    buildJson(){
        let json = [];
        this.headers.forEach(function (header) {
            header.buildJson();
            json.push(header);
        });
        return JSON.stringify(json);
    }
}
