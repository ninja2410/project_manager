<div class="row">
  <h2>{{trans('budget.broken_down_budget')}}</h2>
</div>
<div class="col-lg-12">
  <div class="col-lg-4" id="items_panel">
    <div class="panel panel-warning">
      <div class="panel-heading border-light">
        <h4 class="panel-title">{{trans('budget.tools')}}</h4>
      </div>
      <div class="panel-body" style="height: 330px; overflow:auto;">
        <div class="panel-group" id="accordion" role="tablist"
             aria-multiselectable="true">
          <div class="panel panel-success">
            <div class="panel-heading" role="tab" id="headingTwo">
              <a class="collapsed" role="button" data-toggle="collapse"
                 data-parent="#accordion" href="#collapseTwo" aria-expanded="true"
                 aria-controls="collapseTwo">
                <h4 class="panel-title">
                  {{trans('budget.components')}}
                </h4>
              </a>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="headingTwo">
              <div class="panel-body">
                <div class="row">
                  <div class="panel panel-warning header panel_header">
                    <div class="panel-heading clearfix">
                      <h4 class="panel-title">
                        <a href="#"
                           class="header_title"></a>
                      </h4>
                      <span class="pull-right btnDelete" style="display: none">
                          <button onclick="deleteElement('panel_header', this)" type="button" class="btn btn-danger btn-sm">{{trans('budget.delete')}}</button>
                      </span>
                    </div>
                    <div class="panel-body detail_header" style="height: 40px;">

                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-check">
                    <label class="form-check-label" for="exampleCheck1">{{trans('budget.type_price_change')}}</label>
                    <input type="checkbox" class="form-check-input form-control" id="only_this">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <a role="button" data-toggle="collapse" data-parent="#accordion"
                 href="#collapseOne" aria-expanded="false"
                 aria-controls="collapseOne">
                <h4 class="panel-title">{{trans('line-template.line-templates')}}</h4>
              </a>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                 aria-labelledby="headingOne">
              <div class="panel-body">
                <input type="text" class="form-control" placeholder="Buscar"
                       onkeyup="filter(this, 'lTemplates')"/>
                <hr>
                <div style="overflow-y: scroll; height:200px;">
                  <ul id="lTemplates" style="margin-left: -40px;">
                    @include('project.budget.components.list_line_templates')
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
              <a class="collapsed" role="button" data-toggle="collapse"
                 data-parent="#accordion" href="#collapseThree"
                 aria-expanded="false"
                 aria-controls="collapseThree">
                <h4 class="panel-title"> {{trans('budget.services')}}</h4>
              </a>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="headingThree">
              <div class="panel-body">
                <input type="text" class="form-control" placeholder="Buscar"
                       onkeyup="filterTable(this, 'lServices')"/>
                <hr>
                <div style="overflow-y: scroll; height:200px;">
                  <table class="table table-advance table-hover" id="lServices">
                    <tbody>
                    @include('project.budget.components.list_services', ['_list'=>$services])
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
              <a class="collapsed" role="button" data-toggle="collapse"
                 data-parent="#accordion" href="#collapseFour"
                 aria-expanded="false"
                 aria-controls="collapseFour">
                <h4 class="panel-title"> {{trans('budget.products')}}</h4>
              </a>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="headingFour">
              <div class="panel-body">
                <input type="text" class="form-control" placeholder="Buscar"
                       onkeyup="filterTable(this, 'lProducts')"/>
                <hr>
                <div style="overflow-y: scroll; height:200px;">
                  <table class="table table-advance table-hover" id="lProducts">
                    <tbody>
                    @include('project.budget.components.list_services', ['_list'=>$products])
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
              <a class="collapsed" role="button" data-toggle="collapse"
                 data-parent="#accordion" href="#collapseFive"
                 aria-expanded="false"
                 aria-controls="collapseFive">
                <h4 class="panel-title"> {{trans('budget.wildcards')}}</h4>
              </a>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="headingFive">
              <div class="panel-body">
                <input type="text" class="form-control" placeholder="Buscar"
                       onkeyup="filterTable(this, 'lWildcards')"/>
                <hr>
                <div style="overflow-y: scroll; height:200px;">
                  <table class="table table-advance table-hover" id="lWildcards">
                    <tbody>
                    @include('project.budget.components.list_services', ['_list'=>$wildcards])
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="alert alert-success hidden" id="loading_autosave" data-dismiss="alert">
          <i class="livicon" data-name="spinner-two" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i> {{trans('budget.autosave')}}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading border-light">
        <h4 class="panel-title">{{trans('budget.detail')}}</h4>
      </div>
      <div class="panel-body droppable" id="budgetDetailContainer" style="height: 100px">
        @include('project.budget.components.load_budget')
      </div>
    </div>

    <div class="panel panel-success">
      <div class="panel-heading border-light">
        <h4 class="panel-title">{{trans('budget.summary')}}</h4>
      </div>
      <div class="panel-body droppable" id="summaryBody">
        <div class="row">
          <table class="table table-hover">
            <tbody>
            @foreach($config as $conf)
              <tr>
                <td>{{trans('line-template.total_items').' '.$conf->custom_text}}</td>
                <td id="{{($conf->id == 1 ? "bdITotal" : "bdSTotal")}}">Q {{number_format(0,2)}}</td>
              </tr>
            @endforeach
            <tr>
              <td><strong>{{trans('budget.total_project')}}</strong></td>
              <td id="bdTotal">Q {{number_format(0, 2)}}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="form-group">
        <label for="description" class="control-label">{{trans('budget.comments')}}</label>
        <div class="input-group">
          <span class="input-group-addon">D</span>
          {!! Form::textarea('comment', (isset($budget->comments) ? $budget->comments : ''), ['class' => 'form-control', 'rows' => 2,'form'=>'frmSend']) !!}
        </div>
      </div>
    </div>
  </div>
</div>
