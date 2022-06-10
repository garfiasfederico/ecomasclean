$(document).ready(function () {
    var containdicador=1;
});

function addIGestion(){
    indicadores_agregados = parseInt($("#indicadores_gestion_agregados").val());
    indicador = indicadores_agregados+1;    
    indicador_nuevo = '<table style="width:100%;display:none" class="indicador_gestion" id="indicador_gestion'+indicador+'">'+
                            '<tr>'+
                             '   <td colspan="6" class="enc2" style="text-align:center;">Indicador de Gestión<input type="hidden" name="id_gestion[]" value="0"></td>'+
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Nombre</td>'+
                              '  <td colspan="5" class="" style="text-align: center;"><input type="text" style="width:100%" name="nombre_gestion[]"/></td>      ' +         
                           ' </tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Objetivo</td>'+
                              '  <td colspan="5" class="" style="text-align: center;"><input type="text" style="width:100%" name="objetivo_gestion[]"/></td> '  +             
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Tipo</td>'+
                              '  <td colspan="1" class="" style="text-align: center;"><select style="width: 100%" name="tipo_gestion[]" >'+
                                        '<option value="impacto">Impacto</option>'+
                                        '<option value="gestion">Gestion</option>'+
                                    '</select></td>  '    +          
                               ' <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Dimensión</td>'+
                                '<td colspan="1" class="" style="text-align: center;"><select style="width:100%" name="dimension_gestion[]">'+
                                        '<option value="eficacia">Eficacia</option>'+
                                        '<option value="eficiencia">Eficiencia</option>'+
                                        '<option value="economia">Economía</option>'+
                                        '<option value="calidad">Calidad</option>'+
                                    '</select></td>    '  +          
                                '<td colspan="1" class="enc3" style="text-align: ;width:16.6%">Método de Cálculo</td>'+
                                '<td colspan="1" class="" style="text-align: center;"><select style="width:100%" name="metodo_gestion[]">'+
                                        '<option value="porcentaje">Porcentaje</option>'+
                                        '<option value="razon_promedio">Razon Promedio</option>'+
                                        '<option value="tasa_variacion">Tasa Variación</option>'+
                                        '<option value="tasa">Tasa</option>'+
                                        '<option value="indice">Índice</option>'+
                                    '</select></td>  '    +          
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Fórmula</td>'+
                              '  <td colspan="5" class="" style="text-align: center;"><input type="text" style="width:100%" name="formula_gestion[]"/></td>   '             +
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Frecuencia de Medición</td>'+
                             '   <td colspan="1" class="" style="text-align: center;"><select style="width:100%" name="frecuencia_gestion[]">'+
                                       ' <option value="mensual">Mensual</option>'+
                                       ' <option value="bimestral">Bimestral</option>'+
                                       ' <option value="trimestral">Trimestral</option>'+
                                       ' <option value="semestral">Semestral</option>'+
                                      '  <option value="anual">Anual</option>'+
                                      '  <option value="bienal">Bienal</option>'+
                                     '   <option value="quinquenal">Quinquenal</option>'+
                                    '</select></td>  '              +
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Sentido Esperado</td>'+
                             '   <td colspan="1" class="" style="text-align: center;"><select style="width:100%" name="sentido_gestion[]">'+
                                      '  <option value="ascendente">Ascendente</option>'+
                                     '   <option value="descendente">Descendente</option>'+
                                    '</select></td>    '            +
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Línea Base</td>'+
                             '   <td colspan="1" class="" style="text-align: center;"><input type="text" style="width:100%" name="base_gestion[]" maxlength="4"/></td>  '              +
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan=6" class="enc3" style="text-align: center">Meta Programada</td>'+
                            '<tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align:;width:16.6%">Periodo</td>'+
                              '  <td colspan="2" class="" style="text-align: center;"><input type="text" style="width:100%" name="periodo_gestion[]"/></td>  '              +
                              '  <td colspan="1" class="enc3" style="text-align:;width:16.6%">Valor</td>'+
                              '  <td colspan="2" class="" style="text-align: center;"><input type="text" style="width:100%" name="valor_gestion[]"/></td> '               +
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align:;width:16.6%">Medios de Verificación</td>'+
                             '   <td colspan="5" class="" style="text-align: center;"><textarea style="width:100%" name="mv_gestion[]"></textarea></td>   '             +
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="6" class="enc3" style="text-align:right;padding-right:30px;"><img style="width:30px;cursor:pointer;"src="../images/delete.png" onclick="deleteIGestion(\'indicador_gestion'+indicador+'\')"></td>'+
                            '</tr>'+
                        '</table>';                
                $("#indicadores_gestion").append(indicador_nuevo);
                $("#indicador_gestion"+indicador).show("slow");
                $("#indicadores_gestion_agregados").val(indicador);
}

