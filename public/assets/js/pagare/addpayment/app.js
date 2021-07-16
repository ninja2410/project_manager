$(document).ready(function(){
	var interes=0;
	var total=0;
	var mora=0;
	var total_a_pagar=0;
	mora=parseFloat($('#mora').val());
	interes=parseFloat($('#interes').val());
	total=parseFloat($('#pago_neto').val());
	total_a_pagar=mora+total;
	$('#total_pago').append(total_a_pagar);
	$('#totalPago').val(total_a_pagar);
});


Vue.use(VueCleave);
var vm= new Vue({
	el:'#elementos',
	created:function(){
		this.valoresIniciales();
		this.calcularMoraConFechasDias();
	},
	data:{
		diferencia:0,
		moraInput:0,
		totalPago:0,
		totalMora:0,
		totalTemp:0,
		proximoPago:0,
			options:{
				totalPago:{
					numeral: true,
					numeralPositiveOnly: true,
					noImmediatePrefix: true,
					rawValueTrimPrefix: true,
					numeralIntegerScale: 9,
					numeralDecimalScale: 2,
				},
				totalMora:{
					numeral: true,
					numeralPositiveOnly: true,
					noImmediatePrefix: true,
					rawValueTrimPrefix: true,
					numeralIntegerScale: 9,
					numeralDecimalScale: 2
				}
			}
		},
		methods:{
			calcularMoraConFechasDias:function(){
				var date1=$("#date_reference").val();
				var arraydate1=date1.split('/');
				var dateFormat1=arraydate1[2]+'-'+arraydate1[1]+'-'+arraydate1[0];
				var date2=$("#date_payments").val();
				var arraydate2=date2.split('/');
				var dateFormat2=arraydate2[2]+'-'+arraydate2[1]+'-'+arraydate2[0];
				var date_1=moment(dateFormat1);
				var date_2=moment(dateFormat2);
				var days=date_2.diff(date_1,"days");
				this.totalMora=days;
				this.totalAPagar();
				//calculo de mora

			},
			calcular_diferencia:function(){
				var total;
				var pago=$('#total_pago').html();
				var efectivo=$('#totalPago').val();
				total=pago-efectivo;
				if (total<0) {
					total*=-1;
				}
				total=Math.round(total*100)/100;
				$('#pendiente_pago').val(total);
			},
			calcularMoraConFechasMeses:function(){
				var date1=$("#date_reference").val();
				var arraydate1=date1.split('/');
				var dateFormat1=arraydate1[2]+'-'+arraydate1[1]+'-'+arraydate1[0];
				var date2=$("#date_payments").val();
				var arraydate2=date2.split('/');
				var dateFormat2=arraydate2[2]+'-'+arraydate2[1]+'-'+arraydate2[0];
				var date_1=moment(dateFormat1);
				var date_2=moment(dateFormat2);
				var days=date_2.diff(date_1,"months");
				console.log(days);
			},
			valoresIniciales:function(){
				this.totalPago=$('#amount_mora').val();
			},
			totalAPagar:function(){
				var totalPago=$('#amount_mora').val();
				this.totalTemp=totalPago.replace(',','');
				var mora=$('#ptc_mora').val();
				this.diferencia=parseFloat(this.totalTemp)-parseFloat(this.totalPago);
				this.moraInput=(parseFloat(this.diferencia)*(parseFloat(mora)/100))*this.totalMora;
				//proximo pago
				this.proximoPago=this.diferencia+this.moraInput;
			},
			vermayuores:function(element){
				console.log(element);
			},
			exonerarMora:function(){
				this.moraInput=0;
			}
		}
});
