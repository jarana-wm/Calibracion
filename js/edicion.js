var tanques=1;
var json;
var campo=$("#idDisp");
var dis;
var datUsuario; 
var id_us;
$(document).ready(function(){	
	vista();
	restaurar();
	cargarDisp();
	$("#tanque1").click(function(){
		tanques=1;
		$("#t2").css("display","none");
		$("#t3").css("display","none");
		$("#t4").css("display","none");
	});
	$("#tanque2").click(function(){
		tanques=2;
		$("#t2").css("display","inline");
		$("#t3").css("display","none");
		$("#t4").css("display","none");
	});
	$("#tanque3").click(function(){
		tanques=3;
		$("#t2").css("display","inline");
		$("#t3").css("display","inline");
		$("#t4").css("display","none");
	});
	$("#tanque4").click(function(){
		tanques=4;
		$("#t2").css("display","inline");
		$("#t3").css("display","inline");
		$("#t4").css("display","inline");
	});
	$('.table-remove').click(function(){
		eliminar(this);
	});
	$('.table-up').click(function(){
		arriba(this);
	});
	$('.table-down').click(function(){
		abajo(this);
	});
	$('#export-btn').click(function(){
		var arreglo=[];
		var vacios=false;
		var tvacio=0;
		
		var isData=0;
		var mensaje;
		if(validar()){
			if(validarContId($("#idDisp").val())){
				if(validarModelo()){
					for(var j=1; j<=tanques; j++){
						var i=0;isData=0;
						if($("#table"+j+" tr").length>1){
							$("#table"+j+" tr").each(function () {
								if(i>0){
									var p = $(this).find("td").eq(0);
									var v = $(this).find("td").eq(1);
									if(validarNumero(p.html())){
										if(validarNumero(v.html())){
											var ob={tanque: j, puntos: p.html(), volumen: v.html()}
											arreglo.push(ob);
											isData++;
										}else{
											vacios=true;
											tvacio=j;
											j=5;
											campo=v;
											mensaje="Verifique los campos del tanque "+tvacio+".";
											return false;
										}
									}else{
										vacios=true;
										tvacio=j;
										j=5;
										campo=p;
										mensaje="Verifique los campos del tanque "+tvacio+".";
										return false;
									}
								}
								i++;
							});
						}else{
							vacios=true;
							tvacio=j;
							j=5;
							mensaje="Tanque "+tvacio+" sin datos.";
						}
					}
					if(!vacios&&isData!=0){
						var sistema= new Date();
						var dia = parseInt(sistema.getDate());
						var mes = parseInt(sistema.getMonth())+1;
						var ano = parseInt(sistema.getFullYear());
						var hora = parseInt(sistema.getHours());
						var minuto = parseInt(sistema.getMinutes());
						var segundos = parseInt(sistema.getSeconds());
						
						var reg={
							'id_disp': $("#idDisp").val(),
							'usuario': $("#usuariosSel").val(),
							'carga': $("#uCarga").val(),
							'fecha': ano+"-"+mes+"-"+dia+" "+hora+":"+minuto+":"+segundos,
							'descarga': $("#uDescarga").val(),
							'modelo': $("#modelo").val(),
							'datos': arreglo
						};
						json=JSON.stringify(reg);
						registrar();
					}else{
						$("#usuarioEliminar").html("<p>"+mensaje+"</p>");
						$("#abrirmodal").click();
						
					}
				}else{
					$("#usuarioEliminar").html("<p>Verifique el modelo.</p>");
					$("#abrirmodal").click();
				}
			}else{
				$("#usuarioEliminar").html("<p>Verifique el ID.</p>");
				$("#abrirmodal").click();
			}
			
		}else{
			$("#usuarioEliminar").html("<p>Llene todos los campos.</p>");
			$("#abrirmodal").click();
			
		}
	});
	$('#modificar-btn').click(function(){
		var arreglo=[];
		var vacios=false;
		var tvacio=0;
		
		var isData=0;
		var mensaje;
		if(validar()){
			if(validarContId($("#idDisp").val())){
				if(validarModelo()){
					for(var j=1; j<=tanques; j++){
						var i=0;isData=0;
						if($("#table"+j+" tr").length>1){
							$("#table"+j+" tr").each(function () {	//recorre todos los renglones
								if(i>0){
									var p = $(this).find("td").eq(0);
									var v = $(this).find("td").eq(1);
									if(validarNumero(p.html())){
										if(validarNumero(v.html())){
											var ob={tanque: j, puntos: p.html(), volumen: v.html()}
											arreglo.push(ob);
											isData++;
										}else{
											vacios=true;
											tvacio=j;
											j=5;
											campo=v;
											mensaje="Verifique los campos del tanque "+tvacio+".";
											return false;
										}
									}else{
										vacios=true;
										tvacio=j;
										j=5;
										campo=p;
										mensaje="Verifique los campos del tanque "+tvacio+".";
										return false;
									}
								}
								i++;
							});
						}else{
							vacios=true;
							tvacio=j;
							j=5;
							mensaje="Tanque "+tvacio+" sin datos.";
						}
					}
					if(!vacios&&isData!=0){
						var sistema= new Date();
						var dia = parseInt(sistema.getDate());
						var mes = parseInt(sistema.getMonth())+1;
						var ano = parseInt(sistema.getFullYear());
						var hora = parseInt(sistema.getHours());
						var minuto = parseInt(sistema.getMinutes());
						var segundos = parseInt(sistema.getSeconds());
						
						var reg={
							'id': dis,
							'id_disp': $("#idDisp").val(),
							'usuario': $("#usuariosSel").val(),
							'fecha': ano+"-"+mes+"-"+dia+" "+hora+":"+minuto+":"+segundos,
							'carga': $("#uCarga").val(),
							'descarga': $("#uDescarga").val(),
							'modelo': $("#modelo").val(),
							'datos': arreglo
						};
						json=JSON.stringify(reg);
						editar();
					}else{
						$("#usuarioEliminar").html("<p>"+mensaje+"</p>");
						$("#abrirmodal").click();
						
					}
				}else{
					$("#usuarioEliminar").html("<p>Verifique el modelo.</p>");
					$("#abrirmodal").click();
				}
			}else{
				$("#usuarioEliminar").html("<p>Verifique el ID.</p>");
				$("#abrirmodal").click();
			}
			
		}else{
			$("#usuarioEliminar").html("<p>Llene todos los campos.</p>");
			$("#abrirmodal").click();
			
		}
	});
	$('#cerrarAlta').click(function(){
		restaurar();
	});
	$('#menuCalibracion').click(function(){
		$('#contenidoCalibracion').show();
		$('#menuCalibracion').addClass('active');
		$('#menuEmpresa').removeClass('active');
		$('#menuModelo').removeClass('active');
		$('#contenidoEmpresa').hide();
		$('#contenidoModelo').hide();
	});
	$('#menuEmpresa').click(function(){
		$('#contenidoEmpresa').show();
		$('#contenidoCalibracion').hide();
		$('#contenidoModelo').hide();
		$('#menuCalibracion').removeClass('active');
		$('#menuEmpresa').addClass('active');
		$('#menuModelo').removeClass('active');
		cargarUsuarios();
	});
	$('#menuModelo').click(function(){
		$('#contenidoModelo').show();
		$('#contenidoCalibracion').hide();
		$('#contenidoEmpresa').hide();
		$('#menuCalibracion').removeClass('active');
		$('#menuEmpresa').removeClass('active');
		$('#menuModelo').addClass('active');
	});
	
});
function validarNumero(num){
	regex = /\d{1,3}/;
	if(!num.search(regex))
		return true;
	return false;
}
function agregar(i){
	var row="<tr><td contenteditable='true'></td>"+
				"<td contenteditable='true'></td>"+
				"<td><span class='table-remove glyphicon glyphicon-remove-sign' onclick='eliminar(this)'></span>"+
				"</td><td>"+
				" <span class='table-up glyphicon glyphicon-chevron-up' onclick='arriba(this)'></span>"+
				" <span class='table-down glyphicon glyphicon-chevron-down'onclick='abajo(this)'></span>"+
				"</td></tr>";
		$(".table"+i).append(row);
}
function eliminar(elemento){
	$(elemento).parents('tr').remove();
}
function arriba(elemento){
	var $row = $(elemento).parents('tr');
	  if ($row.index() === 0) return; 
	  $row.prev().before($row.get(0));
}
function abajo(elemento){
	var $row = $(elemento).parents('tr');
	 $row.next().after($row.get(0));
}
function limpiar(i){
	$(".table"+i+" tr").remove();
}
function habilitar(i){
	if($('#excelfile'+i).val().length)
		$("#btnArchivo"+i).removeAttr('disabled');
	else
		$("#btnArchivo"+i).attr('disabled',"true");
}
function BindTable(jsondata, tableid) { 
	for(var i = 0; i < jsondata.length; i++) { 
		var row="<tr><td contenteditable='true'>"+jsondata[i].puntos+"</td>"+
				"<td contenteditable='true'>"+jsondata[i].volumen+"</td>"+
				"<td><span class='table-remove glyphicon glyphicon-remove-sign' onclick='eliminar(this)'></span>"+
				"</td><td>"+
				" <span class='table-up glyphicon glyphicon-chevron-up' onclick='arriba(this)'></span>"+
				" <span class='table-down glyphicon glyphicon-chevron-down'onclick='abajo(this)'></span>"+
				"</td></tr>";
		$(tableid).append(row);
	}
 }  