function addIResultados(){
    indicadores_resultados_agregados = parseInt($("#indicadores_resultados_agregados").val());
    indicador =  indicadores_resultados_agregados +1;
    indicador_nuevo = '<table style="width: 100%;display:none;" class="indicadores" id="indicador_resultados'+indicador+'">'+
                            '<tr>'+
                             '   <td colspan="6" class="enc2" style="text-align:center;">Indicador de Resultados<input type="hidden" name="id_resultados[]" value="0"></td>'+
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Nombre</td>'+
                              '  <td colspan="5" class="" style="text-align: center;"><input type="text" style="width:100%" name="nombre_resul[]"/></td>  '              +
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Objetivo</td>'+
                              '  <td colspan="5" class="" style="text-align: center;"><input type="text" style="width:100%" name="objetivo_resul[]"/></td>'+
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Tipo</td>'+
                              '  <td colspan="1" class="" style="text-align: center;"><select style="width: 100%" name="tipo_resul[]" >'+
                                        '<option value="impacto">Impacto</option>'+
                                        '<option value="gestion">Gestion</option>'+
                                    '</select></td>  '    +
                               ' <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Dimensión</td>'+
                                '<td colspan="1" class="" style="text-align: center;"><select style="width:100%" name="dimension_resul[]">'+
                                        '<option value="eficacia">Eficacia</option>'+
                                        '<option value="eficiencia">Eficiencia</option>'+
                                        '<option value="economia">Economía</option>'+
                                        '<option value="calidad">Calidad</option>'+
                                    '</select></td>    '  +
                              '  <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Método de Cálculo</td>'+
                              '  <td colspan="1" class="" style="text-align: center;"><select style="width:100%" name="metodo_resul[]">'+
                                        '<option value="porcentaje">Porcentaje</option>'+
                                        '<option value="razon_promedio">Razon Promedio</option>'+
                                        '<option value="tasa_variacion">Tasa Variación</option>'+
                                        '<option value="tasa">Tasa</option>'+
                                        '<option value="indice">Índice</option>'+
                                    '</select></td>  '    +
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Fórmula</td>'+
                             '   <td colspan="5" class="" style="text-align: center;"><input type="text" style="width:100%" name="formula_resul[]"/></td>                '+
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Frecuencia de Medición</td>'+
                             '   <td colspan="1" class="" style="text-align: center;">  <select style="width:100%" name="frecuencia_resul[]">'+
                                        '<option value="mensual">Mensual</option>'+
                                        '<option value="bimestral">Bimestral</option>'+
                                        '<option value="trimestral">Trimestral</option>'+
                                        '<option value="semestral">Semestral</option>'+
                                        '<option value="anual">Anual</option>'+
                                        '<option value="bienal">Bienal</option>'+
                                        '<option value="quinquenal">Quinquenal</option>'+
                                    '</select></td>                '+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Sentido Esperado</td>'+
                             '   <td colspan="1" class="" style="text-align: center;"><select style="width:100%" name="sentido_resul[]">'+
                                        '<option value="ascendente">Ascendente</option>'+
                                        '<option value="descendente">Descendente</option>'+
                                    '</select></td>                '+
                             '   <td colspan="1" class="enc3" style="text-align: ;width:16.6%">Línea Base</td>'+
                             '   <td colspan="1" class="" style="text-align: center;"><input type="text" style="width:100%" name="base_resul[]" maxlength="4"/></td>                '+
                           ' </tr>'+
                            '<tr>'+
                             '   <td colspan=6" class="enc3" style="text-align: center">Meta Programada</td>'+
                            '<tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="width:16.6%">Periodo</td>'+
                              '  <td colspan="2" class="" style="text-align: center;"><input type="text" style="width:100%" name="periodo_resul[]"/></td>                '+
                               ' <td colspan="1" class="enc3" style="width:16.6%">Valor</td>'+
                                '<td colspan="2" class="" style="text-align: center;"><input type="text" style="width:100%" name="valor_resul[]"/></td>                '+
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="1" class="enc3" style="width:16.6%">Medios de Verificación</td>'+
                             '   <td colspan="5" class="" style="text-align: center;"><textarea style="width:100%" name="mv_resul[]"></textarea></td>                '+
                            '</tr>'+
                            '<tr>'+
                             '   <td colspan="6" class="enc3" style="text-align:right; padding-right:30px;"><img style="width:30px;cursor:pointer;"src="../images/delete.png" onclick="deleteIResultados(\'indicador_resultados'+indicador+'\')"></td>'+
                            '</tr>'+
                        '</table>';
    $("#indicadores_resultados").append(indicador_nuevo);
    $("#indicador_resultados"+indicador).show("slow");
    $("#indicadores_resultados_agregados").val(indicador);
    
    
}

function deleteIGestion(indicador,id=null){    
    decide=confirm("¿Desea Eliminar el Indicador Seleccionado?");     
    if(decide){        
        $("#"+indicador).remove();
        if(id!=null){
            valores_deletes = $("#gestion_deletes").val();
            $("#gestion_deletes").val(valores_deletes+id+"|");
        }
        
    }
}

function deleteIResultados(indicador,id=null){    
    decide=confirm("¿Desea Eliminar el Indicador Seleccionado?");     
    if(decide){        
        $("#"+indicador).remove();
        if(id!=null){
            valores_deletes = $("#resultados_deletes").val();
            $("#resultados_deletes").val(valores_deletes+id+"|");
        }
    }
        
        
}