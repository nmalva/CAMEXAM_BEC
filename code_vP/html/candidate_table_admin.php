<?php include ("includes/title.php");?>
<?php include ("includes/security_admin.php");?>
<?php include ("includes/security_session.php");?>
<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
<!--  BEGIN INCLUDE CLASSES -->
<?php include_once("../classes/class.candidate.php");?>
<?php include_once("../classes/class.utiles.php");?>
<?php include_once("../classes/class.bd.php");?>
<?php include_once("includes/variables.php");?>

<!--  END INCLUDE CLASSES -->

<!--  BEGIN GLOBAL VARIABLES -->
<?php 
$_SESSION["exa_id"]=$_GET["exa_id"]; //It is used in the form (to not use get)
$get_exa_id=$_GET["exa_id"];
$session_prc_id= $_SESSION["prc_id"];
?>
<!--  END GLOBAL VARIABLES -->

<!--  BEGIN PHP FUNCTIONS -->
<?php 
function getExaInfo($exa_id){
    $class_utiles=new utiles();
    $class_bd=new bd();
    $sql="SELECT * FROM Exam INNER JOIN TypeExam on Exam.tye_id=TypeExam.tye_id WHERE exa_id={$exa_id}";
    $resultado=$class_bd->ejecutar($sql);
    $r=$class_bd->retornar_fila($resultado);   
	$string.="<i style='font-size:20px;' class='icon-book-open'> ".$r["tye_name"]." ".$class_utiles->fecha_mysql_php($r["exa_date"])."</i>";
	$string.= " <i style='font-size:20px;' class='icon-clock'>  DEADLINE: ". $class_utiles->fecha_mysql_php($r["exa_deadlineshow"])."</i>";
	echo $string;	
}
function write_candidate($session_prc_id, $get_exa_id){
    $class_bd=new bd();
    $class_utiles= new utiles();
    $folder="../../files/";
    $sql="SELECT * FROM Candidate 
                   INNER JOIN ExamPlace on Candidate.exp_id=ExamPlace.exp_id
                   INNER JOIN PrepCentre on Candidate.prc_id=PrepCentre.prc_id
                   WHERE exa_id={$get_exa_id}";
    $resultado=$class_bd->ejecutar($sql);
    while ($r=$class_bd->retornar_fila($resultado))
    {  
           if ($r["can_status"]==STATUS_NOT_SENT)
            $color=COLOR_NOT_SENT;
        if ($r["can_status"]==STATUS_SENT)
            $color=COLOR_SENT;
        if ($r["can_status"]==STATUS_CONFIRMED)
            $color=COLOR_CONFIRMED;
        if ($r["can_status"]==STATUS_ERROR)
            $color=COLOR_ERROR;
        
        if ($r["can_gender"]==0)
            $can_gender="Female";
        else
            $can_gender="Male";
        
        if ($r["can_visa"]==0)
            $can_visa="N";
        else
            $can_visa="Y";
        
         if ($r["can_disability"]==0)
             $can_disability="N";
         else
             $can_disability="Y";
         
         if ($r["can_paymentfile"]!=""){$can_paymentfile="Y";}else{$can_paymentfile="N";}
         if ($r["can_dnifile"]!=""){$can_dnifile="Y";}else{$can_dnifile="N";}
         if ($r["can_acifile"]!=""){$can_acifile="Y";}else{$can_acifile="N";}
         if ($r["can_disabilityfile"]!=""){$can_disabilityfile="Y";}else{$can_disabilityfile="N";}
         if ($r["can_disability"]==1){$background_tr_color=COLOR_DISABILITY;}else{$background_tr_color="";}
             
         $path_payment=$folder.$r["can_paymentfile"];
         $path_dni=$folder.$r["can_dnifile"];
         $path_aci=$folder.$r["can_acifile"];
         $path_disability=$folder.$r["can_disabilityfile"];
         
         $table="<tr  style='background-color:{$background_tr_color};'>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>{$r["can_id"]}</td>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>{$r["can_firstname"]}</td>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>{$r["can_lastname"]}</td>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>{$r["can_dni"]}</td>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>{$can_gender}</td>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>".$class_utiles->fecha_mysql_php($r["can_datebirth"])."</td>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>{$r["can_email"]}</td>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>{$r["exp_name"]}</td>";
         $table.="<td style='color:{$color}; cursor:pointer;' onclick='redirect({$r["can_id"]});'>{$r["prc_name"]}</td>";
         $table.="<td style='color:{$color};' align='center'>{$can_visa}</td>";
         $table.="<td style='color:{$color};' align='center'>{$can_disability}</td>";
         $table.="<td style='color:{$color};' align='center'><a href='{$path_payment}' style='color:{$color}' target='_blank'>{$can_paymentfile}</a></td>";
         $table.="<td style='color:{$color};' align='center'><a href='a{$path_dni}' style='color:{$color}'target='_blank'>{$can_dnifile}</a></td>";
         $table.="<td style='color:{$color};' align='center'><a href='{$pth_aci}' style='color:{$color}'target='_blank'>{$can_acifile}</a></td>";
         $table.="<td style='color:{$color};' align='center'><a href='{$path_disability}' style='color:{$color}'target='_blank'>{$can_disabilityfile}</a></td>";
         $table.="</tr>";
        
         echo $table;
      }						                      
    }