function ExportToTable(k) {  
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
    //excel valido
    if (regex.test($("#excelfile"+k).val().toLowerCase())) {  
        var xlsxflag = false;
        if ($("#excelfile"+k).val().toLowerCase().indexOf(".xlsx") > 0)  
            xlsxflag = true;   
        if (typeof (FileReader) != "undefined") {  
            var reader = new FileReader();  
            reader.onload = function (e) {  
				var data = e.target.result;  
                if (xlsxflag)
                   var excel = XLSX.read(data, { type: 'binary' }); 
                else
                   var excel = XLS.read(data, { type: 'binary' });   
                var sheet_name_list = excel.SheetNames;  
                var cnt = 0; //solo la primer hoja
                sheet_name_list.forEach(function (y) {
                    if (xlsxflag) 
                       var exceljson = XLSX.utils.sheet_to_json(excel.Sheets[y]);
                    else 
                       var exceljson = XLS.utils.sheet_to_row_object_array(excel.Sheets[y]);
                    if(exceljson.length > 0 && cnt == 0) {  
                        BindTable(exceljson, '.table'+k);				 
                        cnt++;  
                    }
                 });  
                $('.table'+k).show();  
            }  
            if (xlsxflag) 
                reader.readAsArrayBuffer($("#excelfile"+k)[0].files[0]); 
            else 
                reader.readAsBinaryString($("#excelfile"+k)[0].files[0]);
         }  
         else{ 
			$("#usuarioEliminar").html("<p>No soporta carga de excel.</p>");
			$("#abrirmodal").click();
		 }
    }  
    else{
		$("#usuarioEliminar").html("<p>Archivo no valido.</p>");
		$("#abrirmodal").click();
	}
 }  
