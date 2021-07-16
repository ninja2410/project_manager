new Vue({
  el: "#app",
  components: {
    Multiselect: window.VueMultiselect.default
  },
  data: {
    size:"",
    nombreKit: "",
    categorias: [],
    categoria: "",
    items: [],
    item: "",
    description: "",
    code: "",
    quantity: 1,
    price:[],
    prices: [],
    kitItems: [],
    subtotal: [],
    utility: [],
    utilitySale: [],
    priceSale: [],
    error: false,
    errores: [],
    total: [],
    sumaCost: 0,
    flag: 1,
    imageUrl: "",
    imageFile: "",
    imageName: "",
    idKit: "",
    kit: {},
    loading:false
  },
  mounted: function() {
    this.getUrl();
    this.$emit('input');
    this.getCategory();
    this.getDataEdit();
    this.getDataEditItems();
    this.changeInput('code');
  },
  methods: {
    changeInput(value){
      if(value==='code')
          this.$refs.code.focus();
      else if(value==='name')
          this.$refs.name.focus();
      else if(value==='categoria')
          this.$refs.categoria.$el.focus();
      else if(value==='size')
          this.$refs.size.focus();
      else if(value==='description')
          this.$refs.description.focus();
      else if(value==='image')
          this.$refs.image.focus();
      else if(value==='productos')
          this.$refs.productos.$el.focus();
      else if(value==='cantidad')
          this.$refs.cantidad.focus();
  },
    getUrl() {
      var ruta = window.location.pathname.split("/");
      this.idKit = parseInt(ruta[2]);
    },
    pickFile() {
      this.$refs.image.click();
    },
    onFilePicked(e) {
      const files = e.target.files;
      if (files[0] !== undefined) {
        this.imageName = files[0].name;
        if (this.imageName.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        fr.addEventListener("load", () => {
          this.imageUrl = fr.result;
          this.imageFile = files[0]; // this is an image file that can be sent to server...
        });
      } else {
        this.imageName = "";
        this.imageFile = "";
        this.imageUrl = "";
      }
    },
    validate() {
      this.errores = [];
      this.error = false;
      if (this.categoria === "") {
        this.errores.push("La categoria es requerida");
      }
      if (this.nombreKit === "") {
        this.errores.push("El nombre del Kit es requerido");
      }
      this.prices.forEach((e, j) => {
        if (
          this.priceSale[j] === "0.00" ||
          this.priceSale[j] === "null" ||
          this.priceSale[j] === ""
        ) {
          this.errores.push(`El precio de venta ${e.name} es requerido`);
        }
      });
      this.kitItems.forEach((e, j) => {
        if (e.quantity <= 0)
          this.errores.push(
            `El producto ${e.item_name} no puede tener la cantidad de 0 en el kit`
          );
      });
      if (this.kitItems.length < 1) {
        this.errores.push("No hay productos seleccionados en el kit");
      }
      if (this.errores.length > 0) {
        return true;
      } else {
        return false;
      }
    },
    absQuantity() {
      this.quantity = Math.abs(this.quantity);
    },
    getDataEdit() {
      axios
        .get("/api/item-kit-vue/item/edit?id=" + this.idKit)
        .then(response => {
          this.kit = response.data[0];
          this.categoria = this.kit.item_category;
          this.size=this.kit.size;
          this.price=this.kit.price;
          // se setea el precio costo del kit
          this.sumaCost=this.kit.cost_price;
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    getDataEditItems() {
      axios
        .get("/api/item-kit-vue/items/edit?id=" + this.idKit)
        .then(response => {
          this.kitItems = response.data;
          this.kitItems.forEach((el, j) => {
            this.kitItems[j].total = parseFloat(
              parseFloat(this.kitItems[j].quantity) *
                parseFloat(this.kitItems[j].cost_price)
            ).toFixed(2);
            this.kitItems[j].prices=[];
            this.kitItems[j].valuePrices=[];
            axios
              .get("/api/item-kit-vue/itemPrice?item=" + this.kitItems[j].id)
              .then(response => {
                let data = response.data;
                data.forEach(element => {
                    this.kitItems[j].prices.push(
                    parseFloat(
                      parseFloat(element.selling_price) * this.kitItems[j].quantity
                    ).toFixed(2)
                  );
                  this.kitItems[j].valuePrices.push(
                    parseFloat(element.selling_price).toFixed(2)
                  );
                });
                // this.sumItems();
              })
              .catch(errors => {
                console.log(errors);
                this.getDataEditItems();
              });
          });
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    getCategory() {
      axios
        .get("/api/item-kit-vue/category")
        .then(response => {
          this.categorias = response.data;
          this.items = response.data;
          this.getItem();
          this.getPrices();
        })
        .catch(errors => {
          console.log(errors);
          this.getCategory();
        });
    },
    getItem() {
      this.loading=true;
      axios
        .get("/api/item-kit-vue/item")
        .then(response => {
          this.items = response.data;
          this.loading=false;
        })
        .catch(errors => {
          console.log(errors);
          this.getItem();
        });
    },
    getPagos(index) {
      let cadena = "";

      this.prices[index].pagos.forEach(element => {
        cadena = cadena + element.name + ", ";
      });
      return cadena;
    },
    getPrices() {
      axios
        .get("/api/item-kit-vue/price")
        .then(response => {
          this.prices = response.data;
          this.prices.forEach((element, j) => {
            this.prices[j].pago = this.getPagos(j);
          });

          this.setVariables();
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    getIndex(list, id) {
      return list.findIndex(e => e.id == id);
    },
    changeQuantity(index) {
      this.kitItems[index].quantity = Math.abs(this.kitItems[index].quantity);
      this.kitItems[index].prices = [];
      this.kitItems[index].valuePrices.forEach(i => {
        this.kitItems[index].total = parseFloat(
          parseFloat(this.kitItems[index].quantity) *
            parseFloat(this.kitItems[index].cost_price)
        ).toFixed(2);
        this.kitItems[index].prices.push(
          parseFloat(i * this.kitItems[index].quantity).toFixed(2)
        );
      });
      this.sumItems();
    },
    removeItemFromArr(arr, item) {
      var i = arr.indexOf(item);

      if (i !== -1) {
        arr.splice(i, 1);
      }
    },
    removeKit(kit) {
      this.removeItemFromArr(this.kitItems, kit);
      this.sumItems();
    },
    setVariables() {
      this.prices.forEach((element, j) => {
        this.subtotal[j] = parseFloat(this.price[j].selling_price).toFixed(2);
        this.priceSale[j] = parseFloat(this.price[j].selling_price).toFixed(2);
        this.utility[j] = parseFloat(this.price[j].pct).toFixed(2);
        this.utilitySale[j] = parseFloat(this.price[j].selling_price-this.sumaCost).toFixed(2);
      });
    },
    sumPrice(index) {
      if (index != null)
          this.priceSale[index] = Math.abs(this.priceSale[index]);
      this.flag = 0;
      this.utility = [];
      this.utilitySale = [];
      this.prices.forEach((element, j) => {
          this.utility[j] = parseFloat(((this.priceSale[j] - this.sumaCost) / this.sumaCost) * 100).toFixed(2);
          this.utilitySale[j] = parseFloat(this.priceSale[j] - this.sumaCost).toFixed(2);
      });
    },
    sumUtility(index) {
      if (index != null)
          this.utility[index] = Math.abs(this.utility[index]);
      this.flag = 1;
      this.priceSale = [];
      this.utilitySale = [];
      this.prices.forEach((element, j) => {
          this.priceSale[j] = parseFloat(((parseFloat(this.utility[j]) / 100) * (parseFloat(this.sumaCost))) + (parseFloat(this.sumaCost))).toFixed(2);
          this.utilitySale[j] = parseFloat(this.priceSale[j] - this.sumaCost).toFixed(2);
      });
    },
    sumUtilitySale(index) {
      if (index != null)
          this.utilitySale[index] = Math.abs(this.utilitySale[index]);
      this.flag = 2;
      this.priceSale = [];
      this.utility = [];
      this.prices.forEach((element, j) => {
          this.priceSale[j] = parseFloat((parseFloat(this.sumaCost)) + (parseFloat(this.utilitySale[j]))).toFixed(2);
          this.utility[j] = parseFloat(parseFloat((this.priceSale[j] - this.sumaCost) / this.sumaCost).toFixed(2) * 100).toFixed(2);
      });
  },
    sumItems() {
      this.sumaCost = 0;
      this.kitItems.forEach(element => {
        this.sumaCost = parseFloat(
          parseFloat(this.sumaCost) + parseFloat(element.total)
        ).toFixed(2);
      });

      this.subtotal = [];

      this.prices.forEach((element, j) => {
        this.subtotal[j] = parseFloat(0).toFixed(2);
      });
      this.kitItems.forEach(element => {
        element.prices.forEach((i, j) => {
          this.subtotal[j] = parseFloat(
            parseFloat(this.subtotal[j]) + parseFloat(i)
          ).toFixed(2);
        });
      });
      if (this.flag === 0) this.sumPrice();
      else if (this.flag === 1) this.sumUtility();
      else if (this.flag === 2) this.sumUtilitySale();
    },
    addItemKit() {
      this.error = false;
      this.errores = [];
      if (this.item !== "") {
        if (this.verificarExiste() === false) {
          var newItem = new Object();
          newItem.id = this.item.id;
          newItem.upc_ean_isbn = this.item.upc_ean_isbn;
          newItem.item_name = this.item.item_name;
          newItem.quantity = this.quantity;
          newItem.cost_price = this.item.cost_price;
          newItem.prices = [];
          newItem.valuePrices = [];
          newItem.total = parseFloat(
            parseFloat(this.quantity) * parseFloat(this.item.cost_price)
          ).toFixed(2);
          axios
            .get("/api/item-kit-vue/itemPrice?item=" + this.item.id)
            .then(response => {
              let data = response.data;
              data.forEach(element => {
                newItem.prices.push(
                  parseFloat(
                    parseFloat(element.selling_price) * newItem.quantity
                  ).toFixed(2)
                );
                newItem.valuePrices.push(
                  parseFloat(element.selling_price).toFixed(2)
                );
              });
              this.kitItems.push(newItem);
              this.sumItems();
              this.item = "";
              this.changeInput('productos');
            })
            .catch(errors => {
              console.log(errors);
              this.addItemKit();
            });
        } else {
          this.error = true;
          this.errores.push("El producto ya esta agregado al kit");
          this.item = "";
          return;
        }
      } else {
        this.error = true;
        this.errores.push("No se ha seleccionado ningun producto");
        this.item = "";
        return;
      }
    },
    verificarExiste() {
      let solucion = false;
      this.kitItems.forEach(element => {
        if (element.id === this.item.id) {
          solucion = true;
        }
      });
      return solucion;
    },
    nameWithLang({ upc_ean_isbn, item_name }) {
      return `${upc_ean_isbn} — ${item_name}`;
    },
    save(){
      $('#modalDelete').modal('show');
    },
    saveAll() {
      $('#modalDelete').modal('hide');
      if (this.validate() === true) {
        this.error = true;
        return;
      }
      showLoading();
      var form = new FormData();
      form.append("id", this.idKit);
      form.append("nameKit", this.nombreKit);
      form.append("costPrice", this.sumaCost);
      form.append("sellingPrice", this.priceSale[0]);
      form.append("description", this.description);
      form.append("idCategoria", this.categoria.id);
      form.append('size',this.size);
      form.append("code", this.code);
      form.append("kitItems", JSON.stringify(this.kitItems));
      form.append("totales", JSON.stringify(this.subtotal));
      form.append("prices", JSON.stringify(this.prices));
      form.append("priceSale", JSON.stringify(this.priceSale));
      form.append("utility", JSON.stringify(this.utility));
      form.append("picture", this.imageFile);
      let me = this;
      const ajuste = { headers: { "Content-Type": "multipart/form-data" } };

      axios
        .post("/api/item-kit-vue/store", form, ajuste)
        .then(function(response) {
          window.location.href = "/item-kits-vue";
          hideLoading();
        }).catch(function (e) {
          me.error=true;
          e.response.data.message.forEach(element => {
              me.errores.push(element);
              if(element==='Recarga')
                  location.reload();
          });
          hideLoading();
      });
    }
  }
});