?>
<head>


<td style="background-color: 
<!--  PAGE TITLE  -->
<?php include ("includes/pagetitle.php");?>
<!--  END PAGE TITLE  -->

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<?php include("includes/globalstyle.html");?>
<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN PAGE LEVEL STYLES USED BY TABLE-->
<link rel="stylesheet" type="text/css"
	href="../../assets/global/plugins/select2/select2.css" />
<link rel="stylesheet" type="text/css"
	href="../../assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css" />
<link rel="stylesheet" type="text/css"
	href="../../assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css" />
<link rel="stylesheet" type="text/css"
	href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" />
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<?php include ("includes/themestyle.html")?>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->

<body>
	<!-- BEGIN HEADER -->
	<div class="page-header">
		<!-- BEGIN HEADER TOP -->
	    <?php include("includes/headertop.php");?>
	<!-- END HEADER TOP -->
		<!-- BEGIN HEADER MENU -->
	   <?php include("includes/headermenu.php");?>
	<!-- END HEADER MENU -->
	</div>
	<!-- END HEADER -->
	<!-- BEGIN PAGE CONTAINER -->
	<div class="page-container-fluid">
		<!-- BEGIN PAGE HEAD -->
		<div class="page-head">
			<div class="container">
				<!-- BEGIN PAGE TITLE -->
				<div class="page-title">
					<h1>
                        <?php getExaInfo($get_exa_id);?>
					</h1>
				</div>
				<!-- END PAGE TITLE -->
				<!-- BEGIN PAGE TOOLBAR -->
				<div class="page-toolbar">
					<!-- BEGIN THEME PANEL -->
					<div class="btn-group btn-theme-panel">
                    <a href="javascript:;" class="btn dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-settings"></i>
                    </a>
                    <div class="dropdown-menu theme-panel pull-right dropdown-custom hold-on-click">
                        <div class="row">
                            
                                <ul class="theme-settings">
                                    <li>
                                         Layout
                                        <select class="theme-setting theme-setting-layout form-control input-sm input-small input-inline tooltips" data-original-title="Change layout type" data-container="body" data-placement="left">
                                            <option value="boxed" selected="selected">Boxed</option>
                                            <option value="fluid">Fluid</option>
                                        </select>
                                    </li>
                                </ul>
                          
                        </div>
                    </div>
                </div>
					<!-- END THEME PANEL -->
				</div>
				<!-- END PAGE TOOLBAR -->
			</div>
		</div>
		<!-- END PAGE HEAD -->
		<!-- BEGIN PAGE CONTENT -->
		<div class="page-content">
			<div class="container">
				<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
				<div class="modal fade" id="portlet-config" tabindex="-1"
					role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"
									aria-hidden="true"></button>
								<h4 class="modal-title">Modal title</h4>
							</div>
							<div class="modal-body">Widget settings form goes here</div>
							<div class="modal-footer">
								<button type="button" class="btn blue">Save changes</button>
								<button type="button" class="btn default" data-dismiss="modal">Close</button>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->
				<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
				<!-- BEGIN PAGE BREADCRUMB -->
        			<ul class="page-breadcrumb breadcrumb">
        				<li>
        					<a href="exam_menu.php">Return</a><i class="fa fa-circle"></i>
        				</li>
        				<li>
        					<a href="candidate_form.php">New Candidate</a><i class="fa fa-circle"></i>
        				</li>
        				<li>
        					<a href="javascript:export_excel(<?php echo $get_exa_id;?>);">Export to Excel Cambridge</a><i class="fa fa-circle"></i>
        				</li>
        				<li>
        					<a href="javascript:export_excel_internal(<?php echo $get_exa_id;?>);">Export to Excel Internal Use</a><i class="fa fa-circle"></i>
        				</li>
        				<li>
        					<a href="javascript:redirect_check(<?php echo $get_exa_id;?>);">Candidate Workflow</a><i class="fa fa-circle"></i>
        				</li>
        				<li>
        					<a href="javascript:redirect_addinfo(<?php echo $get_exa_id;?>);">Add Information</a>
        				</li>        				
        			</ul>
			<!-- END PAGE BREADCRUMB -->
				<!-- BEGIN PAGE CONTENT INNER -->
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">Candidate Table</span>
							</div>
							<div class="actions btn-set">
								<div class="btn-group">
									<a class="btn green-haze btn-circle" href="javascript:;" data-toggle="dropdown">
									<!--<i class="fa fa-check-circle"></i>-->
									 Columns <i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-right hold-on-click" id="sample_4_column_toggler">
										
										<li>
											<label><input type="checkbox" checked data-column="1">First Name</label>
										</li>
										<li>
											<label><input type="checkbox" checked data-column="2">Last Name</label>
										</li>
										<li>
											<label><input type="checkbox" checked data-column="3">Dni</label>
										</li>
										<li>
											<label><input type="checkbox" checked data-column="4">Gender</label>
										</li>
										<li>
											<label><input type="checkbox" checked data-column="5">Date of Birth</label>
										</li>
										<li>
											<label><input type="checkbox" checked data-column="6">Email</label>
										</li>
									</ul>
								</div>
							</div>
						</div>
								
							<div class="portlet-body">
								<table class="table table-striped table-bordered table-hover"
									id="sample_4">
									<thead>
										<tr>
											<th>ID</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>DNI</th>
											<th>Gender</th>
											<th>Birth</th>
											<th>Email</th>
											<th>Exam Place</th>
											<th>Prep. Centre</th>
											<th>Visa R.</th>
											<th>Sp Arr</th>
											<th>F-Pay</th>
											<th>F-ID</th>
											<th>F-Con.</th>
											<th>F-Sp. Arr.</th>
											
										</tr>
									</thead>
									<tbody>
							<?php 
							    write_candidate($session_prc_id, $get_exa_id);	
							
							?>
							</tbody>
								</table>
							</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
				<!-- END PAGE CONTENT INNER -->
			</div>
		</div>
		<!-- END PAGE CONTENT -->
	</div>
	<!-- END PAGE CONTAINER -->
