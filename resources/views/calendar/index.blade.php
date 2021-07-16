@extends('layouts/default')

@section('title',trans('menu.days_off_calendar'))
@section('page_parent',trans('credit.credits'))

@section('header_styles')
<link href="{{ asset('assets/css/calendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <!-- <link rel="stylesheet" href="https://fullcalendar.io/releases/fullcalendar/3.9.0/fullcalendar.min.css"> -->
<link href="{{ asset('assets/css/calendar/fullcalendar.print.css') }}" rel="stylesheet"  media='print' type="text/css">
<link href="{{ asset('assets/css/calendar/calendar_custom.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color:  #d9534f;color: white;">
					<h4 class="modal-title" id="exampleModalLabel">Guardar Calendario</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
				</div>
			<div class="modal-body">
				Esta seguro que desea guardar los cambios en el calendario?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn_aceptar">Aceptar</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<section class="content">
	<div class="row">
		<div class="col-md-12 ">
			<div class="panel panel-primary">
				<div class="panel-heading">Haga click sobre el día que quiera inabilitar</div>
				<div class="panel-body">
                    <div class="row">
                     <div class="col-lg-1"></div>

                     <div class="col-lg-10">
                        <div id="calendar">

                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<div class="row">
	<div class="col-lg-4">
	</div>
	<div class="col-lg-4">
		<div class="row">
			<div class="form-group form-check" style="text-align:center;">
				<input type="checkbox" name="reply" id="reply">
				<label class="form-check-label" for="exampleCheck1">Replicar a otros años</label>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
	</div>
</div>
<div class="row">
	<div class="col-lg-4"></div>
	<div class="col-lg-4" style="text-align: center;">
		<div class="form-group">
			<button class="btn btn-primary" id="btn_save" data-toggle="modal" data-target="#exampleModal">
								Guardar
						</button>
			<a class="btn btn-danger" href="{{url('calendar')}}">
								Cancelar
						</a>
		</div>
	</div>
	<div class="col-lg-4"></div>
</div>
@endsection

@section('footer_scripts')
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/js/calendar/fullcalendar.min.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/js/calendar/locale-all.js')}}"></script>
<script>
var inserts=[];
var removes=[];
var reply;
    $(document).ready(function() {
        function ini_events(ele) {
            ele.each(function() {

            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };
            $(this).data('eventObject', eventObject);
            $(this).draggable({
                zIndex: 1070,
                revert: true, // will cause the event to go back to its
                revertDuration: 0 //  original position after the drag
            });

        });
        }
        ini_events($('#external-events div.external-event'));

		$('#btn_aceptar').click(function(){
			if ($('#reply').is(':checked')) {
				reply=1;
			}
			else{
				reply=0;
			}
			$("*").css("cursor", "progress");
			for (var i = 0; i < inserts.length; i++) {
				$.ajax({
				  type:"post",
				  url:'{{url('calendar')}}',
				  data:{
				    _token: '{{csrf_token()}}',
						'reply':reply,
				    'type_action':'insert',
				    'dateReceiving':inserts[i],
				    },
				    success:function(data){

				    }
				});
			}
			for (var i = 0; i < removes.length; i++) {
				console.log(removes[i]);
				$.ajax({
          type:"post",
          url:'{{ url('calendar') }}',
          data:{
            _token: '{{csrf_token()}}',
						'reply':reply,
            'type_action':'remove',
            'dateReceiving':removes[i],
            },
            success:function(data){
							alert(data);
            }
        });
			}
			$("*").css("cursor", "progress");
			location.reload();
		});
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        locale: 'es',
        eventClick: function(calEvent, jsEvent, view) {
        },
				viewRender: function(view,element){
					for (var i = 0; i < inserts.length; i++) {
						$(view.el[0]).find('.fc-day[data-date=' + inserts[i] + ']').css('background-color', '#ff0019');
						$(view.el[0]).find('.fc-day[data-date=' + inserts[i] + ']').addClass("seleccionado");
						$(view.el[0]).find('.fc-day[data-date=' + inserts[i] + ']').append("<td style=\"padding-top:20px; background-color:#ff0019; border-color:#ff0019 \" class=\"fc-event-container\"><a class=\"fc-day-grid-event fc-h-event fc-event fc-start fc-end\"style=\"background-color:#8b8b8b;border-color:#8b8b8b\"><div class=\"fc-content\"> <span class=\"fc-title\">Pendiente de guardar</span></div></a></td>");

					}
				},
        dayClick: function(date, jsEvent, view) {
					console.log(this.position());
             if($(this).hasClass( "seleccionado" )){
                $(this).css('background-color', '#FFFFFF');
                $(this).removeClass("seleccionado");
								$(this).html("");
								//$(this).remove(".fc-event-container");
								var tmp;
								tmp=inserts.indexOf(date.format());
								if(tmp>-1){
									inserts.splice(tmp,1);
								}
								if(removes.indexOf(date.format())==-1 &&tmp==-1){
									removes.push(date.format());
								}

             }else{
                $(this).css('background-color', '#ff0019');
                $(this).addClass("fc-day-top fc-thu fc-today fc-state-highlight seleccionado");
								$(this).append("<td style=\"padding-top:20px; background-color:#ff0019; border-color:#ff0019 \" class=\"fc-event-container\"><a class=\"fc-day-grid-event fc-h-event fc-event fc-start fc-end\"style=\"background-color:#8b8b8b;border-color:#8b8b8b\"><div class=\"fc-content\"> <span class=\"fc-title\">Pendiente de guardar</span></div></a></td>");

								var tmp;
								tmp=removes.indexOf(date.format());
								if(tmp>-1){
									removes.splice(tmp,1);
								}
								if(inserts.indexOf(date.format())==-1 &&tmp==-1){
									inserts.push(date.format());
								}

             }
						 //BOOK
             //location.reload();
        },
        events: [
        @foreach($holidays_date as $value)
        {
            id:'{{$value->id}}',
            title:'{{$value->name_day}}',
            start  : '{{$value->holidays_date}}'
        },
        @endforeach
        ],
        eventRender: function (event, element, view) {
            var dateString = event.start.format("YYYY-MM-DD");
            $(view.el[0]).find('.fc-day[data-date=' + dateString + ']').css('background-color', '#FF0019');
            $(view.el[0]).find('.fc-day[data-date=' + dateString + ']').addClass("seleccionado");
        },
        eventColor: '#0D074A',
        editable: true,
        droppable: false,
        height:450,
    });
});

</script>
@stop
