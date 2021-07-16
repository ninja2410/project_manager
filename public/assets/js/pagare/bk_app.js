Vue.filter('monedaformat',function(value){
	return numeral(value).format('0,0.00');
});
Vue.use(VueCleave);

var vm=new Vue({
	el:"#contenedor",
	created: function (){
		this.cargadoClientes();
	},
	data:{
		montoCredito:0,
		numeroCuotas:0,
		porcInteres:0,
		porcMora:0,
		options:{
			montoCredito:{
				numeral: true,
				numeralPositiveOnly: true,
				noImmediatePrefix: true,
				rawValueTrimPrefix: true,
				numeralIntegerScale: 9,
				numeralDecimalScale: 2
			},
			numeroCuotas:{
				numeral: true,
				numeralPositiveOnly: true,
				noImmediatePrefix: true,
				rawValueTrimPrefix: true,
				numeralIntegerScale: 2,
			},
			porcInteres:{
				numeral: true,
				numeralPositiveOnly: true,
				noImmediatePrefix: true,
				rawValueTrimPrefix: true,
				numeralIntegerScale: 3,
				numeralDecimalScale: 2
			}
		},
		montoInteres:0,
		totalInteres:0,
		totalConInteres:0,
		totalCuotas:0,
		totalCuotasConInteres:0,
		all_data:[],
		all_customers:[],
		customer_name: '',
		customer_id: 0,
		newCustomer_name:'',
		newCustomer_address:'',
		newCustomer_nit:'C/F',
		formSubmited:false,
		errores:'',
		garantia:'',
		fiador_name:'',
		fiador_address:'',
		fiador_phone:''
	},
	methods:{
		generarCuotas: function(){
			//calculo
			this.totalCuotas=this.montoCredito/this.numeroCuotas;
			this.totalCuotas=Math.round(this.totalCuotas*100)/100;
			//Monto de intereses
			this.totalInteres=parseFloat(this.montoCredito)*(parseFloat(this.porcInteres)/100);
			this.totalInteres=Math.round(this.totalInteres*100)/100;
			//total con intereses
			this.totalConInteres=parseFloat(this.montoCredito)+this.totalInteres;
			//Total de interes de cuotas
			this.totalCuotasConInteres=parseFloat(this.totalInteres)/parseInt(this.numeroCuotas);
			this.totalCuotasConInteres=Math.round(this.totalCuotasConInteres*100)/100;
		},
		generarTabla:function(){
			this.all_data=[];
			var fecha_pagos=$('#date_payments').val();
			var arrayDate=fecha_pagos.split('/');
			var diaMes_nuevo=arrayDate[0];
			var mes_nuevo=arrayDate[1];
			var anio_nuevo=arrayDate[2];
			var mes_insertar=mes_nuevo;
			var mes_insertar2;
			var ni=0;
			for (var i = 0; i < parseInt(this.numeroCuotas); i++) {
				mes_insertar2=parseInt(mes_insertar)+i;
				var mesReal=0;
				if((parseInt(mes_insertar2))>=13){
					mes_insertar2=ni+1;
					mesReal = diaMes_nuevo+'/'+mes_insertar2+'/'+(parseInt(anio_nuevo)+1);
					ni++;
				}else {
					mesReal = diaMes_nuevo+'/'+mes_insertar2+'/'+anio_nuevo
				}
				this.all_data.push({
					fecha_pago:mesReal,
					monto_coutas:this.totalCuotas,
					monto_interes:this.totalCuotasConInteres,
					total_pago:(this.totalCuotas+this.totalCuotasConInteres),
				});
			}
		},
		limpiarTabla:function(){
			this.all_data=[];
		},
		cargadoClientes:function(){
			var urlCustomers='../customers/getCustomer';
			axios.get(urlCustomers).then(response =>{
				this.all_customers = response.data;}).
			then((response) => {
				$('#tableCustomers').DataTable({
					// responsive: true,
					"language":{
						"url":"../assets/js/datatables/Spanish.js"
					},
					destroy: true,
					"pageLength": 5,
					"bInfo": false,
					"lengthChange": false,
					"bAutoWidth": false,
					language: {
						search: "_INPUT_",
						searchPlaceholder: "Buscar..."}
					});
			}).catch(function (error) {
				alert(error);
			});
		},
		seleccionarCliente:function(cliente_name, cliente_id){
			this.customer_name=cliente_name;
			this.customer_id=cliente_id;
		},
		crearCliente:function(){
			if(this.newCustomer_name==''){
				toastr.options = {
					"closeButton": true,
					"positionClass": "toast-bottom-left"
				}
				toastr.error('Error.', 'El nombre del cliente es requerido', {timeOut: 5000});
				$('#customer_name').focus();
			}else{
				var url="../customers/addCustomerAjax";
				axios.post(url,{
					name_customer2:this.newCustomer_name,
					nit_customer2:this.newCustomer_nit,
					address_customer2:this.newCustomer_address,
				}).then(respose=>{
					if(respose.data=="Ya existe un cliente con ese nombre"){
						toastr.options = {
							"closeButton": true,
							"positionClass": "toast-bottom-left"
						}
						toastr.error('Error.', 'Cliente existente', {timeOut: 5000});
						$('#customer_name').focus();
					// 	document.getElementById('divError').style.display='block';
					// 	this.newCustomer_name='';
					// 	this.newCustomer_address='';
					// 	this.newCustomer_nit='C/F';
					// 	setTimeout(function	(){
					// 		document.getElementById('divError').style.display='none';
					// 	// this.errorCustomer=null;
					// },	2000);
					}else{
						this.customer_id=respose.data.id;
						this.customer_name=respose.data.name;
					//limpiamos los datos
					this.newCustomer_name='';
					this.newCustomer_address='';
					this.newCustomer_nit='C/F';
					$('#modalAddCustomerClose').click();
					$('#modalListCustomerClose').click();
					this.cargadoClientes();
				}
			}).catch(error=>{
				alert("hay un error");
			});
		}

	},
	validacionesFormPrincipal:function(event){
		if(this.customer_name==''){
			$('#btnListCustomers').click();
			event.preventDefault();
			return -1;				
		}
		if(this.montoCredito==0){
				//this.errores='El monto del crédito no puede ser 0';	
				//$('#btnModalErrors').click();
				toastr.options = {
					"closeButton": true,
					"positionClass": "toast-bottom-left"
				}
				toastr.error('Error.', 'El monto del crédito no puede ser 0', {timeOut: 5000});
				$('#amount').focus();
				event.preventDefault();
				return -1;
			}
			if(this.numeroCuotas==0){
				// this.errores='Tiene que tener al menos una cuota';				
				// $('#btnModalErrors').click();
				toastr.options = {
					"closeButton": true,
					"positionClass": "toast-bottom-left"
				}
				toastr.error('Error.', 'Tiene que tener al menos una cuota', {timeOut: 5000});
				$('#cuotas').focus();
				event.preventDefault();
				return -1;	
			}
			if(this.porcInteres==0){
				// this.errores='El interes no es correcto';				
				// $('#btnModalErrors').click();
				toastr.options = {
					"closeButton": true,
					"positionClass": "toast-bottom-left"
				}
				toastr.error('Error.', 'El interes no es correcto', {timeOut: 5000});
				$('#ptc_interes').focus();
				event.preventDefault();
				return -1;	
			}
			if(this.garantia==''){
				toastr.options = {
					"closeButton": true,
					"positionClass": "toast-bottom-left"
				}
				toastr.error('Error.','El campo garantia es requerido', {timeOut: 5000});

				$('#garantia').focus();
				event.preventDefault();
				return -1;	
			}
			if(this.fiador_name==''){
				toastr.options = {
					"closeButton": true,
					"positionClass": "toast-bottom-left"
				}
				toastr.error('Error.', 'El nombre del fiador es requerido', {timeOut: 5000});
				$('#fiador').focus();
				event.preventDefault();
				return -1;				
			}
			if(this.fiador_address==''){
				toastr.options = {
					"closeButton": true,
					"positionClass": "toast-bottom-left"
				}
				toastr.error('Error.', 'La dirección es requerida', {timeOut: 5000});
				$('#fiador_direccion').focus();
				event.preventDefault();
				return -1;				
			}

		},
		guardarFormulario:function(){
			document.getElementById('formPagare').addEventListener('submit',this.validacionesFormPrincipal,true);
		},
		limpiarErrores:function(){
			this.errores='';
		}
	}
});
