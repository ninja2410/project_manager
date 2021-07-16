<?php
if(!empty($items ))  
{ 
    $count = 1;
    $outputhead = '';
    $outputbody = '';  
    $outputtail ='';

    $outputhead .= '<div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Cant.</th>
                                <th>Agregar</th>
                            </tr>
                        </thead>
                        <tbody>
                ';
                
    foreach ($items as $item)    
    {       
    // $show = url('blog/'.$item->slug);
    $show = '#';
    $outputbody .=  ' 
                
                            <tr> 
		                        <td>'.$count++.'</td>
                                <td>'.$item->upc_ean_isbn.'<input type="hidden" id="search_codigo_'.$item->id.'" value="'.$item->upc_ean_isbn.'"></td>
                                <td>'.$item->item_name.'<input type="hidden" id="search_name_'.$item->id.'" value="'.$item->item_name.'"></td>
                                <td>Q'.$item->selling_price.'<input type="hidden" id="search_price_'.$item->id.'" value="'.$item->selling_price.'"></td>                                
                                <td>'.$item->quantity.'</td>
                                <td><button type="button" name="button"  onclick="add(this);" value="'.$item->id.'" class="btn btn-primary btn-sxs" id="'.$item->id.'"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>';
                                if($item->stock_action=='='){
                                    $outputbody .=  '<input type="hidden" id="refer_'.$item->id.'"  value="'.$item->stock_action.'">';
                                }
                                else {
                                    $outputbody .=  '<input type="hidden" id="refer_'.$item->id.'"  value="'.$item->quantity.'" name="'.$item->quantity.'">';
                                }
                                $outputbody .=  '<input type="hidden" id="minprice_'.$item->id.'"  value="'.$item->low_price.'" >
                                                 <input type="hidden" id="is_kit_'.$item->id.'"  value="'.$item->is_kit.'" >
                            </tr>';
                
    }  

    $outputtail .= ' 
                        </tbody>
                    </table>
                </div>';
         
    echo $outputhead; 
    echo $outputbody; 
    echo $outputtail; 
 }  
 
 else  
 {  
    echo 'No se encontro información';  
 } 
 ?>  