function validar(){
	if($("#idDisp").val()==null||$("#idDisp").val()==" "||$("#idDisp").val()=="")
		return false;
	if($("#uCarga").val()==null||$("#uCarga").val()==" "||$("#uCarga").val()=="")
		return false;
	if($("#uDescarga").val()==null||$("#uDescarga").val()==" "||$("#uDescarga").val()=="")
		return false;
	if($("#modelo").val()==null||$("#modelo").val()==" "||$("#modelo").val()=="")
		return false;
	return true;
}
function validarModelo(){
	if($("#modelo").val()<1||$("#modelo").val()>3)
		return false;
	return true;
}
function validarContId(cadena){
    var patron = /^\d{3,15}$/;
    if(!cadena.search(patron))
		return true;
    else
		return false;
}
function registrar(){
	var dis={};
			dis['id']=$("#idDisp").val();
			$.post(
				'controlador/obtenerDispositivo.php',
				dis,
				function(data){
					if(data==0){
						$.post(
							'controlador/registarCalibracion.php',
							'json='+json,
							function(data){
								$("#usuarioEliminar").html("<p>"+data+"</p>");
								$("#abrirmodal").click();
								restaurar();
								cargarDisp();
								$("#cerrarAlta").click();
							},'json'
						);
					}else{
						$("#usuarioEliminar").html("<p>Ya se ha registrado el dispositivo.</p>");
						$("#abrirmodal").click();
					}
				},'json'
			);
	
}
function enfocar(){
	campo.focus();
}
function cargarDisp(){
		$("#carga").show();
		$.ajax({
			method : "post",
			url: 'controlador/obtenerDispositivos.php', 
			dataType:'json',			
			data: {tipo: datUsuario, id: id_us},
			success: function(data){
					$("#resultado").html("");
					var fecha= new Date();
					var salida="";
					$.each(data,function(index){
						var id = data[index].id;
						var disp = data[index].id_disp;
						var mod = data[index].modelo_nombre;
						var fab = data[index].modelo_fabricante;
						var carga = data[index].carga;
						var descarga = data[index].descarga;
						var activo = data[index].activo;
						var est = data[index].estatus;
						var empresa= data[index].empresa;
						var tanques_ico="";
						if(activo==1){
							for(var l=0; l < est.length; l++){
								if(compararHora(fecha,est[l].fecha)){
									tanques_ico+="<span class='glyphicon glyphicon glyphicon-certificate' style='color: green;'></span>";
								}else{
									tanques_ico+="<span class='glyphicon glyphicon glyphicon-certificate' style='color: red;'></span>";
								}
							}
							salida+="<tr><td>"+id+"</td><td>"+empresa+"</td><td>"+disp+"</td><td>"+mod+"</td><td>"+fab+"</td><td>"+carga+"</td><td>"+descarga+"</td><td>"+tanques_ico+"</td><td>"
							+"<button id='button_"+id+"'type='button' class='btn btn-info' onclick='mostrarEditarDis(this)'>"
							+"<span class='glyphicon glyphicon-edit'></span>"
							+"</button>"
							
							+"<button data-toggle='modal' data-target='#modalUsuario' id='mostrar_"+id+"' type='button' class='btn btn-danger hidden'>"
							+"<span class='glyphicon glyphicon-edit'></span>"
							+"</button>"
							
							+"<button type='button' class='btn btn-danger' id='eliminar_"+id+"' onclick='mostrarEliminarDis(this)'>"
							+"<span class='glyphicon glyphicon-trash'></span>"
							+"</button>"
							
							+"<button data-toggle='modal' data-target='#modalEliminar' id='mostrar2_"+id+"' type='button' class='btn btn-danger hidden'>"
							+"<span class='glyphicon glyphicon-trash'></span>"
							+"</button>"
							+"</td></tr>";
						}
					});
					$("#resultado").append(salida);
					$("#carga").hide();
			}
		//fin ajax
		}).fail(function(x,t,e){$("#usuarioEliminar").html("<p>Error al recibir datos.</p>");
						$("#abrirmodal").click();$("#carga").hide();});
} 
function compararHora(sistema,recuperada){
	var dia = parseInt(sistema.getDate());
	var mes = parseInt(sistema.getMonth())+1;
	var ano = parseInt(sistema.getFullYear());
	var hora = parseInt(sistema.getHours());
	var minuto = parseInt(sistema.getMinutes());
	var segundos = parseInt(sistema.getSeconds());
	
	var d_ano=parseInt(recuperada.split(" ")[0].split("-")[0]);
	var d_mes=parseInt(recuperada.split(" ")[0].split("-")[1]);
	var d_dia=parseInt(recuperada.split(" ")[0].split("-")[2]);
	var d_hora=parseInt(recuperada.split(" ")[1].split(":")[0]);
	var d_min=parseInt(recuperada.split(":")[1]);
	var d_sec=parseInt(recuperada.split(":")[2]);
	if(parseInt(ano)==parseInt(d_ano)){
		if(parseInt(mes)==parseInt(d_mes)){
			if(parseInt(dia)==parseInt(d_dia)){
			var inicio=hora*360+minuto*60+segundos;
			var fin=d_hora*360+d_min*60+d_sec;
			
			if(Math.abs(inicio-fin)<=600)
				return true;
			}
		}
	}
	return false;
}
function mostrarEliminarDis(i){
	dis=i.id.split("_")[1];
	var us={};
	us['id']=dis;
	$.post(
		'controlador/datosDispositivo.php',
		us,
		function(data){
			$("#dispositivoEliminar").empty();
			$("#dispositivoEliminar").append("<div  class='col-sm-12'><p>¿Desea eliminar el dispositivo "+data[0].id+"?</p>"
			+"</div>"
			+"<div class='input-group col-sm-offset-6 test-align-center'>"
			+"<input type='button'  class='btn btn-default' id='btnEliminar' onclick='eliminarDis("+dis+")' value='Eliminar'></input>"
			+"<button type='button' class='btn btn-danger' data-dismiss='modal' id='eliminarCancelar'>Cancelar</button>"
			+"</div>");
			$("#mostrar2_"+dis).click();
		},'json'
	);
}
function mostrarEditarDis(i){
	dis=i.id.split("_")[1];
	var us={};
	us['id']=dis;
	$("#carga").show();
	//cargar datos en modal
	$.post(
		'controlador/datosDispositivo.php',
		us,
		function(data){
			
			$("#title").html("Modificar "+data[0].id_disp);
			$("#idDisp").val(data[0].id_disp);
			$("#uCarga").val(data[0].carga);
			$("#uDescarga").val(data[0].descarga);
			$("#modelo").val(data[0].modelo_id);
			var i;
			if(data[0].calibracion!=0){
				for(i=0; i < data[0].calibracion.length; i++) { 
					if(data[0].calibracion[i].cal_activa==1){
						var row="<tr><td contenteditable='true'>"+data[0].calibracion[i].puntos+"</td>"+
						"<td contenteditable='true'>"+data[0].calibracion[i].volumen+"</td>"+
						"<td><span class='table-remove glyphicon glyphicon-remove-sign' onclick='eliminar(this)'></span>"+
						"</td>"+
						" <td><span class='table-up glyphicon glyphicon-chevron-up' onclick='arriba(this)'></span>"+
						" <span class='table-down glyphicon glyphicon-chevron-down'onclick='abajo(this)'></span>"+
						"</td></tr>";
						$(".table"+data[0].calibracion[i].tanque).append(row);
					}
				}
				var num_tan=data[0].calibracion[(i-1)].tanque;
			}else{
				var num_tan=1;
			}
			var dat_us={tipo: datUsuario, id: id_us};
			$.post(
				'controlador/obtenerUsuarios.php',	
				dat_us,
				function(d){
					$("#usuariosSel").html("");
					$.each(d,function(index){
						if(d[index].activo==1)
							$("#usuariosSel").append("<option value="+d[index].id_us+">"+d[index].nom_us+"</option>");
					});
					$("#tanque"+num_tan).click();
					$("#usuariosSel").val(data[0].usuario);
					$("#modificar-btn").removeClass("hidden");
					$("#export-btn").addClass("hidden");
					$("#carga").hide();
					$("#altaDisp").click();
				},'json'
			);
			
		},'json'
	);

}
function editarDis(){
	$.post(
		'controlador/editarDispositivo.php',
		'json='+json,
		function(data){
			$("#usuarioEliminar").html("<p>"+data+"</p>");
			$("#abrirmodal").click();
			cargarDisp();
			$("#cerrarAlta").click();
			restaurar(); 
		},'json'
	);
					
}
function restaurar(){	
	$("#idDisp").val("");
	document.getElementById('uCarga').value='10';
	document.getElementById('uDescarga').value='10';
	document.getElementById('modelo').value='1';
	$(".table1 tr").remove();
	$(".table2 tr").remove();
	$(".table3 tr").remove();
	$(".table4 tr").remove();
	$("#modificar-btn").addClass("hidden");
	$("#export-btn").removeClass("hidden");
	
	$("#title").html("Alta de dispositivo");
	var dat_us={tipo: datUsuario, id: id_us};
	$.post(
		'controlador/obtenerUsuarios.php',
		dat_us,
		function(d){
			$("#usuariosSel").html("");
			$.each(d,function(index){
				if(d[index].activo==1)
					$("#usuariosSel").append("<option value="+d[index].id_us+">"+d[index].nom_us+"</option>");
			});
			$.post(
					'controlador/obtenerModelos.php',
					dat_us,
					function(da){
						
						$("#modelo").html("");
						$.each(da,function(index){
							$("#modelo").append("<option value="+da[index].valor+">"+da[index].nombre+"</option>");
						});
						$("#modelo").val(da[0].valor);
						$("#usuariosSel").val(d[0].id_us);						
					},'json'
				);
		},'json'
	);
}
function vista(){
	if(datUsuario==1){
		$("#menuEmpresa").show();
		$("#menuModelo").show();
	}else if(datUsuario==2){
		$("#menuEmpresa").hide();
		$("#menuModelo").hide();
	}
}
function eliminarDis(i){
	var us={
		id:i
	};
	$.post(
		'controlador/eliminarDispositivo.php',
		us,
		function(data){
			if(data){
				$("#usuarioEliminar").html("<p>"+data+"</p>");
				$("#abrirmodal").click();
				cargarDisp();
				$("#eliminarCancelar").click();
			}
			else //mensaje de error
				$("#dipositivoEliminar").append("no se puede borrar");
		},'json'
	);
}
function cargarUsuarios(){
	$("#carga").show();
	var dat_us={tipo: datUsuario, id: id_us};
	$.post(
		'controlador/obtenerUsuarios.php',
		dat_us,
		function(d){
			$("#resultadoEpresa").html("");
					var fecha= new Date();
					var salida="";
					$.each(d,function(index){
						var id = d[index].id_us;
						var nom = d[index].nom_us;
						var fec = d[index].fecha;
						var tip = d[index].tipo;
						var act = d[index].activo;
						
							salida+="<tr><td>"+id+"</td><td>"+nom+"</td><td>"+fec+"</td><td>"+tip+"</td><td>"+act+"</td><td>"
							+"<button id='button_"+id+"'type='button' class='btn btn-info' onclick='mostrarEditarUsu(this)'>"
							+"<span class='glyphicon glyphicon-edit'></span>"
							+"</button>"
							
							+"<button data-toggle='modal' data-target='#modalUsuario' id='mostrar_"+id+"' type='button' class='btn btn-danger hidden'>"
							+"<span class='glyphicon glyphicon-edit'></span>"
							+"</button>"
							
							+"<button type='button' class='btn btn-danger' id='eliminar_"+id+"' onclick='mostrarEliminarUsu(this)'>"
							+"<span class='glyphicon glyphicon-trash'></span>"
							+"</button>"
							
							+"<button data-toggle='modal' data-target='#modalEliminar' id='mostrar2_"+id+"' type='button' class='btn btn-danger hidden'>"
							+"<span class='glyphicon glyphicon-trash'></span>"
							+"</button>"
							+"</td></tr>";
					});
			$('#resultadoEpresa').append(salida);
			$("#carga").hide();
		},'json'
	);
}

function mostrarEditarUsu(i){	
	alert(i.id.split("_")[1]);
	//modal de edicion de empresa
}
function mostrarEliminarUsu(i){
	alert(i.id.split("_")[1]);
}

