$(document).ready(function (){
  let detItem = JSON.parse(item_prices.value);
  detItem.forEach((item, i) => {
    var price = new Detail(pm.counter,
        item.unit_id,
        item.price_id,
        cleanNumber(item.quantity),
        cleanNumber(item.selling_price),
        cleanNumber(item.pct),
        cleanNumber(item.default),
        item.price,
        item.unidad
        );
    pm.details.push(price);
    pm.counter++;
    pm.render();
  });
});
