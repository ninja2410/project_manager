class Detail {
  constructor(id, unit_id, price_id, quantity, price, profit, def, price_name, unit_name) {
    this.id = id;
    this.unit_id = unit_id;
    this.price_id = price_id;
    this.quantity = quantity;
    this.selling_price = price;
    this.profit = profit;
    this.default = Boolean(def);
    this.price_name = price_name;
    this.unit_name = unit_name;
    this.default_show = '';
    if (this.default){
      this.default_show = `<i class="fa fa-check-square-o"></i>`;
    }
  }
  update(unit_id, price_id, quantity, price, profit, def, price_name, unit_name) {
    this.unit_id = unit_id;
    this.price_id = price_id;
    this.quantity = quantity;
    this.selling_price = price;
    this.profit = profit;
    this.default = def;
    this.price_name = price_name;
    this.unit_name = unit_name;
    this.default_show = '';
    if (this.default){
      this.default_show = `<i class="fa fa-check-square-o"></i>`;
    }
  }
  getNames(){
    $.ajax({
      type: "post",
      url: APP_URL+"/name_unit_price",
      context:this,
      data: {
        _token: document.getElementsByName("_token")[0].value,
        unit_id: this.unit_id,
        price_id: this.price_id
      },
      error: function (error) {
        hideLoading();
        console.log('Error:' + error);
      }
    }).then(function (resp){
      let rsp = JSON.parse(resp);
      this.price_name = rsp.price;
      this.unit_name = rsp.unit;
    });
  }
}

class PricesManager {
  constructor() {
    this.details = [];
    this.counter = 0;
  }
  addPrice(){
    var price = new Detail(this.counter,
        unit_id.value,
        price_id.value,
        cleanNumber(quantity.value),
        cleanNumber(selling_price.value),
        cleanNumber(profit.value),
        default_price.checked,
        $("#price_id").select2("data")[0].text,
        $("#unit_id").select2("data")[0].text
        );
    this.details.push(price);
    this.counter++;
    this.render();
  }
  deletePrice(index){
    this.details.splice(index,1);
    this.render();
  }
  editPrice(id){
    var price = this.details.find(detail => detail.id === id);
    $("#unit_id").val(price.unit_id).trigger("change");
    $("#price_id").val(price.price_id).trigger("change");
    quantity.value = cleanNumber(price.quantity),
    selling_price.value = cleanNumber(price.selling_price),
    profit.value = cleanNumber(price.profit),
    default_price.checked = price.default;
    detail_id.value = id;
    btnAddPrice.style.display = 'none';
    btnEditPrice.style.display = 'inline';
  }
  updatePrice(){
    this.details.forEach(function (detail){
      if (detail.id == detail_id.value){
        detail.update(
            unit_id.value,
            price_id.value,
            cleanNumber(quantity.value),
            cleanNumber(selling_price.value),
            cleanNumber(profit.value),
            default_price.checked,
            $("#price_id").select2("data")[0].text,
            $("#unit_id").select2("data")[0].text
        );
      }
    });
    this.render();
  }
  render(){
    this.details.sort((a,b) => {
      if (a.price_id>b.price_id) {
        return 1;
      }
      if (a.price_id<b.price_id) {
        return -1;
      }
      return 0;
    });

    var html = '';
    $("#tblPrices tbody").empty();
    this.details.forEach(function (detail, index){
      html+=`<tr>
              <td>${detail.unit_name}</td>
              <td>${detail.price_name}</td>
              <td>${cleanNumber(detail.quantity).format(2)}</td>
              <td>${cleanNumber(detail.profit).format(2)} %</td>
              <td>Q ${cleanNumber(detail.selling_price).format(2)}</td>
              <td>${detail.default_show}</td>
              <td>
                  <button class="btn btn-xs btn-info" type="button" onclick="pm.editPrice(${detail.id})">
                    <i class="fa fa-pencil"></i>
                  </button>
                  <button class="btn btn-xs btn-danger" type="button" onclick="pm.deletePrice(${index})">
                    <i class="fa fa-trash"></i>
                  </button>
              </td>
            </tr>`;
    });
    $("#tblPrices tbody").append(html);
    prices_details.value = JSON.stringify(this.details);
  }
}