<!-- BEGIN PRE-FOOTER -->
<?php include("includes/prefooter.html")?>
<!-- END PRE-FOOTER -->
<!-- BEGIN FOOTER -->
<?php include("includes/footer.html");?>
<!-- END FOOTER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<?php include("includes/coreplugins.html");?>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript"
	src="../../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript"
	src="../../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript"
	src="../../assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript"
	src="../../assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript"
	src="../../assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript"
	src="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../../assets/global/scripts/metronic.js"
	type="text/javascript"></script>
<script src="../../assets/admin/layout3/scripts/layout.js"
	type="text/javascript"></script>
<script src="../../assets/admin/layout3/scripts/demo.js"
	type="text/javascript"></script>
<script src="../../assets/admin/pages/scripts/table-advanced.js"></script>
<script>
jQuery(document).ready(function() {       
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   Demo.init(); // init demo features
   TableAdvanced.init();
});

//--START JAVASCRIPT FUNCTIONS--
function redirect(can_id){
	pagina = "candidate_form.php?can_id="+ can_id;
	setTimeout(redireccionar, 100, pagina);
}
function redireccionar(pagina) {
    location.href=pagina;	          
}   
function export_excel(exa_id){
	mensaje =	"Do you want to export this table to Excel Format?";
	confirmar=confirm(mensaje); 
	if (confirmar) {			
		var pagina = 'candidate_exportexcel.php?exa_id='+exa_id;
		document.location.href= pagina;
	}
}
function export_excel_internal(exa_id){
	mensaje =	"Do you want to export this table to Excel Format?";
	confirmar=confirm(mensaje); 
	if (confirmar) {			
		var pagina = 'candidate_exportexcel_internal.php?exa_id='+exa_id;
		document.location.href= pagina;
	}
}
function redirect_check(exa_id){
	pagina = "candidate_table_admin_check.php?exa_id="+ exa_id;
	setTimeout(redireccionar, 100, pagina);     
}
function redirect_addinfo(exa_id){
	pagina = "candidate_table_admin_addinfo.php?exa_id="+ exa_id;
	setTimeout(redireccionar, 100, pagina);     
}
//--END JAVASCRIPT FUNCTIONS--
</script>

</body>
<!-- END BODY -->
</html>